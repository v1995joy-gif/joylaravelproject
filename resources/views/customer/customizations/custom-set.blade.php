<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Create Custom Set') }}
        </h2>
    </x-slot>

    <div class="mx-auto max-w-6xl px-4 py-8 sm:px-6 lg:px-8">
        <div class="mb-6">
            <a
                href="{{ route('customer.customization.portal') }}"
                class="inline-flex items-center gap-1.5 text-sm font-semibold text-gray-500 transition-colors hover:text-pink-600"
            >
                <x-lucide-arrow-left width="16" height="16" aria-hidden="true" />
                Back to Customization
            </a>
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
            id="custom-set-form"
            action="{{ route('customer.customizations.custom-set.store') }}"
            method="POST"
            onsubmit="removeEmptyRows()"
        >
            @csrf

            <div class="mb-8 overflow-visible rounded-3xl border border-gray-100 bg-white shadow-sm">
                <div class="rounded-t-3xl border-b border-[var(--event-line)] px-6 py-6 sm:px-8" style="background-color: var(--neo-field);">
                    <p class="mb-1 text-xs font-bold uppercase tracking-widest text-yellow-600">Bespoke Experience</p>
                    <h1 class="text-2xl font-bold text-gray-900" style="font-family: 'Playfair Display', serif;">Build Your Custom Set</h1>
                    <p class="mt-1 text-sm text-gray-600">Search our catalogue and choose the items for your custom rental set.</p>
                </div>

                <div class="px-6 pt-6 sm:px-8">
                    <div class="flex items-start gap-3 rounded-xl border border-pink-100 bg-pink-50 p-4 text-pink-800">
                        <x-lucide-info class="mt-0.5 shrink-0 text-pink-500" width="20" height="20" aria-hidden="true" />
                        <p class="text-sm">Select a rental item, choose its available size or color when offered, and enter the quantity. Add more items as needed.</p>
                    </div>
                </div>

                <div class="p-6 sm:p-8">
                    <div class="mb-5">
                        <h2 class="text-lg font-bold text-gray-900" style="font-family: 'Playfair Display', serif;">Existing Rental Items</h2>
                        <p class="mt-1 text-sm text-gray-500">Choose from the available inventory.</p>
                    </div>

                    <div id="custom-items" class="space-y-4">
                        <div class="item-row relative rounded-2xl border border-gray-200 bg-gray-50/70 p-5 sm:p-6" data-item-index="0">
                            <button
                                type="button"
                                class="remove-button remove-btn absolute right-4 top-4 inline-flex items-center gap-1 rounded-lg bg-red-50 px-3 py-1.5 text-xs font-bold text-red-600 transition-colors hover:bg-red-100 hover:text-red-700"
                                onclick="removeItem(this)"
                            >
                                <x-lucide-trash-2 width="14" height="14" aria-hidden="true" />
                                Remove
                            </button>

                            <div class="grid grid-cols-1 gap-5 pt-10 md:grid-cols-12 md:pt-0">
                                <div class="md:col-span-6">
                                    <label class="mb-2 block text-[10px] font-bold uppercase tracking-wider text-gray-500">Rental Item</label>
                                    <div class="search-box relative">
                                        <x-lucide-search class="pointer-events-none absolute left-3.5 top-3 text-gray-400" width="16" height="16" aria-hidden="true" />
                                        <input
                                            type="search"
                                            class="item-search w-full rounded-xl border border-gray-300 bg-white py-2.5 pl-10 pr-4 text-sm font-medium text-gray-900 focus:border-pink-500 focus:outline-none focus:ring-2 focus:ring-pink-200"
                                            placeholder="Search rental items by name..."
                                            autocomplete="off"
                                            aria-label="Search rental items"
                                        >
                                        <input type="hidden" name="items[0][rental_item_id]" class="item-id">
                                        <div class="search-results" role="listbox"></div>
                                        <div class="selected-item" hidden>
                                            <strong class="selected-item-name"></strong>
                                            <button type="button" class="clear-item" onclick="clearItem(this)">Change</button>
                                        </div>
                                    </div>
                                    <p class="mt-1 text-xs text-gray-400">Type to search available inventory.</p>
                                </div>

                                <div class="item-options grid grid-cols-1 gap-4 md:col-span-4 md:grid-cols-2" hidden>
                                    <div>
                                        <label class="mb-2 block text-[10px] font-bold uppercase tracking-wider text-gray-500">Size</label>
                                        <select name="items[0][size]" class="item-size w-full rounded-xl border border-gray-300 bg-white px-3 py-2.5 text-sm focus:border-pink-500 focus:outline-none focus:ring-2 focus:ring-pink-200">
                                            <option value="">Select size</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="mb-2 block text-[10px] font-bold uppercase tracking-wider text-gray-500">Color</label>
                                        <select name="items[0][color]" class="item-color w-full rounded-xl border border-gray-300 bg-white px-3 py-2.5 text-sm focus:border-pink-500 focus:outline-none focus:ring-2 focus:ring-pink-200">
                                            <option value="">Select color</option>
                                        </select>
                                    </div>
                                    <p class="option-note text-xs text-gray-500 md:col-span-2">Select a size or color when options are available for this item.</p>
                                </div>

                                <div class="md:col-span-2">
                                    <label class="mb-2 block text-[10px] font-bold uppercase tracking-wider text-gray-500">Quantity</label>
                                    <input
                                        type="number"
                                        name="items[0][quantity]"
                                        min="1"
                                        value="1"
                                        class="w-full rounded-xl border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-900 focus:border-pink-500 focus:outline-none focus:ring-2 focus:ring-pink-200"
                                    >
                                </div>
                            </div>
                        </div>
                    </div>

                    <button
                        type="button"
                        id="add-item-btn"
                        onclick="addItem()"
                        class="mt-5 inline-flex items-center gap-2 rounded-xl border-2 border-dashed border-gray-300 bg-white px-5 py-2.5 font-bold text-gray-700 transition-all hover:border-pink-400 hover:bg-pink-50 hover:text-pink-600"
                    >
                        <x-lucide-plus width="18" height="18" aria-hidden="true" />
                        Add Another Item
                    </button>
                </div>

                <div class="border-t border-gray-100 px-6 py-6 sm:px-8">
                    <h2 class="mb-2 text-lg font-bold text-gray-900" style="font-family: 'Playfair Display', serif;">Customization Notes</h2>
                    <p class="mb-4 text-xs text-gray-500">Describe your overall preferences, event requirements, or special instructions for the admin.</p>
                    <textarea
                        name="customization_details"
                        rows="4"
                        required
                        placeholder="Describe your overall preferences, event requirements, colors, sizes, styles, or other details..."
                        class="w-full resize-y rounded-xl border border-gray-300 bg-gray-50/50 px-4 py-3 text-sm text-gray-900 focus:border-pink-500 focus:outline-none focus:ring-2 focus:ring-pink-200"
                    >{{ old('customization_details') }}</textarea>
                </div>

                <div class="flex justify-end rounded-b-3xl border-t border-gray-100 bg-gray-50 px-6 py-5 sm:px-8">
                    <button
                        type="submit"
                        class="inline-flex items-center gap-2 rounded-xl px-6 py-3.5 font-bold text-white shadow-md transition-all hover:-translate-y-0.5 hover:shadow-lg"
                        style="background: linear-gradient(135deg, #f97316 0%, #ec4899 100%);"
                    >
                        <x-lucide-check-circle width="18" height="18" aria-hidden="true" />
                        Submit Custom Set Request
                    </button>
                </div>
            </div>
        </form>
    </div>

    <style>
        .item-options[hidden],
        .selected-item[hidden],
        .item-search[hidden] {
            display: none !important;
        }

        .search-results {
            position: absolute;
            z-index: 50;
            top: 100%;
            right: 0;
            left: 0;
            display: none;
            max-height: 220px;
            overflow-y: auto;
            border: 1px solid #e5e7eb;
            border-radius: 0 0 0.75rem 0.75rem;
            background: #fff;
            box-shadow: 0 10px 20px rgba(36, 19, 29, 0.12);
        }

        .search-result {
            display: block;
            width: 100%;
            border: 0 !important;
            border-bottom: 1px solid #f3f4f6 !important;
            background: #fff !important;
            color: #374151 !important;
            padding: 0.625rem 1rem !important;
            text-align: left;
            font: inherit;
            cursor: pointer;
        }

        .search-result:hover,
        .search-result:focus {
            background: #fdf2f8 !important;
        }

        .selected-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 0.75rem;
            margin-top: 0.5rem;
            border: 1px solid var(--event-line);
            border-radius: 0.5rem;
            background: var(--neo-field);
            padding: 0.55rem 0.7rem;
        }

        .selected-item strong {
            color: var(--event-ink);
            font-size: 0.875rem;
        }

        .clear-item {
            border: 0;
            background: transparent;
            padding: 0.125rem 0.25rem;
            color: var(--event-danger);
            font-size: 0.75rem;
            cursor: pointer;
        }

        .empty-results {
            padding: 0.75rem;
            color: var(--event-muted);
            font-size: 0.8125rem;
        }
    </style>

    <script>
        let itemIndex = 1;
        const rentalItems = @json($rentalItemOptions);

        function itemOptionsMarkup(index) {
            return `
                <div class="item-options grid grid-cols-1 gap-4 md:col-span-4 md:grid-cols-2" hidden>
                    <div>
                        <label class="mb-2 block text-[10px] font-bold uppercase tracking-wider text-gray-500">Size</label>
                        <select name="items[${index}][size]" class="item-size w-full rounded-xl border border-gray-300 bg-white px-3 py-2.5 text-sm focus:border-pink-500 focus:outline-none focus:ring-2 focus:ring-pink-200">
                            <option value="">Select size</option>
                        </select>
                    </div>
                    <div>
                        <label class="mb-2 block text-[10px] font-bold uppercase tracking-wider text-gray-500">Color</label>
                        <select name="items[${index}][color]" class="item-color w-full rounded-xl border border-gray-300 bg-white px-3 py-2.5 text-sm focus:border-pink-500 focus:outline-none focus:ring-2 focus:ring-pink-200">
                            <option value="">Select color</option>
                        </select>
                    </div>
                    <p class="option-note text-xs text-gray-500 md:col-span-2">Select a size or color when options are available for this item.</p>
                </div>
            `;
        }

        function itemPickerMarkup(index) {
            return `
                <div class="md:col-span-6">
                    <label class="mb-2 block text-[10px] font-bold uppercase tracking-wider text-gray-500">Rental Item</label>
                    <div class="search-box relative">
                        <input
                            type="search"
                            class="item-search w-full rounded-xl border border-gray-300 bg-white py-2.5 pl-4 pr-4 text-sm font-medium text-gray-900 focus:border-pink-500 focus:outline-none focus:ring-2 focus:ring-pink-200"
                            placeholder="Search rental items by name..."
                            autocomplete="off"
                            aria-label="Search rental items"
                        >
                        <input type="hidden" name="items[${index}][rental_item_id]" class="item-id">
                        <div class="search-results" role="listbox"></div>
                        <div class="selected-item" hidden>
                            <strong class="selected-item-name"></strong>
                            <button type="button" class="clear-item" onclick="clearItem(this)">Change</button>
                        </div>
                    </div>
                    <p class="mt-1 text-xs text-gray-400">Type to search available inventory.</p>
                </div>
            `;
        }

        function addItem() {
            const container = document.getElementById('custom-items');
            const row = document.createElement('div');

            row.className = 'item-row relative rounded-2xl border border-gray-200 bg-gray-50/70 p-5 sm:p-6';
            row.innerHTML = `
                <button
                    type="button"
                    class="remove-button remove-btn absolute right-4 top-4 inline-flex items-center gap-1 rounded-lg bg-red-50 px-3 py-1.5 text-xs font-bold text-red-600 transition-colors hover:bg-red-100 hover:text-red-700"
                    onclick="removeItem(this)"
                >
                    Remove
                </button>
                <div class="grid grid-cols-1 gap-5 pt-10 md:grid-cols-12 md:pt-0">
                    ${itemPickerMarkup(itemIndex)}
                    ${itemOptionsMarkup(itemIndex)}
                    <div class="md:col-span-2">
                        <label class="mb-2 block text-[10px] font-bold uppercase tracking-wider text-gray-500">Quantity</label>
                        <input
                            type="number"
                            name="items[${itemIndex}][quantity]"
                            min="1"
                            value="1"
                            class="w-full rounded-xl border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-900 focus:border-pink-500 focus:outline-none focus:ring-2 focus:ring-pink-200"
                        >
                    </div>
                </div>
            `;

            container.appendChild(row);
            itemIndex++;
        }

        function removeItem(button) {
            button.closest('.item-row').remove();
        }

        function removeEmptyRows() {
            document.querySelectorAll('#custom-items .item-row').forEach((row) => {
                if (!row.querySelector('.item-id').value) {
                    row.remove();
                }
            });
        }

        const escapeHtml = (value) => String(value)
            .replaceAll('&', '&amp;')
            .replaceAll('<', '&lt;')
            .replaceAll('>', '&gt;')
            .replaceAll('"', '&quot;')
            .replaceAll("'", '&#039;');

        function showResults(searchInput) {
            const query = searchInput.value.trim().toLowerCase();
            const searchBox = searchInput.closest('.search-box');
            const row = searchBox.closest('.item-row');
            const results = searchBox.querySelector('.search-results');
            const matches = rentalItems
                .filter((item) => item.name.toLowerCase().includes(query))
                .slice(0, 30);

            document.querySelectorAll('#custom-items .item-row').forEach((itemRow) => {
                itemRow.style.zIndex = itemRow === row ? '50' : '1';
            });

            results.style.zIndex = '50';
            results.style.backgroundColor = '#fff';
            results.innerHTML = matches.length
                ? matches.map((item) => `
                    <button
                        type="button"
                        class="search-result"
                        role="option"
                        data-id="${item.id}"
                        data-name="${escapeHtml(item.name)}"
                        style="display:block;width:100%;padding:10px 16px;background-color:#fff!important;color:#374151!important;border:0!important;border-bottom:1px solid #f3f4f6!important;text-align:left;font:inherit;font-size:14px;font-weight:500;cursor:pointer;"
                        onmouseenter="this.style.setProperty('background-color','#fdf2f8','important')"
                        onmouseleave="this.style.setProperty('background-color','#fff','important')"
                    >
                        ${escapeHtml(item.name)}
                    </button>
                `).join('')
                : '<div class="empty-results" style="padding:12px;color:#6b7280;background:#fff;font-size:13px;">No available items match your search.</div>';

            results.style.display = 'block';
        }

        function selectItem(result) {
            const searchBox = result.closest('.search-box');
            const row = result.closest('.item-row');
            const searchInput = searchBox.querySelector('.item-search');
            const hiddenInput = searchBox.querySelector('.item-id');
            const selectedItem = searchBox.querySelector('.selected-item');
            const item = rentalItems.find((rentalItem) => String(rentalItem.id) === result.dataset.id);
            const options = row.querySelector('.item-options');
            const sizeSelect = row.querySelector('.item-size');
            const colorSelect = row.querySelector('.item-color');

            hiddenInput.value = result.dataset.id;
            searchInput.value = '';
            searchInput.hidden = true;
            searchBox.querySelector('.selected-item-name').textContent = result.dataset.name;
            selectedItem.hidden = false;
            searchBox.querySelector('.search-results').style.display = 'none';
            row.style.zIndex = '1';

            sizeSelect.innerHTML = '<option value="">Select size</option>';
            Object.keys(item.sizes || {}).forEach((size) => {
                sizeSelect.insertAdjacentHTML('beforeend', `<option value="${escapeHtml(size)}">${escapeHtml(size)} (${item.sizes[size]} available)</option>`);
            });

            colorSelect.innerHTML = '<option value="">Select color</option>';
            (item.colors || []).forEach((color) => {
                colorSelect.insertAdjacentHTML('beforeend', `<option value="${escapeHtml(color)}">${escapeHtml(color)}</option>`);
            });

            options.hidden = !(Object.keys(item.sizes || {}).length || (item.colors || []).length);
        }

        function clearItem(button) {
            const searchBox = button.closest('.search-box');
            const row = button.closest('.item-row');

            searchBox.querySelector('.item-id').value = '';
            searchBox.querySelector('.item-search').hidden = false;
            searchBox.querySelector('.item-search').focus();
            searchBox.querySelector('.selected-item').hidden = true;
            row.querySelector('.item-options').hidden = true;
            row.querySelector('.item-size').value = '';
            row.querySelector('.item-color').value = '';
        }

        document.addEventListener('input', (event) => {
            if (event.target.matches('.item-search')) {
                showResults(event.target);
            }
        });

        document.addEventListener('click', (event) => {
            const result = event.target.closest('.search-result');

            if (result) {
                selectItem(result);
                return;
            }

            document.querySelectorAll('.search-results').forEach((results) => {
                if (!results.closest('.search-box').contains(event.target)) {
                    results.style.display = 'none';
                    results.closest('.item-row').style.zIndex = '1';
                }
            });
        });
    </script>
</x-app-layout>
