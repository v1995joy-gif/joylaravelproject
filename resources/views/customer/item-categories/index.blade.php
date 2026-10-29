<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <title>Item Categories</title>
    <style>
        body {
            margin: 40px;
            margin-left: 300px;
            background: var(--neo-field);
            color: var(--event-ink);
            font-family: 'DM Sans', 'Segoe UI', sans-serif;
        }

        .catalog-container {
            max-width: 1180px;
            padding: 28px;
            background: var(--neo-field);
            border: 1px solid var(--event-line);
            border-radius: 20px;
        }

        .catalog-heading {
            margin-bottom: 24px;
        }

        .catalog-heading h1 {
            margin: 0 0 8px;
        }

        .catalog-heading p {
            margin: 0;
            color: var(--event-muted);
        }

        .empty {
            padding: 32px;
            text-align: center;
            color: var(--event-muted);
        }

        @media (max-width: 760px) {
            body {
                margin: 20px;
                margin-left: 20px;
            }

            .catalog-container {
                padding: 20px;
            }
        }
    </style>
</head>
<body>
    @include('layouts.navigation')

    <main class="catalog-container">
        <div class="catalog-heading">
            <h1>Browse by category</h1>
        </div>

        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4">
            @forelse ($categories as $category)
                <a
                    href="{{ route('customer.rental-items.index', ['category' => $category->id]) }}"
                    class="group flex flex-col overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm transition-all duration-300 hover:-translate-y-1 hover:shadow-xl"
                >
                    <div class="relative aspect-[4/3] w-full overflow-hidden bg-gray-100">
                        @if ($category->image)
                            <img
                                src="{{ asset('storage/' . $category->image) }}"
                                alt="{{ $category->name }}"
                                class="h-full w-full object-cover object-center transition-transform duration-500 group-hover:scale-105"
                            >
                        @else
                            <div class="flex h-full w-full items-center justify-center text-gray-300">
                                <x-lucide-image width="48" height="48" aria-hidden="true" />
                            </div>
                        @endif
                        <div class="absolute inset-0 bg-black/0 transition-colors duration-300 group-hover:bg-black/10" aria-hidden="true"></div>
                    </div>

                    <div class="flex flex-1 flex-col p-6">
                        <h2 class="mb-2 text-lg font-bold text-gray-900" style="font-family: 'Playfair Display', serif;">
                            {{ $category->name }}
                        </h2>
                        <div class="mt-auto">
                            <span class="inline-flex items-center rounded-full border border-yellow-100 bg-yellow-50 px-3 py-1 text-xs font-bold text-yellow-700">
                                {{ $category->rental_items_count }} available {{ Illuminate\Support\Str::plural('item', $category->rental_items_count) }}
                            </span>
                        </div>
                    </div>
                </a>
            @empty
                <div class="empty">No item categories are available yet.</div>
            @endforelse
        </div>
    </main>
</body>
</html>