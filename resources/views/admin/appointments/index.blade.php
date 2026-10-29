<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js"></script>
    <title>Appointment Management</title>

    <style>
        body {
            font-family: 'DM Sans', 'Segoe UI', sans-serif;
            margin: 40px;
            background: var(--neo-field);
        }

        h1 {
            margin-bottom: 20px;
        }

        .container {
            background: var(--neo-bg);
            padding: 25px;
            border-radius: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 12px;
            border-bottom: 1px solid var(--event-line);
            text-align: left;
        }

        th {
            background: var(--neo-field);
        }

        .empty {
            padding: 20px;
            text-align: center;
        }

        .schedule-form {
            display: flex;
            gap: 6px;
            align-items: center;
        }

        .schedule-form input {
            padding: 6px;
        }

        .schedule-form button {
            padding: 6px 10px;
            cursor: pointer;
        }

        .appointment-calendar {
            background: var(--neo-panel);
            border: 1px solid var(--event-line);
            border-radius: 16px;
            margin-bottom: 24px;
            padding: 24px;
        }

        .appointment-calendar .fc .fc-button-primary {
            background: var(--event-gold);
            border-color: var(--event-gold);
        }

        .appointment-calendar .fc .fc-button-primary:hover,
        .appointment-calendar .fc .fc-button-primary:focus {
            background: var(--event-gold-dark);
            border-color: var(--event-gold-dark);
        }

        .appointment-calendar .fc .fc-toolbar-title {
            color: var(--event-ink);
            font-family: var(--event-display);
        }

        .appointment-calendar .fc .fc-daygrid-day-number,
        .appointment-calendar .fc .fc-col-header-cell-cushion {
            color: var(--event-ink);
            text-decoration: none;
        }

        .appointment-calendar .fc .fc-day-past {
            background: rgba(121, 91, 104, 0.08);
        }

        .appointment-calendar .fc .fc-day-past .fc-daygrid-day-number {
            color: var(--event-faint);
            cursor: not-allowed;
        }

        .closure-modal-backdrop {
            background: rgba(36, 19, 33, 0.55);
        }
    </style>
</head>

