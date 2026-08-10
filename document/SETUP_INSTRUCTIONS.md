# Setup Instructions - PT Nusantara ERP System

## 🎯 Yang Sudah Dibuat

### 1. **Database Structure**
- ✅ Migration untuk `suppliers` table
- ✅ Migration untuk `stock_outs` table
- ✅ Model `Supplier` dengan SoftDeletes
- ✅ Model `StockOut` dengan SoftDeletes

### 2. **Controllers**
- ✅ `SupplierController` - Full CRUD + Search + Filter + Export
- ✅ `StockOutController` - Full CRUD + Search + Filter + Date Range + Export

### 3. **Views**
- ✅ `suppliers/index.blade.php` - List supplier dengan filter dan pagination
- ✅ `stock-outs/index.blade.php` - List barang keluar dengan filter
- ✅ `layouts/sidebar.blade.php` - Reusable sidebar component
- ✅ `layouts/header.blade.php` - Reusable header component

### 4. **Seeders**
- ✅ `SupplierSeeder` - 4 data dummy supplier
- ✅ `StockOutSeeder` - 5 data dummy transaksi keluar

### 5. **Routes**
- ✅ Resource routes untuk suppliers
- ✅ Resource routes untuk stock-outs
- ✅ Export routes

---

## 🚀 Cara Menjalankan

### Step 1: Buat Database
**Buka phpMyAdmin** (http://localhost/phpmyadmin) dan jalankan SQL ini:

```sql
CREATE DATABASE IF NOT EXISTS PTbuku_nusantara CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

**ATAU** gunakan file `create_database.sql` yang sudah saya buat.

### Step 2: Jalankan Migration
```bash
php artisan migrate
```

Ini akan membuat semua tabel:
- users
- suppliers
- stock_outs
- migrations
- cache
- jobs
- sessions

### Step 3: Jalankan Seeder (Data Dummy)
```bash
php artisan db:seed
```

Atau kalau mau manual:
```bash
php artisan db:seed --class=SupplierSeeder
php artisan db:seed --class=StockOutSeeder
```

### Step 4: Jalankan Development Server
```bash
php artisan serve
```

Buka browser: **http://localhost:8000**

---

## 📋 Fitur yang Sudah Jalan

### ✅ Halaman Supplier (`/suppliers`)
- **List Supplier** dengan tabel lengkap
- **Search** by: Supplier Code, Name, Company, Phone, Email
- **Filter** by Status (Aktif/Non-aktif)
- **Pagination** (10 items per page)
- **Status Badge** (Green untuk Aktif, Red untuk Non-aktif)
- **Actions**: Edit, Delete
- **Button**: Tambah Supplier, Export

### ✅ Halaman Stock Out (`/stock-outs`)
- **List Barang Keluar** dengan tabel lengkap
- **Search** by: Transaction ID, Customer Name, Recipient Name
- **Filter** by:
  - Date Range (From - To)
  - Status (Completed/In-Progress/Canceled)
- **Pagination** (10 items per page)
- **Status Badge**: 
  - Green = Completed
  - Yellow = In-Progress
  - Red = Canceled
- **Actions**: View, Edit, Delete
- **Button**: Input Barang Keluar, Export

---

## 🔐 Login Credentials
Lihat file **`LOGIN_CREDENTIALS.md`** untuk daftar lengkap semua akun.

**Quick Access:**

| Role | Email | Password |
|------|-------|----------|
| Super Admin | admin@ptbuku.com | admin123 |
| Manager | manager@ptbuku.com | manager123 |
| Warehouse | warehouse@ptbuku.com | warehouse123 |
| Sales | sales@ptbuku.com | sales123 |
| Finance | finance@ptbuku.com | finance123 |
| Customer Service | cs@ptbuku.com | cs123 |

---

## 📁 File Structure
```
app/
├── Models/
│   ├── Supplier.php
│   └── StockOut.php
├── Http/Controllers/
│   ├── SupplierController.php
│   └── StockOutController.php

database/
├── migrations/
│   ├── xxxx_create_suppliers_table.php
│   └── xxxx_create_stock_outs_table.php
└── seeders/
    ├── SupplierSeeder.php
    └── StockOutSeeder.php

resources/views/
├── layouts/
│   ├── sidebar.blade.php
│   └── header.blade.php
├── suppliers/
│   └── index.blade.php
└── stock-outs/
    └── index.blade.php

routes/
└── web.php (updated with new routes)
```

---

## 🎨 Design Sesuai Mockup
- ✅ Header dengan tabs: Dashboard, Inventory, Logistics
- ✅ Sidebar dengan menu navigasi
- ✅ Search bar dan filter dropdown
- ✅ Table dengan styling modern
- ✅ Status badges dengan warna
- ✅ Action buttons dengan icons
- ✅ Pagination di bawah table

---

## 🔜 Yang Belum (To-Do Next)
1. **Form Create/Edit** untuk Supplier dan Stock Out
2. **View Detail** untuk Stock Out
3. **Export Excel** functionality
4. **Dashboard** dengan chart dan statistik real data
5. **Master Data** menu (Products, Categories, dll)
6. **Warehouse** features
7. **Reports** module

---

## ⚡ Quick Commands
```bash
# Refresh database (hapus semua data dan mulai dari awal)
php artisan migrate:fresh --seed

# Clear cache
php artisan cache:clear
php artisan config:clear
php artisan route:clear

# Lihat semua routes
php artisan route:list

# Create new seeder
php artisan make:seeder NamaSeeder

# Create new controller
php artisan make:controller NamaController

# Create new model with migration
php artisan make:model NamaModel -m
```

---

## 🐛 Troubleshooting

### Database Connection Error
1. Pastikan Laragon MySQL sudah running
2. Cek `.env` file:
   ```
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=PTbuku_nusantara
   DB_USERNAME=root
   DB_PASSWORD=
   ```

### Migration Error
```bash
php artisan migrate:fresh
```

### Seeder Error
```bash
php artisan db:seed --class=DatabaseSeeder
```

---

**🎉 Selamat! Sistem ERP PT Nusantara sudah siap digunakan!**
