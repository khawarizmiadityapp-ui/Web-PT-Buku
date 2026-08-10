# 🚀 QUICK GUIDE - SISTEM KASIR PT NUSANTARA

## 📍 URL Akses Cepat

```
Dashboard Kasir:     http://localhost/cashier
Transaksi Baru:      http://localhost/cashier/transaction
Riwayat Transaksi:   http://localhost/cashier/history
Customer List:       http://localhost/customers
```

## 🎯 Alur Transaksi (3 Steps)

### Step 1: PILIH PRODUK
1. Search atau click produk di grid
2. Produk otomatis masuk cart
3. Adjust quantity jika perlu (click +/- atau input langsung)
4. Remove produk jika salah (click trash icon)

### Step 2: INPUT PEMBAYARAN
1. **Pilih Customer**: 
   - Pilih dari dropdown ATAU
   - Biarkan "Walk-in Customer" ATAU
   - Click "+" untuk add customer baru
   
2. **Discount** (optional):
   - Input nominal discount
   - Total akan update otomatis
   
3. **Tax**:
   - Otomatis 11% dari subtotal
   - Bisa manual override
   
4. **Payment Method**:
   - Cash
   - Credit Card
   - Debit Card
   - Bank Transfer
   - E-Wallet
   
5. **Paid Amount**:
   - Input jumlah uang yang dibayar
   - Kembalian akan calculate otomatis
   - Bisa partial payment (kurang dari total)

### Step 3: PROCESS & PRINT
1. Click "Process Payment"
2. Tunggu konfirmasi success
3. Print receipt? (Yes/No)
4. Done! Cart akan clear otomatis

## 💡 Tips & Shortcuts

### Product Selection
- 🔍 **Search**: Ketik nama/code produk di search bar
- 👆 **Quick Add**: Click product card langsung add to cart
- ➕ **Increase Qty**: Click tombol + atau input manual
- ➖ **Decrease Qty**: Click tombol - 
- 🗑️ **Remove**: Click tombol trash

### Customer
- 🚶 **Walk-in**: Biarkan default jika customer tidak terdaftar
- 👥 **Select**: Pilih dari dropdown untuk customer registered
- ➕ **Quick Add**: Click tombol + untuk add customer baru cepat
- 📝 **Quick Form**: Hanya butuh Name & Phone

### Payment
- 💵 **Exact Amount**: Input = Total → Status PAID
- 💰 **Over Amount**: Input > Total → Calculate change
- 💳 **Partial**: Input < Total → Status PARTIAL
- 🧮 **Auto Tax**: Tax otomatis 11%, bisa di-edit

### After Transaction
- 🖨️ **Print**: Receipt format thermal 80mm
- 👁️ **View**: Lihat detail di history
- 🔍 **Search**: Cari by invoice/customer name
- 📅 **Filter**: Filter by date range & status

## ⚠️ Important Notes

### Stock Management
- ✅ Stock di-check otomatis
- ✅ Tidak bisa jual lebih dari stock available
- ✅ Stock update otomatis after payment
- ✅ Both system_stock & physical_stock updated

### Invoice Number
- Format: `INV-20260731-001`
- Auto-increment per hari
- Unique per transaction
- Reset sequence tiap hari baru

### Payment Status
- 🟢 **PAID**: Lunas penuh
- 🟡 **PARTIAL**: Bayar sebagian
- 🔴 **UNPAID**: Belum bayar

### Validation
- ❌ Cart tidak boleh kosong
- ❌ Paid amount harus > 0
- ❌ Customer name wajib diisi
- ❌ Stock tidak boleh negatif

## 🔧 Troubleshooting

### Problem: Produk tidak muncul
**Solution**: 
- Check product status = Active
- Check stock > 0
- Refresh page

### Problem: Stock tidak update
**Solution**:
- Check transaction berhasil
- Lihat di product management
- Check database connection

