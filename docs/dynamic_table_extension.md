# Dynamic Table Extension — Tài liệu kỹ thuật

> Tài liệu này mô tả ý tưởng thiết kế, kiến trúc, và hướng dẫn triển khai tính năng
> **Dynamic Table Extension** trong EAM-MES Package.

---

## 1. Bối cảnh & Vấn đề

Package EAM-MES cung cấp các bảng cơ sở (`eamo_*`) cho hệ thống quản lý sản xuất.
Mỗi ứng dụng sử dụng package đều có nhu cầu khác nhau — một số cần thêm trường
`department_id`, số khác cần `is_urgent`, `custom_notes`, v.v.

**Vấn đề đặt ra**: Làm thế nào để ứng dụng bổ sung cột vào bảng của package mà
không cần sửa source code của package?

Các cách tiếp cận thông thường đều có hạn chế:

| Cách | Hạn chế |
|---|---|
| Tự viết migration thủ công | Không có validation, không sinh `down()` tự động, không detect trùng lặp |
| Closure đăng ký trong `boot()` | Không introspect được closure → không sinh `down()` |
| Alter schema từ HTTP request | Race condition, bypass CI/CD, không audit trail |
| Alter schema khi app khởi động | Chạy mỗi request → thảm họa performance |

---

## 2. Ý tưởng thiết kế

### Nguyên tắc cốt lõi

> Mọi thay đổi schema **đều phải đi qua migration file**.  
> Package chỉ là công cụ **sinh** file đó — không bao giờ tự alter DB.

Người dùng khai báo ý định mở rộng bằng **class** (không phải closure, không phải HTTP).
Package đọc khai báo đó, validate, sinh migration file hoàn chỉnh, người dùng commit file
vào git và chạy `migrate` như bình thường.

### Tại sao dùng class thay vì closure?

```
Closure:  $table->string('dept_id', 36)->nullable();
          ↓
          Package không biết: tên cột gì? kiểu gì?
          → Không thể tự sinh dropColumn('???') cho down()

Class (ColumnDefinition):  name='dept_id', type='string', length=36, nullable=true
          ↓
          Package biết tất cả → tự sinh down() hoàn chỉnh ✅
```

**`ColumnDefinition` là Value Object bất biến** — mô tả đặc tả của một cột dưới dạng
dữ liệu thuần, không phải hành vi. Đây là nền tảng cho toàn bộ cơ chế.

---

## 3. Kiến trúc tổng thể

```
┌─────────────────────────────────────────────────────────────────┐
│  Ứng dụng của người dùng                                        │
│                                                                 │
│  config/eam.php                                                 │
│    └── 'extensions' => [MaintenancePlanExtension::class, ...]   │
│                                                                 │
│  app/Extensions/MaintenancePlanExtension.php                    │
│    └── implements TableExtension                                │
│         ├── targetTable(): 'eamo_maintenance_plans'             │
│         ├── columns(): [ColumnDefinition::make(...), ...]       │
│         └── priority(): 10                                      │
└───────────────────────────┬─────────────────────────────────────┘
                            │  php artisan eam:sync-extensions
                            ▼
┌─────────────────────────────────────────────────────────────────┐
│  EAM-MES Package                                                │
│                                                                 │
│  SyncExtensionsCommand                                          │
│    ├── ExtensionRegistry::resolve()    ← đọc config             │
│    ├── ExtensionValidator::validate()  ← kiểm tra hợp lệ       │
│    ├── MigrationFileChecker            ← phát hiện trùng lặp   │
│    └── MigrationGenerator             ← sinh file migration    │
│         └── StubRenderer              ← render up() + down()   │
└───────────────────────────┬─────────────────────────────────────┘
                            │  ghi file
                            ▼
              database/migrations/
              2026_07_05_120000_extend_eamo_maintenance_plans_table.php
                            │
                            │  php artisan migrate
                            ▼
                       Database ✅
```

---

## 4. Cấu trúc thư mục

```
src/
├── Contracts/
│   └── TableExtension.php              ← Interface người dùng implement
│
├── Extensions/
│   ├── ColumnDefinition.php            ← Value object mô tả 1 cột
│   ├── ExtensionRegistry.php           ← Đọc config, resolve, group by table
│   └── ExtensionValidator.php          ← Validate whitelist, tên cột, kiểu
│
├── Migration/
│   ├── StubRenderer.php                ← Chuyển ColumnDefinition → PHP source
│   ├── MigrationFileChecker.php        ← Detect duplicate (file + DB)
│   └── MigrationGenerator.php         ← Render stub → ghi file
│
├── Commands/
│   └── SyncExtensionsCommand.php       ← php artisan eam:sync-extensions
│
└── Exceptions/
    └── InvalidExtensionException.php   ← Exception với factory methods

database/
└── stubs/
    └── add_columns.stub                ← Template migration

config/
└── eam.php                             ← Config publishable
```

---

## 5. Luồng hoạt động chi tiết

### Bước 1 — Đọc config

`ExtensionRegistry::resolve()` đọc mảng class từ `config('eam.extensions')`, khởi tạo
từng class qua Laravel DI container (`app($class)`), kiểm tra implement đúng interface,
rồi **sắp xếp theo `priority()`** và **group theo `targetTable()`**.

