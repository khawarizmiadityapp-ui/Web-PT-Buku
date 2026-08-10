<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * 
     * IMPORTANT: Passwords are documented in comments for development purposes.
     * In production, use secure password management and remove these comments.
     */
    public function run(): void
    {
        // Admin Account
        User::updateOrCreate(
            ['email' => 'admin@ptbuku.com'],
            [
                'name' => 'Admin Nusantara',
                'password' => Hash::make('admin123'),
                'phone' => '+62 812-3456-7890',
                'position' => 'System Administrator',
                'department' => 'IT / Development',
                'role' => 'System Admin',
                'bio' => 'System administrator responsible for ERP operations and user management.',
            ]
        );

        // Manager Account
        User::updateOrCreate(
            ['email' => 'manager@ptbuku.com'],
            [
                'name' => 'Manager Operasional',
                'password' => Hash::make('manager123'),
                'phone' => '+62 813-9876-5432',
                'position' => 'Operations Manager',
                'department' => 'Operations',
                'role' => 'Manager',
            ]
        );

        // Warehouse Manager (Head)
        User::updateOrCreate(
            ['email' => 'warehouse@ptbuku.com'],
            [
                'name' => 'Ahmad Nusantara',
                'password' => Hash::make('warehouse123'),
                'phone' => '+62 812-3456-7890',
                'position' => 'Warehouse Operations Manager',
                'department' => 'Warehouse & Logistics',
                'role' => 'Warehouse Manager',
                'bio' => 'Responsible for warehouse operations, stock management, inventory audits, and logistics coordination.',
            ]
        );

        // Warehouse Staff 1 - Picker
        User::updateOrCreate(
            ['email' => 'picker@ptbuku.com'],
            [
                'name' => 'Budi Santoso',
                'password' => Hash::make('picker123'),
                'phone' => '+62 813-2222-3333',
                'position' => 'Warehouse Picker',
                'department' => 'Warehouse & Logistics',
                'role' => 'Warehouse Manager',
                'bio' => 'Order picking specialist, responsible for accurate item selection and zone management.',
            ]
        );

        // Warehouse Staff 2 - Packer
        User::updateOrCreate(
            ['email' => 'packer@ptbuku.com'],
            [
                'name' => 'Siti Rahmawati',
                'password' => Hash::make('packer123'),
                'phone' => '+62 813-4444-5555',
                'position' => 'Warehouse Packer',
                'department' => 'Warehouse & Logistics',
                'role' => 'Warehouse Manager',
                'bio' => 'Packing and shipping specialist, ensures proper packaging and labeling of all outbound orders.',
            ]
        );

        // Warehouse Staff 3 - Verifier
        User::updateOrCreate(
            ['email' => 'verifier@ptbuku.com'],
            [
                'name' => 'Eko Prasetyo',
                'password' => Hash::make('verifier123'),
                'phone' => '+62 813-6666-7777',
                'position' => 'Warehouse Verifier',
                'department' => 'Warehouse & Logistics',
                'role' => 'Warehouse Manager',
                'bio' => 'Quality control and verification specialist for incoming and outgoing goods.',
            ]
        );

        // Sales Staff
        User::updateOrCreate(
            ['email' => 'sales@ptbuku.com'],
            [
                'name' => 'Sales Marketing',
                'password' => Hash::make('sales123'), // Password: 'sales123'
            ]
        );

        // Finance Staff
        User::updateOrCreate(
            ['email' => 'finance@ptbuku.com'],
            [
                'name' => 'Staff Finance',
                'password' => Hash::make('finance123'), // Password: 'finance123'
            ]
        );

        // CS / Customer Service
        User::updateOrCreate(
            ['email' => 'cs@ptbuku.com'],
            [
                'name' => 'Customer Service',
                'password' => Hash::make('cs123'), // Password: 'cs123'
            ]
        );

        // Cashier - John Doe
        User::updateOrCreate(
            ['email' => 'kasir@ptbuku.com'],
            [
                'name' => 'John Doe',
                'password' => Hash::make('kasir123'),
                'phone' => '+62 812-3456-1111',
                'position' => 'Head Cashier',
                'department' => 'Cashier / POS',
                'role' => 'Cashier',
                'bio' => 'Head cashier responsible for daily POS operations and transaction management.',
            ]
        );
    }
}
