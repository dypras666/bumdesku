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

## System Settings Integration Guidelines

### Overview
The BUMDES system includes a comprehensive system settings module that manages configurable parameters across the entire application. When developing new features, **ALWAYS** check if the feature requires configurable settings and integrate with the system settings module accordingly.

### System Settings Structure

#### Available Setting Groups
1. **Company Settings** (`company` group)
   - Company name, address, contact information
   - Logo, letterhead, and branding assets
   - Legal information and registration details

2. **Financial Settings** (`financial` group)
   - Default currency and formatting
   - Tax rates and calculation methods
   - Account numbering schemes
   - Financial year settings

3. **Journal Settings** (`journal` group)
   - Journal entry templates
   - Approval workflows
   - Posting rules and validations

4. **Report Settings** (`report` group)
   - Report headers and footers
   - Default report formats
   - Logo and branding for reports

5. **System Settings** (`system` group)
   - Application behavior settings
   - User interface preferences
   - Performance and caching options

### Integration Requirements

#### When to Use System Settings
**MANDATORY** integration with system settings for:
- Any configurable business rules or parameters
- User interface customization options
- Company branding and identity elements
- Financial calculation parameters
- Report formatting and templates
- Email templates and notifications
- Default values for forms and processes

#### How to Integrate System Settings

##### 1. Using Helper Functions
```php
// Get company information
$companyName = company_info('name');
$companyLogo = company_info('logo');
$allCompanyInfo = company_info(); // Returns all company settings

// Get specific settings
$currency = setting('default_currency');
$taxRate = setting('default_tax_rate');

// Get formatted settings
$formattedCurrency = setting_formatted('default_currency');
$formattedAmount = format_currency(1000); // Uses system currency settings

// Get financial settings
$financialSettings = financial_settings();

// Get journal settings
$journalSettings = journal_settings();
```

##### 2. Using SystemSettingHelper Class
```php
use App\Helpers\SystemSettingHelper;

// Get settings by group
$companySettings = SystemSettingHelper::getCompanyInfo();
$financialSettings = SystemSettingHelper::getFinancialSettings();
$journalSettings = SystemSettingHelper::getJournalSettings();

// Get individual settings
$setting = SystemSettingHelper::get('setting_key');
$settingWithDefault = SystemSettingHelper::get('setting_key', 'default_value');

// Set settings programmatically
SystemSettingHelper::set('setting_key', 'new_value');

// Clear cache after changes
SystemSettingHelper::clearSystemCache();
```

##### 3. In Blade Templates
```blade
{{-- Company information --}}
<h1>{{ company_info('name') }}</h1>
<img src="{{ asset('storage/' . company_info('logo')) }}" alt="Logo">

{{-- Financial formatting --}}
<span>{{ format_currency($amount) }}</span>

{{-- Settings with defaults --}}
<p>Records per page: {{ setting('records_per_page', 25) }}</p>
```

### Development Workflow for Settings-Dependent Features

#### Step 1: Identify Configurable Elements
Before implementing any feature, identify:
- What parameters should be configurable by administrators?
- What default values make sense for the business?
- What branding or formatting elements are needed?
- What business rules might vary between installations?

#### Step 2: Create Settings Entries
Add new settings to the SystemSettingSeeder:
```php
// In database/seeders/SystemSettingSeeder.php
[
    'key' => 'feature_setting_name',
    'value' => 'default_value',
    'type' => 'text', // text, number, boolean, file
    'group' => 'appropriate_group',
    'description' => 'Human-readable description',
    'is_protected' => false // true for system-critical settings
]
```

#### Step 3: Implement Feature Logic
Use the helper functions throughout your feature implementation:
```php
// In Controllers
public function index()
{
    $perPage = setting('records_per_page', 25);
    $data = Model::paginate($perPage);
    return view('feature.index', compact('data'));
}

// In Models
public function getFormattedAmountAttribute()
{
    return format_currency($this->amount);
}

// In Services
public function calculateTax($amount)
{
    $taxRate = setting('default_tax_rate', 0);
    return $amount * ($taxRate / 100);
}
```

#### Step 4: Update Settings Interface
Ensure new settings appear in the system settings interface by:
- Running the seeder to populate new settings
- Verifying settings appear in the appropriate group
- Testing the settings update functionality

### Cache Management

#### Automatic Cache Clearing
The system automatically clears cache when settings are updated through:
- `SystemSettingController::store()` - New settings
- `SystemSettingController::update()` - Individual updates
- `SystemSettingController::updateBatch()` - Batch updates
- `SystemSettingController::destroy()` - Setting deletion

#### Manual Cache Clearing
```php
// Clear all system caches
clear_system_cache();

// Or using the helper class
SystemSettingHelper::clearSystemCache();

// Via Artisan command
php artisan optimize:clear
```

### Best Practices

#### 1. Setting Key Naming
- Use snake_case for setting keys
- Group related settings with prefixes (e.g., `email_smtp_host`, `email_smtp_port`)
- Use descriptive names that indicate purpose

#### 2. Default Values
- Always provide sensible default values
- Consider the most common use case for defaults
- Document why specific defaults were chosen

#### 3. Setting Types
- Use appropriate types: `text`, `number`, `boolean`, `file`
- Validate input based on type
- Handle file uploads properly for `file` type settings

#### 4. Performance Considerations
- Settings are cached for performance
- Avoid frequent setting updates in loops
- Use batch updates when changing multiple settings

#### 5. Security
- Mark sensitive settings as protected (`is_protected = true`)
- Validate all setting inputs
- Sanitize file uploads for security

### Examples of Good Integration

#### Financial Report Generation
```php
public function generateReport()
{
    $report = new FinancialReport();
    
    // Use company settings for headers
    $report->setCompanyName(company_info('name'));
    $report->setCompanyLogo(company_info('logo'));
    
    // Use financial settings for formatting
    $report->setCurrency(setting('default_currency'));
    $report->setDateFormat(setting('date_format'));
    
    // Use report settings for layout
    $report->setHeaderText(setting('report_header_text'));
    $report->setFooterText(setting('report_footer_text'));
    
    return $report->generate();
}
```

#### Email Notifications
```php
public function sendNotification($user, $data)
{
    $notification = new EmailNotification();
    
    // Use company settings for sender info
    $notification->setFromName(company_info('name'));
    $notification->setFromEmail(setting('notification_email'));
    
    // Use system settings for templates
    $template = setting('email_template_' . $data['type']);
    $notification->setTemplate($template);
    
    return $notification->send($user);
}
```

### Testing Settings Integration

#### Unit Tests
```php
public function test_feature_uses_system_settings()
{
    // Set test setting
    SystemSettingHelper::set('test_setting', 'test_value');
    
    // Test feature behavior
    $result = $this->service->processWithSettings();
    
    // Assert setting was used
    $this->assertEquals('expected_result', $result);
}
```

#### Feature Tests
```php
public function test_settings_affect_feature_output()
{
    // Update setting via HTTP
    $this->put('/system-settings/batch', [
        'settings' => ['feature_setting' => 'new_value']
    ]);
    
    // Test feature reflects new setting
    $response = $this->get('/feature');
    $response->assertSee('new_value');
}
```

### Migration and Deployment

#### Adding New Settings
1. Add settings to `SystemSettingSeeder`
2. Run seeder in production: `php artisan db:seed --class=SystemSettingSeeder`
3. Clear cache: `php artisan optimize:clear`

#### Removing Settings
1. Remove from seeder
2. Create migration to remove from database if needed
3. Update code to remove references
4. Clear cache after deployment

This integration ensures that all features are properly configurable and maintainable through the centralized system settings interface.