```
config('eam.extensions') = [A::class, B::class, C::class]
                                ↓
Collection keyed by table:
  'eamo_maintenance_plans' => [A, C]   (sorted by priority)
  'eamo_checklist_details' => [B]
```

### Bước 2 — Validate

`ExtensionValidator::validate()` kiểm tra:
- Table có trong **whitelist** của package không?
- Tên cột có đúng format `[a-z][a-z0-9_]*` không?
- Kiểu cột có trong danh sách được hỗ trợ không?
- Có tên cột trùng lặp giữa các Extension không?

### Bước 3 — Phát hiện trùng lặp (2 tầng)

`MigrationFileChecker::filterNewColumns()` lọc ra chỉ những cột **thực sự cần migration mới**:

**Tầng 1 — Scan file migration:**
```
database/migrations/*extend_{table}_table*
  → Đọc nội dung → regex tìm $table->someType('column_name'
  → Lấy danh sách tên cột đã có trong file
```

**Tầng 2 — Query DB thực tế:**
```
Schema::getColumnListing($table)
  → Cột đang thực sự tồn tại trong DB
  → Bắt trường hợp file đã chạy rồi bị xóa
```

Một cột chỉ được sinh migration khi nó **không có trong cả 2 tầng**.

### Bước 4 — Render migration

`StubRenderer` chuyển từng `ColumnDefinition` thành dòng PHP:

```
ColumnDefinition:
  name='department_id', type='string', length=36, nullable=true, after='user_id'
                    ↓
up():   $table->string('department_id', 36)->nullable()->after('user_id');
down(): $table->dropColumn('department_id');
```

Template `add_columns.stub` được điền các placeholder:
- `{{ table }}` → tên bảng
- `{{ up_columns }}` → các dòng `$table->...`
- `{{ down_columns }}` → các dòng `$table->dropColumn(...)`
- `{{ extension_classes }}` → tên các class nguồn (cho comment header)
- `{{ generated_at }}` → timestamp sinh file

### Bước 5 — Ghi file

`MigrationGenerator` ghi file theo naming convention:
```
{Y_m_d_His}_extend_{table}_table.php
```
Ví dụ: `2026_07_05_120000_extend_eamo_maintenance_plans_table.php`

---

## 6. Thiết kế các thành phần chính

### `TableExtension` Interface

```php
interface TableExtension
{
    public function targetTable(): string;   // bảng cần mở rộng
    public function columns(): array;        // ColumnDefinition[]
    public function priority(): int;         // thứ tự (nhỏ hơn = trước)
}
```

### `ColumnDefinition` Value Object

Bất biến (immutable) — mỗi modifier trả về instance mới:

```php
ColumnDefinition::make('department_id', 'string')
    ->length(36)        // → instance mới
    ->nullable()        // → instance mới
    ->after('user_id'); // → instance mới
```

Hỗ trợ các kiểu: `string`, `integer`, `bigInteger`, `boolean`, `text`, `longText`,
`mediumText`, `json`, `jsonb`, `date`, `dateTime`, `timestamp`, `decimal`, `float`,
`double`, `tinyInteger`, `smallInteger`, `unsignedInteger`, `unsignedBigInteger`.

### `SyncExtensionsCommand` — Artisan Command

```bash
php artisan eam:sync-extensions [options]
```

| Option | Mô tả |
|---|---|
| `--dry-run` | Xem trước, không ghi file |
| `--migrate` | Tự chạy `migrate` sau khi sinh file |
| `--force` | Bỏ qua duplicate detection, sinh lại toàn bộ |

Exit codes: `0` = thành công, `1` = thất bại → tích hợp được với CI/CD.

### `ExtensionRegistry` — Whitelist

Chỉ các bảng sau mới được phép mở rộng:

| Bảng | Module |
|---|---|
| `eamo_maintenance_plans` | Maintenance |
| `eamo_maintenance_schedules` | Maintenance |
| `eamo_maintenance_items` | Maintenance |
| `eamo_maintenance_categories` | Maintenance |
| `eamo_maintenance_logs` | Maintenance |
| `eamo_checklist_details` | Checklist |
| `eamo_checklist_sessions` | Checklist |
| `eamo_equipment_parameter_logs` | Parameter Log |
| `eamo_equipment_error_logs` | Error Monitoring |
| `eamo_operating_times` | Error Monitoring |

---

## 7. Hướng dẫn triển khai

### Cài đặt & Publish config

```bash
php artisan vendor:publish --tag="eam-mes-package-config"
```

File `config/eam.php` được tạo trong ứng dụng của bạn.

### Tạo Extension class

```php
<?php
// app/Extensions/MaintenancePlanExtension.php

namespace App\Extensions;

use Spatie\LaravelPackageTools\Contracts\TableExtension;
use Spatie\LaravelPackageTools\Extensions\ColumnDefinition;

class MaintenancePlanExtension implements TableExtension
{
    public function targetTable(): string
    {
        return 'eamo_maintenance_plans';
    }

    public function columns(): array
    {
        return [
            ColumnDefinition::make('department_id', 'string')
                ->length(36)
                ->nullable()
                ->after('user_id'),

            ColumnDefinition::make('is_urgent', 'boolean')
                ->default(false),

            ColumnDefinition::make('custom_notes', 'text')
                ->nullable(),
        ];
    }

    public function priority(): int
    {
        return 10;
    }
}
```

