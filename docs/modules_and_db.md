# EAM MES Package — Modules và Database

Tài liệu này phản ánh schema thực tế trong `database/migrations` của package.

## Quy ước đọc sơ đồ

- Sơ đồ được tách theo đúng ranh giới module/model.
- Chỉ vẽ đường quan hệ khi hai bảng thuộc cùng một submodule.
- Các cột tham chiếu sang module khác hoặc sang ứng dụng host vẫn được liệt kê, nhưng không nối đường trên ERD.
- `users`, product, lot, checklist và các master data ngoài package là dependency của host app.

## 1. Tổng quan toàn bộ database

| Submodule | Bảng | Vai trò | Quan hệ nội bộ hiển thị |
|---|---|---|---|
| Masterdata Equipment | `eamo_equipment_categories`, `eamo_equipment`, `eamo_equipment_states`, `eamo_equipment_images` | Thiết bị cốt lõi | Category → Equipment → State / Images |
| Masterdata Equipment | `eamo_units`, `eamo_equipment_parameters` | Thông số và đơn vị đo | Unit → Equipment parameters |
| Masterdata Equipment | `eamo_equipment_errors` | Danh mục lỗi (được gán trực tiếp qua bản ghi định nghĩa trong `eamo_equipment_error_logs` với `occurred_at IS NULL`) | Equipment error mapping |
| Checklist | `eamo_checklist_sessions`, `eamo_checklist_details`, `eamo_checklist_schedules`, `eamo_checklist_logs` | Lập lịch và thực hiện checklist | Session → Detail / Schedule → Log |
| Maintenance | `eamo_maintenance_categories`, `eamo_maintenance_items`, `eamo_maintenance_plans`, `eamo_maintenance_schedules`, `eamo_maintenance_logs` | Lập kế hoạch và log bảo trì | Category → Item / Plan → Schedule → Log |
| Error Monitoring | `eamo_equipment_error_logs`, `eamo_operating_times` | Log lỗi và vận hành | Bảng log độc lập |
| Parameter Log | `eamo_equipment_parameter_logs` | Timeseries thông số | Bảng log độc lập |
| Extension | `eamo_extension_requests` | Theo dõi migration động | Bảng độc lập |

## 2. Masterdata Equipment

| Bảng | Model | Mục đích |
|---|---|---|
| `eamo_equipment_categories` | `EquipmentCategory` | Danh mục thiết bị |
| `eamo_equipment` | `Equipment` | Thiết bị, cấu trúc cha–con |
| `eamo_equipment_states` | `EquipmentState` | Trạng thái 1–1 của thiết bị |
| `eamo_equipment_images` | `EquipmentImage` | Ảnh thiết bị |
| `eamo_equipment_parameters` | `EquipmentParameter` | Thông số, ngưỡng chuẩn |
| `eamo_units` | `Unit` | Đơn vị đo |
| `eamo_equipment_errors` | `EquipmentError` | Danh mục lỗi (mapping qua bản ghi định nghĩa trong `eamo_equipment_error_logs`) |

```mermaid
erDiagram
    eamo_equipment_categories ||--o{ eamo_equipment : categorizes
    eamo_equipment ||--o| eamo_equipment_states : has_state
    eamo_equipment ||--o{ eamo_equipment_images : has_images

    eamo_equipment {
        uuid id PK
        uuid parent_id FK
        string code UK
        uuid equipment_category_id FK
        string name
        string device_id
        integer maintenance_interval_hours
        json last_maintenance
        boolean is_active
    }
```

`parent_id` là self-reference của equipment nên chỉ hiển thị là cột; không vẽ line vòng lặp.

### 2.1 Thông số và đơn vị đo

`equipment_id`, `equipment_category_id` và `product_category_id` là các ID tham chiếu ra ngoài sơ đồ nhỏ này.

```mermaid
erDiagram
    eamo_units ||--o{ eamo_equipment_parameters : unit

    eamo_units {
        uuid id PK
        string code UK
        string name
    }
    eamo_equipment_parameters {
        uuid id PK
        string code UK
        uuid equipment_id
        uuid equipment_category_id
        uuid unit_id FK
        uuid product_category_id
        string name
        decimal standard
        decimal standard_min
        decimal standard_max
    }
```

### 2.2 Danh mục lỗi

`equipment_id` trong pivot tham chiếu sang sơ đồ thiết bị cốt lõi nên không nối line.

```mermaid
erDiagram
    eamo_equipment_errors ||--o{ eamo_equipment_equipment_errors : maps

    eamo_equipment_errors {
        uuid id PK
        string name
        text reason
        text fix
        text protection_measures
    }
    eamo_equipment_equipment_errors {
        uuid equipment_id PK
        uuid equipment_error_id PK, FK
    }
```

## 3. Mô hình dữ liệu vận hành & logs

### 3.1 Checklist

