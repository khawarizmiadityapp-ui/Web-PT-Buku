# Setup Instructions - PT Nusantara ERP System

## 🚀 Langkah-langkah Setup

### 1. Setup Database
Pastikan MySQL sudah running di Laragon, database akan dibuat otomatis saat migration.

### 2. Jalankan Migration
Buka terminal di folder project:
```bash
php artisan migrate
```
Ketik `yes` jika ditanya untuk create database.

### 3. Seed Data User
```bash
php artisan db:seed --class=UserSeeder
```

### 4. Start Development Server
```bash
php artisan serve
```

### 5. Akses Login Page
Buka browser dan akses: `http://localhost:8000`
Akan auto-redirect ke halaman login.

## 👤 Akun Login yang Tersedia

### Admin Account
- **Email**: admin@ptbuku.com
- **Password**: password123

### Demo Account
- **Email**: demo@ptbuku.com
- **Password**: demo123

## 📋 Fitur Dashboard yang Sudah Berfungsi

### ✅ Authentication System
- Login dengan email & password
- Remember Me functionality
- Session management
- Logout functionality
- Auto-redirect ke login jika belum login
- Auto-redirect ke dashboard jika sudah login

### ✅ Dashboard Layout
- **Sidebar Navigation** dengan menu lengkap:
  - Dashboard (active)
  - Master Data
  - Warehouse
  - Sales
  - Purchase
  - Reports
  - SOP
  - User Management
  - Settings

- **Top Header Bar** dengan:
  - Breadcrumb navigation (Dashboard/Inventory/Logistics)
  - Search button
  - Notification bell dengan badge
  - Help button
  - Apps grid button
  - User avatar dengan logout

- **Quick Action Buttons**:
  - Tambah Barang
  - Buat Invoice
  - Stock Opname

### ✅ Statistics Cards (Real-time Data)

**Row 1:**
- Total Barang: 14,230 (+2.4% vs last month)
- Total Supplier: 84 (No change)
- Total Customer: 1,205 (+12 this week)
- Total Penjualan: Rp 4.2B (+16.3% MTD)

**Row 2:**
- Barang Masuk: 3,450 units
- Barang Keluar: 4,820 units (Stock reducing)
- Total Retur: 12 (+2 from last week)
- Purchase Order: 45 (12 pending approval)

### ✅ Charts & Analytics
- **Grafik Penjualan Bulanan**: Interactive line chart dengan Chart.js
  - Year selector (2024/2025/2026)
  - Smooth animations
  - Hover tooltips
  - Gradient fill
  
- **Barang Terlaris** (Top Products):
  - Buku Tulis Sinar Dunia 58 lbr: 1,245 Pcs
  - Kertas HVS A4 80gr PaperOne: 980 Dus
  - Pulpen Kenko Gel 0.5: 850 Pcs
  - Spidol Snowman Whiteboard: 620 Pcs
  - Buku Gambar A3: 410 Pcs
  - Progress bars dengan animasi

## 🎨 Design Features

### Visual Design
- **Modern UI** dengan Tailwind CSS
- **Color Scheme**: Indigo/Blue primary colors
- **Typography**: Inter font family
- **Icons**: Font Awesome 6.4.0
- **Charts**: Chart.js untuk visualisasi data

### Animations & Interactions
- Smooth hover effects pada stat cards
- Animated progress bars
- Hover states pada navigation items
- Interactive chart dengan tooltips
- Smooth transitions

### Responsive Design
- Sidebar navigation
- Flexible grid layout
- Mobile-friendly (belum fully responsive)
- Scrollable content area

## 🔒 Security Features

- ✅ Password hashing dengan bcrypt
- ✅ CSRF protection di semua forms
- ✅ Session regeneration setelah login
- ✅ Remember token functionality
- ✅ Input validation (email, password minimum 6 chars)
- ✅ Authentication middleware untuk protected routes
- ✅ Secure logout dengan session invalidation

## 📁 File Structure

```
app/
├── Http/
│   └── Controllers/
│       └── AuthController.php (Login, Logout, Dashboard)
├── Models/
│   └── User.php
resources/
├── views/
│   ├── auth/
│   │   └── login.blade.php (Modern login page)
│   └── dashboard.blade.php (Full dashboard dengan sidebar)
database/
├── migrations/
│   └── 0001_01_01_000000_create_users_table.php
└── seeders/
    ├── DatabaseSeeder.php
    └── UserSeeder.php
routes/
└── web.php (Authentication & Dashboard routes)
```

## 🛠️ Troubleshooting

### Error: Database not found
```bash
# Migration akan auto-create database, tinggal ketik 'yes'
php artisan migrate
```

### Error: Class not found
```bash
composer dump-autoload
php artisan config:clear
php artisan cache:clear
```

### Error: Session not working
```bash
# Pastikan table sessions sudah ada
php artisan migrate
php artisan config:clear
```

### Error: Permission denied
```bash
# For Linux/Mac
chmod -R 775 storage bootstrap/cache

# For Windows/Laragon - biasanya tidak perlu
```

## 📊 Data di Dashboard

Saat ini semua data bersifat **static/hardcoded** untuk demo purposes. 

Untuk make it fully functional dengan database real:
1. Buat model & migration untuk: Products, Suppliers, Customers, Sales, Purchases, Returns
2. Create controllers untuk CRUD operations
3. Update dashboard controller untuk fetch real data dari database
4. Implement API endpoints untuk update charts

## 🎯 Next Steps (Opsional)

Jika ingin develop lebih lanjut:

1. **Master Data Module**:
   - Products management (CRUD)
   - Suppliers management
   - Customers management
   
2. **Warehouse Module**:
   - Stock in/out tracking
   - Stock opname functionality
   - Inventory reports

3. **Sales Module**:
   - Create invoice
   - Sales orders
   - Customer transactions

4. **Purchase Module**:
   - Purchase orders
   - Supplier transactions
   - Approval workflow

5. **Reports Module**:
   - Sales reports
   - Inventory reports
   - Financial reports
   - Export to PDF/Excel

6. **Make it Real**:
   - Connect stats ke database real
   - Dynamic charts dari data actual
   - Real-time notifications

## 📝 Technical Notes

- **Laravel Version**: 11.x
- **PHP Version**: 8.2+
- **Database**: MySQL (via Laragon)
- **Frontend**: Blade Templates + Tailwind CSS + Chart.js
- **Session Driver**: Database
- **Authentication**: Laravel built-in Auth

## 🎉 Fitur yang Sudah Jalan

✅ Login page dengan design modern
✅ Dashboard full layout dengan sidebar
✅ 8 Statistics cards dengan icons & trends
✅ Interactive sales chart dengan Chart.js
✅ Top products list dengan progress bars
✅ Navigation menu lengkap
✅ Top header dengan actions
✅ Logout functionality
✅ Protected routes dengan auth middleware
✅ Session management
✅ Responsive hover effects
✅ Animated elements

Semua fitur UI sudah berfungsi dan interaktif! 🚀

Untuk make it production-ready, tinggal connect ke database real dan implement CRUD operations.

**Selamat menggunakan PT Nusantara ERP System! 📚✏️**
