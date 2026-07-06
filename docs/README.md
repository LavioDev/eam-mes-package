# EAM-MES Package — Tài liệu kỹ thuật

## Danh sách tài liệu

| Tài liệu | Mô tả |
|---|---|
| [Modules & Database Structure](modules_and_db.md) | Mô tả các submodule và sơ đồ ERD database |
| [Dynamic Table Extension](dynamic_table_extension.md) | Cơ chế mở rộng schema động — ý tưởng, kiến trúc, hướng dẫn triển khai |
| [HTTP Table Extension Plan](http_extension_plan.md) | Kế hoạch tích hợp chức năng thêm trường qua API HTTP (Auto Queue) |

---

## Tổng quan Package

**EAM-MES Package** là một Laravel package cung cấp nền tảng cho hệ thống quản lý
sản xuất (Manufacturing Execution System) và quản lý thiết bị (Equipment Asset Management).

### Các tính năng chính

| Tính năng | Mô tả |
|---|---|
| **Base Migrations** | Các bảng chuẩn `eamo_*` cho 4 submodule |
| **Submodule Publishing** | `eam-mes:publish` — copy code files vào ứng dụng |
| **Dynamic Table Extension** | `eam:sync-extensions` — sinh migration bổ sung trường |

### Submodules

| Submodule | Bảng DB | Chức năng |
|---|---|---|
| `core` | `eam_extension_requests` | Quản lý yêu cầu mở rộng bảng (bảng lưu request API) |
| `checklist` | `eamo_checklist_sessions`, `eamo_checklist_details` | Quản lý kiểm tra thiết bị |
| `error-monitoring` | `eamo_equipment_error_logs`, `eamo_operating_times` | Theo dõi lỗi thiết bị |
| `maintenance` | `eamo_maintenance_plans`, `eamo_maintenance_schedules`, `eamo_maintenance_items`, `eamo_maintenance_categories`, `eamo_maintenance_logs` | Quản lý bảo trì |
| `parameter-log` | `eamo_equipment_parameter_logs` | Ghi log thông số vận hành |

### Artisan Commands

| Command | Mô tả |
|---|---|
| `eam-mes:publish --all` | Publish tất cả submodule vào ứng dụng |
| `eam-mes:publish --submodule=<name>` | Publish một submodule cụ thể |
| `eam:sync-extensions` | Sinh migration từ các Extension class đã đăng ký |
| `eam:sync-extensions --dry-run` | Xem trước không ghi file |
| `eam:sync-extensions --migrate` | Sinh file và chạy migrate luôn |
