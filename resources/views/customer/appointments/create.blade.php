<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Book Appointment') }}
        </h2>
    </x-slot>

    <div class="mx-auto max-w-5xl px-4 py-8 sm:px-6 lg:px-8">
        <div class="mb-6">
            <a
                href="{{ route('customer.appointments.index') }}"
                class="inline-flex items-center gap-1.5 text-sm font-semibold text-gray-500 transition-colors hover:text-pink-600"
            >
                <x-lucide-arrow-left width="16" height="16" aria-hidden="true" />
                Back to My Appointments
            </a>
        </div>

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
                <div class="flex flex-col border-b border-gray-100 p-6 sm:p-8 md:col-span-2 md:border-b-0 md:border-r" style="background-color: var(--neo-field);">
                    <div class="mb-6">
                        <p class="mb-2 text-xs font-bold uppercase tracking-widest text-yellow-600">Booking For</p>
                        <h1 class="mb-1 text-2xl font-bold text-gray-900" style="font-family: 'Playfair Display', serif;">
                            {{ $reservation->rentalItem?->name ?? 'Rental Item' }}
                        </h1>
                        <p class="text-sm text-gray-500">Reservation #{{ $reservation->id }}</p>
                    </div>

                    <div class="mt-auto space-y-4">
                        <div class="flex items-center justify-between gap-4 border-b border-[var(--event-line)] pb-3">
                            <span class="text-xs font-bold uppercase tracking-wider text-gray-500">Quantity</span>
                            <span class="font-semibold text-gray-900">{{ $reservation->quantity }}</span>
                        </div>
                        <div class="flex items-center justify-between gap-4 border-b border-[var(--event-line)] pb-3">
                            <span class="text-xs font-bold uppercase tracking-wider text-gray-500">Event Date</span>
                            <span class="font-semibold text-gray-900">{{ $reservation->event_date?->format('M d, Y') ?? '—' }}</span>
                        </div>
                        <div class="flex items-center justify-between gap-4 border-b border-[var(--event-line)] pb-3">
                            <span class="text-xs font-bold uppercase tracking-wider text-gray-500">Status</span>
                            <span class="inline-flex items-center rounded-full border border-green-200 bg-green-100 px-2.5 py-1 text-xs font-bold text-green-700">
                                {{ $reservation->status }}
                            </span>
                        </div>
                    </div>

                    <div class="mt-8 rounded-xl border border-gray-200 bg-white p-4 shadow-sm">
                        <div class="flex gap-3">
                            <x-lucide-info class="shrink-0 text-pink-500" width="20" height="20" aria-hidden="true" />
                            <p class="text-xs leading-relaxed text-gray-600">
                                Fittings typically take 30–45 minutes. Please arrive 5 minutes early.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="p-6 sm:p-8 md:col-span-3 md:p-10">
                    <form
                        action="{{ route('customer.appointments.store', $reservation->id) }}"
                        method="POST"
                        class="appointment-form flex h-full w-full min-w-0 flex-col"
                    >
                        @csrf

                        <div class="w-full flex-1 space-y-6">
                            <h2 class="mb-4 border-b border-gray-100 pb-3 text-lg font-bold text-gray-900" style="font-family: 'Playfair Display', serif;">Schedule Details</h2>

                            <div>
                                <label for="appointment_date" class="mb-2 block text-xs font-bold uppercase tracking-wider text-gray-600">Fitting / Appointment Date</label>
                                <input
                                    type="date"
                                    id="appointment_date"
                                    name="appointment_date"
                                    value="{{ old('appointment_date') }}"
                                    min="{{ now()->toDateString() }}"
                                    required
                                    class="w-full rounded-xl border border-gray-300 bg-gray-50/50 px-4 py-3 font-medium text-gray-900 transition-colors focus:border-pink-500 focus:outline-none focus:ring-2 focus:ring-pink-200"
                                >
                            </div>

                            <div>
                                <label for="appointment_time" class="mb-2 block text-xs font-bold uppercase tracking-wider text-gray-600">Fitting / Appointment Time</label>
                                <input
                                    type="time"
                                    id="appointment_time"
                                    name="appointment_time"
                                    value="{{ old('appointment_time') }}"
                                    required
                                    class="w-full rounded-xl border border-gray-300 bg-gray-50/50 px-4 py-3 font-medium text-gray-900 transition-colors focus:border-pink-500 focus:outline-none focus:ring-2 focus:ring-pink-200"
                                >
                            </div>

                            <div>
                                <label for="notes" class="mb-2 block text-xs font-bold uppercase tracking-wider text-gray-600">Notes <span class="font-normal normal-case text-gray-400">(Optional)</span></label>
                                <textarea
                                    id="notes"
                                    name="notes"
                                    rows="4"
                                    placeholder="Add any fitting or appointment notes..."
                                    class="w-full resize-y rounded-xl border border-gray-300 bg-gray-50/50 px-4 py-3 text-sm text-gray-900 transition-colors focus:border-pink-500 focus:outline-none focus:ring-2 focus:ring-pink-200"
                                >{{ old('notes') }}</textarea>
                            </div>
                        </div>

                        <div class="mt-8 flex flex-col-reverse justify-end gap-3 border-t border-gray-100 pt-6 sm:flex-row">
                            <a
                                href="{{ route('customer.appointments.index') }}"
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
                                Submit Appointment Request
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </section>
    </div>

    <style>
        .appointment-form {
            width: 100% !important;
            max-width: none !important;
        }

        .appointment-form input,
        .appointment-form textarea {
            display: block;
            width: 100% !important;
            max-width: none !important;
            box-sizing: border-box;
        }
    </style>
</x-app-layout>