### Đăng ký trong config

```php
// config/eam.php
'extensions' => [
    App\Extensions\MaintenancePlanExtension::class,
],
```

### Sinh và chạy migration

```bash
# Xem trước (không ghi file)
php artisan eam:sync-extensions --dry-run

# Sinh file migration
php artisan eam:sync-extensions

# Sinh và migrate luôn
php artisan eam:sync-extensions --migrate

# Chạy migrate thủ công
php artisan migrate
```

### Output mẫu

```
Reading extensions from config/eam.php...

Table: eamo_maintenance_plans
+--------------+-------------+----------+---------+--------+
| Column       | Type        | Nullable | Default | After  |
+--------------+-------------+----------+---------+--------+
| department_id| string(36)  | true     | null    | user_id|
| is_urgent    | boolean     | false    | false   | —      |
| custom_notes | text        | true     | null    | —      |
+--------------+-------------+----------+---------+--------+
  ✓  Generated: 2026_07_05_120000_extend_eamo_maintenance_plans_table.php

Run php artisan migrate to apply the generated migration(s).
```

### File được sinh ra

```php
// database/migrations/2026_07_05_120000_extend_eamo_maintenance_plans_table.php

return new class extends Migration {
    public function up(): void
    {
        Schema::table('eamo_maintenance_plans', function (Blueprint $table) {
            $table->string('department_id', 36)->nullable()->after('user_id');
            $table->boolean('is_urgent')->default(false);
            $table->text('custom_notes')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('eamo_maintenance_plans', function (Blueprint $table) {
            $table->dropColumn('department_id');
            $table->dropColumn('is_urgent');
            $table->dropColumn('custom_notes');
        });
    }
};
```

---

## 8. Rollback

```bash
php artisan migrate:rollback --step=1
```

`down()` được sinh tự động và đầy đủ, nên rollback hoạt động hoàn toàn qua hệ thống
migration chuẩn của Laravel.

### Các tình huống đặc biệt

| Tình huống | Cách xử lý |
|---|---|
| Xóa Extension khỏi config | File migration vẫn còn → rollback vẫn hoạt động |
| Thêm cột mới vào Extension đã có | Sinh thêm file migration **mới** — không sửa file cũ |
| Cần đổi tên cột | Viết migration thủ công (package không hỗ trợ rename) |
| Cần thay đổi kiểu cột | Viết migration thủ công (package không hỗ trợ change) |
| Cần thêm index, foreign key | Viết migration thủ công |

> **Quy tắc bất biến**: Package chỉ sinh migration `addColumn`. File migration đã sinh
> là bất biến — không bao giờ bị sửa. Thay đổi mới = file migration mới.

---

## 9. Lưu ý triển khai production

### ✅ Nên làm

- Để `auto_migrate = false` trên production (đây là mặc định)
- Chạy `--dry-run` để review trước
- Commit file migration vào git trước khi deploy
- Chạy `eam:sync-extensions` trong CI/CD pipeline như một bước deployment
- Test rollback trên staging trước khi lên production

### ❌ Không nên làm

- Bật `auto_migrate = true` trên production
- Sinh migration trực tiếp trên server production
- Trigger thay đổi schema từ HTTP request
- Gọi `eam:sync-extensions` trong `AppServiceProvider::boot()`

### Tích hợp CI/CD

```yaml
# .github/workflows/deploy.yml (ví dụ)
- name: Sync extension migrations
  run: php artisan eam:sync-extensions

- name: Run migrations
  run: php artisan migrate --force
```

---

## 10. Giới hạn của tính năng

Tính năng này chỉ hỗ trợ **thêm cột đơn giản**. Các thao tác phức tạp hơn vẫn cần
viết migration thủ công:

| Thao tác | Extension | Migration thủ công |
|---|:---:|:---:|
| Thêm cột | ✅ | ✅ |
| Xóa cột (rollback) | ✅ (tự động) | ✅ |
| Đổi tên cột | ❌ | ✅ |
| Đổi kiểu cột | ❌ | ✅ |
| Thêm index đơn | ❌ | ✅ |
| Thêm foreign key | ❌ | ✅ |
| Index kết hợp nhiều cột | ❌ | ✅ |

---

## 11. Đăng ký trong ServiceProvider

`EamMesPackageServiceProvider` bind các service vào container và đăng ký command:

```php
public function packageRegistered(): void
{
    $this->app->singleton(StubRenderer::class);
    $this->app->singleton(MigrationFileChecker::class);
    $this->app->singleton(ExtensionValidator::class);

    $this->app->singleton(MigrationGenerator::class, function ($app) {
        return new MigrationGenerator($app->make(StubRenderer::class));
    });
}
```

`SyncExtensionsCommand` nhận các dependency qua constructor injection — không có static
call hay global state — dễ test và dễ mock.
