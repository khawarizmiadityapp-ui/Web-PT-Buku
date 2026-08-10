# 🛒 FITUR KASIR (POS SYSTEM) - PT NUSANTARA

## 📋 Overview
Sistem kasir lengkap untuk mengelola transaksi penjualan harian dengan antarmuka yang user-friendly dan fitur yang komprehensif.

## ✨ Fitur Utama

### 1. **Dashboard Kasir** (`/cashier`)
- **Statistik Real-time**:
  - Total Penjualan Hari Ini
  - Jumlah Transaksi
  - Pendapatan Bersih
  - Produk Terjual
- **Quick Actions**: Shortcut untuk transaksi baru, retur produk, dan customer baru
- **10 Transaksi Terakhir**: Monitoring transaksi terbaru
- **Sales Trend**: Grafik penjualan 7 hari terakhir

### 2. **Transaksi Baru** (`/cashier/transaction`)
#### A. Product Selection
- Grid produk dengan stock real-time
- Search bar untuk cari produk cepat
- Tampilan harga dan stock tersedia
- Click produk untuk add to cart

#### B. Shopping Cart
- **Customer Management**:
  - Pilih customer dari database
  - Walk-in customer option
  - Quick add customer baru
- **Cart Management**:
  - Add/remove produk
  - Update quantity dengan stock validation
  - Real-time subtotal calculation
- **Discount & Tax**:
  - Input discount manual
  - Auto-calculate tax 11%
  - Real-time total update
- **Payment**:
  - Multiple payment methods (Cash, Credit Card, Debit Card, Bank Transfer, E-Wallet)
  - Calculate change automatically
  - Partial payment support
- **Notes**: Optional transaction notes

#### C. Transaction Processing
- Stock validation sebelum proses
- Automatic invoice number generation (INV-YYYYMMDD-XXX)
- Real-time stock update
- Customer purchase history update
- Success confirmation dengan change amount
- Auto-print receipt option

### 3. **Riwayat Transaksi** (`/cashier/history`)
- **Filter & Search**:
  - Search by invoice atau customer
  - Filter by payment status
  - Filter by date range
- **Transaction List**:
  - Invoice number
  - Date/time
  - Customer info
  - Items count
  - Total & paid amount
  - Payment method
  - Payment status
- **Actions**:
  - View detail
  - Print receipt

### 4. **Detail Transaksi** (`/cashier/{id}`)
- **Transaction Information**:
  - Invoice number
  - Date & time
  - Customer info
  - Payment method
- **Items List**:
  - Product name & code
  - Quantity
  - Price per item
  - Subtotal
- **Payment Summary**:
  - Subtotal
  - Discount (jika ada)
  - Tax
  - Total
  - Paid amount
  - Change/Remaining
- **Status Badge**: Visual status indicator
- **Notes**: Transaction notes (jika ada)

### 5. **Print Receipt** (`/cashier/print/{id}`)
- Format receipt 80mm thermal printer
- Company branding
- Complete transaction details
- Items list dengan harga
- Payment summary
- Auto-print on load
- Print-friendly layout

### 6. **Customer Management** (`/customers`)
- **Customer List**:
  - Search & filter customers
  - View total purchases
  - Active/Inactive status
- **Add/Edit Customer**:
  - Customer code (unique)
  - Name, email, phone
  - Address & city
  - Status management
- **Quick Add**: Modal untuk add customer cepat di POS
- **Customer History**: Lihat semua transaksi customer

## 🔧 Technical Details

### Database Tables
1. **customers**
   - id, customer_code, name, email, phone
   - address, city, total_purchases, status
   - timestamps, soft deletes

2. **sales_invoices** (Updated)
   - id, invoice_number, date
   - customer_id (FK), customer_name
   - total_amount, discount_amount, tax_amount
   - payment_status, payment_method
   - paid_amount, due_date, notes
   - timestamps, soft deletes

3. **sales_invoice_items**
   - id, sales_invoice_id (FK), product_id (FK)
   - product_name, quantity, price, subtotal
   - timestamps

### Controllers
1. **CashierController**
   - `index()`: Dashboard
   - `transaction()`: POS page
   - `processTransaction()`: Handle checkout
   - `history()`: Transaction history
   - `show()`: Transaction detail
   - `printReceipt()`: Print view
   - `searchProduct()`: Product search API

2. **CustomerController**
   - Standard CRUD operations
   - `quickStore()`: Quick add API for POS

### Models
1. **Customer**: Customer data model
2. **SalesInvoice**: Invoice dengan relationships
3. **SalesInvoiceItem**: Invoice items

