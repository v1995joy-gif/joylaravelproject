<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Packages') }}
        </h2>
    </x-slot>

    <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
        <div class="mb-8">
            <p class="mb-1 text-xs font-bold uppercase tracking-widest text-yellow-600">Catalogue</p>
            <h1 class="text-2xl font-bold text-gray-900" style="font-family: 'Playfair Display', serif;">Available Packages</h1>
        </div>

        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4">
            @forelse ($packages as $package)
                <a
                    href="{{ route('customer.packages.show', $package->id) }}"
                    class="group flex flex-col overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm transition-all duration-300 hover:-translate-y-1 hover:shadow-xl"
                >
                    <div class="relative aspect-[4/3] w-full overflow-hidden bg-gray-50">
                        @if ($package->image)
                            <img
                                src="{{ asset('storage/' . $package->image) }}"
                                alt="{{ $package->name }}"
                                class="h-full w-full object-cover object-center transition-transform duration-500 group-hover:scale-105"
                            >
                        @else
                            <div class="flex h-full w-full items-center justify-center text-pink-200">
                                <x-lucide-package width="48" height="48" aria-hidden="true" />
                            </div>
                        @endif

                        <div class="absolute inset-0 bg-black/0 transition-colors duration-300 group-hover:bg-black/5" aria-hidden="true"></div>
                        <div class="absolute left-3 top-3">
                            <span class="inline-flex items-center rounded-md bg-white/90 px-2.5 py-1 text-[10px] font-bold uppercase tracking-wider text-gray-800 shadow-sm backdrop-blur-sm">
                                {{ $package->event_type }}
                            </span>
                        </div>
                    </div>

                    <div class="flex flex-1 flex-col p-5">
                        <h3 class="mb-1 line-clamp-1 text-lg font-bold text-gray-900" style="font-family: 'Playfair Display', serif;">
                            {{ $package->name }}
                        </h3>
                        <p class="mb-4 line-clamp-2 flex-1 text-xs text-gray-500">
                            {{ $package->description ?: 'A ready-to-book rental package for your event.' }}
                        </p>

                        <div class="mt-auto flex items-end justify-between border-t border-gray-100 pt-3">
                            <div>
                                <p class="mb-0.5 text-[10px] font-bold uppercase tracking-wider text-gray-400">Price</p>
                                <strong class="text-base font-bold text-gray-900">₱{{ number_format($package->price, 2) }}</strong>
                            </div>
                            <div class="text-right">
                                <span class="rounded-md bg-pink-50 px-2 py-1 text-xs font-semibold text-pink-600">
                                    {{ $package->packageItems->count() }} items
                                </span>
                            </div>
                        </div>
                    </div>
                </a>
            @empty
                <div class="col-span-full flex flex-col items-center justify-center rounded-2xl border border-gray-100 bg-white p-12 text-center">
                    <div class="mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-gray-50 text-gray-400">
                        <x-lucide-package-open width="32" height="32" aria-hidden="true" />
                    </div>
                    <h3 class="mb-1 text-lg font-bold text-gray-900">No packages available</h3>
                    <p class="text-sm text-gray-500">Check back later or contact the admin for custom packages.</p>
                </div>
            @endforelse
        </div>
    </div>
</x-app-layout>
