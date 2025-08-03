# Project Rules - BUMDES

## Database Flow Diagram

### Main Tables and Relationships

#### Core Tables

**PENGGUNA (Users)**
- BIGINT UNSIGNED: id (PK, AUTO_INCREMENT)
- VARCHAR(255): name
- VARCHAR(255): email (UNIQUE)
- TIMESTAMP: email_verified_at (NULLABLE)
- VARCHAR(255): password
- VARCHAR(100): role (DEFAULT: 'user')
- REMEMBERTOKEN: remember_token (NULLABLE)
- TIMESTAMPS: created_at, updated_at

**LAPORAN_KEUANGAN (Financial Reports)**
- BIGINT UNSIGNED: id (PK, AUTO_INCREMENT)
- VARCHAR(100): jenis_laporan
- DATE: periode_awal
- DATE: periode_akhir
- BIGINT UNSIGNED: user_id (FK to users.id)
- TIMESTAMPS: created_at, updated_at

**BUKU_BESAR (General Ledger)**
- VARCHAR: id_buku_besar (PK)
- VARCHAR: id_akun (FK)
- DATE: tanggal_posting
- DECIMAL: debit
- DECIMAL: kredit
- VARCHAR: keterangan

**TRANSAKSI (Transactions)**
- VARCHAR: id_transaksi (PK)
- VARCHAR: jenis_transaksi
- DATE: tanggal_transaksi
- DECIMAL: jumlah
- VARCHAR: keterangan
- VARCHAR: id_akun (FK)

#### Supporting Tables

**DAFTAR_AKUN (Chart of Accounts)**
- VARCHAR: id_akun (PK)
- VARCHAR: nama_akun
- VARCHAR: kategori_akun
- DECIMAL: saldo_awal

**DAFTA_MASTER_AKUN (Master Account)**
- VARCHAR: id_akun (PK)
- VARCHAR: nama_akun
- VARCHAR: kategori_akun
- VARCHAR: kode_akun

**DAFTA_MASTER_UNIT (Master Unit)**
- VARCHAR: id_unit (PK)
- VARCHAR: nama_unit
- VARCHAR: kategori_unit
- DECIMAL: nilai_aset

**DAFTA_MASTER_PERSEDIAAN (Master Inventory)**
- VARCHAR: id_barang (PK)
- VARCHAR: nama_barang
- DECIMAL: harga_beli
- DECIMAL: harga_jual

### Flow Relationships

1. **User Management Flow**
   - PENGGUNA (users) → manages → LAPORAN_KEUANGAN (financial_reports)
   - Users can create and manage financial reports through user_id foreign key

2. **Financial Reporting Flow**
   - TRANSAKSI → feeds into → BUKU_BESAR
   - BUKU_BESAR → generates → LAPORAN_KEUANGAN
   - Transactions are posted to general ledger, which generates reports

3. **Account Management Flow**
   - DAFTAR_AKUN → referenced by → TRANSAKSI
   - DAFTAR_AKUN → referenced by → BUKU_BESAR
   - Chart of accounts is the foundation for all financial transactions

4. **Master Data Flow**
   - DAFTA_MASTER_AKUN → provides structure for → DAFTAR_AKUN
   - DAFTA_MASTER_UNIT → defines organizational units
   - DAFTA_MASTER_PERSEDIAAN → manages inventory items

### Key Business Rules

1. **Transaction Processing**
   - All transactions must reference a valid account from DAFTAR_AKUN
   - Transactions automatically create entries in BUKU_BESAR
   - Debit and credit entries must balance

2. **Financial Reporting**
   - Reports are generated from BUKU_BESAR data
   - Each report must have a defined period (periode_awal to periode_akhir)
   - Reports are linked to the user who created them

3. **Master Data Integrity**
   - Master account data provides the template for operational accounts
   - Unit and inventory master data support operational transactions
   - All master data changes should be tracked and audited

4. **User Access Control**
   - All financial operations must be linked to a valid user
   - User permissions should control access to different modules
   - Audit trail should track all user activities

### Data Flow Summary

The system follows a standard accounting flow:
1. Master data setup (accounts, units, inventory)
2. Transaction entry and validation
3. Automatic posting to general ledger
4. Financial report generation
5. User access control and audit trail

This structure ensures proper financial controls and reporting capabilities for the BUMDES (Village-Owned Enterprise) management system.

## Laravel Database Standards

### Table Naming Conventions
- Use plural snake_case for table names (e.g., `users`, `financial_reports`)
- Primary keys should be named `id` (BIGINT UNSIGNED AUTO_INCREMENT)
- Foreign keys should follow pattern `{table_name}_id` (e.g., `user_id`)
- Use `created_at` and `updated_at` timestamps for all tables

### Column Standards
- **Primary Key**: `BIGINT UNSIGNED id AUTO_INCREMENT`
- **Foreign Keys**: `BIGINT UNSIGNED {table}_id`
- **Timestamps**: `created_at`, `updated_at` (automatically managed by Laravel)
- **Email Verification**: `email_verified_at TIMESTAMP NULLABLE`
- **Remember Token**: `remember_token VARCHAR(100) NULLABLE`
- **Soft Deletes**: `deleted_at TIMESTAMP NULLABLE` (when needed)

### Authentication Fields (Users Table)
- `id`: Primary key
- `name`: User's full name
- `email`: Unique email address
- `email_verified_at`: Email verification timestamp
- `password`: Hashed password
- `role`: User role (admin, user, etc.)
- `remember_token`: For "remember me" functionality
- `created_at`, `updated_at`: Automatic timestamps

