# Các Module & Cấu trúc Cơ sở Dữ liệu - EAM MES Package

`eam-mes-package` được thiết kế để cung cấp các module cốt lõi cho hệ thống Quản lý Thiết bị & Tài sản (EAM) và Hệ thống Điều hành Sản xuất (MES).

---

## 1. Các Module Con (Submodules) của Package

Package bao gồm 5 module con chính sau đây:

### 1.1 Checklist (Bảng kiểm tra)
- **Chức năng**: Quản lý quy trình kiểm tra thiết bị trước khi vận hành hoặc kiểm tra định kỳ. Thiết lập các hạng mục kiểm tra, ghi nhận các phiên kiểm tra (sessions) và lưu trữ nhật ký kết quả kiểm tra chi tiết cho từng hạng mục.
- **Đường dẫn**: `src/Checklist/`

### 1.2 Error Monitoring (Giám sát Lỗi)
- **Chức năng**: Theo dõi và giám sát lịch sử lỗi của thiết bị. Lưu trữ thông tin về thời điểm xảy ra lỗi, mã lỗi, mô tả lỗi và thời gian khắc phục.
- **Đường dẫn**: `src/ErrorMonitoring/`

### 1.3 Maintenance (Bảo trì)
- **Chức năng**: Quản lý toàn bộ vòng đời bảo trì thiết bị, bao gồm kế hoạch bảo trì (plans), lịch trình bảo trì (schedules), định nghĩa các danh mục bảo trì (categories), các hạng mục cần bảo trì (items) và nhật ký lịch sử bảo trì thực tế (logs).
- **Đường dẫn**: `src/Maintenance/`

### 1.4 Parameter Log (Ghi nhận Thông số)
- **Chức năng**: Ghi nhận các thông số vận hành theo thời gian thực của thiết bị (như nhiệt độ, áp suất, điện áp, tần số, v.v.) theo thời gian.
- **Đường dẫn**: `src/ParameterLog/`

### 1.5 Equipment (Quản lý Equipment)
- **Chức năng**: Lớp quản lý hoạt động và ghi log thời gian thực (IoT logs, seeding lỗi dừng ngắn) cho các thiết bị.
- **Đường dẫn**: `src/Equipment/MasterData/`

### 1.6 Masterdata Equipment (Dữ liệu gốc Thiết bị)
- **Chức năng**: Định nghĩa cấu trúc dữ liệu gốc của thiết bị (equipment), nhóm thiết bị (categories), thông số kỹ thuật (parameters), các ngưỡng tiêu chuẩn (standard parameters) và phân loại lỗi hệ thống (errors).
- **Đường dẫn**: `src/Masterdata/Equipment/`

---

## 2. Sơ đồ Database (Mermaid ERD)

### 2.1 Master Data (Thiết bị & Thông số gốc)

Sơ đồ quan hệ dưới đây mô tả cấu trúc dữ liệu gốc của thiết bị (được quản lý bởi module `masterdata-equipment` và `equipment`):

```mermaid
erDiagram
    equipment ||--o| equipment_categories : "belongs to"
    equipment ||--o{ equipment_parameters : "has"
    equipment ||--o{ standard_parameters : "defines standards"
    equipment ||--o{ equipment_equipment_errors : "pivot"
    equipment_errors ||--o{ equipment_equipment_errors : "pivot"
    equipment ||--o{ iot_logs : "records logs"

    equipment {
        string id PK
        string code UK
        string name
        string process_id FK
        string factory_id
        boolean virtual_equipment
        string equipment_category_id FK
        string image_id
        date date_imported
        boolean state
        string device_id
        unsigned_integer assigned_productivity_per_hour
        decimal assigned_machine_productivity_person
        boolean is_active
        timestamp created_at
        timestamp updated_at
    }

    equipment_categories {
        string id PK
        string name
        timestamp created_at
        timestamp updated_at
    }

    equipment_parameters {
        string id PK
        string code UK
        string equipment_id FK
        string product_category_id
        string equipment_category_id
        string unit_id
        string name
        timestamp created_at
        timestamp updated_at
    }

    standard_parameters {
        string id PK
        string equipment_id FK
        string equipment_parameter_id FK
        decimal standard
        decimal standard_max
        decimal standard_min
        string unit_id
        timestamp created_at
        timestamp updated_at
    }

    equipment_errors {
        string id PK
        string name
        text reason
        text fix
        text protection_measures
        timestamp created_at
        timestamp updated_at
    }

    equipment_equipment_errors {
        string equipment_id PK_FK
        string equipment_error_id PK_FK
        timestamp created_at
        timestamp updated_at
    }

    iot_logs {
        bigint id PK
        timestamptz ts
        string type
        jsonb data
        string equipment_id FK
        string work_center_id
        string work_order_id
    }
```

### 2.2 Operational Data (Checklist, Bảo trì, Lỗi & Log)

Sơ đồ quan hệ dưới đây minh họa các bảng lưu trữ thông tin phát sinh định kỳ hoặc theo chu kỳ bảo trì, vận hành (tất cả các bảng đều sử dụng prefix `eamo_`):

```mermaid
erDiagram
    eamo_checklist_sessions ||--o{ eamo_checklist_details : "has"
    eamo_maintenance_categories ||--o{ eamo_maintenance_plans : "categorizes"
    eamo_maintenance_categories ||--o{ eamo_maintenance_items : "contains"
    eamo_maintenance_plans ||--o{ eamo_maintenance_schedules : "schedules"
    eamo_maintenance_schedules ||--o{ eamo_maintenance_logs : "records"

    eamo_checklist_sessions {
        string id PK
        string checklist_id
        string equipment_id
        string user_id
        string status
        text notes
        timestamp created_at
        timestamp updated_at
    }

    eamo_checklist_details {
        string id PK
        string checklist_session_id FK
        string checklist_item_id
        string status
        text notes
        timestamp created_at
        timestamp updated_at
    }

    eamo_operating_times {
        string id PK
        string equipment_id
        decimal operating_hours
        date date
        timestamp created_at
        timestamp updated_at
    }

    eamo_equipment_parameter_logs {
        string id PK
        string equipment_id
        string parameter_id
        string value
        datetime logged_at
        timestamp created_at
        timestamp updated_at
    }

    eamo_equipment_error_logs {
        string id PK
        string equipment_id
        string error_id
        string error_code
        text message
        datetime occurred_at
        datetime resolved_at
        timestamp created_at
        timestamp updated_at
    }

    eamo_maintenance_plans {
        string id PK
        string plan_code
        string equipment_id
        time start_time
        time end_time
        time actual_start_time
        time actual_end_time
        date date
        string cycle_type
        integer cycle_interval
        text notes
        string maintenance_type
        string maintenance_category_id FK
        string user_id
        timestamp created_at
        timestamp updated_at
    }

    eamo_maintenance_categories {
        string id PK
        string name
        text description
        timestamp created_at
        timestamp updated_at
    }

    eamo_maintenance_items {
        string id PK
        string maintenance_category_id FK
        string name
        text description
        timestamp created_at
        timestamp updated_at
    }

    eamo_maintenance_schedules {
        string id PK
        string equipment_id
        string maintenance_item_id
        string maintenance_plan_id FK
        date date
        timestamp created_at
        timestamp updated_at
    }

    eamo_maintenance_logs {
        string id PK
        string maintenance_schedule_id FK
        date log_date
        string note
        string result
        string type
        timestamp created_at
        timestamp updated_at
    }
```