## 🎯 Key Features Implementation

### 1. Invoice Number Generation
Format: `INV-YYYYMMDD-XXX`
- Auto-increment per hari
- Unique per transaksi
- Reset sequence tiap hari

### 2. Stock Management
- Real-time stock validation
- Automatic stock deduction saat transaksi
- Update both system_stock dan physical_stock
- Prevent overselling

### 3. Payment Status
- **Paid**: Paid amount >= Total
- **Partial**: 0 < Paid amount < Total
- **Unpaid**: Paid amount = 0

### 4. Customer Integration
- Link transaction ke customer (optional)
- Auto-update customer total_purchases
- Walk-in customer support
- Quick customer creation

### 5. Multi-Currency & Tax
- Rupiah (IDR) formatting
- 11% tax calculation
- Discount support
- Change calculation

## 📱 User Interface

### Design System
- Bootstrap 5 untuk responsive layout
- FontAwesome icons
- Cards dengan shadow untuk modern look
- Color coding untuk status
- Mobile-friendly design

### Color Codes
- **Success/Paid**: Green badge
- **Warning/Partial**: Yellow badge
- **Danger/Unpaid**: Red badge
- **Primary Actions**: Blue buttons

## 🔐 Security & Validation

### Form Validation
- Required fields validation
- Stock availability check
- Unique constraints (customer_code, email)
- Numeric validations untuk amounts

### Transaction Safety
- Database transactions (BEGIN/COMMIT/ROLLBACK)
- Error handling dengan try-catch
- Stock locking untuk prevent race conditions

### User Experience
- Real-time calculations
- Instant feedback
- Confirmation dialogs untuk destructive actions
- Auto-save untuk prevent data loss

## 🚀 Routes Summary

```php
// Cashier Routes
GET    /cashier                    → Dashboard
GET    /cashier/transaction        → New Transaction (POS)
POST   /cashier/process            → Process Transaction
GET    /cashier/history            → Transaction History
GET    /cashier/{id}               → Transaction Detail
GET    /cashier/print/{id}         → Print Receipt

// Customer Routes
GET    /customers                  → Customer List
POST   /customers                  → Create Customer
GET    /customers/create           → Create Form
POST   /customers/quick-store      → Quick Add (API)
GET    /customers/{id}             → Customer Detail
GET    /customers/{id}/edit        → Edit Form
PUT    /customers/{id}             → Update Customer
DELETE /customers/{id}             → Delete Customer
```

## 📊 Testing Checklist

### Basic Functionality
- ✅ Create new transaction
- ✅ Add products to cart
- ✅ Update quantities
- ✅ Remove items from cart
- ✅ Apply discount
- ✅ Calculate tax
- ✅ Process payment
- ✅ Generate invoice
- ✅ Update stock
- ✅ Print receipt

### Edge Cases
- ✅ Out of stock handling
- ✅ Partial payment
- ✅ Change calculation
- ✅ Walk-in customer
- ✅ Quick add customer
- ✅ Empty cart validation
- ✅ Negative stock prevention

### Integration
- ✅ Customer total purchases update
- ✅ Product stock synchronization
- ✅ Invoice number uniqueness
- ✅ Date/time accuracy

## 🎓 Usage Guide

### For Kasir (Cashier)
1. Login ke sistem
2. Akses menu "Kasir" → "Transaksi Baru"
3. Pilih produk dengan click atau search
4. Pilih/add customer (optional)
5. Review cart, adjust quantity jika perlu
6. Input discount (jika ada)
7. Pilih payment method
8. Input paid amount
9. Click "Process Payment"
10. Print receipt (optional)

### For Admin
1. Monitor dashboard untuk sales statistics
2. Review transaction history
3. Manage customer database
4. Export reports jika diperlukan

## 🔄 Future Enhancements (Optional)
- [ ] Barcode scanner integration
- [ ] Multi-user shift management
- [ ] Cash drawer tracking
- [ ] Loyalty points system
- [ ] Sales target tracking
- [ ] Product categories filter
- [ ] Favorite products quick access
- [ ] Transaction void/cancel
- [ ] Refund processing
- [ ] Receipt email/WhatsApp

## 📝 Notes
- Semua transaksi tercatat dengan timestamp
- Soft delete untuk customer & transactions
- Audit trail untuk tracking changes
- Responsive design untuk tablet/mobile cashier

---

**Developed for PT Nusantara Distribution Solutions**
**Version: 1.0.0**
**Last Updated: July 31, 2026**
