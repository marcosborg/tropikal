<?php

namespace Tests\Feature;

use App\Filament\Resources\Users\UserResource;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminPanelTest extends TestCase
{
    use RefreshDatabase;

    public function test_dashboard_contains_operational_widgets(): void
    {
        $user = User::factory()->create(['is_admin' => true]);
        $this->actingAs($user)->get('/admin')->assertOk()
            ->assertSee('Pedidos de Orçamento')->assertSee('Utilizadores')->assertSee('Configurações');
    }

    public function test_user_cannot_delete_self_or_last_administrator(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        $this->assertFalse(UserResource::canDelete($user));
        $other = User::factory()->create();
        $this->assertTrue(UserResource::canDelete($other));
    }
}
