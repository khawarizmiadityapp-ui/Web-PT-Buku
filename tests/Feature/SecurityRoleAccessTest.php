<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SecurityRoleAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_unauthenticated_user_redirected_from_protected_routes(): void
    {
        $response = $this->get('/settings/system');
        $response->assertRedirect('/login');
    }

    public function test_cashier_cannot_access_system_settings(): void
    {
        $cashier = new User(['name' => 'Test Cashier', 'email' => 'cashier_test@example.com', 'role' => 'Cashier']);
        
        $response = $this->actingAs($cashier)->get('/settings/system');
        $response->assertStatus(403);
    }

    public function test_cashier_cannot_access_audit_logs(): void
    {
        $cashier = new User(['name' => 'Test Cashier', 'email' => 'cashier_test@example.com', 'role' => 'Cashier']);
        
        $response = $this->actingAs($cashier)->get('/settings/audit');
        $response->assertStatus(403);
    }

    public function test_cashier_cannot_delete_product(): void
    {
        $product = \App\Models\Product::create([
            'product_code' => 'BOOK-001',
            'product_name' => 'Test Book',
            'category' => 'Buku',
            'system_stock' => 10,
            'physical_stock' => 10,
            'price' => 50000,
            'unit' => 'Pcs',
            'status' => 'Active',
        ]);

        $cashier = new User(['name' => 'Test Cashier', 'email' => 'cashier_test@example.com', 'role' => 'Cashier']);
        
        $response = $this->actingAs($cashier)->delete('/products/' . $product->id);
        $response->assertStatus(403);
    }

    public function test_admin_can_access_system_settings(): void
    {
        $admin = new User(['name' => 'Test Admin', 'email' => 'admin_test@example.com', 'role' => 'System Admin']);
        
        $response = $this->actingAs($admin)->get('/settings/system');
        $response->assertStatus(200);
    }
}
