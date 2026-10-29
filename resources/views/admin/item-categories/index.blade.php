<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Item Categories') }}
        </h2>
    </x-slot>

    <div class="py-8 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Top Header Bar -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
            <div>
                <p class="text-xs font-bold tracking-widest uppercase text-yellow-600 mb-1">Inventory Setup</p>
            </div>
            
            <div class="flex items-center gap-3">
                <!-- Category Count Pill -->
                <span class="px-4 py-2 rounded-full bg-yellow-50 text-yellow-700 font-semibold text-sm border border-yellow-100">
                    {{ $categories->count() ?? $categoryCount ?? 0 }} categories
                </span>
                
                <!-- Add Button -->
                <a href="{{ route('admin.item-categories.create') }}" 
                   class="inline-flex items-center gap-2 px-5 py-2.5 rounded-lg text-white font-medium shadow-sm hover:shadow-md transition-all" 
                   style="background: linear-gradient(135deg, #f97316 0%, #ec4899 100%);">
                    <x-lucide-plus width="18" height="18" />
                    Add Category
                </a>
            </div>
        </div>

        <!-- Main Table Card -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            
            <div class="px-6 py-5 border-b border-gray-100 bg-gray-50/50">
                <h3 class="text-sm font-bold text-gray-700 uppercase tracking-wider">Current Records</h3>
            </div>

            @if(isset($categories) && $categories->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse min-w-[800px]">
                        <thead class="bg-gray-50/80 border-b border-gray-100">
                            <tr class="bg-gray-50 border-b border-gray-100">
                                <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider w-16">ID</th>
                                <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider w-48">Category Name</th>
                                <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider w-24">Image</th>
                                <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider w-32 text-center">Stock Quantity</th>
                                <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider w-32">Date Created</th>
                                <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider text-right w-40">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($categories as $category)
                                <tr class="hover:bg-gray-50/50 transition-colors group">
                                    <!-- ID -->
                                    <td class="px-6 py-4 text-sm font-medium text-gray-500">
                                        {{ $category->id }}
                                    </td>
                                    
                                    <!-- Name -->
                                    <td class="px-6 py-4">
                                        <strong class="text-sm font-semibold text-gray-900">{{ $category->name }}</strong>
                                    </td>
                                    
                                    <!-- Image -->
                                    <td class="px-6 py-4">
                                        @if($category->image)
                                            <img src="{{ asset('storage/' . $category->image) }}" alt="{{ $category->name }}" class="w-14 h-14 min-w-[56px] rounded-xl object-cover border border-gray-200 shadow-sm">
                                        @else
                                            <span class="flex items-center justify-center w-14 h-14 min-w-[56px] rounded-xl bg-pink-50 text-pink-300">
                                                <x-lucide-image width="24" height="24" />
                                            </span>
                                        @endif
                                    </td>

                                    <!-- Stock Quantity -->
                                    <td class="px-6 py-4 text-center">
                                        <span class="inline-flex min-w-10 items-center justify-center rounded-full bg-yellow-50 px-3 py-1 text-sm font-semibold text-yellow-700">
                                            {{ (int) $category->stock_quantity }}
                                        </span>
                                    </td>
                                    
                                    <!-- Date -->
                                    <td class="px-6 py-4">
                                        <span class="text-sm text-gray-500">
                                            {{ $category->created_at ? $category->created_at->format('M d, Y') : 'N/A' }}
                                        </span>
                                    </td>
                                    
                                    <!-- Actions -->
                                    <td class="px-6 py-4 text-right">
                                        <div class="flex items-center justify-end gap-2">
                                            <!-- Edit Button -->
                                            <a href="{{ route('admin.item-categories.edit', $category->id) }}" 
                                               class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-gray-800 text-white text-xs font-semibold hover:bg-gray-900 transition-colors shadow-sm">
                                                <x-lucide-pencil width="14" height="14" />
                                                Edit
                                            </a>
                                            
                                            <!-- Delete Form -->
                                            <form action="{{ route('admin.item-categories.destroy', $category->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this category?');" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-white text-xs font-semibold shadow-sm hover:shadow-md transition-all" style="background: linear-gradient(135deg, #ef4444 0%, #b91c1c 100%);">
                                                    <x-lucide-trash-2 width="14" height="14" />
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
                <!-- Empty State -->
                <div class="p-12 text-center flex flex-col items-center justify-center">
                    <div class="w-16 h-16 rounded-full bg-gray-50 flex items-center justify-center text-gray-400 mb-4">
                        <x-lucide-tags width="32" height="32" />
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-1">No categories yet</h3>
                    <p class="text-sm text-gray-500 mb-4">Create categories to keep your rental items organized.</p>
                    <a href="{{ route('admin.item-categories.create') }}" class="text-sm font-semibold text-pink-600 hover:text-pink-700">+ Add your first category</a>
                </div>
            @endif
        </div>

    </div>

</x-app-layout>