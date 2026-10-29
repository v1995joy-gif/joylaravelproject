<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Packages') }}
        </h2>
    </x-slot>

    <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
        <div class="mb-6 flex flex-col items-start justify-between gap-4 sm:flex-row sm:items-center">
            <div>
                <p class="mb-1 text-xs font-bold uppercase tracking-widest text-yellow-600">Inventory Setup</p>
            </div>

            <a
                href="{{ route('admin.packages.create') }}"
                class="inline-flex items-center gap-2 rounded-lg px-5 py-2.5 font-medium text-white shadow-sm transition-all hover:shadow-md"
                style="background: linear-gradient(135deg, #f97316 0%, #ec4899 100%);"
            >
                <x-lucide-plus width="18" height="18" aria-hidden="true" />
                Add Package
            </a>
        </div>

        @if (session('success'))
            <div class="mb-4 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800" role="status">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-4 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800" role="alert">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <div class="overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm">
            <div class="border-b border-[var(--event-line)] px-6 py-5" style="background-color: var(--neo-field);">
                <h3 class="text-sm font-bold uppercase tracking-wider text-gray-700">Current Packages</h3>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full min-w-[900px] border-collapse text-left">
                    <thead>
                        <tr class="border-b border-[var(--event-line)]" style="background-color: rgba(232, 201, 138, 0.12);">
                            <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider" style="background-color: rgba(232, 201, 138, 0.12) !important; color: var(--event-muted) !important; border-bottom: 1px solid var(--event-line) !important; box-shadow: none !important;">Image</th>
                            <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider" style="background-color: rgba(232, 201, 138, 0.12) !important; color: var(--event-muted) !important; border-bottom: 1px solid var(--event-line) !important; box-shadow: none !important;">Package Name</th>
                            <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider" style="background-color: rgba(232, 201, 138, 0.12) !important; color: var(--event-muted) !important; border-bottom: 1px solid var(--event-line) !important; box-shadow: none !important;">Event Type</th>
                            <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider" style="background-color: rgba(232, 201, 138, 0.12) !important; color: var(--event-muted) !important; border-bottom: 1px solid var(--event-line) !important; box-shadow: none !important;">Price</th>
                            <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider" style="background-color: rgba(232, 201, 138, 0.12) !important; color: var(--event-muted) !important; border-bottom: 1px solid var(--event-line) !important; box-shadow: none !important;">Items</th>
                            <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider" style="background-color: rgba(232, 201, 138, 0.12) !important; color: var(--event-muted) !important; border-bottom: 1px solid var(--event-line) !important; box-shadow: none !important;">Status</th>
                            <th class="px-6 py-4 text-right text-xs font-bold uppercase tracking-wider" style="background-color: rgba(232, 201, 138, 0.12) !important; color: var(--event-muted) !important; border-bottom: 1px solid var(--event-line) !important; box-shadow: none !important;">Action</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-[var(--event-line)]">
                        @forelse ($packages as $package)
                            <tr class="transition-colors hover:bg-black/5">
                                <td class="px-6 py-4">
                                    @if ($package->image)
                                        <img
                                            src="{{ asset('storage/' . $package->image) }}"
                                            alt="{{ $package->name }}"
                                            class="h-14 min-w-[56px] w-14 rounded-xl border border-[var(--event-line)] object-cover shadow-sm"
                                        >
                                    @else
                                        <span class="flex h-14 w-14 min-w-[56px] items-center justify-center rounded-xl bg-pink-50 text-pink-300">
                                            <x-lucide-package width="24" height="24" aria-hidden="true" />
                                        </span>
                                    @endif
                                </td>

                                <td class="px-6 py-4">
                                    <strong class="text-sm font-semibold" style="color: var(--event-ink);">{{ $package->name }}</strong>
                                </td>

                                <td class="px-6 py-4 text-sm capitalize" style="color: var(--event-ink);">
                                    {{ $package->event_type }}
                                </td>

                                <td class="px-6 py-4 text-sm font-medium" style="color: var(--event-ink);">
                                    ₱{{ number_format($package->price, 2) }}
                                </td>

                                <td class="px-6 py-4">
                                    <a
                                        href="{{ route('admin.packages.show', $package->id) }}"
                                        class="inline-flex items-center rounded-lg px-3 py-1.5 text-xs font-semibold text-white shadow-sm transition-all hover:shadow-md"
                                        style="background: linear-gradient(135deg, #c9a35f 0%, #ec4899 100%);"
                                    >
                                        View {{ $package->packageItems->count() }} item(s)
                                    </a>
                                </td>

                                <td class="px-6 py-4">
                                    <span class="status-badge status-badge-{{ strtolower($package->status) }} inline-flex items-center rounded-full border px-2.5 py-1 text-xs font-bold">
                                        {{ $package->status === 'Active' ? 'Available for booking' : 'Not available for booking' }}
                                    </span>
                                </td>

                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <a
                                            href="{{ route('admin.packages.edit', $package->id) }}"
                                            class="inline-flex items-center gap-1.5 rounded-lg bg-gray-800 px-3 py-1.5 text-xs font-semibold text-white shadow-sm transition-colors hover:bg-gray-900"
                                        >
                                            <x-lucide-pencil width="14" height="14" aria-hidden="true" />
                                            Edit
                                        </a>

                                        <form
                                            action="{{ route('admin.packages.destroy', $package->id) }}"
                                            method="POST"
                                            onsubmit="return confirm('Are you sure you want to delete this package?');"
                                            class="inline"
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
                                <td colspan="7" class="p-12 text-center">
                                    <div class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-gray-50 text-gray-400">
                                        <x-lucide-package width="32" height="32" aria-hidden="true" />
                                    </div>
                                    <h3 class="mb-1 text-lg font-bold text-gray-900">No packages yet</h3>
                                    <p class="mb-4 text-sm text-gray-500">Create your first event package to start offering bundles.</p>
                                    <a href="{{ route('admin.packages.create') }}" class="text-sm font-semibold text-pink-600 hover:text-pink-700">
                                        + Add your first package
                                    </a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
