<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Item Details') }}
        </h2>
    </x-slot>

    <div class="mx-auto max-w-6xl px-4 py-8 sm:px-6 lg:px-8">
        <div class="mb-6">
            <a
                href="{{ route('customer.rental-items.index', request()->filled('category') ? ['category' => request('category')] : []) }}"
                class="inline-flex items-center gap-1.5 text-sm font-semibold text-gray-500 transition-colors hover:text-pink-600"
            >
                <x-lucide-arrow-left width="16" height="16" aria-hidden="true" />
                Back to Catalogue
            </a>
        </div>

        <article class="overflow-hidden rounded-3xl border border-gray-100 bg-white shadow-sm">
            <div class="grid grid-cols-1 md:grid-cols-2">
                <div class="relative aspect-square bg-gray-50 md:min-h-[520px] md:aspect-auto">
                    @if ($item->image)
                        <img
                            src="{{ asset('storage/' . $item->image) }}"
                            alt="{{ $item->name }}"
                            class="absolute inset-0 h-full w-full object-cover object-center"
                        >
                    @else
                        <div class="absolute inset-0 flex items-center justify-center text-gray-300">
                            <x-lucide-image width="64" height="64" aria-hidden="true" />
                        </div>
                    @endif

                    <div class="absolute left-5 top-5">
                        @if ($remainingQuantity > 0)
                            <span class="inline-flex items-center rounded-full border border-green-200 bg-green-100 px-3 py-1.5 text-xs font-bold text-green-700 shadow-sm">
                                {{ $remainingQuantity }} Available
                            </span>
                        @else
                            <span class="inline-flex items-center rounded-full border border-red-200 bg-red-100 px-3 py-1.5 text-xs font-bold text-red-700 shadow-sm">
                                Out of Stock
                            </span>
                        @endif
                    </div>
                </div>

                <div class="flex flex-col p-6 sm:p-8 md:p-10">
                    <div class="mb-6 border-b border-gray-100 pb-6">
                        <p class="mb-2 text-xs font-bold uppercase tracking-widest text-yellow-600">
                            {{ $item->category?->name ?? 'Category' }}
                        </p>
                        <h1 class="mb-2 text-3xl font-bold text-gray-900" style="font-family: 'Playfair Display', serif;">
                            {{ $item->name }}
                        </h1>
                        <p class="text-sm text-gray-500">
                            Item Code: <span class="font-medium text-gray-700">{{ $item->item_code ?: '—' }}</span>
                        </p>
                    </div>

                    <div class="mb-8">
                        <div class="mb-4 flex flex-wrap items-baseline gap-2">
                            <span class="text-3xl font-bold text-gray-900">₱{{ number_format($item->rental_price, 2) }}</span>
                            <span class="text-sm font-medium text-gray-500">/ rental</span>
                        </div>
                        <p class="text-sm leading-relaxed text-gray-600">
                            {{ $item->description ?: 'No description available for this item.' }}
                        </p>
                    </div>

                    <div class="mb-8 grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <div class="rounded-xl border border-gray-100 bg-gray-50 p-4">
                            <h2 class="mb-2 text-xs font-bold uppercase tracking-wider text-gray-600">Available Colors</h2>
                            <p class="text-sm font-medium text-gray-800">
                                {{ is_array($item->colors) && count($item->colors) ? implode(', ', $item->colors) : 'Standard' }}
                            </p>
                        </div>
                        @if ($item->size_quantities)
                            <div class="rounded-xl border border-gray-100 bg-gray-50 p-4">
                                <h2 class="mb-2 text-xs font-bold uppercase tracking-wider text-gray-600">Available Sizes</h2>
                                <ul class="space-y-1 text-sm text-gray-800">
                                    @foreach ($item->size_quantities as $size => $sizeQuantity)
                                        <li class="flex justify-between gap-3">
                                            <span>{{ $size }}</span>
                                            <span class="font-medium">{{ $sizeQuantity }} available</span>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                    </div>

                    <div class="mt-auto space-y-3">
                        @if ($remainingQuantity > 0)
                            <a
                                href="{{ route('customer.rental-items.reserve', ['id' => $item->id, 'category' => request('category')]) }}"
                                class="inline-flex w-full items-center justify-center gap-2 rounded-xl px-8 py-4 font-bold text-white shadow-md transition-all hover:-translate-y-0.5 hover:shadow-lg"
                                style="background: linear-gradient(135deg, #f97316 0%, #ec4899 100%);"
                            >
                                <x-lucide-calendar-plus width="20" height="20" aria-hidden="true" />
                                Reserve This Item
                            </a>
                        @else
                            <span class="inline-flex w-full cursor-not-allowed items-center justify-center gap-2 rounded-xl bg-gray-200 px-8 py-4 font-bold text-gray-500">
                                Fully Booked
                            </span>
                        @endif

                        <div
                            class="inline-flex w-full cursor-default items-center justify-center gap-2 rounded-xl border border-gray-200 bg-gray-50 px-8 py-4 font-bold text-gray-700"
                            aria-hidden="true"
                        >
                            <x-lucide-scissors width="20" height="20" aria-hidden="true" />
                            Add to Custom Set Instead
                        </div>
                    </div>
                </div>
            </div>
        </article>
    </div>
</x-app-layout>
