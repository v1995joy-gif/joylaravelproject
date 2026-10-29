<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Rental Items') }}
        </h2>
    </x-slot>

    <div class="py-8 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- Top Action Bar -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900" style="font-family: 'Playfair Display', serif;">Inventory</h1>
                <p class="text-sm text-gray-500 mt-1">Manage your rental catalogue and track stock availability.</p>
            </div>
            <a href="{{ route('admin.rental-items.create') }}"
               class="inline-flex items-center gap-2 px-5 py-2.5 rounded-lg text-white font-medium shadow-sm hover:shadow-md transition-all"
               style="background: linear-gradient(135deg, #f97316 0%, #ec4899 100%);">
                <x-lucide-plus width="18" height="18" />
                Add Item
            </a>
        </div>

        <!-- Inventory Overview Card -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-8">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
                <div>
                    <p class="text-xs font-bold tracking-widest uppercase text-yellow-600 mb-1">Stock Distribution</p>
                    <h2 class="text-lg font-bold text-gray-900" style="font-family: 'Playfair Display', serif;">Inventory Status</h2>
                </div>
                <div class="flex gap-6 text-sm text-gray-500">
                    <span><strong class="text-gray-900 text-lg">{{ $inventorySummary['total'] }}</strong> total units</span>
                    <span><strong class="text-gray-900 text-lg">{{ $inventorySummary['items'] }}</strong> items</span>
                </div>
            </div>

            @php
                $inventoryDistribution = [
                    ['label' => 'Available', 'key' => 'available', 'quantity' => $inventorySummary['available'], 'color' => '#4b8b64'],
                    ['label' => 'Reserved', 'key' => 'reserved', 'quantity' => $inventorySummary['reserved'], 'color' => '#bb8c2c'],
                    ['label' => 'Rented', 'key' => 'rented', 'quantity' => $inventorySummary['rented'], 'color' => '#397f91'],
                    ['label' => 'Not returned', 'key' => 'not-returned', 'quantity' => $inventorySummary['not_returned'], 'color' => '#c2413b'],
                    ['label' => 'Maintenance', 'key' => 'maintenance', 'quantity' => $inventorySummary['maintenance'], 'color' => '#81838b'],
                ];
                $inventoryDistributionTotal = max(1, array_sum(array_column($inventoryDistribution, 'quantity')));
            @endphp

            <!-- Progress Bar -->
            <div class="w-full h-3 rounded-full flex gap-1 overflow-hidden mb-6 bg-gray-100" role="img" aria-label="Inventory distribution across {{ $inventorySummary['total'] }} total units">
                @foreach ($inventoryDistribution as $segment)
                    @if ($segment['quantity'] > 0)
                        <div class="h-full rounded-full transition-all duration-500"
                             style="width: {{ ($segment['quantity'] / $inventoryDistributionTotal) * 100 }}%; background-color: {{ $segment['color'] }};"
                             title="{{ $segment['label'] }}: {{ $segment['quantity'] }}">
                        </div>
                    @endif
                @endforeach
            </div>

            <!-- Legend -->
            <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
                @foreach ($inventoryDistribution as $segment)
                    <div class="flex flex-col gap-1">
                        <span class="flex items-center gap-2 text-xs text-gray-500 font-medium">
                            <span class="w-2.5 h-2.5 rounded-full" style="background-color: {{ $segment['color'] }};" aria-hidden="true"></span>
                            {{ $segment['label'] }}
                        </span>
                        <span class="text-base font-bold text-gray-900">{{ $segment['quantity'] }} <span class="text-xs font-normal text-gray-400">units</span></span>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Table Card -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            @if($items->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse min-w-[1000px]">
                        <thead>
                            <tr class="bg-gray-50 border-b border-gray-100">
                                <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Item</th>
                                <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Category</th>
                                <th class="px-4 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider text-center">Total Qty</th>
                                <th class="px-4 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider text-center text-green-600">Avail</th>
                                <th class="px-4 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider text-center text-yellow-600">Res</th>
                                <th class="px-4 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider text-center text-blue-600">Rent</th>
                                <th class="px-4 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider text-center text-red-600">Not Ret</th>
                                <th class="px-4 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider text-center text-gray-500">Maint</th>
                                <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($items as $item)
                                <tr class="hover:bg-gray-50/50 transition-colors group">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-4">
                                            @if ($item->image)
                                                <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->name }}" class="w-12 h-12 rounded-lg object-cover border border-gray-200">
                                            @else
                                                <span class="flex items-center justify-center w-12 h-12 rounded-lg bg-pink-50 text-pink-400" aria-hidden="true">
                                                    <x-lucide-shirt width="24" height="24" />
                                                </span>
                                            @endif
                                            <div class="flex flex-col">
                                                <strong class="text-sm font-semibold text-gray-900">{{ $item->name }}</strong>
                                                <span class="text-xs text-gray-500">{{ $item->item_code }}</span>
                                            </div>
                                        </div>
                                    </td>

                                    <td class="px-6 py-4">
                                        <span class="text-sm text-gray-700">{{ $item->category->name ?? 'No Category' }}</span>
                                    </td>

                                    <td class="px-4 py-4 text-center text-sm font-medium text-gray-700">{{ $item->inventory_breakdown['total'] }}</td>
                                    <td class="px-4 py-4 text-center text-sm font-bold text-green-600">{{ $item->inventory_breakdown['available'] }}</td>
                                    <td class="px-4 py-4 text-center text-sm font-medium text-yellow-600">{{ $item->inventory_breakdown['reserved'] }}</td>
                                    <td class="px-4 py-4 text-center text-sm font-medium text-blue-600">{{ $item->inventory_breakdown['rented'] }}</td>
                                    <td class="px-4 py-4 text-center text-sm font-bold text-red-600">{{ $item->inventory_breakdown['not_returned'] }}</td>
                                    <td class="px-4 py-4 text-center text-sm font-medium text-gray-500">{{ $item->inventory_breakdown['maintenance'] }}</td>

                                    <td class="px-6 py-4">
                                        @php
                                            $statusColors = [
                                                'available' => 'bg-green-100 text-green-700 border-green-200',
                                                'rented' => 'bg-blue-100 text-blue-700 border-blue-200',
                                                'reserved' => 'bg-yellow-100 text-yellow-700 border-yellow-200',
                                                'maintenance' => 'bg-gray-100 text-gray-700 border-gray-200',
                                                'not-returned' => 'bg-red-100 text-red-700 border-red-200',
                                            ];
                                            $badgeColor = $statusColors[\Illuminate\Support\Str::slug($item->inventory_breakdown['status'])] ?? 'bg-gray-100 text-gray-700 border-gray-200';
                                        @endphp
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold border {{ $badgeColor }}">
                                            {{ $item->inventory_breakdown['status'] }}
                                        </span>
                                    </td>

                                    <td class="px-6 py-4 text-right">
                                        <div class="flex items-center justify-end gap-2">
                                            <a href="{{ route('admin.rental-items.show', $item->id) }}"
                                               class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg border border-gray-200 bg-white text-gray-700 text-xs font-semibold hover:bg-gray-50 hover:text-gray-900 transition-colors shadow-sm"
                                               title="View item details" aria-label="View details for {{ $item->name }}">
                                                <x-lucide-eye width="14" height="14" aria-hidden="true" />
                                                View
                                            </a>

                                            <form action="{{ route('admin.rental-items.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this rental item?');" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-white text-xs font-semibold shadow-sm hover:shadow-md transition-all" style="background: linear-gradient(135deg, #ef4444 0%, #b91c1c 100%);">
                                                    <x-lucide-trash-2 width="14" height="14" aria-hidden="true" />
                                                    Delete
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="p-12 text-center flex flex-col items-center justify-center">
                    <div class="w-16 h-16 rounded-full bg-gray-50 flex items-center justify-center text-gray-400 mb-4">
                        <x-lucide-package-open width="32" height="32" />
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-1">No inventory items yet</h3>
                    <p class="text-sm text-gray-500 mb-4">Get started by adding your first rental item to the catalogue.</p>
                    <a href="{{ route('admin.rental-items.create') }}" class="text-sm font-semibold text-pink-600 hover:text-pink-700">+ Add your first item</a>
                </div>
            @endif
        </div>

    </div>

</x-app-layout>
