# AI Agent Automation Workflow: Syncing Database Schemas & Fields in `eam-mes-package`

Tài liệu này quy định quy trình từng bước dành cho AI Agent để tự động hóa việc cập nhật cấu trúc bảng (schema/fields) từ ứng dụng Host/Backend vào `eam-mes-package` một cách nhất quán và chuẩn xác.

---

## 1. Mục Tiêu & Nguyên Tắc Cốt Lõi

1. **Ghi Đè Migration Trực Tiếp Trong Package**:
   - Khác với ứng dụng chính (Backend) cần tạo migration chuyển đổi theo thời gian (`2026_xx_xx_...`), Package chứa **Schema Chuẩn**.
   - Khi có thay đổi cấu trúc bảng, Agent sẽ cập nhật trực tiếp file migration gốc tương ứng trong `package/database/migrations/`.

2. **Cập Nhật Đồng Bộ Toàn Bộ Luồng (End-to-End Layer Sync)**:
   Mỗi khi thay đổi/thêm/bớt cột của 1 bảng DB, Agent **BẮT BUỘC** phải rà soát và cập nhật đồng thời các thành phần sau:
   - **Migration**: `package/database/migrations/`
   - **Eloquent Model**: `package/src/Modules/.../Models/`
   - **Form Requests**: `package/src/Modules/.../Requests/`
   - **Actions / Controllers**: `package/src/Modules/.../Actions/`
   - **Publish Command**: `package/src/Commands/EamMesPublishCommand.php`
   - **Tài Liệu**: `package/docs/modules_and_db.md` & `package/README.md`
   - **Unit Tests**: `package/tests/`

---

## 2. Quy Trình 5 Bước Tự Động Hóa (Checklist Dành Cho Agent)

### Bước 1: So Sánh & Nhận Diện Schema
- Đối chiếu file migration từ Backend (`backend/database/migrations/`) với file migration tương ứng trong Package (`package/database/migrations/`).
- Xác định rõ danh sách các trường:
  - Cột mới thêm (Name, Data type, Nullable, Default, Index, Foreign key).
  - Cột bị sửa đổi / đổi tên / chuyển kiểu dữ liệu.
  - Cột bị xóa.
  - Sử dụng SoftDeletes (`$table->softDeletes()`).

### Bước 2: Cập Nhật Migration Trong Package
- Cập nhật file migration gốc tại `package/database/migrations/`.
- Giữ lại các ràng buộc khóa ngoại (Foreign key) chuẩn với kiểu dữ liệu UUID (string 36).
- Đảm bảo thứ tự timestamp file migration của các bảng tham chiếu luôn đứng trước bảng phụ thuộc.

### Bước 3: Cập Nhật Eloquent Model
- **PHPDoc**: Cập nhật danh sách `@property` ở đầu file Model.
- **Traits**: Thêm/Xóa traits tương ứng (ví dụ `use SoftDeletes;`).
- **`$fillable`**: Thêm/Xóa các trường trong mảng `$fillable`.
- **`casts()`**: Khai báo kiểu dữ liệu tự động (`datetime`, `immutable_datetime`, `boolean`, `array`, `json`, `decimal`).
- **Relationships**: Khai báo các mối quan hệ Eloquent mới (`belongsTo`, `hasMany`, v.v.).

### Bước 4: Cập Nhật Form Requests & Actions
- **Form Requests**:
  - Thêm quy tắc validation cho các trường mới trong `rules()`.
  - Cấu hình kiểu dữ liệu phù hợp (`string`, `max:36`, `date`, `numeric`, `nullable`, `sometimes`).
- **Actions / Controllers**:
  - Cập nhật logic truyền dữ liệu vào Model (`create`, `update`, `fill`).

### Bước 5: Chạy Kiểm Thử & Cập Nhật Tài Liệu
- **Chạy Test Suite**:
  ```powershell
  .\vendor\bin\pest
  ```
- **Cập nhật tài liệu ERD**:
  Cập nhật file `package/docs/modules_and_db.md` (bảng mô tả trường & sơ đồ Mermaid ERD).

---

## 3. Ví Dụ Mẫu Khi Thêm Trường Mới (`user_id`, `recorded_at`, `softDeletes`)

### A. File Migration (`package/database/migrations/2025_08_06_102920_eamo_create_equipment_parameter_logs_table.php`)
```php
Schema::create('eamo_equipment_parameter_logs', function (Blueprint $table) {
    $table->uuid('id')->primary();
    $table->string('equipment_id', 36);
    $table->string('equipment_parameter_id', 36);
    $table->string('unit_id', 36)->nullable();
    $table->string('value', 36)->nullable();
    $table->uuid('user_id')->nullable();
    $table->timestamp('recorded_at')->nullable();
    $table->timestamps();
    $table->softDeletes();
});
```

### B. File Model (`EquipmentParameterLog.php`)
```php
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property string|null $user_id
 * @property CarbonImmutable|null $recorded_at
 */
final class EquipmentParameterLog extends Model
{
    use HasUuids, SoftDeletes;

    protected $fillable = [
        'equipment_id',
        'equipment_parameter_id',
        'unit_id',
        'value',
        'user_id',
        'recorded_at',
    ];

    protected function casts(): array
    {
        return [
            'recorded_at' => 'immutable_datetime',
        ];
    }
}
```

---

## 4. Lệnh Kiểm Tra Bắt Buộc Sau Khi Cập Nhật

Mỗi khi Agent hoàn thành thay đổi, Agent phải tự động chạy lệnh sau để xác nhận 100% test pass:
```powershell
.\vendor\bin\pest
```
