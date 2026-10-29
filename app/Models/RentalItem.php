<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\ItemCategory;

class RentalItem extends Model
{
    protected $fillable = [
        'item_code',
        'name',
        'description',
        'image',
        'category_id',
        'rental_price',
        'status',
        'quantity',
        'size_quantities',
        'colors',
    ];

    protected $casts = [
        'size_quantities' => 'array',
        'colors' => 'array',
    ];

    public function category()
    {
        return $this->belongsTo(ItemCategory::class, 'category_id');
    }
    public function reservations()
    {
        return $this->hasMany(Reservation::class, 'rental_item_id');
    }

    /**
     * Units committed by Pending/Confirmed reservations.
     * With a [start, end] window, only overlapping bookings count;
     * without one, only bookings whose return date hasn't passed —
     * stale/past reservations no longer block availability forever.
     */
    public function committedQuantity(?string $start = null, ?string $end = null): int
    {
        $query = $this->reservations()
            ->whereIn('status', ['Pending', 'Confirmed']);

        if ($start && $end) {
            $query->where('event_date', '<=', $end)
                ->where('return_date', '>=', $start);
        } else {
            $query->where('return_date', '>=', now()->toDateString());
        }

        return (int) $query->sum('quantity');
    }

    public function remainingQuantity(?string $start = null, ?string $end = null): int
    {
        return max(0, (int) $this->quantity - $this->committedQuantity($start, $end));
    }

    public function unavailableQuantity(): int
    {
        if ($this->status !== 'Available') {
            return (int) $this->quantity;
        }

        return min((int) $this->quantity, $this->committedQuantity());
    }

    public function availableQuantity(): int
    {
        return max(0, (int) $this->quantity - $this->unavailableQuantity());
    }

    public function inventoryBreakdown(?string $asOf = null): array
    {
        $asOf ??= now()->toDateString();
        $reservations = $this->relationLoaded('reservations')
            ? $this->getRelation('reservations')
            : $this->reservations()
                ->whereIn('status', ['Pending', 'Confirmed'])
                ->whereNull('returned_at')
                ->get();

        $reserved = 0;
        $rented = 0;
        $notReturned = 0;

        foreach ($reservations as $reservation) {
            if ($reservation->returned_at) {
                continue;
            }

            $quantity = (int) $reservation->quantity;
            $status = strtolower((string) $reservation->status);

            if ($status === 'pending') {
                $reserved += $quantity;
                continue;
            }

            if ($status !== 'confirmed') {
                continue;
            }

            $returnDate = $reservation->return_date?->toDateString();
            $eventDate = $reservation->event_date?->toDateString();

            if ($returnDate && $returnDate < $asOf) {
                $notReturned += $quantity;
            } elseif (! $eventDate || $eventDate <= $asOf) {
                $rented += $quantity;
            } else {
                $reserved += $quantity;
            }
        }

        $total = (int) $this->quantity;
        $unallocated = max(0, $total - $reserved - $rented - $notReturned);
        $manualStatusBucket = match (strtolower((string) $this->status)) {
            'reserved' => 'reserved',
            'rented' => 'rented',
            'maintenance' => 'maintenance',
            default => 'available',
        };

        $available = 0;
        $maintenance = 0;

        if ($manualStatusBucket === 'reserved') {
            $reserved += $unallocated;
        } elseif ($manualStatusBucket === 'rented') {
            $rented += $unallocated;
        } elseif ($manualStatusBucket === 'maintenance') {
            $maintenance = $unallocated;
        } else {
            $available = $unallocated;
        }

        $states = array_filter([
            'Available' => $available,
            'Reserved' => $reserved,
            'Rented' => $rented,
            'Not returned' => $notReturned,
            'Maintenance' => $maintenance,
        ], fn (int $quantity): bool => $quantity > 0);

        return [
            'total' => $total,
            'available' => $available,
            'reserved' => $reserved,
            'rented' => $rented,
            'not_returned' => $notReturned,
            'maintenance' => $maintenance,
            'status' => count($states) > 1 ? 'Mixed' : (array_key_first($states) ?? 'Available'),
        ];
    }

    public function packageItems(): HasMany
{
    return $this->hasMany(PackageItem::class);
}
}