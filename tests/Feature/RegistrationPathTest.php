<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Role;
use App\Models\RegistrationPath;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationPathTest extends TestCase
{
    use RefreshDatabase;

    protected User $adminUser;

    protected function setUp(): void
    {
        parent::setUp();

        // Create SUPER_ADMIN role
        $superAdmin = Role::create([
            'role_name' => 'Super Admin',
            'role_code' => 'SUPER_ADMIN',
            'description' => 'Full access',
            'status' => 'active',
        ]);

        // Create admin user and attach role
        $this->adminUser = User::create([
            'name' => 'Admin Test',
            'email' => 'admin@test.com',
            'password' => bcrypt('password'),
            'status' => 'active',
        ]);
        $this->adminUser->roles()->attach($superAdmin->id);

        // Create some registration paths
        RegistrationPath::create([
            'code' => 'TEST01',
            'name' => 'Test Path 1',
            'fee' => 100000,
            'is_active' => true,
        ]);
        RegistrationPath::create([
            'code' => 'TEST02',
            'name' => 'Test Path 2',
            'fee' => 150000,
            'is_active' => true,
        ]);
    }

    public function test_index_page_returns_html_normally(): void
    {
        $response = $this->actingAs($this->adminUser)
            ->get('/settings/registration-paths');

        $response->assertStatus(200);
        $response->assertViewIs('registration-paths.index');
        $response->assertSee('Test Path 1');
        $response->assertSee('Test Path 2');
    }

    public function test_index_page_returns_json_on_ajax_request(): void
    {
        $response = $this->actingAs($this->adminUser)
            ->get('/settings/registration-paths', [
                'X-Requested-With' => 'XMLHttpRequest'
            ]);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'html',
            'next_page',
            'has_more'
        ]);

        $jsonData = $response->json();
        $this->assertStringContainsString('Test Path 1', $jsonData['html']);
        $this->assertStringContainsString('Test Path 2', $jsonData['html']);
        $this->assertFalse($jsonData['has_more']);
    }
}
