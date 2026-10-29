<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Customers') }}
        </h2>
    </x-slot>

    <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
        <div class="mb-6 flex flex-col items-start justify-between gap-4 sm:flex-row sm:items-center">
            <div>
                <p class="mb-1 text-xs font-bold uppercase tracking-widest text-yellow-600">Management</p>
                <h1 class="text-2xl font-bold text-gray-900" style="font-family: 'Playfair Display', serif;">Customer Management</h1>
            </div>
            <span class="rounded-full border border-yellow-100 bg-yellow-50 px-4 py-2 text-sm font-semibold text-yellow-700">
                {{ $customers->total() }} customers
            </span>
        </div>

        @if (session('success'))
            <div class="mb-4 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800" role="status">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="mb-4 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800" role="alert">
                {{ session('error') }}
            </div>
        @endif

        <div class="overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm">
            <div class="border-b border-[var(--event-line)] px-6 py-5" style="background-color: var(--neo-field);">
                <h3 class="text-sm font-bold uppercase tracking-wider text-gray-700">All Registered Customers</h3>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full min-w-[800px] border-collapse text-left">
                    <thead>
                        <tr class="border-b border-[var(--event-line)]" style="background-color: rgba(232, 201, 138, 0.12);">
                            <th class="w-16 px-6 py-4 text-left text-xs font-bold uppercase tracking-wider" style="background-color: rgba(232, 201, 138, 0.12) !important; color: var(--event-muted) !important; border-bottom: 1px solid var(--event-line) !important; box-shadow: none !important;">ID</th>
                            <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider" style="background-color: rgba(232, 201, 138, 0.12) !important; color: var(--event-muted) !important; border-bottom: 1px solid var(--event-line) !important; box-shadow: none !important;">Name</th>
                            <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider" style="background-color: rgba(232, 201, 138, 0.12) !important; color: var(--event-muted) !important; border-bottom: 1px solid var(--event-line) !important; box-shadow: none !important;">Email</th>
                            <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider" style="background-color: rgba(232, 201, 138, 0.12) !important; color: var(--event-muted) !important; border-bottom: 1px solid var(--event-line) !important; box-shadow: none !important;">Role</th>
                            <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider" style="background-color: rgba(232, 201, 138, 0.12) !important; color: var(--event-muted) !important; border-bottom: 1px solid var(--event-line) !important; box-shadow: none !important;">Status</th>
                            <th class="w-48 px-6 py-4 text-right text-xs font-bold uppercase tracking-wider" style="background-color: rgba(232, 201, 138, 0.12) !important; color: var(--event-muted) !important; border-bottom: 1px solid var(--event-line) !important; box-shadow: none !important;">Actions</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-[var(--event-line)]">
                        @forelse ($customers as $customer)
                            <tr class="transition-colors hover:bg-black/5">
                                <td class="px-6 py-4 text-sm font-medium" style="color: var(--event-ink);">
                                    {{ $customer->id }}
                                </td>
                                <td class="px-6 py-4">
                                    <strong class="text-sm font-semibold" style="color: var(--event-ink);">{{ $customer->name }}</strong>
                                </td>
                                <td class="px-6 py-4 text-sm" style="color: var(--event-ink);">
                                    {{ $customer->email }}
                                </td>
                                <td class="px-6 py-4 text-sm capitalize" style="color: var(--event-ink);">
                                    {{ $customer->role ?? 'Customer' }}
                                </td>
                                <td class="px-6 py-4">
                                    @if ($customer->blocked_at)
                                        <span class="inline-flex items-center rounded-full border border-red-200 bg-red-100 px-2.5 py-1 text-xs font-bold text-red-700">
                                            Blocked
                                        </span>
                                    @else
                                        <span class="inline-flex items-center rounded-full border border-green-200 bg-green-100 px-2.5 py-1 text-xs font-bold text-green-700">
                                            Active
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        @if ($customer->blocked_at)
                                            <form action="{{ route('admin.customers.unblock', $customer->id) }}" method="POST" class="inline">
                                                @csrf
                                                @method('PATCH')
                                                <button
                                                    type="submit"
                                                    class="inline-flex items-center gap-1.5 rounded-lg px-3 py-1.5 text-xs font-semibold text-white shadow-sm transition-all hover:shadow-md"
                                                    style="background: linear-gradient(135deg, #4b8b64 0%, #2f6b45 100%);"
                                                >
                                                    <x-lucide-check-circle width="14" height="14" aria-hidden="true" />
                                                    Unblock
                                                </button>
                                            </form>
                                        @else
                                            <form
                                                action="{{ route('admin.customers.block', $customer->id) }}"
                                                method="POST"
                                                class="inline"
                                                onsubmit="return confirm('Block this customer from logging in?');"
                                            >
                                                @csrf
                                                @method('PATCH')
                                                <button
                                                    type="submit"
                                                    class="inline-flex items-center gap-1.5 rounded-lg px-3 py-1.5 text-xs font-semibold text-white shadow-sm transition-all hover:shadow-md"
                                                    style="background: linear-gradient(135deg, #c9a35f 0%, #bb8c2c 100%);"
                                                >
                                                    <x-lucide-ban width="14" height="14" aria-hidden="true" />
                                                    Block
                                                </button>
                                            </form>
                                        @endif

                                        <form
                                            action="{{ route('admin.customers.destroy', $customer->id) }}"
                                            method="POST"
                                            class="inline"
                                            onsubmit="return confirm('Permanently delete this customer and all related reservations, payments, appointments, and requests? This cannot be undone.');"
                                        >
                                            @csrf
                                            @method('DELETE')
                                            <button
                                                type="submit"
                                                class="inline-flex items-center gap-1.5 rounded-lg px-3 py-1.5 text-xs font-semibold text-white shadow-sm transition-all hover:shadow-md"
                                                style="background: linear-gradient(135deg, #ef4444 0%, #b91c1c 100%);"
                                            >
                                                <x-lucide-trash-2 width="14" height="14" aria-hidden="true" />
                                                Delete
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="p-12 text-center">
                                    <div class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-gray-50 text-gray-400">
                                        <x-lucide-users width="32" height="32" aria-hidden="true" />
                                    </div>
                                    <h3 class="mb-1 text-lg font-bold text-gray-900">No customers found</h3>
                                    <p class="text-sm text-gray-500">When customers register, they will appear here.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if ($customers->hasPages())
            <div class="mt-6">
                {{ $customers->links() }}
            </div>
        @endif
    </div>
</x-app-layout>
