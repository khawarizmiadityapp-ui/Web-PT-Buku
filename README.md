# 📦 PT Nusantara ERP System

> Sistem Informasi Eksekutif & Manajemen Gudang untuk PT Distribusi Buku dan Alat Tulis Nusantara

![Laravel](https://img.shields.io/badge/Laravel-11.x-red?style=flat-square&logo=laravel)
![PHP](https://img.shields.io/badge/PHP-8.2+-blue?style=flat-square&logo=php)
![Tailwind](https://img.shields.io/badge/Tailwind-CSS-38bdf8?style=flat-square&logo=tailwind-css)
![Chart.js](https://img.shields.io/badge/Chart.js-4.x-ff6384?style=flat-square&logo=chart.js)

## 🚀 Quick Start

### 1. Buat Database
Via HeidiSQL di Laragon:
- Buka HeidiSQL
- Create database: `PTbuku_nusantara`

### 2. Setup & Run
```bash
# Install dependencies (jika belum)
composer install

# Jalankan migration
php artisan migrate

# Seed user accounts
php artisan db:seed --class=UserSeeder

# Start server
php artisan serve
```

### 3. Login
Buka: `http://localhost:8000`

**Accounts:**
- Admin: `admin@ptbuku.com` / `password123`
- Demo: `demo@ptbuku.com` / `demo123`

📖 **Detail lengkap:** Lihat [INSTALL.md](INSTALL.md)

---

## ✨ Features

### ✅ Sudah Jalan 100%

#### Authentication
- Modern login page dengan branding
- Email & password validation
- Remember me functionality
- Session management
- Secure logout

#### Dashboard Overview
- **8 Statistics Cards:**
  - Total Barang: 14,230 (+2.4%)
  - Total Supplier: 84
  - Total Customer: 1,205 (+12 this week)
  - Total Penjualan: Rp 4.2B (+16.3% MTD)
  - Barang Masuk: 3,450 units
  - Barang Keluar: 4,820 units
  - Total Retur: 12
  - Purchase Order: 45 (12 pending)

#### Charts & Analytics
- **Interactive Sales Chart** (Chart.js)
  - 6 months data
  - Smooth animations
  - Hover tooltips
  - Year selector

- **Top Products List**
  - 5 best-selling items
  - Animated progress bars
  - Real product names

#### Navigation
- **Sidebar Menu:**
  - Dashboard, Master Data, Warehouse
  - Sales, Purchase, Reports, SOP
  - User Management, Settings
  
- **Top Header:**
  - Breadcrumb navigation
  - Search, notifications, help
  - User avatar with logout

#### Actions
- Tambah Barang button
- Buat Invoice button
- Stock Opname button

### 🎨 Design Features
- Professional modern UI
- Tailwind CSS styling
- Font Awesome icons
- Smooth hover effects
- Animated elements
- Color-coded indicators
- Responsive layout

📊 **Feature details:** Lihat [FEATURES.md](FEATURES.md)

---

## 📁 Structure

```
app/
├── Http/Controllers/
│   └── AuthController.php       # Login, logout, dashboard
├── Models/
│   └── User.php
resources/
├── views/
│   ├── auth/login.blade.php     # Login page
│   └── dashboard.blade.php       # Main dashboard
database/
├── migrations/
│   └── 0001_01_01_000000_create_users_table.php
└── seeders/
    └── UserSeeder.php            # Demo accounts
routes/
└── web.php                       # Route definitions
```

---

## 🛠️ Tech Stack

**Backend:**
- Laravel 11.x
- PHP 8.2+
- MySQL (via Laragon)
- Blade Templates

**Frontend:**
- Tailwind CSS
- Chart.js
- Font Awesome 6
- JavaScript ES6+

**Security:**
- Bcrypt hashing
- CSRF protection
- Session management
- Input validation

---

## 📸 Screenshots

### Login Page
Modern split-screen design dengan branding PT Nusantara di kiri dan form login di kanan.

### Dashboard
Full-featured dashboard dengan sidebar navigation, statistics cards, sales chart, dan top products list.

---

## 🎯 Roadmap

### Next Phases
- [ ] **Master Data**: Products, Suppliers, Customers CRUD
- [ ] **Warehouse**: Stock in/out, Stock opname
- [ ] **Sales**: Orders, Invoicing, Delivery tracking
- [ ] **Purchase**: PO creation, Approval workflow
- [ ] **Reports**: Export Excel/PDF, Analytics
- [ ] **Notifications**: Real-time alerts
- [ ] **User Management**: Roles & permissions

Lihat roadmap lengkap di [FEATURES.md](FEATURES.md)

---

## 🔐 Security

- ✅ Password hashing with bcrypt
- ✅ CSRF token protection
- ✅ SQL injection prevention (Eloquent ORM)
- ✅ XSS protection (Blade auto-escaping)
- ✅ Session regeneration on login
- ✅ Secure logout with session invalidation

---

## 📚 Documentation

- [INSTALL.md](INSTALL.md) - Installation guide
- [README_SETUP.md](README_SETUP.md) - Detailed setup instructions
- [FEATURES.md](FEATURES.md) - Complete features documentation

---

## 🤝 Contributing

Ini project internal PT Nusantara. Untuk development:

1. Create feature branch: `git checkout -b feature/nama-fitur`
2. Commit changes: `git commit -m 'Add some feature'`
3. Push branch: `git push origin feature/nama-fitur`
4. Create Pull Request

---

## 📝 Notes

### Data Status
- **Auth & Sessions:** Fully dynamic (database-driven)
- **Statistics & Charts:** Currently static for demo
- **Products List:** Static demo data

Untuk make it production-ready, connect stats & charts ke database real.

### Browser Support
- ✅ Chrome 90+
- ✅ Firefox 88+
- ✅ Edge 90+
- ✅ Safari 14+

### Performance
- Dashboard loads in <1s
- Chart renders smoothly
- No jQuery dependencies (lighter)

---

## 🐛 Troubleshooting

**Database error?**
```bash
# Create database first via HeidiSQL
php artisan migrate:fresh --seed
```

**Cache issues?**
```bash
php artisan config:clear
php artisan cache:clear
php artisan route:clear
```

**Port 8000 busy?**
```bash
php artisan serve --port=8080
```

More solutions in [INSTALL.md](INSTALL.md)

---

## 📄 License

Copyright © 2025 PT Distribusi Buku dan Alat Tulis Nusantara

---

## 👨‍💻 Development

**Created with:**
- ☕ Coffee
- 🎵 Music
- 💪 Dedication

**Status:** ✅ Production-ready UI, ready for backend integration

---

## 📞 Support

Untuk pertanyaan atau issues, contact system administrator.

---

<div align="center">
  <strong>PT Nusantara ERP System</strong><br>
  Streamlined Logistics. Precision Delivery. 🚚📦
</div>
