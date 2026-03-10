# 🚀 PulsaStore Pro — Sistem Manajemen Inventaris Toko Pulsa
## Laravel 11 + PHP 8.3 + Bootstrap 5

---

## 📦 Paket yang Dibutuhkan

Tambahkan ke `composer.json` lalu jalankan `composer install`:

```json
{
    "require": {
        "php": "^8.3",
        "laravel/framework": "^11.0",
        "maatwebsite/excel": "^3.1",
        "barryvdh/laravel-dompdf": "^2.2"
    }
}
```

```bash
composer require maatwebsite/excel barryvdh/laravel-dompdf
```

---

## ⚙️ Instalasi Langkah demi Langkah

### 1. Setup Project Baru
```bash
composer create-project laravel/laravel pulsa-store
cd pulsa-store
```

### 2. Konfigurasi Database
Edit `.env`:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=pulsa_store
DB_USERNAME=root
DB_PASSWORD=yourpassword

APP_NAME="PulsaStore Pro"
APP_URL=http://localhost:8000
```

### 3. Install Dependencies
```bash
composer require maatwebsite/excel barryvdh/laravel-dompdf
```

### 4. Copy File-File Ini
Salin semua file dari folder ini ke project Laravel Anda:

```
database/migrations/    → ke database/migrations/
app/Models/             → ke app/Models/
app/Http/Controllers/   → ke app/Http/Controllers/
app/Http/Requests/      → ke app/Http/Requests/
app/Http/Middleware/    → ke app/Http/Middleware/
app/Exports/            → ke app/Exports/ (buat folder baru)
app/Imports/            → ke app/Imports/ (buat folder baru)
resources/views/        → ke resources/views/
routes/web.php          → ke routes/web.php
database/seeders/       → ke database/seeders/
```

> **Note:** File dengan prefix `_All` berisi multiple class. Pecah menjadi file terpisah sesuai namespace!

### 5. Daftarkan Middleware
Di `bootstrap/app.php`:
```php
->withMiddleware(function (Middleware $middleware) {
    $middleware->alias([
        'role'         => \App\Http\Middleware\RoleMiddleware::class,
        'check.active' => \App\Http\Middleware\CheckActiveUser::class,
    ]);
})
```

### 6. Jalankan Migrasi & Seeder
```bash
php artisan migrate:fresh --seed
```

### 7. Link Storage
```bash
php artisan storage:link
```

### 8. Jalankan Server
```bash
php artisan serve
```

Buka: **http://localhost:8000**

---

## 🔑 Akun Login Default

| Role | Email | Password |
|------|-------|----------|
| **Admin** | admin@pulsastore.com | password |
| **Kasir** | kasir@pulsastore.com | password |
| **Gudang** | gudang@pulsastore.com | password |

---

## 🏗️ Struktur Hak Akses (Role)

| Fitur | Admin | Kasir | Gudang |
|-------|-------|-------|--------|
| Dashboard | ✅ | ✅ | ✅ |
| Penjualan (CRUD) | ✅ | ✅ | ❌ |
| Pembelian (CRUD) | ✅ | ❌ | ✅ |
| Produk (CRUD) | ✅ | View | View |
| Stok Keluar/Masuk | ✅ | ❌ | ✅ |
| Karyawan | ✅ | ❌ | ❌ |
| Penggajian | ✅ | ❌ | ❌ |
| Laporan + Export | ✅ | ❌ | ❌ |
| Manajemen User | ✅ | ❌ | ❌ |
| Kategori & Supplier | ✅ | ❌ | ❌ |

---

## 📊 Fitur Lengkap

### ✅ Manajemen Barang
- CRUD produk dengan gambar
- Kategori & supplier
- Alert stok minimum
- Soft delete

### ✅ Transaksi Penjualan
- POS (Point of Sale) dengan keranjang dinamis
- Auto generate no. invoice: `INV-20240101-0001`
- Dukungan Cash, Transfer, QRIS
- Cetak nota PDF (format thermal 80mm)
- Auto kurangi stok saat transaksi

### ✅ Pembelian / Barang Masuk
- Input pembelian dengan multiple produk
- Auto tambah stok saat pembelian
- Cetak PO PDF (A5)
- Riwayat pembelian per supplier

### ✅ Manajemen Stok
- Riwayat keluar/masuk stok
- Penyesuaian stok manual (opname)
- Semua perubahan stok tercatat otomatis

### ✅ Karyawan & Penggajian
- Data karyawan dengan kode auto-generate
- Input gaji bulanan (pokok, tunjangan, bonus, potongan)
- Cetak slip gaji PDF
- Import data karyawan dari Excel
- Export data karyawan ke Excel

### ✅ Laporan
- Laporan penjualan dengan filter tanggal
- Laporan pembelian
- Laporan stok (termasuk filter low stock)
- Export semua laporan ke **PDF** dan **Excel**

### ✅ Dashboard
- Grafik penjualan vs pembelian 6 bulan (Chart.js)
- Top 5 produk terjual
- Alert produk stok rendah
- Statistik cepat (total produk, omzet, dll)

### ✅ Fitur Teknis
- Multi-role login (Admin, Kasir, Gudang)
- Sidebar dinamis sesuai role
- Form validasi via Request Classes
- Flash messages (success/error/warning)
- Soft Deletes semua model utama
- Search & filter + pagination di semua tabel
- Responsive mobile-friendly

---

## 🗄️ Skema Database

```
roles ──────────────┐
users ───────────────┤ (role_id FK)
                    │
