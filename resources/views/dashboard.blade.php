<x-app-layout>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js"></script>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight dashboard-title">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="dashboard-wrap py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <section class="mb-8 grid grid-cols-1 items-start gap-6 lg:grid-cols-3">
                <div
                    class="dashboard-welcome-banner relative flex min-h-[320px] flex-col items-start justify-center overflow-hidden rounded-3xl border p-8 md:p-10 lg:col-span-2"
                >
                    <div class="dashboard-welcome-glow absolute -right-16 -top-16 h-64 w-64 rounded-full" aria-hidden="true"></div>
                    <div class="relative z-10 flex flex-col items-start justify-between gap-6 md:flex-row md:items-center">
                        <div class="max-w-xl">
                            <p class="dashboard-welcome-kicker mb-3 text-xs font-bold uppercase tracking-widest">Event Apparel</p>
                            <h1 class="mb-3 text-3xl font-bold md:text-4xl" style="font-family: 'Playfair Display', serif;">
                                Welcome back, {{ auth()->user()->name }}.
                            </h1>
                        </div>
                        <div class="hidden flex-shrink-0 rounded-2xl p-3 text-white shadow-lg md:flex" style="background: linear-gradient(135deg, #f97316 0%, #ec4899 100%);">
                            <x-application-logo class="h-12 w-12 rounded-full border-2 border-white" />
                        </div>
                    </div>
                </div>

                <div class="flex flex-col rounded-3xl border border-gray-100 bg-white p-4 shadow-sm" style="min-height: 320px;">
                    <div class="mb-2 flex items-center justify-between">
                        <div>
                            <h2 class="text-sm font-bold text-gray-900" style="font-family: 'Playfair Display', serif;">Availability</h2>
                            <p class="text-[10px] uppercase tracking-widest text-gray-500">Plan your visit</p>
                        </div>
                        <span class="flex items-center gap-1 rounded-md bg-red-50 px-2 py-1 text-[10px] font-bold text-red-500">
                            <span class="h-2 w-2 rounded-full bg-red-500" aria-hidden="true"></span>
                            Closed
                        </span>
                    </div>
                    <div id="miniCustomerCalendar" class="h-[260px]"></div>
                </div>
            </section>

            <section class="dashboard-grid" aria-label="Customer shortcuts">
                <a class="dashboard-card dashboard-card-accent" href="{{ route('customer.item-categories.index') }}">
                    <span class="dashboard-card-icon"><x-lucide-tags width="22" height="22" /></span>
                    <span class="dashboard-card-label">Start here</span>
                    <strong>Browse item categories</strong>
                </a>

                <a class="dashboard-card" href="{{ route('customer.reservations.index') }}">
                    <span class="dashboard-card-icon"><x-lucide-calendar-check width="22" height="22" /></span>
                    <span class="dashboard-card-label">Your orders</span>
                    <strong>My reservations</strong>
                </a>

                <a class="dashboard-card" href="{{ route('customer.appointments.index') }}">
                    <span class="dashboard-card-icon"><x-lucide-calendar-days width="22" height="22" /></span>
                    <span class="dashboard-card-label">Need a fitting?</span>
                    <strong>Request an appointment</strong>
                </a>

                <a class="dashboard-card dashboard-card-wide" href="{{ route('customer.payments.index') }}">
                    <span class="dashboard-card-icon"><x-lucide-wallet width="22" height="22" /></span>
                    <span class="dashboard-card-label">Stay on track</span>
                    <strong>Review payments</strong>
                </a>
            </section>
        </div>
    </div>

    <style>
        #miniCustomerCalendar .fc .fc-toolbar {
            margin-bottom: 0.5rem;
        }

        #miniCustomerCalendar .fc .fc-toolbar-title {
            font-size: 1rem;
            font-weight: 700;
            color: var(--event-ink);
        }

        #miniCustomerCalendar .fc .fc-button {
            padding: 0.2rem 0.5rem;
            font-size: 0.75rem;
            color: #374151;
            background: #f3f4f6;
            border: 0;
            border-radius: 6px;
            box-shadow: none;
        }

        #miniCustomerCalendar .fc .fc-button:hover {
            background: #e5e7eb;
        }

        #miniCustomerCalendar .fc .fc-col-header-cell-cushion {
            color: #9ca3af;
            font-size: 0.7rem;
            font-weight: 600;
            text-transform: uppercase;
            text-decoration: none;
        }

        #miniCustomerCalendar .fc .fc-daygrid-day-number {
            color: #4b5563;
            font-size: 0.75rem;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const calendarElement = document.getElementById('miniCustomerCalendar');

            if (!calendarElement) {
                throw new Error('Mini availability calendar element was not found.');
            }

            if (typeof FullCalendar === 'undefined') {
                calendarElement.innerHTML = '<p role="alert" style="color: var(--event-danger);">The availability calendar could not load. Check your internet connection and reload the page.</p>';
                return;
            }

            const calendar = new FullCalendar.Calendar(calendarElement, {
                initialView: 'dayGridMonth',
                headerToolbar: {
                    left: 'prev',
                    center: 'title',
                    right: 'next',
                },
                height: 'auto',
                contentHeight: 'auto',
                handleWindowResize: true,
                fixedWeekCount: false,
                dayMaxEvents: 1,
                selectable: false,
                editable: false,
                height: 260,
                contentHeight: 230,
                dayHeaderFormat: { weekday: 'narrow' },
                titleFormat: { month: 'short', year: 'numeric' },
                events: @json($closures),
            });

            calendar.render();
        });
    </script>
</x-app-layout>