<body class="admin-appointments-page" style="margin-left: 300px;">

    @include('layouts.navigation')

    <div
        class="container appointment-page"
        x-data="{ view: 'calendar', isClosureModalOpen: false, selectedDate: '', closureReason: '', closureId: '' }"
        @closure-date-selected.window="selectedDate = $event.detail.date; closureReason = $event.detail.reason || ''; closureId = $event.detail.id || ''; isClosureModalOpen = true"
    >
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
            <div>
                <h1>Appointment Management</h1>
                <p class="text-sm" style="color: var(--event-muted);">Manage fittings, schedules, and boutique closures.</p>
            </div>

            <div class="flex p-1 rounded-lg shadow-inner" style="background: var(--neo-field);">
                <button
                    type="button"
                    @click="view = 'calendar'"
                    :class="view === 'calendar' ? 'bg-white shadow-sm' : ''"
                    class="px-4 py-2 text-sm font-semibold rounded-md transition-all"
                    style="color: var(--event-ink);"
                >
                    Calendar
                </button>
                <button
                    type="button"
                    @click="view = 'table'"
                    :class="view === 'table' ? 'bg-white shadow-sm' : ''"
                    class="px-4 py-2 text-sm font-semibold rounded-md transition-all"
                    style="color: var(--event-ink);"
                >
                    Table View
                </button>
            </div>
        </div>

        @if (session('success'))
            <p class="appointment-feedback appointment-feedback-success" role="status">
                {{ session('success') }}
            </p>
        @endif

        @if ($errors->any())
            <div class="appointment-feedback appointment-feedback-error" role="alert">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <section x-show="view === 'calendar'" class="appointment-calendar">
            <p class="mb-4 text-sm" style="color: var(--event-muted);">
                Use the calendar arrows to browse months and view appointments booked in advance. Click a future or current open date to mark the boutique closed; click a closed date to edit it or reopen the boutique.
            </p>
            <p class="mb-4 text-xs" style="color: var(--event-faint);">Past dates are disabled. Closed dates appear in red.</p>
            <div id="adminCalendar"></div>
        </section>

        <div class="appointment-table-wrap" x-show="view === 'table'" x-cloak>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Customer</th>
                    <th>Rental Item</th>
                    <th>Appointment Date</th>
                    <th>Appointment Time</th>
                    <th>Status</th>
                    <th>Schedule</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($appointments as $appointment)
                    <tr>
                        <td>{{ $appointment->id }}</td>

                        <td>
                            {{ $appointment->customer?->name ?? "—" }}
                        </td>

                        <td>
                            {{ $appointment->reservation?->rentalItem?->name ?? "—" }}
                        </td>

                        <td>
                            {{ $appointment->appointment_date->format('M d, Y') }}
                        </td>

                        <td>
                            {{ \Illuminate\Support\Carbon::parse($appointment->appointment_time)->format('h:i A') }}
                        </td>

                        <td>
                            <span class="status-badge status-badge-{{ strtolower($appointment->status) }}">
                                {{ match ($appointment->status) {
                                    'Pending' => 'Awaiting confirmation',
                                    'Confirmed' => 'Confirmed appointment',
                                    'Completed' => 'Completed',
                                    'Cancelled' => 'Cancelled',
                                    'Declined' => 'Declined',
                                    default => $appointment->status,
                                } }}
                            </span>
                        </td>

                        <td>
                            @if (in_array($appointment->status, ['Pending', 'Confirmed'], true))
                                <form
                                    action="{{ route('admin.appointments.update-schedule', $appointment->id) }}"
                                    method="POST"
                                    class="schedule-form"
                                >
                                    @csrf
                                    @method('PATCH')

                                    <input
                                        type="date"
                                        name="appointment_date"
                                        value="{{ $appointment->appointment_date->format('Y-m-d') }}"
                                        required
                                        aria-label="Appointment date"
                                    >

                                    <input
                                        type="time"
                                        name="appointment_time"
                                        value="{{ \Illuminate\Support\Carbon::parse($appointment->appointment_time)->format('H:i') }}"
                                        required
                                        aria-label="Appointment time"
                                    >

                                    <button type="submit">
                                        Save schedule
                                    </button>
                                </form>
                            @else
                                <span>Schedule finalized</span>
                            @endif
                        </td>

                        <td>
                            <div class="appointment-actions">
                                @if ($appointment->status === 'Pending')
                                    <form action="{{ route('admin.appointments.update-status', $appointment->id) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <button class="appointment-action-button appointment-action-confirm" type="submit" name="status" value="Confirmed">
                                            Confirm
                                        </button>
                                    </form>

                                    <form action="{{ route('admin.appointments.update-status', $appointment->id) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <button class="appointment-action-button appointment-action-decline" type="submit" name="status" value="Declined">
                                            Decline
                                        </button>
                                    </form>
                                @elseif ($appointment->status === 'Confirmed')
                                    <form action="{{ route('admin.appointments.update-status', $appointment->id) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <button class="appointment-action-button appointment-action-confirm" type="submit" name="status" value="Completed">
                                            Mark complete
                                        </button>
                                    </form>

                                    <form action="{{ route('admin.appointments.update-status', $appointment->id) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <button class="appointment-action-button appointment-action-decline" type="submit" name="status" value="Cancelled">
                                            Cancel
                                        </button>
                                    </form>
                                @else
                                    <span>No further actions</span>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="empty">
                            No appointments found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        </div>

        <div
            x-show="isClosureModalOpen"
            x-cloak
            class="fixed inset-0 z-[10000] flex items-center justify-center overflow-y-auto"
            @keydown.escape.window="isClosureModalOpen = false"
        >
            <div class="closure-modal-backdrop fixed inset-0" @click="isClosureModalOpen = false"></div>
            <div class="relative z-10 mx-4 w-full max-w-md rounded-2xl p-6 shadow-xl" style="background: var(--neo-panel);">
                <div class="mb-4 flex items-center gap-3">
                    <div class="flex h-10 w-10 items-center justify-center rounded-full bg-red-100 text-red-600">
                        <x-lucide-calendar-x width="20" height="20" />
                    </div>
                    <div>
                        <h2 class="text-lg font-bold" style="color: var(--event-ink);" x-text="closureId ? 'Edit Boutique Closure' : 'Mark as Closed'"></h2>
                        <p class="text-sm" style="color: var(--event-muted);" x-text="'Date: ' + selectedDate"></p>
                    </div>
                </div>

                <form
                    id="closure-edit-form"
                    method="POST"
                    x-bind:action="closureId ? '{{ url('/admin/appointments/closure') }}/' + closureId : '{{ route('admin.appointments.store-closure') }}'"
                >
                    @csrf
                    <template x-if="closureId">
                        <input type="hidden" name="_method" value="PATCH">
                    </template>
                    <div class="mb-4">
                        <label for="closure-date" class="mb-2 block text-sm font-bold" style="color: var(--event-ink);">
                            Closure Date
                        </label>
                        <input
                            id="closure-date"
                            type="date"
                            name="closure_date"
                            x-model="selectedDate"
                            min="{{ now()->toDateString() }}"
                            required
                            class="w-full rounded-lg border px-4 py-2.5"
                            style="background: var(--neo-field); border-color: var(--event-line); color: var(--event-ink);"
                        >
                    </div>
                    <div class="mb-4">
                        <label for="closure-reason" class="mb-2 block text-sm font-bold" style="color: var(--event-ink);">
                            Reason for Closure (Optional)
                        </label>
                        <input
                            id="closure-reason"
                            type="text"
                            name="reason"
                            x-model="closureReason"
                            maxlength="255"
                            placeholder="e.g., Public Holiday, Maintenance"
                            class="w-full rounded-lg border px-4 py-2.5"
                            style="background: var(--neo-field); border-color: var(--event-line); color: var(--event-ink);"
                        >
                    </div>
                </form>

                <div class="mt-6 flex justify-end gap-3">
                    <form
                        x-show="closureId"
                        x-bind:action="'{{ url('/admin/appointments/closure') }}/' + closureId"
                        method="POST"
                        class="mr-auto"
                        onsubmit="return confirm('Reopen the boutique for this date?');"
                    >
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="rounded-lg border border-red-300 px-4 py-2 font-medium text-red-700 hover:bg-red-50">
                            Reopen Date
                        </button>
                    </form>
                    <button
                        type="button"
                        @click="isClosureModalOpen = false"
                        class="rounded-lg border px-4 py-2 font-medium"
                        style="border-color: var(--event-line); color: var(--event-ink);"
                    >
                        Cancel
                    </button>
                    <button
                        type="submit"
                        form="closure-edit-form"
                        class="rounded-lg bg-red-600 px-4 py-2 font-medium text-white hover:bg-red-700"
                        x-text="closureId ? 'Save Changes' : 'Mark as Closed'"
                    ></button>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const calendarElement = document.getElementById('adminCalendar');

            if (!calendarElement) {
                throw new Error('Appointment calendar element was not found.');
            }

            if (typeof FullCalendar === 'undefined') {
                calendarElement.innerHTML = '<p role="alert" style="color: var(--event-danger);">The calendar could not load. Check your internet connection and reload; you can still manage appointments in Table View.</p>';
                return;
            }

            const calendar = new FullCalendar.Calendar(calendarElement, {
                initialView: 'dayGridMonth',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay',
                },
                events: @json($calendarEvents, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT),
                dateClick: function (info) {
                    const today = new Date();
                    today.setHours(0, 0, 0, 0);
                    const clickedDate = new Date(info.date);
                    clickedDate.setHours(0, 0, 0, 0);

                    if (clickedDate < today) {
                        return;
                    }

                    const closure = calendar.getEvents().find(function (event) {
                        return event.extendedProps.type === 'closure' && event.startStr === info.dateStr;
                    });

                    window.dispatchEvent(new CustomEvent('closure-date-selected', {
                        detail: {
                            date: info.dateStr,
                            id: closure ? closure.extendedProps.closureId : '',
                            reason: closure ? closure.extendedProps.reason : '',
                        },
                    }));
                },
                eventDidMount: function (info) {
                    if (info.event.extendedProps.type !== 'appointment') {
                        return;
                    }

                    const colors = info.event.extendedProps.status === 'Confirmed'
                        ? { background: '#dcfce7', border: '#bbf7d0', text: '#166534' }
                        : { background: '#fef3c7', border: '#fde68a', text: '#92400e' };

                    info.el.style.backgroundColor = colors.background;
                    info.el.style.borderColor = colors.border;
                    info.el.style.color = colors.text;
                },
            });

            calendar.render();
        });
    </script>

</body>
</html>