| Bảng | Model | Cột liên kết ngoài module |
|---|---|---|
| `eamo_checklist_sessions` | `ChecklistSession` | `equipment_id`, `user_id` |
| `eamo_checklist_details` | `ChecklistDetail` | `checklist_id` |
| `eamo_checklist_schedules` | `ChecklistSchedule` | `equipment_id`, `user_id` |
| `eamo_checklist_logs` | `ChecklistLog` | `user_id` |

```mermaid
erDiagram
    eamo_checklist_sessions ||--o{ eamo_checklist_details : contains
    eamo_checklist_sessions ||--o{ eamo_checklist_schedules : generates
    eamo_checklist_details ||--o{ eamo_checklist_schedules : schedules
    eamo_checklist_schedules ||--o{ eamo_checklist_logs : records

    eamo_checklist_sessions {
        uuid id PK
        string name
        uuid equipment_id
        uuid user_id
        datetime session_date
        string cycle_type
        integer cycle_interval
    }
    eamo_checklist_schedules {
        uuid id PK
        uuid checklist_session_id FK
        uuid checklist_detail_id FK
        uuid equipment_id
        uuid user_id
        date date
        boolean is_rescheduled
        date original_date
    }
    eamo_checklist_logs {
        uuid id PK
        uuid checklist_schedule_id FK
        uuid user_id
        enum result
        enum status
        timestamp checked_at
    }
```

### 3.2 Maintenance

| Bảng | Model | Cột liên kết ngoài module |
|---|---|---|
| `eamo_maintenance_categories` | `MaintenanceCategory` | — |
| `eamo_maintenance_items` | `MaintenanceItem` | `user_id` |
| `eamo_maintenance_plans` | `MaintenancePlan` | `equipment_id`, `user_id` |
| `eamo_maintenance_schedules` | `MaintenanceSchedule` | `equipment_id`, `user_id` |
| `eamo_maintenance_logs` | `MaintenanceLog` | — |

```mermaid
erDiagram
    eamo_maintenance_categories ||--o{ eamo_maintenance_items : contains
    eamo_maintenance_categories ||--o{ eamo_maintenance_plans : classifies
    eamo_maintenance_plans ||--o{ eamo_maintenance_schedules : generates
    eamo_maintenance_items ||--o{ eamo_maintenance_schedules : schedules
    eamo_maintenance_schedules ||--o{ eamo_maintenance_logs : records

    eamo_maintenance_plans {
        uuid id PK
        string plan_code
        uuid equipment_id
        uuid maintenance_category_id FK
        uuid user_id
        date date
        string cycle_type
        integer cycle_interval
        string maintenance_type
    }
    eamo_maintenance_schedules {
        uuid id PK
        uuid maintenance_plan_id FK
        uuid maintenance_item_id FK
        uuid equipment_id
        uuid user_id
        date date
        boolean is_rescheduled
        date original_date
    }
```

### 3.3 Error Monitoring

`eamo_equipment_error_logs` và `eamo_operating_times` là hai bảng log độc lập trong submodule này. Các ID `equipment_id`, `equipment_error_id` và `handler_id` tham chiếu sang Masterdata/host app nên không vẽ line ở đây.

| Bảng | Model | Cột chính |
|---|---|---|
| `eamo_equipment_error_logs` | `EquipmentErrorLog` | `equipment_id`, `equipment_error_id`, `occurred_at`, `restarted_at`, `handled_at`, `handler_id` |
| `eamo_operating_times` | `OperatingTime` | `equipment_id`, các thời lượng, `start_time`, `end_time`, `date` |

```mermaid
erDiagram
    eamo_equipment_error_logs {
        uuid id PK
        uuid equipment_id
        uuid equipment_error_id
        uuid handler_id
        datetime occurred_at
        datetime restarted_at
        datetime handled_at
    }
    eamo_operating_times {
        uuid id PK
        uuid equipment_id
        decimal working_time
        decimal planned_stop_time
        decimal unplanned_stop_time
        timestamp start_time
        timestamp end_time
    }
```

### 3.4 Parameter Log

`eamo_equipment_parameter_logs` là bảng timeseries độc lập ghi log thông số thiết bị. `equipment_id`, `equipment_parameter_id`, `unit_id` và `user_id` là các ID tham chiếu.

```mermaid
erDiagram
    eamo_equipment_parameter_logs {
        uuid id PK
        uuid equipment_id
        uuid equipment_parameter_id
        uuid unit_id
        uuid user_id
        string value
        timestamp recorded_at
        timestamp created_at
        timestamp updated_at
        timestamp deleted_at
    }
```

## 4. Extension

| Bảng | Model | Mục đích |
|---|---|---|
| `eamo_extension_requests` | `Spatie\LaravelPackageTools\Models\ExtensionRequest` | Lưu yêu cầu sinh migration động: target table, danh sách cột, trạng thái và lỗi |

## 5. Kiểm tra schema

- Package tạo 21 bảng và có model tương ứng.
- Migration được kiểm tra bằng `tests/EamMesMigrationsTest.php`.
- Các bảng có `user_id` giả định host app đã có `users.id` kiểu UUID.