### Migration Best Practices
- Always use Laravel migrations for database schema
- Use proper data types and constraints
- Add indexes for foreign keys and frequently queried columns
- Use `nullable()` for optional fields
- Use `default()` for fields with default values

## Development Rules

### Laravel Artisan Command Guidelines
- **ALWAYS** use Laravel Artisan commands to generate components instead of creating files manually
- **REQUIRED** Artisan commands for code generation:

#### Controllers
```bash
# Generate basic controller
php artisan make:controller ControllerName

# Generate resource controller with CRUD methods
php artisan make:controller ControllerName --resource

# Generate API resource controller
php artisan make:controller ControllerName --api

# Generate controller with model binding
php artisan make:controller ControllerName --model=ModelName
```

#### Models
```bash
# Generate basic model
php artisan make:model ModelName

# Generate model with migration
php artisan make:model ModelName -m

# Generate model with migration, factory, and seeder
php artisan make:model ModelName -mfs

# Generate model with all options (migration, factory, seeder, controller)
php artisan make:model ModelName -a
```

#### Migrations
```bash
# Create migration for new table
php artisan make:migration create_table_name_table

# Create migration to modify existing table
php artisan make:migration add_column_to_table_name_table --table=table_name

# Create migration to drop table
php artisan make:migration drop_table_name_table
```

#### Seeders
```bash
# Generate seeder
php artisan make:seeder SeederName

# Run specific seeder
php artisan db:seed --class=SeederName

# Run all seeders
php artisan db:seed
```

#### Factories
```bash
# Generate factory
php artisan make:factory FactoryName

# Generate factory for specific model
php artisan make:factory FactoryName --model=ModelName
```

#### Requests (Form Validation)
```bash
# Generate form request
php artisan make:request RequestName
```

#### Resources (API Resources)
```bash
# Generate API resource
php artisan make:resource ResourceName

# Generate API resource collection
php artisan make:resource ResourceName --collection
```

#### Middleware
```bash
# Generate middleware
php artisan make:middleware MiddlewareName
```

#### Jobs
```bash
# Generate job
php artisan make:job JobName
```

#### Events and Listeners
```bash
# Generate event
php artisan make:event EventName

# Generate listener
php artisan make:listener ListenerName

# Generate listener for specific event
php artisan make:listener ListenerName --event=EventName
```

#### Policies
```bash
# Generate policy
php artisan make:policy PolicyName

# Generate policy for specific model
php artisan make:policy PolicyName --model=ModelName
```

### Code Generation Best Practices
- **NEVER** create Laravel components manually - always use Artisan commands
- Use the most appropriate Artisan command flags for your needs (e.g., `-mfs` for models)
- Follow Laravel naming conventions automatically enforced by Artisan commands
- Generate related components together when possible (e.g., model with migration and seeder)
- Always run `php artisan migrate` after creating migrations
- Use `php artisan db:seed` to populate test data after creating seeders

### File Structure Compliance
- All generated files must follow Laravel's default directory structure
- Controllers: `app/Http/Controllers/`
- Models: `app/Models/`
- Migrations: `database/migrations/`
- Seeders: `database/seeders/`
- Factories: `database/factories/`
- Requests: `app/Http/Requests/`
- Resources: `app/Http/Resources/`
- Middleware: `app/Http/Middleware/`
- Policies: `app/Policies/`

### Documentation Guidelines
- **DO NOT** create README files or Swagger documentation until ALL features are completed
- Focus on implementing core functionality first before documentation
- Documentation should only be created after all API endpoints and features are fully tested and working

## URL/Link Management Guidelines

### Development Server Guidelines
- **NEVER** use `php artisan serve` for development or preview
- **ALWAYS** use the configured domain `bumdesku.test` for all development work
- **REQUIRED**: Use Laravel Herd for local development environment
- **FORBIDDEN**: Starting development server with `php artisan serve --port=XXXX`

#### Correct Development Workflow
```bash
# ✅ CORRECT - Use Herd for domain setup
herd park
herd link bumdesku

# ✅ CORRECT - Access application via domain
# Open browser to: http://bumdesku.test

# ❌ WRONG - Do not use artisan serve
# php artisan serve
# php artisan serve --port=8000
```

#### Why Use bumdesku.test Instead of php artisan serve?
- **Consistency**: Same URL across all development environments
- **SSL Support**: Automatic HTTPS with valid certificates
- **Performance**: Better performance than built-in PHP server
- **Real Environment**: Closer to production environment
- **Multiple Projects**: Can run multiple Laravel projects simultaneously
- **No Port Conflicts**: No need to manage different ports

### Route Naming Conventions
- Use descriptive and consistent route names following Laravel conventions
- Use kebab-case for URL segments (e.g., `/financial-reports`, `/user-management`)
- Group related routes using route groups and prefixes
- Use resource routes for CRUD operations when possible

#### Route Examples
```php
// Resource routes (preferred for CRUD)
Route::resource('users', UserController::class);
Route::resource('financial-reports', FinancialReportController::class);
Route::resource('transactions', TransactionController::class);

// Custom routes with descriptive names
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
Route::get('/reports/balance-sheet', [ReportController::class, 'balanceSheet'])->name('reports.balance-sheet');
Route::post('/transactions/approve/{id}', [TransactionController::class, 'approve'])->name('transactions.approve');
```

### URL Structure Standards
- **Base URL**: Use domain-based routing (e.g., `bumdesku.test`)
- **API Routes**: Prefix with `/api/v1/` for API endpoints
- **Admin Routes**: Group admin routes under `/admin/` prefix
- **Public Routes**: Keep public routes at root level
