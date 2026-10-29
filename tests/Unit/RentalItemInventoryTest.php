<?php

namespace Tests\Unit;

use App\Models\RentalItem;
use App\Models\Reservation;
use Tests\TestCase;

class RentalItemInventoryTest extends TestCase
{
    public function test_inventory_counts_reserved_rented_overdue_and_available_units(): void
    {
        $item = RentalItem::make([
            'quantity' => 12,
            'status' => 'Available',
        ]);

        $item->setRelation('reservations', collect([
            Reservation::make([
                'status' => 'Pending',
                'quantity' => 2,
                'event_date' => '2026-10-20',
                'return_date' => '2026-10-22',
            ]),
            Reservation::make([
                'status' => 'Confirmed',
                'quantity' => 3,
                'event_date' => '2026-10-20',
                'return_date' => '2026-10-22',
            ]),
            Reservation::make([
                'status' => 'Confirmed',
                'quantity' => 1,
                'event_date' => '2026-10-10',
                'return_date' => '2026-10-16',
            ]),
            Reservation::make([
                'status' => 'Confirmed',
                'quantity' => 1,
                'event_date' => '2026-10-01',
                'return_date' => '2026-10-05',
            ]),
            Reservation::make([
                'status' => 'Confirmed',
                'quantity' => 4,
                'event_date' => '2026-10-10',
                'return_date' => '2026-10-16',
                'returned_at' => '2026-10-15 12:00:00',
            ]),
        ]));

        $this->assertSame([
            'total' => 12,
            'available' => 5,
            'reserved' => 5,
            'rented' => 1,
            'not_returned' => 1,
            'maintenance' => 0,
            'status' => 'Mixed',
        ], $item->inventoryBreakdown('2026-10-14'));
    }

    public function test_manual_maintenance_status_assigns_unallocated_units_to_maintenance(): void
    {
        $item = RentalItem::make([
            'quantity' => 4,
            'status' => 'Maintenance',
        ]);
        $item->setRelation('reservations', collect());

        $this->assertSame([
            'total' => 4,
            'available' => 0,
            'reserved' => 0,
            'rented' => 0,
            'not_returned' => 0,
            'maintenance' => 4,
            'status' => 'Maintenance',
        ], $item->inventoryBreakdown('2026-10-14'));
    }
}