### Problem: Print tidak jalan
**Solution**:
- Allow pop-up di browser
- Check printer connection
- Try manual print (Ctrl+P)

### Problem: Cart tidak clear
**Solution**:
- Click "Clear Cart" button
- Refresh page jika perlu

## 📊 Dashboard Metrics

### Cards Explained
1. **Total Penjualan Hari Ini**: Sum of all transactions today
2. **Jumlah Transaksi**: Count of transactions today
3. **Pendapatan Bersih**: Total paid amount (excludes unpaid)
4. **Produk Terjual**: Sum of quantities sold today

### Recent Transactions
- Shows last 10 transactions
- Click 👁️ untuk detail
- Click 🖨️ untuk print receipt

## 🎨 Status Colors

- 🟢 **Green/Success**: Paid, Active
- 🟡 **Yellow/Warning**: Partial, Pending
- 🔴 **Red/Danger**: Unpaid, Cancelled
- ⚪ **Gray/Secondary**: Inactive, Archived

## 📱 Mobile Usage

### Portrait Mode (Phone)
- Grid 1-2 columns
- Scrollable cart
- Full-width buttons
- Touch-friendly

### Landscape Mode (Tablet)
- Grid 3-4 columns
- Side-by-side layout
- Better overview

## 🔐 Security

### Best Practices
- ✅ Logout after shift
- ✅ Don't share login
- ✅ Verify amounts
- ✅ Keep receipt copies
- ✅ Report discrepancies

### Data Safety
- Auto-save on transaction
- Timestamps recorded
- Audit trail enabled
- Soft delete (recoverable)

## 📞 Support

### Common Questions

**Q: Bisa cancel transaksi?**
A: Sebelum "Process Payment", bisa click "Clear Cart"

**Q: Bisa void transaction?**
A: Contact admin/manager untuk void

**Q: Receipt habis, gimana?**
A: Bisa print ulang dari History

**Q: Customer salah pilih?**
A: Click dropdown, pilih yang benar before payment

**Q: Stock salah?**
A: Report ke warehouse team untuk audit

**Q: Lupa password?**
A: Contact IT/admin untuk reset

## 🎓 Keyboard Shortcuts (Coming Soon)

```
F1  - Help
F2  - New Transaction
F3  - Search Product
F4  - Search Customer
F9  - Clear Cart
F10 - Process Payment
Esc - Cancel/Back
```

## 📈 Performance Tips

### Fast Checkout
1. Use product search for quick find
2. Memorize popular product codes
3. Use walk-in for anonymous sales
4. Keep exact change ready

### Accuracy
1. Always verify quantity
2. Double-check total
3. Confirm payment method
4. Print receipt as proof

### Customer Service
1. Greet customer
2. Offer assistance
3. Verify items
4. Thank you message

---

## 🏆 Pro Tips

1. **Morning**: Check dashboard, verify stock
2. **During Shift**: Monitor transactions, help customers
3. **Peak Hours**: Use quick shortcuts, be efficient
4. **End Shift**: Verify cash, reconcile sales
5. **Issues**: Document and report immediately

---

## ✅ Checklist Kasir

### Opening Shift
- [ ] Login ke sistem
- [ ] Check dashboard stats
- [ ] Verify stock availability
- [ ] Prepare receipt paper
- [ ] Test printer

### During Shift
- [ ] Process transactions accurately
- [ ] Provide excellent service
- [ ] Monitor stock levels
- [ ] Keep workspace clean
- [ ] Report any issues

### Closing Shift
- [ ] Complete pending transactions
- [ ] Print daily report
- [ ] Verify cash count
- [ ] Clean workspace
- [ ] Logout from system

---

**Need Help?** Contact: support@ptnusantara.com
**Emergency:** Call supervisor or IT team

**Remember:** 
- Accuracy > Speed
- Customer satisfaction first
- Report issues immediately
- Keep learning & improving

---

*Happy Cashiering! 🎉*
*Version 1.0 | Updated: July 31, 2026*
