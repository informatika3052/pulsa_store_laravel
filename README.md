<div align="center">

# 📱 PulsaStore Pro

### Sistem Manajemen Inventaris Toko Pulsa

![Laravel](https://img.shields.io/badge/Laravel-11-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)
![PHP](https://img.shields.io/badge/PHP-8.3-777BB4?style=for-the-badge&logo=php&logoColor=white)
![Bootstrap](https://img.shields.io/badge/Bootstrap-5-7952B3?style=for-the-badge&logo=bootstrap&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-8-4479A1?style=for-the-badge&logo=mysql&logoColor=white)

Aplikasi web berbasis Laravel 11 untuk manajemen inventaris toko pulsa secara lengkap —
dari pencatatan barang, transaksi POS, pembelian, penggajian karyawan, hingga laporan & cetak nota.

</div>

---

## ✨ Fitur Utama

| Fitur | Keterangan |
|-------|------------|
| 🔐 **Multi Role** | Admin, Kasir, Gudang — sidebar & akses berbeda per role |
| 📦 **Manajemen Barang** | CRUD produk, kategori, supplier dengan upload foto |
| 🛒 **POS Penjualan** | Keranjang dinamis, auto invoice, Cash / Transfer / QRIS |
| 🚚 **Pembelian** | Input barang masuk, stok otomatis bertambah, cetak PO |
| 📊 **Laporan** | Penjualan, pembelian, stok — export PDF & Excel |
| 👥 **Karyawan** | Data karyawan, slip gaji PDF, import/export Excel |
| 📈 **Dashboard** | Grafik 6 bulan (Chart.js), statistik real-time, alert stok rendah |
| 🔔 **Alert Stok** | Notifikasi otomatis jika stok di bawah minimum |
| 🗑️ **Soft Delete** | Data tidak langsung hilang dari database |

---

## 🖥️ Hak Akses Per Role

| Fitur | Admin | Kasir | Gudang |
|-------|:-----:|:-----:|:------:|
| Dashboard | ✅ | ✅ | ✅ |
| Penjualan | ✅ | ✅ | ❌ |
| Pembelian | ✅ | ❌ | ✅ |
| Produk CRUD | ✅ | 👁️ | 👁️ |
| Stok Keluar/Masuk | ✅ | ❌ | ✅ |
| Karyawan & Gaji | ✅ | ❌ | ❌ |
| Laporan & Export | ✅ | ❌ | ❌ |
| Manajemen User | ✅ | ❌ | ❌ |
| Kategori & Supplier | ✅ | ❌ | ❌ |

> 👁️ = Hanya bisa melihat, tidak bisa edit/hapus

---

## 🛠️ Teknologi

- **Backend** — Laravel 11, PHP 8.3
- **Frontend** — Bootstrap 5.3, Flowbite 2.3, Bootstrap Icons
- **Database** — MySQL 8
- **Chart** — Chart.js 4
- **PDF** — barryvdh/laravel-dompdf
- **Excel** — maatwebsite/laravel-excel

---

## ⚙️ Instalasi

### Prasyarat
- PHP >= 8.3
- Composer
- MySQL
- Laragon / XAMPP / WAMP

### Langkah-langkah

**1. Clone repository**
```bash
git clone https://github.com/USERNAME/pulsa-store.git
cd pulsa-store
```

**2. Install dependencies**
```bash
composer install
```

**3. Konfigurasi environment**
```bash
cp .env.example .env
php artisan key:generate
```

**4. Setting database di `.env`**
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=pulsa_store
DB_USERNAME=root
DB_PASSWORD=
```

**5. Daftarkan Middleware di `bootstrap/app.php`**
```php
->withMiddleware(function (Middleware $middleware) {
    $middleware->alias([
        'role'         => \App\Http\Middleware\RoleMiddleware::class,
        'check.active' => \App\Http\Middleware\CheckActiveUser::class,
    ]);
})
```

**6. Migrasi & seeder**
```bash
php artisan migrate:fresh --seed
```

**7. Link storage**
```bash
php artisan storage:link
```

**8. Jalankan server**
```bash
php artisan serve
```

Buka browser: **http://127.0.0.1:8000**

---

## 🔑 Akun Default

> ⚠️ **Segera ganti password setelah pertama kali login!**

Akun default tersedia di `database/seeders/DatabaseSeeder.php`.
Silakan cek file tersebut untuk melihat email dan password default.

---

## 📁 Struktur Folder

```
pulsa-store/
├── app/
│   ├── Http/
│   │   ├── Controllers/        # Semua controller
│   │   ├── Middleware/         # RoleMiddleware, CheckActiveUser
│   │   └── Requests/           # Form validation
│   ├── Models/                 # Eloquent models
│   └── Exports/                # Excel export/import classes
├── database/
│   ├── migrations/             # Skema tabel
│   └── seeders/                # Data awal
└── resources/
    └── views/
        ├── layouts/            # Master layout
        ├── auth/               # Halaman login
        ├── dashboard/          # Dashboard
        ├── products/           # Manajemen barang
        ├── sales/              # Penjualan & POS
        ├── purchases/          # Pembelian
        ├── employees/          # Karyawan
        ├── salary/             # Penggajian
        ├── stock/              # Histori stok
        ├── reports/            # Laporan
        ├── suppliers/          # Supplier
        └── users/              # Manajemen user
```

---

## 🗄️ Skema Database

```
roles ──── users
            │
categories ─┤
suppliers ──┤──── products ──── purchase_items ──── purchases
                           └─── sale_items     ──── sales
                           └─── stock_adjustments

employees ──────── salaries
```

---

## 📸 Screenshot

> Coming soon...

---

## 📄 Lisensi

Project ini dibuat untuk keperluan pribadi / bisnis.

---

<div align="center">
  Dibuat dengan ❤️ menggunakan <strong>Laravel 11</strong>
</div>
