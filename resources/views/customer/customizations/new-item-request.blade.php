<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Request a New Item') }}
        </h2>
    </x-slot>

    <div class="mx-auto max-w-5xl px-4 py-8 sm:px-6 lg:px-8">
        <div class="mb-6">
            <a
                href="{{ route('customer.customization.portal') }}"
                class="inline-flex items-center gap-1.5 text-sm font-semibold text-gray-500 transition-colors hover:text-pink-600"
            >
                <x-lucide-arrow-left width="16" height="16" aria-hidden="true" />
                Back to Customization
            </a>
        </div>

        <div class="mb-8">
            <p class="mb-1 text-xs font-bold uppercase tracking-widest text-yellow-600">Bespoke Experience</p>
            <h1 class="mb-2 text-3xl font-bold text-gray-900" style="font-family: 'Playfair Display', serif;">Request a New Item</h1>
            <p class="text-sm text-gray-500">Request items that are not currently available in the rental inventory. The admin will quote them separately.</p>
        </div>

        @if ($errors->any())
            <div class="mb-6 rounded-xl border border-red-200 bg-red-50 px-5 py-4 text-sm text-red-700" role="alert">
                <p class="font-bold">Please correct the following:</p>
                <ul class="mt-2 list-inside list-disc space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form
            action="{{ route('customer.customizations.new-item-request.store') }}"
            method="POST"
            enctype="multipart/form-data"
            onsubmit="removeEmptyRows()"
        >
            @csrf

            <div id="new-items" class="mb-8 space-y-6">
                <div class="new-item-row relative rounded-2xl border border-gray-100 bg-white p-6 shadow-sm md:p-8">
                    <button
                        type="button"
                        class="remove-button remove-btn absolute right-6 top-6 inline-flex items-center gap-1 rounded-lg bg-red-50 px-3 py-1.5 text-xs font-bold text-red-600 transition-colors hover:bg-red-100 hover:text-red-700"
                        onclick="removeNewItem(this)"
                    >
                        <x-lucide-trash-2 width="14" height="14" aria-hidden="true" />
                        Remove
                    </button>

                    <div class="mb-6 grid grid-cols-1 gap-6 pt-10 md:grid-cols-3 md:pt-0">
                        <div class="md:col-span-2">
                            <label for="new-item-name-0" class="mb-2 block text-xs font-bold uppercase tracking-wider text-gray-600">Requested Item Name</label>
                            <input
                                id="new-item-name-0"
                                type="text"
                                name="new_items[0][item_name]"
                                value="{{ old('new_items.0.item_name') }}"
                                placeholder="Example: Burgundy Long-Sleeve Gown"
                                required
                                class="w-full rounded-xl border border-gray-300 bg-gray-50/50 px-4 py-3 text-sm font-medium text-gray-900 transition-colors focus:border-pink-500 focus:outline-none focus:ring-2 focus:ring-pink-200"
                            >
                        </div>

                        <div class="md:col-span-1">
                            <label for="new-item-quantity-0" class="mb-2 block text-xs font-bold uppercase tracking-wider text-gray-600">Quantity</label>
                            <input
                                id="new-item-quantity-0"
                                type="number"
                                name="new_items[0][quantity]"
                                value="{{ old('new_items.0.quantity', 1) }}"
                                min="1"
                                required
                                class="w-full rounded-xl border border-gray-300 bg-gray-50/50 px-4 py-3 text-sm font-medium text-gray-900 transition-colors focus:border-pink-500 focus:outline-none focus:ring-2 focus:ring-pink-200"
                            >
                        </div>
                    </div>

                    <div class="mb-6">
                        <label for="new-item-description-0" class="mb-2 block text-xs font-bold uppercase tracking-wider text-gray-600">
                            Description / Specifications <span class="font-normal normal-case text-gray-400">(Optional)</span>
                        </label>
                        <textarea
                            id="new-item-description-0"
                            name="new_items[0][description]"
                            rows="3"
                            placeholder="Describe the color, style, size, material, or other details..."
                            class="w-full resize-y rounded-xl border border-gray-300 bg-gray-50/50 px-4 py-3 text-sm text-gray-900 transition-colors focus:border-pink-500 focus:outline-none focus:ring-2 focus:ring-pink-200"
                        >{{ old('new_items.0.description') }}</textarea>
                    </div>

                    <div>
                        <label for="new-item-image-0" class="mb-2 block text-xs font-bold uppercase tracking-wider text-gray-600">Reference Image <span class="font-normal normal-case text-gray-400">(Optional)</span></label>
                        <div class="rounded-xl border-2 border-dashed border-gray-300 bg-gray-50/50 px-6 py-6 transition-colors hover:border-pink-400">
                            <div class="space-y-2 text-center">
                                <x-lucide-image-plus class="mx-auto text-gray-400" width="40" height="40" aria-hidden="true" />
                                <label for="new-item-image-0" class="inline-flex cursor-pointer items-center gap-1 text-sm font-semibold text-pink-600 hover:text-pink-500">
                                    <span>Upload a file</span>
                                </label>
                                <input
                                    id="new-item-image-0"
                                    type="file"
                                    name="new_items[0][reference_image]"
                                    accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp"
                                    class="mx-auto block w-full max-w-xs text-sm text-gray-600 file:mr-3 file:rounded-lg file:border-0 file:bg-pink-50 file:px-3 file:py-2 file:text-sm file:font-semibold file:text-pink-700 hover:file:bg-pink-100"
                                >
                                <p class="text-xs text-gray-500">JPG, JPEG, PNG, WEBP up to 5 MB.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex flex-col items-center justify-between gap-4 sm:flex-row">
                <button
                    type="button"
                    id="add-new-item-btn"
                    onclick="addNewItem()"
                    class="inline-flex w-full items-center justify-center gap-2 rounded-xl border-2 border-dashed border-gray-300 bg-white px-5 py-3 font-bold text-gray-700 transition-all hover:border-pink-400 hover:bg-pink-50 hover:text-pink-600 sm:w-auto"
                >
                    <x-lucide-plus width="18" height="18" aria-hidden="true" />
                    Add Another New Item
                </button>

                <button
                    type="submit"
                    class="inline-flex w-full items-center justify-center gap-2 rounded-xl px-8 py-3.5 font-bold text-white shadow-md transition-all hover:-translate-y-0.5 hover:shadow-lg sm:w-auto"
                    style="background: linear-gradient(135deg, #f97316 0%, #ec4899 100%);"
                >
                    <x-lucide-check-circle width="18" height="18" aria-hidden="true" />
                    Submit New Item Request
                </button>
            </div>
        </form>
    </div>

    <script>
        let newItemIndex = 1;

        function addNewItem() {
            const container = document.getElementById('new-items');
            const row = document.createElement('div');
            const index = newItemIndex++;

            row.className = 'new-item-row relative rounded-2xl border border-gray-100 bg-white p-6 shadow-sm md:p-8';
            row.innerHTML = `
                <button
                    type="button"
                    class="remove-button remove-btn absolute right-6 top-6 inline-flex items-center gap-1 rounded-lg bg-red-50 px-3 py-1.5 text-xs font-bold text-red-600 transition-colors hover:bg-red-100 hover:text-red-700"
                    onclick="removeNewItem(this)"
                >
                    Remove
                </button>

                <div class="mb-6 grid grid-cols-1 gap-6 pt-10 md:grid-cols-3 md:pt-0">
                    <div class="md:col-span-2">
                        <label for="new-item-name-${index}" class="mb-2 block text-xs font-bold uppercase tracking-wider text-gray-600">Requested Item Name</label>
                        <input
                            id="new-item-name-${index}"
                            type="text"
                            name="new_items[${index}][item_name]"
                            placeholder="Example: Burgundy Long-Sleeve Gown"
                            required
                            class="w-full rounded-xl border border-gray-300 bg-gray-50/50 px-4 py-3 text-sm font-medium text-gray-900 transition-colors focus:border-pink-500 focus:outline-none focus:ring-2 focus:ring-pink-200"
                        >
                    </div>

                    <div class="md:col-span-1">
                        <label for="new-item-quantity-${index}" class="mb-2 block text-xs font-bold uppercase tracking-wider text-gray-600">Quantity</label>
                        <input
                            id="new-item-quantity-${index}"
                            type="number"
                            name="new_items[${index}][quantity]"
                            min="1"
                            value="1"
                            required
                            class="w-full rounded-xl border border-gray-300 bg-gray-50/50 px-4 py-3 text-sm font-medium text-gray-900 transition-colors focus:border-pink-500 focus:outline-none focus:ring-2 focus:ring-pink-200"
                        >
                    </div>
                </div>

                <div class="mb-6">
                    <label for="new-item-description-${index}" class="mb-2 block text-xs font-bold uppercase tracking-wider text-gray-600">
                        Description / Specifications <span class="font-normal normal-case text-gray-400">(Optional)</span>
                    </label>
                    <textarea
                        id="new-item-description-${index}"
                        name="new_items[${index}][description]"
                        rows="3"
                        placeholder="Describe the color, style, size, material, or other details..."
                        class="w-full resize-y rounded-xl border border-gray-300 bg-gray-50/50 px-4 py-3 text-sm text-gray-900 transition-colors focus:border-pink-500 focus:outline-none focus:ring-2 focus:ring-pink-200"
                    ></textarea>
                </div>

                <div>
                    <label for="new-item-image-${index}" class="mb-2 block text-xs font-bold uppercase tracking-wider text-gray-600">Reference Image <span class="font-normal normal-case text-gray-400">(Optional)</span></label>
                    <div class="rounded-xl border-2 border-dashed border-gray-300 bg-gray-50/50 px-6 py-6 transition-colors hover:border-pink-400">
                        <div class="space-y-2 text-center">
                            <div class="mx-auto text-gray-400">
                                <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <rect width="18" height="18" x="3" y="3" rx="2" ry="2"></rect>
                                    <circle cx="9" cy="9" r="2"></circle>
                                    <path d="m21 15-3.086-3.086a2 2 0 0 0-2.828 0L6 21"></path>
                                    <path d="M12 8v6"></path>
                                    <path d="M9 11h6"></path>
                                </svg>
                            </div>
                            <label for="new-item-image-${index}" class="inline-flex cursor-pointer items-center text-sm font-semibold text-pink-600 hover:text-pink-500">Upload a file</label>
                            <input
                                id="new-item-image-${index}"
                                type="file"
                                name="new_items[${index}][reference_image]"
                                accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp"
                                class="mx-auto block w-full max-w-xs text-sm text-gray-600 file:mr-3 file:rounded-lg file:border-0 file:bg-pink-50 file:px-3 file:py-2 file:text-sm file:font-semibold file:text-pink-700 hover:file:bg-pink-100"
                            >
                            <p class="text-xs text-gray-500">JPG, JPEG, PNG, WEBP up to 5 MB.</p>
                        </div>
                    </div>
                </div>
            `;

            container.appendChild(row);
        }

        function removeNewItem(button) {
            const row = button.closest('.new-item-row');
            const rows = document.querySelectorAll('#new-items .new-item-row');

            if (rows.length > 1) {
                row.remove();
                return;
            }

            row.querySelectorAll('input, textarea').forEach((input) => {
                if (input.type === 'file') {
                    input.value = '';
                } else if (input.name.includes('[quantity]')) {
                    input.value = '1';
                } else {
                    input.value = '';
                }
            });
        }

        function removeEmptyRows() {
            document.querySelectorAll('#new-items .new-item-row').forEach((row) => {
                if (!row.querySelector('input[name*="[item_name]"]').value.trim()) {
                    row.remove();
                }
            });
        }
    </script>
</x-app-layout>
