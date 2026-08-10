# 🚀 Instalasi Cepat - PT Nusantara ERP System

## Step 1: Buat Database via Laragon

### Opsi A: Via HeidiSQL (Recommended)
1. Buka Laragon
2. Klik **Database** button atau klik kanan → **HeidiSQL**
3. Di HeidiSQL, klik kanan pada sidebar kiri
4. Pilih **Create new** → **Database**
5. Nama database: `PTbuku_nusantara`
6. Collation: `utf8mb4_unicode_ci`
7. Klik **OK**

### Opsi B: Via Laragon Menu
1. Buka Laragon
2. Klik **Menu** → **Tools** → **Quick Create** → **Database**
3. Nama: `PTbuku_nusantara`

### Opsi C: Via Terminal (Dari Laragon Terminal)
1. Buka Laragon
2. Klik **Terminal** button
3. Jalankan:
```bash
mysql -u root
```
4. Lalu ketik:
```sql
CREATE DATABASE PTbuku_nusantara CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
exit;
```

## Step 2: Jalankan Migration & Seeder

Buka terminal di folder project (via VS Code atau Laragon Terminal):

```bash
# Jalankan migration untuk create tables
php artisan migrate

# Jalankan seeder untuk create user accounts
php artisan db:seed --class=UserSeeder
```

## Step 3: Start Server

```bash
php artisan serve
```

Server akan jalan di: `http://localhost:8000`

## Step 4: Login

Buka browser dan akses: `http://localhost:8000`

### 👤 Akun Login:

**Admin:**
- Email: `admin@ptbuku.com`
- Password: `password123`

**Demo:**
- Email: `demo@ptbuku.com`  
- Password: `demo123`

---

## ✅ Selesai!

Dashboard ERP sudah siap digunakan dengan semua fitur UI yang berfungsi! 🎉

---

## 🛠️ Troubleshooting

### Error: "Unknown database"
→ Pastikan database `PTbuku_nusantara` sudah dibuat (Step 1)

### Error: "Class not found"
```bash
composer dump-autoload
php artisan config:clear
```

### Error: "No application encryption key"
```bash
php artisan key:generate
```

### Port 8000 sudah dipakai
```bash
php artisan serve --port=8080
```
Akses via: `http://localhost:8080`

---

## 📋 Quick Commands

```bash
# Clear cache
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Reset database (HATI-HATI: Hapus semua data!)
php artisan migrate:fresh --seed

# Check routes
php artisan route:list
```

---

Enjoy! 🚀
