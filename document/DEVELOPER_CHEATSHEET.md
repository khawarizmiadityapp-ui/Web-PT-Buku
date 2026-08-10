# 🚀 Developer Cheat Sheet - PT Nusantara ERP

Quick reference untuk command dan workflow yang sering dipakai.

---

## 📋 Quick Start

```bash
# Start development server
php artisan serve

# Access application
http://localhost:8000
```

---

## 🗄️ Database Commands

### Migration
```bash
# Run migrations
php artisan migrate

# Rollback last migration
php artisan migrate:rollback

# Rollback all & re-run
php artisan migrate:fresh

# Fresh migrate + seeding
php artisan migrate:fresh --seed
```

### Seeder
```bash
# Run all seeders
php artisan db:seed

# Run specific seeder
php artisan db:seed --class=UserSeeder
php artisan db:seed --class=SupplierSeeder
php artisan db:seed --class=SalesInvoiceSeeder
php artisan db:seed --class=StockOutSeeder
```

### Database Reset (Full)
```bash
# Reset everything (DROP tables, migrate, seed)
php artisan migrate:fresh --seed
```

---

## 🛠️ Artisan Commands

### Create Files
```bash
# Model + Migration
php artisan make:model NamaModel -m

# Controller
php artisan make:controller NamaController

# Seeder
php artisan make:seeder NamaSeeder

# Migration
php artisan make:migration create_nama_table

# Request (Form Validation)
php artisan make:request NamaRequest
```

### Cache Management
```bash
# Clear all cache
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Clear everything at once
php artisan optimize:clear
```

### Routes
```bash
# List all routes
php artisan route:list

# Filter by name
php artisan route:list --name=suppliers

# Filter by method
php artisan route:list --method=GET
```

---

## 🔑 Tinker (Laravel REPL)

```bash
# Start Tinker
php artisan tinker
```

### Common Tinker Commands:
```php
// Get all users
User::all()

// Find user by email
User::where('email', 'admin@ptbuku.com')->first()

// Create new user
User::create(['name' => 'Test', 'email' => 'test@test.com', 'password' => Hash::make('123')])

// Update password
$user = User::find(1);
$user->password = Hash::make('newpassword');
$user->save();

// Delete user
User::find(1)->delete();

// Count records
User::count()
Supplier::count()
SalesInvoice::count()

// Get latest records
SalesInvoice::latest()->take(5)->get()
```

---

## 📁 Project Structure

```
app/
├── Http/Controllers/     # Controllers
├── Models/              # Eloquent Models
└── Providers/           # Service Providers

database/
├── migrations/          # Database migrations
└── seeders/            # Database seeders

resources/
├── views/              # Blade templates
│   ├── auth/          # Authentication views
│   ├── layouts/       # Layout components
│   ├── suppliers/     # Supplier module
│   ├── stock-outs/    # Stock out module
│   └── sales/         # Sales module
└── css/               # Styles

routes/
└── web.php            # Web routes

public/
└── index.php          # Entry point
```

---

## 🔗 Important URLs

```bash
# Login
http://localhost:8000/login

# Dashboard
http://localhost:8000/dashboard

# Suppliers
http://localhost:8000/suppliers

# Stock Out
http://localhost:8000/stock-outs

# Sales Invoices
http://localhost:8000/sales/invoices

# Sales Report
http://localhost:8000/sales/report
```

---

## 🎨 Frontend Assets

### Tailwind CSS
```html
<script src="https://cdn.tailwindcss.com"></script>
```

### Font Awesome
```html
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
```

### Chart.js
```html
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
```

---

## 🐛 Debugging

### View Logs
```bash
# Tail laravel log
tail -f storage/logs/laravel.log

# On Windows (PowerShell)
Get-Content storage\logs\laravel.log -Wait -Tail 50
```

### Debug Mode
```env
# In .env file
APP_DEBUG=true
```

### Common Errors & Solutions

#### "Class not found"
```bash
composer dump-autoload
```

#### "Route not found"
```bash
php artisan route:clear
php artisan route:cache
```

#### "View not found"
```bash
php artisan view:clear
```

#### "Database connection error"
```bash
# Check .env file
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=PTbuku_nusantara
DB_USERNAME=root
DB_PASSWORD=
```

---

## 📊 SQL Direct Access

### Via Tinker
```bash
php artisan tinker
DB::table('users')->get()
DB::select('SELECT * FROM suppliers')
```

### Via MySQL Client
```bash
# Connect to database
mysql -u root -p

# Select database
USE PTbuku_nusantara;

# Query
SELECT * FROM users;
SELECT * FROM suppliers;
SELECT * FROM sales_invoices;
```

---

## 🔄 Git Workflow (Optional)

```bash
# Check status
git status

# Add changes
git add .

# Commit
git commit -m "Add sales module"

# Push
git push origin main

# Pull latest
git pull origin main
```

---

## 💡 Pro Tips

### 1. Auto-reload on file change
```bash
# Install browser-sync or use Laravel Mix
npm install
npm run watch
```

### 2. Generate fake data
```php
// In tinker
User::factory()->count(10)->create()
```

### 3. Quick test query
```bash
php artisan tinker
>>> User::count()
=> 6
```

### 4. Database backup
```bash
# Export
mysqldump -u root PTbuku_nusantara > backup.sql

# Import
mysql -u root PTbuku_nusantara < backup.sql
```

---

## 📞 Quick Reference

| Task | Command |
|------|---------|
| Start server | `php artisan serve` |
| Run migration | `php artisan migrate` |
| Run seeder | `php artisan db:seed` |
| Reset DB | `php artisan migrate:fresh --seed` |
| Clear cache | `php artisan optimize:clear` |
| List routes | `php artisan route:list` |
| Open tinker | `php artisan tinker` |
| View logs | `tail -f storage/logs/laravel.log` |

---

**💻 Happy Coding!**
