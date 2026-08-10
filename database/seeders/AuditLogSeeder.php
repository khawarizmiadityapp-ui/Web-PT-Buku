<?php

namespace Database\Seeders;

use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Database\Seeder;

class AuditLogSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();
        if ($users->isEmpty()) {
            return;
        }

        $sampleLogs = [
            ['action_type' => 'UPDATE', 'description' => 'Updated stock quantity for Product SKU-001', 'subject_table' => 'products', 'hours_ago' => 1],
            ['action_type' => 'CREATE', 'description' => 'Generated new Shipping Manifest #SM-2024-0847', 'subject_table' => 'stock_outs', 'hours_ago' => 2],
            ['action_type' => 'DELETE', 'description' => 'Deleted obsolete vendor profile', 'subject_table' => 'suppliers', 'hours_ago' => 3],
            ['action_type' => 'UPDATE', 'description' => 'Updated payment status for Invoice #INV-2024-112', 'subject_table' => 'sales_invoices', 'hours_ago' => 4],
            ['action_type' => 'CREATE', 'description' => 'Created new product entry: Buku Tulis A5 58 Lembar', 'subject_table' => 'products', 'hours_ago' => 5],
            ['action_type' => 'LOGIN', 'description' => 'User logged in successfully', 'subject_table' => 'users', 'hours_ago' => 6],
            ['action_type' => 'SYSTEM', 'description' => 'Automated daily backup completed', 'subject_table' => null, 'hours_ago' => 8],
            ['action_type' => 'UPDATE', 'description' => 'Processed stock audit adjustment for 3 items', 'subject_table' => 'stock_audits', 'hours_ago' => 10],
            ['action_type' => 'CREATE', 'description' => 'Registered new supplier: CV Sumber Alat Tulis', 'subject_table' => 'suppliers', 'hours_ago' => 12],
            ['action_type' => 'LOGOUT', 'description' => 'User logged out', 'subject_table' => 'users', 'hours_ago' => 14],
            ['action_type' => 'UPDATE', 'description' => 'Updated company profile settings', 'subject_table' => 'company_settings', 'hours_ago' => 16],
            ['action_type' => 'CREATE', 'description' => 'Created sales invoice #INV-2024-113', 'subject_table' => 'sales_invoices', 'hours_ago' => 18],
            ['action_type' => 'UPDATE', 'description' => 'Updated user profile', 'subject_table' => 'users', 'hours_ago' => 20],
            ['action_type' => 'SYSTEM', 'description' => 'Low stock alert triggered for 5 products', 'subject_table' => 'products', 'hours_ago' => 22],
            ['action_type' => 'DELETE', 'description' => 'Removed expired product return record', 'subject_table' => 'product_returns', 'hours_ago' => 24],
        ];

        foreach ($sampleLogs as $log) {
            $user = $users->random();
            AuditLog::create([
                'user_id' => $user->id,
                'action_type' => $log['action_type'],
                'description' => $log['description'],
                'subject_table' => $log['subject_table'],
                'subject_id' => rand(1, 100),
                'ip_address' => '192.168.1.' . rand(10, 250),
                'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
                'created_at' => now()->subHours($log['hours_ago']),
                'updated_at' => now()->subHours($log['hours_ago']),
            ]);
        }
    }
}
