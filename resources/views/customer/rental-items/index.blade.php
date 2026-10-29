<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ $selectedCategory?->name ?? __('Available Rental Items') }}
        </h2>
    </x-slot>

    <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
        <div class="mb-8">
            <p class="mb-1 text-xs font-bold uppercase tracking-widest text-yellow-600">
                {{ $selectedCategory ? 'Category' : 'Catalogue' }}
            </p>
            <h1 class="text-2xl font-bold uppercase text-gray-900" style="font-family: 'Playfair Display', serif;">
                {{ $selectedCategory->name ?? 'Available Rental Items' }}
            </h1>
            <p class="mt-1 text-sm text-gray-500">
                {{ $selectedCategory
                    ? ($selectedCategory->description ?: 'Items available in this category.')
                    : 'Browse available rental items for your event.' }}
            </p>

            @if ($selectedCategory)
                <a
                    href="{{ route('customer.item-categories.index') }}"
                    class="mt-4 inline-flex items-center gap-2 rounded-lg border border-gray-200 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm transition-colors hover:bg-gray-50"
                >
                    <x-lucide-arrow-left width="16" height="16" aria-hidden="true" />
                    Back to item categories
                </a>
            @endif
        </div>

        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4">
            @forelse ($items as $item)
                <a
                    href="{{ route('customer.rental-items.show', ['id' => $item->id, 'category' => $selectedCategory?->id]) }}"
                    class="group flex flex-col overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm transition-all duration-300 hover:-translate-y-1 hover:shadow-xl"
                >
                    <div class="relative aspect-[4/5] w-full overflow-hidden bg-gray-50">
                        @if ($item->image)
                            <img
                                src="{{ asset('storage/' . $item->image) }}"
                                alt="{{ $item->name }}"
                                class="h-full w-full object-cover object-center transition-transform duration-500 group-hover:scale-105"
                            >
                        @else
                            <div class="flex h-full w-full items-center justify-center text-pink-200">
                                <x-lucide-shirt width="48" height="48" aria-hidden="true" />
                            </div>
                        @endif

                        <div class="absolute right-3 top-3">
                            <span class="inline-flex items-center rounded-md bg-white/90 px-2 py-1 text-[10px] font-bold uppercase tracking-wider text-gray-800 shadow-sm backdrop-blur-sm">
                                {{ $item->remaining_quantity > 0 ? 'Available' : 'Fully Booked' }}
                            </span>
                        </div>
                    </div>

                    <div class="flex flex-1 flex-col border-t border-pink-50 p-5" style="background-color: #fdf4f0;">
                        <h3 class="mb-1 line-clamp-2 text-base font-bold text-gray-900" style="font-family: 'Playfair Display', serif;">
                            {{ $item->name }}
                        </h3>
                        <div class="mt-auto pt-3">
                            <strong class="text-base font-bold" style="color: #c9a35f;">
                                ₱{{ number_format($item->rental_price, 2) }}
                            </strong>
                        </div>
                    </div>
                </a>
            @empty
                <div class="col-span-full rounded-2xl border border-gray-100 bg-white p-12 text-center">
                    <p class="text-sm text-gray-500">No items available in this category yet.</p>
                </div>
            @endforelse
        </div>
    </div>
</x-app-layout>
