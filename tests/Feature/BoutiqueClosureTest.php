<?php

namespace Tests\Feature;

use App\Models\BoutiqueClosure;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BoutiqueClosureTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_create_and_view_a_boutique_closure(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)
            ->post(route('admin.appointments.store-closure'), [
                'closure_date' => '2030-01-02',
                'reason' => 'Annual Maintenance',
            ])
            ->assertRedirect(route('admin.appointments.index'));

        $this->assertTrue(BoutiqueClosure::whereDate('closure_date', '2030-01-02')
            ->where('reason', 'Annual Maintenance')
            ->exists());

        $this->actingAs($admin)
            ->get(route('admin.appointments.index'))
            ->assertOk()
            ->assertSee('Annual Maintenance')
            ->assertSee('2030-01-02');
    }

    public function test_a_date_cannot_be_marked_closed_twice(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $payload = [
            'closure_date' => '2030-01-02',
            'reason' => 'Annual Maintenance',
        ];

        $this->actingAs($admin)
            ->post(route('admin.appointments.store-closure'), $payload)
            ->assertRedirect(route('admin.appointments.index'));

        $this->from(route('admin.appointments.index'))
            ->post(route('admin.appointments.store-closure'), $payload)
            ->assertSessionHasErrors('closure_date');
    }

    public function test_admin_can_edit_and_reopen_a_closure(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $closure = BoutiqueClosure::create([
            'closure_date' => '2030-01-02',
            'reason' => 'Annual Maintenance',
        ]);

        $this->actingAs($admin)
            ->patch(route('admin.appointments.update-closure', $closure->id), [
                'closure_date' => '2030-01-03',
                'reason' => 'Public Holiday',
            ])
            ->assertRedirect(route('admin.appointments.index'));

        $this->assertTrue(BoutiqueClosure::whereDate('closure_date', '2030-01-03')
            ->where('reason', 'Public Holiday')
            ->exists());

        $this->delete(route('admin.appointments.reopen-closure', $closure->id))
            ->assertRedirect(route('admin.appointments.index'));

        $this->assertFalse(BoutiqueClosure::whereKey($closure->id)->exists());
    }

    public function test_admin_cannot_create_or_edit_a_closure_for_a_past_date(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $closure = BoutiqueClosure::create([
            'closure_date' => '2030-01-02',
            'reason' => 'Annual Maintenance',
        ]);

        $this->actingAs($admin)
            ->from(route('admin.appointments.index'))
            ->post(route('admin.appointments.store-closure'), [
                'closure_date' => '2000-01-01',
                'reason' => 'Past date',
            ])
            ->assertSessionHasErrors('closure_date');

        $this->from(route('admin.appointments.index'))
            ->patch(route('admin.appointments.update-closure', $closure->id), [
                'closure_date' => '2000-01-01',
                'reason' => 'Past date',
            ])
            ->assertSessionHasErrors('closure_date');

        $this->assertTrue(BoutiqueClosure::whereKey($closure->id)
            ->whereDate('closure_date', '2030-01-02')
            ->exists());
    }

    public function test_customer_dashboard_displays_boutique_closure_dates(): void
    {
        $customer = User::factory()->create(['role' => 'customer']);
        BoutiqueClosure::create([
            'closure_date' => '2030-01-02',
            'reason' => 'Annual Maintenance',
        ]);

        $this->actingAs($customer)
            ->get(route('dashboard'))
            ->assertOk()
            ->assertSee('Availability')
            ->assertSee('2030-01-02')
            ->assertDontSee('Annual Maintenance');
    }
}
