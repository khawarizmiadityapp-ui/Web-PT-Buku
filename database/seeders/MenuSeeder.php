<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Menu;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // -----------------------------------------------------
        // ADMIN NAVBAR MENUS
        // -----------------------------------------------------
        $adminRole = 'System Admin';

        Menu::updateOrCreate(
            ['name' => 'Dashboard', 'role' => $adminRole, 'location' => 'navbar'],
            ['route' => 'dashboard', 'order' => 1]
        );

        $adminInventory = Menu::updateOrCreate(
            ['name' => 'Inventory', 'role' => $adminRole, 'location' => 'navbar'],
            ['route' => null, 'order' => 2]
        );
        // Inventory Sub-menus
        Menu::updateOrCreate(['name' => 'Supplier', 'parent_id' => $adminInventory->id, 'role' => $adminRole, 'location' => 'navbar'], ['route' => 'suppliers.index', 'order' => 1]);
        Menu::updateOrCreate(['name' => 'Produk', 'parent_id' => $adminInventory->id, 'role' => $adminRole, 'location' => 'navbar'], ['route' => 'products.index', 'order' => 2]);
        Menu::updateOrCreate(['name' => 'Customer', 'parent_id' => $adminInventory->id, 'role' => $adminRole, 'location' => 'navbar'], ['route' => 'customers.index', 'order' => 3]);

        $adminLogistics = Menu::updateOrCreate(
            ['name' => 'Logistics', 'role' => $adminRole, 'location' => 'navbar'],
            ['route' => null, 'order' => 3]
        );
        // Logistics Sub-menus
        Menu::updateOrCreate(['name' => 'Barang Keluar', 'parent_id' => $adminLogistics->id, 'role' => $adminRole, 'location' => 'navbar'], ['route' => 'stock-outs.index', 'order' => 1]);

        $adminSales = Menu::updateOrCreate(
            ['name' => 'Sales', 'role' => $adminRole, 'location' => 'navbar'],
            ['route' => null, 'order' => 4]
        );
        // Sales Sub-menus
        Menu::updateOrCreate(['name' => 'Invoices', 'parent_id' => $adminSales->id, 'role' => $adminRole, 'location' => 'navbar'], ['route' => 'sales.invoices.index', 'order' => 1]);
        Menu::updateOrCreate(['name' => 'Performance Report', 'parent_id' => $adminSales->id, 'role' => $adminRole, 'location' => 'navbar'], ['route' => 'sales.report', 'order' => 2]);

        // -----------------------------------------------------
        // ADMIN SIDEBAR MENUS
        // -----------------------------------------------------
        Menu::updateOrCreate(
            ['name' => 'Dashboard', 'role' => $adminRole, 'location' => 'sidebar'],
            ['route' => 'dashboard', 'icon' => 'fas fa-th-large', 'order' => 1]
        );
        
        $adminSidebarMaster = Menu::updateOrCreate(
            ['name' => 'Master Data', 'role' => $adminRole, 'location' => 'sidebar'],
            ['route' => null, 'icon' => 'fas fa-database', 'order' => 2]
        );
        Menu::updateOrCreate(['name' => 'Supplier', 'parent_id' => $adminSidebarMaster->id, 'role' => $adminRole, 'location' => 'sidebar'], ['route' => 'suppliers.index', 'icon' => 'fas fa-truck-loading', 'order' => 1]);
        Menu::updateOrCreate(['name' => 'Produk', 'parent_id' => $adminSidebarMaster->id, 'role' => $adminRole, 'location' => 'sidebar'], ['route' => 'products.index', 'icon' => 'fas fa-box', 'order' => 2]);
        Menu::updateOrCreate(['name' => 'Customer', 'parent_id' => $adminSidebarMaster->id, 'role' => $adminRole, 'location' => 'sidebar'], ['route' => 'customers.index', 'icon' => 'fas fa-users', 'order' => 3]);

        $adminSidebarWarehouse = Menu::updateOrCreate(
            ['name' => 'Warehouse', 'role' => $adminRole, 'location' => 'sidebar'],
            ['route' => null, 'icon' => 'fas fa-warehouse', 'order' => 3]
        );
        Menu::updateOrCreate(['name' => 'Stock Count & Audit', 'parent_id' => $adminSidebarWarehouse->id, 'role' => $adminRole, 'location' => 'sidebar'], ['route' => 'warehouse.stock-audit.index', 'icon' => 'fas fa-clipboard-check', 'order' => 1]);
        Menu::updateOrCreate(['name' => 'Retur Barang', 'parent_id' => $adminSidebarWarehouse->id, 'role' => $adminRole, 'location' => 'sidebar'], ['route' => 'warehouse.returns.index', 'icon' => 'fas fa-undo', 'order' => 2]);
        Menu::updateOrCreate(['name' => 'Barang Masuk', 'parent_id' => $adminSidebarWarehouse->id, 'role' => $adminRole, 'location' => 'sidebar'], ['route' => 'warehouse.incoming-goods', 'icon' => 'fas fa-dolly', 'order' => 3]);

        $adminSidebarSales = Menu::updateOrCreate(
            ['name' => 'Sales', 'role' => $adminRole, 'location' => 'sidebar'],
            ['route' => null, 'icon' => 'fas fa-shopping-cart', 'order' => 4]
        );
        Menu::updateOrCreate(['name' => 'Invoices', 'parent_id' => $adminSidebarSales->id, 'role' => $adminRole, 'location' => 'sidebar'], ['route' => 'sales.invoices.index', 'icon' => 'fas fa-file-invoice', 'order' => 1]);
        Menu::updateOrCreate(['name' => 'Performance Report', 'parent_id' => $adminSidebarSales->id, 'role' => $adminRole, 'location' => 'sidebar'], ['route' => 'sales.report', 'icon' => 'fas fa-chart-pie', 'order' => 2]);

        Menu::updateOrCreate(['name' => 'Purchase Order', 'role' => $adminRole, 'location' => 'sidebar'], ['route' => 'purchases.index', 'icon' => 'fas fa-shopping-bag', 'order' => 5]);
        Menu::updateOrCreate(['name' => 'Barang Keluar', 'role' => $adminRole, 'location' => 'sidebar'], ['route' => 'stock-outs.index', 'icon' => 'fas fa-dolly', 'order' => 6]);
        Menu::updateOrCreate(['name' => 'Reports', 'role' => $adminRole, 'location' => 'sidebar'], ['route' => 'reports.financial', 'icon' => 'fas fa-chart-bar', 'order' => 7]);
        Menu::updateOrCreate(['name' => 'Settings', 'role' => $adminRole, 'location' => 'sidebar'], ['route' => 'settings', 'icon' => 'fas fa-cog', 'order' => 8]);


        // -----------------------------------------------------
        // WAREHOUSE MANAGER NAVBAR MENUS
        // -----------------------------------------------------
        $wmRole = 'Warehouse Manager';

        Menu::updateOrCreate(
            ['name' => 'Inventory', 'role' => $wmRole, 'location' => 'navbar'],
            ['route' => 'warehouse.stock', 'order' => 1]
        );
        Menu::updateOrCreate(
            ['name' => 'Orders', 'role' => $wmRole, 'location' => 'navbar'],
            ['route' => 'stock-outs.index', 'order' => 2]
        );
        Menu::updateOrCreate(
            ['name' => 'Reports', 'role' => $wmRole, 'location' => 'navbar'],
            ['route' => 'reports.financial', 'order' => 3]
        );

        // -----------------------------------------------------
        // WAREHOUSE MANAGER SIDEBAR MENUS
        // -----------------------------------------------------
        Menu::updateOrCreate(['name' => 'Dashboard', 'role' => $wmRole, 'location' => 'sidebar'], ['route' => 'warehouse.index', 'icon' => 'fas fa-th-large', 'order' => 1]);
        Menu::updateOrCreate(['name' => 'Barang Masuk', 'role' => $wmRole, 'location' => 'sidebar'], ['route' => 'warehouse.incoming-goods', 'icon' => 'fas fa-box-open', 'order' => 2]);
        Menu::updateOrCreate(['name' => 'Stok Gudang', 'role' => $wmRole, 'location' => 'sidebar'], ['route' => 'warehouse.stock', 'icon' => 'fas fa-dolly', 'order' => 3]);
        Menu::updateOrCreate(['name' => 'Picking', 'role' => $wmRole, 'location' => 'sidebar'], ['route' => 'warehouse.picking', 'icon' => 'fas fa-box', 'order' => 4]);
        Menu::updateOrCreate(['name' => 'Packing', 'role' => $wmRole, 'location' => 'sidebar'], ['route' => 'warehouse.packing', 'icon' => 'fas fa-truck-loading', 'order' => 5]);
        Menu::updateOrCreate(['name' => 'Barang Keluar', 'role' => $wmRole, 'location' => 'sidebar'], ['route' => 'stock-outs.index', 'icon' => 'fas fa-dolly-flatbed', 'order' => 6]);
        Menu::updateOrCreate(['name' => 'Verifikasi Masuk', 'role' => $wmRole, 'location' => 'sidebar'], ['route' => 'warehouse.verifikasi.index', 'icon' => 'fas fa-exchange-alt', 'order' => 7]);
        Menu::updateOrCreate(['name' => 'Riwayat Aktivitas', 'role' => $wmRole, 'location' => 'sidebar'], ['route' => 'warehouse.returns.index', 'icon' => 'fas fa-undo-alt', 'order' => 8]);

        // -----------------------------------------------------
        // CASHIER SIDEBAR MENUS
        // -----------------------------------------------------
        $cashierRole = 'Cashier';
        Menu::updateOrCreate(['name' => 'Dashboard', 'role' => $cashierRole, 'location' => 'sidebar'], ['route' => 'cashier.index', 'icon' => 'fas fa-th-large', 'order' => 1]);
        Menu::updateOrCreate(['name' => 'Transaction', 'role' => $cashierRole, 'location' => 'sidebar'], ['route' => 'cashier.transaction.enhanced', 'icon' => 'fas fa-cash-register', 'order' => 2]);
        Menu::updateOrCreate(['name' => 'Returns', 'role' => $cashierRole, 'location' => 'sidebar'], ['route' => 'returns.index', 'icon' => 'fas fa-undo', 'order' => 3]);
        Menu::updateOrCreate(['name' => 'History', 'role' => $cashierRole, 'location' => 'sidebar'], ['route' => 'cashier.history', 'icon' => 'fas fa-history', 'order' => 4]);
        Menu::updateOrCreate(['name' => 'Customer', 'role' => $cashierRole, 'location' => 'sidebar'], ['route' => 'customers.index', 'icon' => 'fas fa-users', 'order' => 5]);
    }
}
