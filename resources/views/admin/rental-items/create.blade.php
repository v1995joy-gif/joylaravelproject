<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Add Rental Item') }}
        </h2>
    </x-slot>

    <div class="py-8 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 md:p-8">
            <div class="mb-8 border-b border-gray-100 pb-6">
                <h1 class="text-2xl font-bold text-gray-900" style="font-family: 'Playfair Display', serif;">Create New Item</h1>
                <p class="text-sm text-gray-500 mt-1">Fill in the details below to add a new gown or package to your catalogue.</p>
            </div>

            @if ($errors->any())
                <div class="mb-6 p-4 rounded-xl bg-red-50 border border-red-200 text-red-600 text-sm" role="alert">
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('admin.rental-items.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 lg:gap-12">
                    <div class="space-y-6">
                        <h2 class="text-sm font-bold text-gray-800 uppercase tracking-wider border-b border-gray-100 pb-2">Basic Information</h2>

                        <div>
                            <label for="item_code" class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-2">Item Code</label>
                            <input type="text" id="item_code" name="item_code" value="{{ old('item_code') }}" placeholder="Example: GWN-001" required
                                   class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:border-pink-500 focus:ring-2 focus:ring-pink-200 transition-colors bg-gray-50/50">
                        </div>

                        <div>
                            <label for="name" class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-2">Item Name</label>
                            <input type="text" id="name" name="name" value="{{ old('name') }}" placeholder="Example: Princess Wedding Gown" required
                                   class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:border-pink-500 focus:ring-2 focus:ring-pink-200 transition-colors bg-gray-50/50">
                        </div>

                        <div>
                            <label for="description" class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-2">Description</label>
                            <textarea id="description" name="description" placeholder="Enter item description" rows="4"
                                      class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:border-pink-500 focus:ring-2 focus:ring-pink-200 transition-colors bg-gray-50/50 resize-y">{{ old('description') }}</textarea>
                        </div>

                        <div>
                            <label for="image" class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-2">Item Image</label>
                            <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-xl hover:border-pink-400 transition-colors bg-gray-50/50">
                                <div class="space-y-1 text-center">
                                    <svg class="mx-auto h-10 w-10 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                    <div class="flex text-sm text-gray-600 justify-center">
                                        <label for="image" class="relative cursor-pointer rounded-md font-medium text-pink-600 hover:text-pink-500 focus-within:outline-none">
                                            <span>Select an image to upload</span>
                                            <input id="image" name="image" type="file" class="sr-only" accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp">
                                        </label>
                                    </div>
                                    <p class="text-xs text-gray-500">JPG, JPEG, PNG, or WEBP up to 5 MB</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-6">
                        <h2 class="text-sm font-bold text-gray-800 uppercase tracking-wider border-b border-gray-100 pb-2">Pricing &amp; Details</h2>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label for="category_id" class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-2">Category</label>
                                <select id="category_id" name="category_id" required class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:border-pink-500 focus:ring-2 focus:ring-pink-200 transition-colors bg-gray-50/50">
                                    <option value="">-- Select Category --</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" @selected(old('category_id') == $category->id)>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label for="status" class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-2">Status</label>
                                <select id="status" name="status" required class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:border-pink-500 focus:ring-2 focus:ring-pink-200 transition-colors bg-gray-50/50">
                                    <option value="Available" @selected(old('status', 'Available') === 'Available')>Available</option>
                                    <option value="Rented" @selected(old('status', 'Available') === 'Rented')>Rented</option>
                                    <option value="Reserved" @selected(old('status', 'Available') === 'Reserved')>Reserved</option>
                                    <option value="Maintenance" @selected(old('status', 'Available') === 'Maintenance')>Maintenance</option>
                                </select>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label for="rental_price" class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-2">Rental Price (PHP)</label>
                                <input type="number" id="rental_price" name="rental_price" step="0.01" min="0" value="{{ old('rental_price') }}" placeholder="5000" required
                                       class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:border-pink-500 focus:ring-2 focus:ring-pink-200 transition-colors bg-gray-50/50">
                            </div>

                            <div>
                                <label for="quantity" class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-2">Total Quantity</label>
                                <input type="number" id="quantity" name="quantity" value="{{ old('quantity', 1) }}" min="1" required
                                       class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:border-pink-500 focus:ring-2 focus:ring-pink-200 transition-colors bg-gray-50/50">
                            </div>
                        </div>

                        <div class="bg-gray-50/80 p-4 rounded-xl border border-gray-200">
                            <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-1">Quantity by Size</label>
                            <p class="text-xs text-gray-500 mb-3">Add sizes such as Small, Medium, or Large.</p>

                            <div id="size-rows" class="space-y-2 mb-3">
                                <div class="size-row flex gap-2">
                                    <input type="text" name="size_names[]" placeholder="Size (e.g. Small)" data-size-name class="flex-1 px-3 py-2 rounded-lg border border-gray-300 focus:border-pink-500 focus:ring-1 focus:ring-pink-200 text-sm">
                                    <input type="number" name="size_quantities[]" value="0" min="0" placeholder="Qty" data-size-quantity class="w-20 px-3 py-2 rounded-lg border border-gray-300 focus:border-pink-500 focus:ring-1 focus:ring-pink-200 text-sm">
                                    <button type="button" onclick="this.parentElement.remove()" class="px-3 py-2 bg-red-100 text-red-600 rounded-lg text-sm font-medium hover:bg-red-200 transition-colors" aria-label="Remove size row">Remove</button>
                                </div>
                            </div>
                            <button type="button" onclick="addSizeRow()" class="text-sm font-semibold text-pink-600 hover:text-pink-700 flex items-center gap-1">
                                + Add Size Row
                            </button>
                        </div>

                        <div>
                            <label for="colors" class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-2">Available Colors</label>
                            <textarea id="colors" name="colors" placeholder="White, Black, Red" rows="2"
                                      class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:border-pink-500 focus:ring-2 focus:ring-pink-200 transition-colors bg-gray-50/50 resize-y">{{ old('colors') }}</textarea>
                            <p class="text-xs text-gray-500 mt-1">Separate colors with commas.</p>
                        </div>
                    </div>
                </div>

                <div class="mt-8 pt-6 border-t border-gray-100 flex items-center justify-end gap-3">
                    <a href="{{ route('admin.rental-items.index') }}" class="px-5 py-2.5 rounded-lg border border-gray-300 text-gray-700 font-medium hover:bg-gray-50 transition-colors">
                        Cancel
                    </a>
                    <button type="submit" class="px-6 py-2.5 rounded-lg text-white font-medium shadow-sm hover:shadow-md transition-all" style="background: linear-gradient(135deg, #f97316 0%, #ec4899 100%);">
                        Save Rental Item
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function addSizeRow() {
            const row = document.createElement('div');
            row.className = 'size-row flex gap-2';
            row.innerHTML = `
                <input type="text" name="size_names[]" placeholder="Size (e.g. Medium)" data-size-name class="flex-1 px-3 py-2 rounded-lg border border-gray-300 focus:border-pink-500 focus:ring-1 focus:ring-pink-200 text-sm">
                <input type="number" name="size_quantities[]" value="0" min="0" placeholder="Qty" data-size-quantity class="w-20 px-3 py-2 rounded-lg border border-gray-300 focus:border-pink-500 focus:ring-1 focus:ring-pink-200 text-sm">
                <button type="button" onclick="this.parentElement.remove()" class="px-3 py-2 bg-red-100 text-red-600 rounded-lg text-sm font-medium hover:bg-red-200 transition-colors" aria-label="Remove size row">Remove</button>
            `;
            document.getElementById('size-rows').appendChild(row);
        }
    </script>

</x-app-layout>
