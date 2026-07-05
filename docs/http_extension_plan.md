# Kế hoạch Thiết kế: Thêm Trường qua HTTP (HTTP Table Extension)

Tài liệu này mô tả chi tiết phương án thiết kế và kế hoạch triển khai tính năng mở rộng bảng động thông qua API HTTP kết hợp hàng đợi xử lý tự động (Queue Job).

---

## 1. Hướng Tiếp Cận: Biến thể 3 (HTTP → Validate → Auto Queue Job)

Để giải quyết vấn đề thay đổi cấu trúc bảng trực tiếp từ HTTP request (gây lock bảng, race condition, timeout), chúng ta tách biệt quá trình này thành 2 bước thông qua cơ chế bất đồng bộ (Asynchronous):

1. **HTTP Request**: Nhận yêu cầu, kiểm tra tính hợp lệ của dữ liệu (Validate), kiểm tra trùng lặp, ghi nhận yêu cầu vào cơ sở dữ liệu với trạng thái `queued` và đẩy một Job xử lý vào Queue. Trả về mã phản hồi `202 Accepted` ngay lập tức.
2. **Queue Worker (Background)**: Lấy Job từ hàng đợi, tiến hành sinh file migration tự động, chạy lệnh `migrate` và cập nhật lại trạng thái xử lý thành `done` hoặc `failed`.

---

## 2. Luồng Hoạt Động (Flowchart)

```
[Client]                [ExtensionController]         [Queue/Database]           [Queue Worker]
   │                              │                           │                         │
   │── POST /eam/api/extensions ─►│                           │                         │
   │   (payload table/columns)    │                           │                         │
   │                              │── Validate Whitelist & ──►│                         │
   │                              │   ColumnDefinition        │                         │
   │                              │                           │                         │
   │                              │── Kiểm tra trùng lặp ────►│                         │
   │                              │   (File & DB)             │                         │
   │                              │                           │                         │
   │                              │── Lưu record yêu cầu ────►│ (Trạng thái: queued)    │
   │                              │                           │                         │
   │                              │── Dispatch Queue Job ────►│ (Đẩy vào Queue)         │
   │                              │                           │                         │
   │◄── Phản hồi 202 Accepted ────│                           │                         │
   │   (kèm Request ID & URL check)                           │                         │
   │                                                          │                         │
   │                                                          │── Lấy Job ra chạy ─────►│
   │                                                          │                         │── Đổi trạng thái: processing
   │                                                          │                         │
   │                                                          │                         │── Sinh file migration
   │                                                          │                         │
   │                                                          │                         │── Chạy php artisan migrate
   │                                                          │                         │
   │                                                          │                         │── Thành công: status = done
   │                                                          │                         │   (Thất bại: status = failed)
```

---

## 3. Cấu Trúc Bảng Dữ Liệu `eam_extension_requests`

Bảng này đóng vai trò lưu trữ nhật ký (audit trail), quản lý tiến trình và trạng thái của các yêu cầu mở rộng bảng.

```php
Schema::create('eam_extension_requests', function (Blueprint $table) {
    $table->id();
    $table->string('table_name');
    $table->json('columns');                   // Mảng các ColumnDefinition đã được định nghĩa
    $table->string('migration_file')->nullable(); // Tên file migration được sinh ra
    $table->enum('status', [
        'queued',       // Đang chờ trong hàng đợi
        'processing',   // Đang tiến hành tạo file và chạy migrate
        'done',         // Đã hoàn thành migrate thành công
        'failed'        // Gặp lỗi trong quá trình thực thi
    ])->default('queued');
    $table->text('error_message')->nullable();     // Nội dung lỗi nếu status là 'failed'
    $table->string('requested_by')->nullable();    // IP hoặc User ID thực hiện yêu cầu
    $table->timestamps();

    // Index hỗ trợ truy vấn nhanh trạng thái xử lý
    $table->index(['table_name', 'status']);
});
```

---

## 4. Chi Tiết API Endpoints

### 4.1 Tạo yêu cầu mở rộng bảng (POST)

*   **Endpoint**: `POST /eam/api/extensions`
*   **Headers**: `Content-Type: application/json`, `Accept: application/json`
*   **Payload mẫu**:

```json
{
  "table": "eamo_maintenance_plans",
  "columns": [
    {
      "name": "department_id",
      "type": "string",
      "length": 36,
      "nullable": true,
      "after": "user_id"
    },
    {
      "name": "is_urgent",
      "type": "boolean",
      "default": false
    }
  ]
}
```

*   **Đặc tả các thuộc tính của cột trong `columns`**:

