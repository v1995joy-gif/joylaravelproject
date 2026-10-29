<?php

namespace Tests\Feature;

use App\Models\Package;
use App\Models\PackageItem;
use App\Models\ItemCategory;
use App\Models\RentalItem;
use App\Models\CustomizationRequest;
use App\Models\Reservation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GenericPackageItemsTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_create_a_package_with_generic_item_types(): void
    {
        $admin = User::factory()->create();
        $admin->forceFill(['role' => 'admin'])->save();

        $response = $this->actingAs($admin)->post(route('admin.packages.store'), [
            'name' => 'Wedding Package',
            'event_type' => 'Wedding',
            'price' => 20000,
            'status' => 'Active',
            'items' => [
                ['item_name' => 'Formal outfit', 'quantity' => 2],
                ['item_name' => 'Accessories', 'quantity' => 1],
            ],
        ]);

        $response->assertRedirect(route('admin.packages.index'));
        $this->assertDatabaseHas('package_items', [
            'item_name' => 'Formal outfit',
            'rental_item_id' => null,
            'quantity' => 2,
        ]);
        $this->assertDatabaseHas('package_items', [
            'item_name' => 'Accessories',
            'rental_item_id' => null,
            'quantity' => 1,
        ]);
    }

    public function test_generic_package_submission_creates_pending_reservations_and_customization_request(): void
    {
        $customer = User::factory()->create();
        $category = ItemCategory::create(['name' => 'Formal Wear']);
        $rentalItem = RentalItem::create([
            'item_code' => 'FORMAL-001',
            'name' => 'Blue Formal Gown',
            'category_id' => $category->id,
            'rental_price' => 2500,
            'status' => 'Available',
            'quantity' => 4,
        ]);
        $additionalItem = RentalItem::create([
            'item_code' => 'FORMAL-ADD-001',
            'name' => 'Silver Clutch',
            'category_id' => $category->id,
            'rental_price' => 600,
            'status' => 'Available',
            'quantity' => 4,
        ]);
        $trousersItem = RentalItem::create([
            'item_code' => 'FORMAL-TROUSERS-001',
            'name' => 'Navy Groom Trousers',
            'category_id' => $category->id,
            'rental_price' => 1800,
            'status' => 'Available',
            'quantity' => 4,
        ]);
        $package = Package::create([
            'name' => 'Wedding Package',
            'event_type' => 'Wedding',
            'price' => 20000,
            'status' => 'Active',
        ]);
        $jacketSlot = PackageItem::create([
            'package_id' => $package->id,
            'item_name' => 'Groom jacket',
            'quantity' => 1,
        ]);
        $trousersSlot = PackageItem::create([
            'package_id' => $package->id,
            'item_name' => 'Groom trousers',
            'quantity' => 1,
        ]);

        $this->actingAs($customer)
            ->get(route('customer.packages.reserve', $package->id))
            ->assertRedirect(route('customer.customizations.create', $package->id));

        $this->actingAs($customer)
            ->get(route('customer.customizations.create', $package->id))
            ->assertOk()
            ->assertSee('Groom jacket')
            ->assertSee('Groom trousers')
            ->assertSee('Package piece: Groom jacket')
            ->assertSee('Choose one available rental item for each included package piece')
            ->assertSee('name="event_date"', false);

        $reservationDate = now()->toDateString();
        $eventDate = now()->addDays(10)->toDateString();
        $returnDate = now()->addDays(12)->toDateString();
        $response = $this->post(route('customer.customizations.store', $package->id), [
            'reservation_date' => $reservationDate,
            'event_date' => $eventDate,
            'return_date' => $returnDate,
            'items' => [
                [
                    'package_item_id' => $jacketSlot->id,
                    'rental_item_id' => $rentalItem->id,
                    'quantity' => 99,
                    'size' => 'M',
                    'color' => 'Blue',
                ],
                [
                    'package_item_id' => $trousersSlot->id,
                    'rental_item_id' => $trousersItem->id,
                    'quantity' => 1,
                    'size' => 'M',
                    'color' => 'Blue',
                ],
                [
                    'rental_item_id' => $additionalItem->id,
                    'quantity' => 2,
                    'size' => 'Standard',
                    'color' => 'Silver',
                ],
            ],
            'customization_details' => 'Use a blue and silver theme with matching accessories.',
            'notes' => 'Please call before pickup.',
        ]);

        $response->assertRedirect(route('customer.reservations.index'));
        $customizationRequest = CustomizationRequest::where('customer_id', $customer->id)->firstOrFail();
        $response->assertSessionHas('customization_request_id', $customizationRequest->id);
        $this->assertDatabaseHas('reservations', [
            'customer_id' => $customer->id,
            'rental_item_id' => $rentalItem->id,
            'customization_request_id' => $customizationRequest->id,
            'quantity' => 1,
            'amount_due' => '20000.00',
            'reservation_date' => $reservationDate . ' 00:00:00',
            'event_date' => $eventDate . ' 00:00:00',
            'return_date' => $returnDate . ' 00:00:00',
            'status' => 'Pending',
        ]);
        $this->assertDatabaseHas('reservations', [
            'customer_id' => $customer->id,
            'rental_item_id' => $additionalItem->id,
            'customization_request_id' => $customizationRequest->id,
            'quantity' => 2,
            'amount_due' => '1200.00',
            'status' => 'Pending',
        ]);
        $this->assertDatabaseHas('reservations', [
            'customer_id' => $customer->id,
            'rental_item_id' => $trousersItem->id,
            'customization_request_id' => $customizationRequest->id,
            'quantity' => 1,
            'amount_due' => '0.00',
            'status' => 'Pending',
        ]);
        $jacketReservation = Reservation::where('customization_request_id', $customizationRequest->id)
            ->where('rental_item_id', $rentalItem->id)
            ->firstOrFail();
        $this->assertStringContainsString('Package piece: Groom jacket', $jacketReservation->notes);
        $this->assertDatabaseHas('customization_requests', [
            'customer_id' => $customer->id,
            'package_id' => $package->id,
            'package_total' => '21200.00',
            'customization_details' => 'Use a blue and silver theme with matching accessories.',
        ]);
        $this->assertDatabaseHas('customization_request_items', [
            'rental_item_id' => $rentalItem->id,
            'package_item_id' => $jacketSlot->id,
            'package_item_name' => 'Groom jacket',
            'quantity' => 1,
            'size' => 'M',
            'color' => 'Blue',
            'is_additional' => 0,
        ]);
        $this->assertDatabaseHas('customization_request_items', [
            'rental_item_id' => $trousersItem->id,
            'package_item_id' => $trousersSlot->id,
            'package_item_name' => 'Groom trousers',
            'quantity' => 1,
            'is_additional' => 0,
        ]);
        $this->assertDatabaseHas('customization_request_items', [
            'rental_item_id' => $additionalItem->id,
            'package_item_id' => null,
            'quantity' => 2,
            'is_additional' => 1,
        ]);
        $this->assertDatabaseCount('customization_request_new_items', 0);

        Reservation::create([
            'customer_id' => $customer->id,
            'rental_item_id' => $additionalItem->id,
            'quantity' => 1,
            'reservation_date' => $reservationDate,
            'event_date' => $eventDate,
            'return_date' => $returnDate,
            'status' => 'Pending',
            'notes' => 'Individual item reservation',
        ]);

        $this->actingAs($customer)
            ->get(route('customer.reservations.index'))
            ->assertOk()
            ->assertSee('Package reservation request submitted successfully.')
            ->assertSee('View package request #'.$customizationRequest->id)
            ->assertSee('Rental Package Reservations')
            ->assertSee('Rental Item Reservations')
            ->assertSee('Rental Package')
            ->assertSee('Selected Rental Item')
            ->assertSee('Rental Item')
            ->assertSee('Wedding Package')
            ->assertSee('View package request #'.$customizationRequest->id);

        $admin = User::factory()->create();
        $admin->forceFill(['role' => 'admin'])->save();
        $this->actingAs($admin)
            ->get(route('admin.reservations.index'))
            ->assertOk()
            ->assertSee('Package Request')
            ->assertSee('Wedding Package')
            ->assertSee('Request #'.$customizationRequest->id);

        $this->actingAs($admin)
            ->get(route('admin.customization-requests.show', $customizationRequest->id))
            ->assertOk()
            ->assertSee('Package Piece')
            ->assertSee('Groom jacket')
            ->assertSee('Groom trousers')
            ->assertSee('Blue Formal Gown')
            ->assertSee('Navy Groom Trousers');

        $package->packageItems()->delete();
        $this->actingAs($admin)
            ->get(route('admin.customization-requests.show', $customizationRequest->id))
            ->assertOk()
            ->assertSee('Groom jacket')
            ->assertSee('Groom trousers');
    }

    public function test_package_submission_requires_a_choice_for_every_included_piece(): void
    {
        $customer = User::factory()->create();
        $category = ItemCategory::create(['name' => 'Formal Wear']);
        $rentalItem = RentalItem::create([
            'item_code' => 'FORMAL-SLOT-001',
            'name' => 'Blue Formal Jacket',
            'category_id' => $category->id,
            'rental_price' => 2500,
            'status' => 'Available',
            'quantity' => 4,
        ]);
        $package = Package::create([
            'name' => 'Wedding Package',
            'event_type' => 'Wedding',
            'price' => 20000,
            'status' => 'Active',
        ]);
        $jacketSlot = PackageItem::create([
            'package_id' => $package->id,
            'item_name' => 'Groom jacket',
            'quantity' => 1,
        ]);
        PackageItem::create([
            'package_id' => $package->id,
            'item_name' => 'Groom trousers',
            'quantity' => 1,
        ]);

        $this->actingAs($customer)
            ->from(route('customer.customizations.create', $package->id))
            ->post(route('customer.customizations.store', $package->id), [
                'reservation_date' => now()->toDateString(),
                'event_date' => now()->addDays(10)->toDateString(),
                'return_date' => now()->addDays(12)->toDateString(),
                'items' => [[
                    'package_item_id' => $jacketSlot->id,
                    'rental_item_id' => $rentalItem->id,
                    'quantity' => 1,
                ]],
                'customization_details' => 'Navy suit.',
            ])
            ->assertRedirect(route('customer.customizations.create', $package->id))
            ->assertSessionHasErrors('items');

        $this->assertDatabaseCount('customization_requests', 0);
        $this->assertDatabaseCount('reservations', 0);
    }

    public function test_admin_can_quote_an_independent_new_item_request(): void
    {
        $admin = User::factory()->create();
        $admin->forceFill(['role' => 'admin'])->save();
        $customer = User::factory()->create();
        $this->actingAs($customer)
            ->post(route('customer.customizations.new-item-request.store'), [
                'new_items' => [
                    [
                        'item_name' => 'Matching hair accessory',
                        'quantity' => 2,
                        'description' => 'Gold-tone accessory',
                    ],
                ],
            ])
            ->assertRedirect(route('customer.customizations.index'));

        $customizationRequest = CustomizationRequest::where('customer_id', $customer->id)->firstOrFail();
        $this->assertSame('New Item Request', $customizationRequest->request_type);
        $this->assertNull($customizationRequest->package_id);
        $this->assertNull($customizationRequest->package_total);
        $newItem = $customizationRequest->newItems()->firstOrFail();

        $this->actingAs($admin)->patch(route('admin.customization-request-items.costing', $newItem->id), [
            'quoted_price' => 300,
            'expense_price' => 150,
        ])->assertRedirect(route('admin.customization-requests.show', $customizationRequest->id));

        $this->assertSame(300.0, (float) $newItem->fresh()->quoted_price);
        $this->assertSame(150.0, (float) $newItem->fresh()->expense_price);
        $this->assertNull($customizationRequest->fresh()->package_total);
    }

    public function test_generic_package_submission_fails_when_inventory_is_unavailable(): void
    {
        $customer = User::factory()->create();
        $category = ItemCategory::create(['name' => 'Formal Wear']);
        $rentalItem = RentalItem::create([
            'item_code' => 'FORMAL-002',
            'name' => 'Blue Formal Gown',
            'category_id' => $category->id,
            'rental_price' => 2500,
            'status' => 'Available',
            'quantity' => 1,
        ]);
        $package = Package::create([
            'name' => 'Wedding Package',
            'event_type' => 'Wedding',
            'price' => 20000,
            'status' => 'Active',
        ]);
        $packageItem = PackageItem::create([
            'package_id' => $package->id,
            'item_name' => 'Formal outfit',
            'quantity' => 2,
        ]);

        $response = $this->actingAs($customer)
            ->from(route('customer.customizations.create', $package->id))
            ->post(route('customer.customizations.store', $package->id), [
                'reservation_date' => now()->toDateString(),
                'event_date' => now()->addDays(10)->toDateString(),
                'return_date' => now()->addDays(12)->toDateString(),
                'items' => [[
                    'package_item_id' => $packageItem->id,
                    'rental_item_id' => $rentalItem->id,
                    'quantity' => 2,
                ]],
                'customization_details' => 'Blue and silver theme.',
            ]);

        $response->assertRedirect(route('customer.customizations.create', $package->id))
            ->assertSessionHasErrors('items.0.rental_item_id');
        $this->assertDatabaseCount('reservations', 0);
        $this->assertDatabaseCount('customization_requests', 0);
    }

    public function test_legacy_inventory_package_details_show_only_item_types_and_route_to_customization(): void
    {
        $customer = User::factory()->create();
        $category = ItemCategory::create(['name' => 'TUXEDO']);
        $rentalItem = RentalItem::create([
            'item_code' => 'TUX-DETAIL-001',
            'name' => 'Raven Tux',
            'category_id' => $category->id,
            'rental_price' => 2500,
            'status' => 'Available',
            'quantity' => 4,
            'size_quantities' => ['Large' => 3, 'Medium' => 2],
            'colors' => ['Black'],
        ]);
        $package = Package::create([
            'name' => 'Legacy Wedding Package',
            'event_type' => 'Wedding',
            'price' => 20000,
            'status' => 'Active',
        ]);
        PackageItem::create([
            'package_id' => $package->id,
            'rental_item_id' => $rentalItem->id,
            'quantity' => 1,
        ]);

        $this->actingAs($customer)
            ->get(route('customer.packages.show', $package->id))
            ->assertOk()
            ->assertSee('Included Piece')
            ->assertSee('Included Quantity')
            ->assertSee('Raven Tux')
            ->assertDontSee('TUXEDO')
            ->assertDontSee('Sizes')
            ->assertDontSee('Colors')
            ->assertDontSee('class="reserve-btn"')
            ->assertSee('Customize')
            ->assertSee('Reserve This Package');

        $this->actingAs($customer)
            ->get(route('customer.packages.reserve', $package->id))
            ->assertRedirect(route('customer.customizations.create', $package->id));

        $this->actingAs($customer)
            ->post(route('customer.packages.reserve.store', $package->id), [])
            ->assertRedirect(route('customer.customizations.create', $package->id));

        $this->assertDatabaseCount('reservations', 0);
    }
}
