<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Make Reservation') }}
        </h2>
    </x-slot>

    <div class="mx-auto max-w-5xl px-4 py-8 sm:px-6 lg:px-8">
        @if ($errors->any())
            <div class="mb-6 rounded-xl border border-red-200 bg-red-50 px-5 py-4 text-sm text-red-700" role="alert">
                <p class="font-bold">Please correct the following:</p>
                <ul class="mt-2 list-inside list-disc space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <section class="overflow-hidden rounded-3xl border border-gray-100 bg-white shadow-sm">
            <div class="grid grid-cols-1 md:grid-cols-5">
                <div class="flex flex-col border-b border-gray-100 bg-gray-50/50 p-6 sm:p-8 md:col-span-2 md:border-b-0 md:border-r">
                    <div class="mb-6 aspect-square w-full overflow-hidden rounded-2xl border border-gray-200 bg-gray-100 shadow-sm">
                        @if ($item->image)
                            <img
                                src="{{ asset('storage/' . $item->image) }}"
                                alt="{{ $item->name }}"
                                class="h-full w-full object-cover"
                            >
                        @else
                            <div class="flex h-full w-full items-center justify-center text-gray-300">
                                <x-lucide-shirt width="48" height="48" aria-hidden="true" />
                            </div>
                        @endif
                    </div>

                    <h1 class="mb-1 text-xl font-bold text-gray-900" style="font-family: 'Playfair Display', serif;">{{ $item->name }}</h1>
                    <p class="mb-4 text-xs font-semibold uppercase tracking-wider text-gray-500">{{ $item->category?->name ?? 'Category' }}</p>

                    <div class="mt-auto space-y-3 border-t border-gray-200 pt-6">
                        <div class="flex justify-between gap-4 text-sm">
                            <span class="text-gray-500">Rental Price</span>
                            <span class="font-bold text-gray-900">₱{{ number_format($item->rental_price, 2) }}</span>
                        </div>
                        <div class="flex justify-between gap-4 text-sm">
                            <span class="text-gray-500">Available Colors</span>
                            <span class="text-right font-medium text-gray-900">
                                {{ is_array($item->colors) && count($item->colors) ? implode(', ', $item->colors) : 'Standard' }}
                            </span>
                        </div>
                        <div class="flex justify-between gap-4 text-sm">
                            <span class="text-gray-500">Max Quantity</span>
                            <span class="font-medium text-gray-900">{{ $remainingQuantity }}</span>
                        </div>
                        @if ($item->size_quantities)
                            <div class="border-t border-gray-200 pt-3">
                                <p class="mb-2 text-sm text-gray-500">Available Sizes</p>
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
                </div>

                <div class="p-6 sm:p-8 md:col-span-3 md:p-10">
                    <form action="{{ route('customer.reservations.store') }}" method="POST" class="reservation-form flex h-full w-full min-w-0 flex-col">
                        @csrf
                        <input type="hidden" name="rental_item_id" value="{{ $item->id }}">

                        <div class="w-full flex-1 space-y-5">
                            <div>
                                <label for="quantity" class="mb-2 block text-xs font-bold uppercase tracking-wider text-gray-600">Quantity</label>
                                <input
                                    type="number"
                                    id="quantity"
                                    name="quantity"
                                    value="{{ old('quantity', 1) }}"
                                    min="1"
                                    max="{{ $remainingQuantity }}"
                                    required
                                    class="w-full rounded-xl border border-gray-300 bg-white px-4 py-3 font-medium text-gray-900 transition-colors focus:border-pink-500 focus:outline-none focus:ring-2 focus:ring-pink-200"
                                >
                            </div>

                            <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                                <div>
                                    <label for="reservation_date" class="mb-2 block text-xs font-bold uppercase tracking-wider text-gray-600">Reservation Date</label>
                                    <input
                                        type="date"
                                        id="reservation_date"
                                        name="reservation_date"
                                        value="{{ old('reservation_date') }}"
                                        min="{{ now()->toDateString() }}"
                                        required
                                        class="w-full rounded-xl border border-gray-300 bg-white px-4 py-3 font-medium text-gray-900 transition-colors focus:border-pink-500 focus:outline-none focus:ring-2 focus:ring-pink-200"
                                    >
                                </div>
                                <div>
                                    <label for="event_date" class="mb-2 block text-xs font-bold uppercase tracking-wider text-gray-600">Event Date</label>
                                    <input
                                        type="date"
                                        id="event_date"
                                        name="event_date"
                                        value="{{ old('event_date') }}"
                                        min="{{ old('reservation_date', now()->toDateString()) }}"
                                        required
                                        class="w-full rounded-xl border border-gray-300 bg-white px-4 py-3 font-medium text-gray-900 transition-colors focus:border-pink-500 focus:outline-none focus:ring-2 focus:ring-pink-200"
                                    >
                                </div>
                            </div>

                            <div>
                                <label for="return_date" class="mb-2 block text-xs font-bold uppercase tracking-wider text-gray-600">Return Date</label>
                                <input
                                    type="date"
                                    id="return_date"
                                    name="return_date"
                                    value="{{ old('return_date') }}"
                                    min="{{ old('event_date', old('reservation_date', now()->toDateString())) }}"
                                    required
                                    class="w-full rounded-xl border border-gray-300 bg-white px-4 py-3 font-medium text-gray-900 transition-colors focus:border-pink-500 focus:outline-none focus:ring-2 focus:ring-pink-200"
                                >
                            </div>

                            <div>
                                <label for="notes" class="mb-2 block text-xs font-bold uppercase tracking-wider text-gray-600">Notes <span class="font-normal normal-case text-gray-400">(Optional)</span></label>
                                <textarea
                                    id="notes"
                                    name="notes"
                                    rows="3"
                                    placeholder="Any special requests or details we should know?"
                                    class="w-full resize-y rounded-xl border border-gray-300 bg-white px-4 py-3 text-sm text-gray-900 transition-colors focus:border-pink-500 focus:outline-none focus:ring-2 focus:ring-pink-200"
                                >{{ old('notes') }}</textarea>
                            </div>
                        </div>

                        <div class="mt-8 flex flex-col-reverse justify-end gap-3 border-t border-gray-100 pt-6 sm:flex-row">
                            <a
                                href="{{ route('customer.rental-items.show', ['id' => $item->id, 'category' => request('category')]) }}"
                                class="w-full rounded-xl border border-gray-300 px-6 py-3 text-center font-bold text-gray-700 transition-colors hover:bg-gray-50 sm:w-auto"
                            >
                                Cancel
                            </a>
                            <button
                                type="submit"
                                class="inline-flex w-full items-center justify-center gap-2 rounded-xl px-8 py-3 font-bold text-white shadow-md transition-all hover:-translate-y-0.5 hover:shadow-lg sm:w-auto"
                                style="background: linear-gradient(135deg, #f97316 0%, #ec4899 100%);"
                            >
                                <x-lucide-calendar-check width="18" height="18" aria-hidden="true" />
                                Submit Reservation
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </section>
    </div>

    <style>
        .reservation-form {
            width: 100% !important;
            max-width: none !important;
        }

        .reservation-form input:not([type="hidden"]),
        .reservation-form textarea {
            display: block;
            width: 100% !important;
            max-width: none !important;
            box-sizing: border-box;
        }
    </style>

    <script>
        const reservationDateInput = document.getElementById('reservation_date');
        const eventDateInput = document.getElementById('event_date');
        const returnDateInput = document.getElementById('return_date');

        function updateReservationDateLimits() {
            const today = @json(now()->toDateString());
            const reservationDate = reservationDateInput.value || today;
            const eventDate = eventDateInput.value || reservationDate;

            eventDateInput.min = reservationDate;
            returnDateInput.min = eventDate;
        }

        reservationDateInput.addEventListener('change', updateReservationDateLimits);
        eventDateInput.addEventListener('change', updateReservationDateLimits);
    </script>
</x-app-layout>
