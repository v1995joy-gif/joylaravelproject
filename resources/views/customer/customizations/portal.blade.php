<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Customization') }}
        </h2>
    </x-slot>

    <div class="mx-auto max-w-5xl px-4 py-10 sm:px-6 lg:px-8">
        <div class="mb-6">
            <a
                href="{{ route('dashboard') }}"
                class="inline-flex items-center gap-1.5 text-sm font-semibold text-gray-500 transition-colors hover:text-pink-600"
            >
                <x-lucide-arrow-left width="16" height="16" aria-hidden="true" />
                Back to Dashboard
            </a>
        </div>

        <div class="mb-10 text-center">
            <p class="mb-2 text-xs font-bold uppercase tracking-widest text-yellow-600">Bespoke Experience</p>
            <h1 class="mb-3 text-3xl font-bold text-gray-900 sm:text-4xl" style="font-family: 'Playfair Display', serif;">Build Your Rental Set</h1>
        </div>

        <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
            <a
                href="{{ route('customer.customizations.custom-set') }}"
                class="group relative flex h-full flex-col overflow-hidden rounded-3xl p-8 transition-all duration-300 hover:-translate-y-1 hover:shadow-xl md:p-10"
                style="background: linear-gradient(135deg, #fff7f5 0%, #fdf4f0 50%, #fff 100%); border: 1px solid rgba(201, 163, 95, 0.3);"
            >
                <div class="absolute -right-12 -top-12 h-48 w-48 rounded-full opacity-30 transition-opacity duration-500 group-hover:opacity-50" style="background: #c9a35f; filter: blur(30px);" aria-hidden="true"></div>
                <div class="relative z-10 flex-1">
                    <div class="mb-6 flex h-14 w-14 items-center justify-center rounded-2xl text-white shadow-sm" style="background: linear-gradient(135deg, #f97316 0%, #ec4899 100%);">
                        <x-lucide-shirt width="28" height="28" aria-hidden="true" />
                    </div>
                    <h2 class="mb-3 text-2xl font-bold text-gray-900" style="font-family: 'Playfair Display', serif;">Create Custom Set</h2>
                    <p class="mb-8 text-sm leading-relaxed text-gray-600">
                        Browse our available catalogue and hand-pick the items you want. Mix gowns, suits, and accessories to build the perfect look for your event.
                    </p>
                </div>
                <div class="relative z-10 mt-auto inline-flex w-full items-center justify-center gap-2 rounded-xl bg-gray-900 px-6 py-3.5 font-bold text-white shadow-sm transition-all group-hover:shadow-md">
                    Start Building
                    <x-lucide-arrow-right width="18" height="18" class="transition-transform group-hover:translate-x-1" aria-hidden="true" />
                </div>
            </a>

            <a
                href="{{ route('customer.customizations.new-item-request') }}"
                class="group relative flex h-full flex-col overflow-hidden rounded-3xl p-8 transition-all duration-300 hover:-translate-y-1 hover:shadow-xl md:p-10"
                style="background: linear-gradient(135deg, #fdf4f0 0%, #fce7f3 50%, #fff 100%); border: 1px solid rgba(236, 72, 153, 0.2);"
            >
                <div class="absolute -right-12 -top-12 h-48 w-48 rounded-full opacity-30 transition-opacity duration-500 group-hover:opacity-50" style="background: #ec4899; filter: blur(30px);" aria-hidden="true"></div>
                <div class="relative z-10 flex-1">
                    <div class="mb-6 flex h-14 w-14 items-center justify-center rounded-2xl border border-pink-100 bg-white text-pink-600 shadow-sm">
                        <x-lucide-scissors width="28" height="28" aria-hidden="true" />
                    </div>
                    <h2 class="mb-3 text-2xl font-bold text-gray-900" style="font-family: 'Playfair Display', serif;">Request a New Item</h2>
                    <p class="mb-8 text-sm leading-relaxed text-gray-600">
                        Can't find exactly what you're looking for? Submit a custom design request. Tell our admin team your sizes and vision, and we'll tailor it for you.
                    </p>
                </div>
                <div class="relative z-10 mt-auto inline-flex w-full items-center justify-center gap-2 rounded-xl border border-pink-200 bg-white px-6 py-3.5 font-bold text-pink-700 shadow-sm transition-all group-hover:border-pink-300 group-hover:shadow-md">
                    Request Customization
                    <x-lucide-arrow-right width="18" height="18" class="transition-transform group-hover:translate-x-1" aria-hidden="true" />
                </div>
            </a>
        </div>
    </div>
</x-app-layout>
