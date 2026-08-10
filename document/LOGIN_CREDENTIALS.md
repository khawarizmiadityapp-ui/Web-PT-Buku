# 🔐 Login Credentials - PT Nusantara ERP System

Berikut adalah daftar akun untuk testing dan development.

---

## 👥 User Accounts

### 1. **Super Administrator**
```
Email    : admin@ptbuku.com
Password : admin123
Role     : Administrator
Access   : Full System Access
```
**Kewenangan:**
- Akses ke semua modul
- Manage users
- System settings
- Full CRUD operations
- View all reports

---

### 2. **Manager Operasional**
```
Email    : manager@ptbuku.com
Password : manager123
Role     : Manager
Access   : Operational Management
```
**Kewenangan:**
- Dashboard & Analytics
- Approve Purchase Orders
- View all inventory
- Sales & Stock reports
- Supplier management

---

### 3. **Petugas Gudang**
```
Email    : warehouse@ptbuku.com
Password : warehouse123
Role     : Warehouse Staff
Access   : Warehouse Operations
```
**Kewenangan:**
- Stock In/Out management
- Inventory tracking
- Goods receiving
- Stock opname
- Print delivery notes

---

### 4. **Sales Marketing**
```
Email    : sales@ptbuku.com
Password : sales123
Role     : Sales
Access   : Sales Management
```
**Kewenangan:**
- Create invoices
- Customer management
- Sales orders
- View sales reports
- Update payment status

---

### 5. **Staff Finance**
```
Email    : finance@ptbuku.com
Password : finance123
Role     : Finance
Access   : Financial Operations
```
**Kewenangan:**
- Invoice management
- Payment tracking
- Financial reports
- Customer billing
- Payment reconciliation

---

### 6. **Customer Service**
```
Email    : cs@ptbuku.com
Password : cs123
Role     : Customer Service
Access   : Customer Support
```
**Kewenangan:**
- View customer data
- Order tracking
- Basic reporting
- Customer inquiries
- Order status updates

---

## 🔒 Security Notes

### Development Environment:
- ✅ Passwords are documented for easy access
- ✅ Use `updateOrCreate` to prevent duplicate entries
- ✅ Passwords hashed with Laravel's Hash facade

### Production Environment:
- ⚠️ **IMPORTANT:** Change all passwords before deploying to production
- ⚠️ Remove password comments from UserSeeder
- ⚠️ Implement strong password policy (min 12 chars, symbols, etc)
- ⚠️ Enable Two-Factor Authentication (2FA)
- ⚠️ Implement role-based access control (RBAC)
- ⚠️ Use environment variables for sensitive data

---

## 📝 Quick Login Guide

### Cara Login:
1. Buka: http://localhost:8000/login
2. Pilih akun dari list di atas
3. Masukkan email & password
4. Klik "Sign in to Dashboard"

### Forgot Password:
- Untuk development, cek file ini: `LOGIN_CREDENTIALS.md`
- Untuk reset password, jalankan:
  ```bash
  php artisan tinker
  $user = User::where('email', 'admin@ptbuku.com')->first();
  $user->password = Hash::make('newpassword');
  $user->save();
  ```

---

## 🔄 Reset All Users

Jika ingin reset semua user ke default:

```bash
# Re-run user seeder
php artisan db:seed --class=UserSeeder

# Atau reset seluruh database
php artisan migrate:fresh --seed
```

---

## 🎯 Testing Scenarios

### Test Login Flow:
1. Login sebagai **Admin** → Akses semua fitur
2. Logout → Login sebagai **Sales** → Test create invoice
3. Logout → Login sebagai **Warehouse** → Test stock management
4. Logout → Login sebagai **CS** → Test customer queries

### Test Access Control:
- Admin dapat akses User Management
- Sales hanya dapat akses Sales module
- Warehouse hanya dapat akses Warehouse module

---

**💡 Tips:**
- Bookmark halaman ini untuk referensi cepat
- Update credentials ini jika ada perubahan
- Jangan commit file ini ke production repository

**Last Updated:** {{ date('Y-m-d H:i:s') }}
