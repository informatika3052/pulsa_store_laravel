<?php

// ==================== database/seeders/DatabaseSeeder.php ====================
namespace Database\Seeders;

use App\Models\{Role, User, Category, Supplier, Product, Employee};
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Roles
        $adminRole  = Role::create(['name' => 'admin',  'display_name' => 'Administrator', 'description' => 'Akses penuh ke semua fitur']);
        $kasirRole  = Role::create(['name' => 'kasir',  'display_name' => 'Kasir',         'description' => 'Akses transaksi penjualan']);
        $gudangRole = Role::create(['name' => 'gudang', 'display_name' => 'Gudang',        'description' => 'Akses stok dan pembelian']);

        // Users
        User::create([
            'role_id'  => $adminRole->id,
            'name'     => 'Super Admin',
            'email'    => 'admin@pulsastore.com',
            'password' => Hash::make('password'),
            'phone'    => '081234567890',
            'is_active' => true,
        ]);
        User::create([
            'role_id'  => $kasirRole->id,
            'name'     => 'Budi Kasir',
            'email'    => 'kasir@pulsastore.com',
            'password' => Hash::make('password'),
            'is_active' => true,
        ]);
        User::create([
            'role_id'  => $gudangRole->id,
            'name'     => 'Siti Gudang',
            'email'    => 'gudang@pulsastore.com',
            'password' => Hash::make('password'),
            'is_active' => true,
        ]);

        // Categories
        $cats = [
            ['name' => 'Pulsa Telkomsel', 'slug' => 'pulsa-telkomsel'],
            ['name' => 'Pulsa Indosat',   'slug' => 'pulsa-indosat'],
            ['name' => 'Pulsa XL',        'slug' => 'pulsa-xl'],
            ['name' => 'Paket Data',      'slug' => 'paket-data'],
            ['name' => 'Voucher Game',    'slug' => 'voucher-game'],
            ['name' => 'Token Listrik',   'slug' => 'token-listrik'],
            ['name' => 'Aksesoris HP',    'slug' => 'aksesoris-hp'],
        ];
        foreach ($cats as $cat) {
            Category::create($cat + ['description' => null]);
        }

        // Suppliers
        $sup1 = Supplier::create(['name' => 'PT. Mitra Pulsa Indonesia', 'phone' => '02112345678', 'contact_person' => 'Pak Joko']);
        $sup2 = Supplier::create(['name' => 'CV. Distributor Selular',   'phone' => '02287654321', 'contact_person' => 'Bu Rani']);

        // Products
        $products = [
            ['category_id' => 1, 'supplier_id' => $sup1->id, 'code' => 'PLT-005', 'name' => 'Pulsa Telkomsel 5rb',  'purchase_price' => 5200,  'selling_price' => 6000,  'stock' => 100, 'unit' => 'pcs'],
            ['category_id' => 1, 'supplier_id' => $sup1->id, 'code' => 'PLT-010', 'name' => 'Pulsa Telkomsel 10rb', 'purchase_price' => 10200, 'selling_price' => 11000, 'stock' => 100, 'unit' => 'pcs'],
            ['category_id' => 1, 'supplier_id' => $sup1->id, 'code' => 'PLT-020', 'name' => 'Pulsa Telkomsel 20rb', 'purchase_price' => 20200, 'selling_price' => 21000, 'stock' => 80,  'unit' => 'pcs'],
            ['category_id' => 1, 'supplier_id' => $sup1->id, 'code' => 'PLT-050', 'name' => 'Pulsa Telkomsel 50rb', 'purchase_price' => 50200, 'selling_price' => 51500, 'stock' => 3,   'unit' => 'pcs', 'min_stock' => 10],
            ['category_id' => 2, 'supplier_id' => $sup1->id, 'code' => 'IMT-010', 'name' => 'Pulsa Indosat 10rb',  'purchase_price' => 10300, 'selling_price' => 11000, 'stock' => 50,  'unit' => 'pcs'],
            ['category_id' => 4, 'supplier_id' => $sup2->id, 'code' => 'DAT-TLS1', 'name' => 'Paket Data Tsel 1GB 7hr', 'purchase_price' => 12000, 'selling_price' => 14000, 'stock' => 60, 'unit' => 'pcs'],
            ['category_id' => 4, 'supplier_id' => $sup2->id, 'code' => 'DAT-TLS5', 'name' => 'Paket Data Tsel 5GB 30hr', 'purchase_price' => 48000, 'selling_price' => 52000, 'stock' => 40, 'unit' => 'pcs'],
            ['category_id' => 5, 'supplier_id' => $sup2->id, 'code' => 'GME-ML25', 'name' => 'Diamond Mobile Legend 275', 'purchase_price' => 67000, 'selling_price' => 72000, 'stock' => 20, 'unit' => 'pcs'],
            ['category_id' => 6, 'supplier_id' => $sup1->id, 'code' => 'TKL-020',  'name' => 'Token Listrik 20rb', 'purchase_price' => 20000, 'selling_price' => 21500, 'stock' => 2, 'unit' => 'pcs', 'min_stock' => 5],
            ['category_id' => 7, 'supplier_id' => $sup2->id, 'code' => 'AKS-CSN', 'name' => 'Casing HP Universal', 'purchase_price' => 8000, 'selling_price' => 15000, 'stock' => 30, 'unit' => 'pcs'],
        ];

        foreach ($products as $p) {
            Product::create(array_merge(['description' => null, 'min_stock' => 5, 'is_active' => true], $p));
        }

        // Employees
        Employee::create([
            'employee_code' => 'EMP0001',
            'name'          => 'Budi Santoso',
            'position'      => 'Kasir',
            'phone'         => '08112345678',
            'join_date'     => '2023-01-15',
            'base_salary'   => 2500000,
            'salary_type'   => 'monthly',
            'status'        => 'active',
        ]);
        Employee::create([
            'employee_code' => 'EMP0002',
            'name'          => 'Siti Rahayu',
            'position'      => 'Staff Gudang',
            'phone'         => '08987654321',
            'join_date'     => '2023-03-01',
            'base_salary'   => 2200000,
            'salary_type'   => 'monthly',
            'status'        => 'active',
        ]);

        $this->command->info('✅ Seeder berhasil! Login:');
        $this->command->info('   Admin: admin@pulsastore.com / password');
        $this->command->info('   Kasir: kasir@pulsastore.com / password');
        $this->command->info('   Gudang: gudang@pulsastore.com / password');
    }
}