| Trường | Kiểu dữ liệu | Yêu cầu | Mô tả |
|---|---|---|---|
| `name` | String | Bắt buộc | Tên cột cần tạo (regex: `/^[a-z][a-z0-9_]*$/`) |
| `type` | String | Bắt buộc | Kiểu dữ liệu (ví dụ: `string`, `integer`, `boolean`, `text`, `json`, `decimal`...) |
| `nullable` | Boolean | Tùy chọn | Cho phép cột có giá trị `NULL` hay không (mặc định: `true`) |
| `default` | Mixed | Tùy chọn | Giá trị mặc định của cột (ví dụ: `false`, `"active"`, `10`...) |
| `length` | Integer | Tùy chọn | Độ dài của cột (chỉ áp dụng khi `type` là `string`, mặc định: 255) |
| `after` | String | Tùy chọn | Đặt cột mới sau tên cột đã chỉ định trong bảng |
| `unsigned` | Boolean | Tùy chọn | Chỉ định kiểu số không âm (chỉ dùng cho `integer`, `bigInteger`...) |

*   **Phản hồi thành công (202 Accepted)**:

```json
{
  "message": "Extension request queued successfully.",
  "id": 42,
  "status": "queued",
  "table": "eamo_maintenance_plans",
  "columns": ["department_id", "is_urgent"],
  "check_url": "http://yourdomain.com/eam/api/extensions/42"
}
```

### 4.2 Kiểm tra trạng thái yêu cầu (GET)

*   **Endpoint**: `GET /eam/api/extensions/{id}`
*   **Phản hồi thành công (200 OK)**:

```json
{
  "id": 42,
  "table": "eamo_maintenance_plans",
  "status": "done",
  "migration_file": "2026_07_05_120000_extend_eamo_maintenance_plans_table.php",
  "error_message": null,
  "created_at": "2026-07-05T04:15:00.000000Z",
  "updated_at": "2026-07-05T04:16:02.000000Z"
}
```

---

## 5. Queue Job Xử Lý (`GenerateExtensionMigrationJob`)

Job này sẽ chạy ngầm bằng Queue Worker (`php artisan queue:work --queue=eam-extensions`):

1.  **Thiết lập an toàn**:
    *   `$tries = 1`: Tránh chạy đi chạy lại các thao tác liên quan tới database schema khi gặp lỗi.
    *   `$timeout = 120`: Đảm bảo đủ thời gian chạy lệnh `migrate` trên hệ thống.
2.  **Logic thực thi**:
    *   Chuyển trạng thái yêu cầu sang `processing`.
    *   Tái cấu trúc các Object `ColumnDefinition` từ trường `columns` (JSON) trong database.
    *   Gọi `MigrationGenerator::generate()` để tạo file migration dạng vật lý.
    *   Gọi `Artisan::call('migrate', ['--force' => true])` để áp dụng ngay lập tức cấu trúc mới.
    *   Chuyển trạng thái sang `done` và cập nhật tên `migration_file`.
    *   Nếu có bất kỳ lỗi nào xảy ra (`Throwable`), ghi lại chi tiết lỗi vào `error_message` và chuyển trạng thái sang `failed`.

---

## 6. Các Rủi Ro và Giải Pháp Xử Lý

*   **Race Condition**: Người dùng gửi hai request giống hệt nhau liên tiếp.
    *   *Giải pháp*: Sử dụng DB unique constraint/index, đồng thời sử dụng `MigrationFileChecker` để quét cả file lẫn thực tế cấu trúc DB hiện tại trước khi tạo record và dispatch job.
*   **Lỗi trong quá trình Chạy Migration**: File migration sinh ra thành công nhưng không migrate được (do lỗi cấu trúc hoặc DB lock).
    *   *Giải pháp*: Trạng thái sẽ cập nhật là `failed`, nhà phát triển có thể kiểm tra lỗi thông qua API hoặc bảng `eam_extension_requests` và tiến hành sửa lỗi bằng cách chạy lệnh migrate thủ công sau đó.
*   **Security (Bảo mật)**: Attacker lạm dụng API để thay đổi cấu trúc DB.
    *   *Giải pháp*: Bắt buộc tích hợp Middleware xác thực (ví dụ: `auth:sanctum` hoặc các middleware phân quyền admin) trong file cấu hình `config/eam.php`.

---

## 7. Kế Hoạch Triển Khai (Checklist)

- [ ] **Bước 1**: Tạo file migration tạo bảng `eam_extension_requests`.
- [ ] **Bước 2**: Tạo Model `ExtensionRequest` hỗ trợ lưu dữ liệu dạng json cho các cột.
- [ ] **Bước 3**: Tạo `StoreExtensionRequest` để validate dữ liệu từ HTTP payload.
- [ ] **Bước 4**: Tạo `ExtensionController` chứa phương thức `store` và `show`.
- [ ] **Bước 5**: Tạo Queue Job `GenerateExtensionMigrationJob` tích hợp cơ chế sinh file và migrate tự động.
- [ ] **Bước 6**: Khai báo routes API trong file `routes/eam-api.php` và load routes từ ServiceProvider của package.
- [ ] **Bước 7**: Bổ sung cấu hình `api_middleware` và cấu hình Queue vào file config `config/eam.php`.
- [ ] **Bước 8**: Viết Test case giả lập gửi HTTP request thành công và thất bại để kiểm thử luồng.
- [ ] **Bước 9**: Cập nhật tài liệu hướng dẫn sử dụng tính năng HTTP Extension vào `README.md`.