categories ─────────┤
suppliers ───────────┤     products ────────┐
                    └──── (category_id FK)  │
                           (supplier_id FK) │
                                           ├──── purchase_items ──── purchases
                                           ├──── sale_items     ──── sales
                                           └──── stock_adjustments

employees ──── salaries
```

---

## 🎨 UI Stack

- **Bootstrap 5.3** — framework CSS utama
- **Flowbite 2.3** — komponen UI modern
- **Bootstrap Icons** — iconografi
- **Chart.js 4** — grafik dashboard
- **DomPDF** — generate PDF nota & laporan
- **Maatwebsite Excel** — import/export Excel

---

## 📝 Catatan Pengembangan

### Pisahkan file `_All*.php` menjadi:

**`_AllModels.php`** → pisah jadi:
- `Role.php`, `User.php`, `Category.php`, `Supplier.php`
- `Product.php`, `Purchase.php`, `PurchaseItem.php`
- `Sale.php`, `SaleItem.php`, `Employee.php`
- `Salary.php`, `StockAdjustment.php`

**`_AllControllers.php`** → pisah jadi:
- `DashboardController.php`, `ProductController.php`
- `SaleController.php`, `PurchaseController.php`
- `EmployeeController.php`, `SalaryController.php`
- `ReportController.php`, `StockController.php`

**`_AllRequests.php`** → pisah jadi:
- `ProductRequest.php`, `SaleRequest.php`
- `PurchaseRequest.php`, `EmployeeRequest.php`
- `SalaryRequest.php`, `UserRequest.php`

**`_AllExports.php`** → pisah menjadi folder `app/Exports/` dan `app/Imports/`

### Controller Auth yang masih perlu dibuat:
```php
// app/Http/Controllers/Auth/AuthController.php
public function login(Request $request) {
    $credentials = $request->validate([
        'email'    => 'required|email',
        'password' => 'required',
    ]);
    if (auth()->attempt($credentials, $request->remember)) {
        $request->session()->regenerate();
        return redirect()->intended(route('dashboard'));
    }
    return back()->with('error', 'Email atau password salah!');
}

public function logout(Request $request) {
    auth()->logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect()->route('login');
}
```

### View yang masih perlu dibuat (sebagai latihan/pengembangan):
- `sales/index.blade.php` — list transaksi penjualan
- `sales/show.blade.php` — detail transaksi
- `purchases/` — semua view pembelian
- `employees/` — semua view karyawan
- `salary/` — semua view penggajian
- `reports/` — view laporan
- `stock/index.blade.php` — histori stok
- `users/` — manajemen user

Pola semua view mengikuti `products/index.blade.php` yang sudah ada.

---

Made with ❤️ using Laravel 11 + PHP 8.3
