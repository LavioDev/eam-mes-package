# Modules & Database Structure - EAM MES Package

The `eam-mes-package` is designed to provide core modules for Equipment Asset Management (EAM) and Manufacturing Execution Systems (MES).

---

## 1. Package Submodules

The package consists of the following 5 main submodules:

### 1.1 Checklist
- **Function**: Manages the equipment check processes prior to operation or periodically. It configures checklist items, records checklist sessions, and logs detailed inspection results for each item.
- **Path**: `src/Checklist/`

### 1.2 Error Monitoring
- **Function**: Tracks and monitors equipment error history. It stores records about when errors occur, error codes, error descriptions, and recovery times.
- **Path**: `src/ErrorMonitoring/`

### 1.3 Maintenance
- **Function**: Manages the complete equipment maintenance lifecycle, including maintenance plans, schedules, category definitions, target maintenance items, and actual maintenance history logs.
- **Path**: `src/Maintenance/`

### 1.4 Parameter Log
- **Function**: Logs real-time operational parameters of the equipment (such as temperature, pressure, voltage, frequency, etc.) over time.
- **Path**: `src/ParameterLog/`

### 1.5 Thingsboard
- **Function**: Integrates IoT data directly from the Thingsboard platform, allowing the package to capture telemetry and synchronize device actions.
- **Path**: `src/Thingsboard/`

---

## 2. Database Schema (Mermaid ERD)

The relationship diagram below illustrates the database tables created by the migrations, prefixed with `eamo_`:

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
