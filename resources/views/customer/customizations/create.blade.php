<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <title>Customize Package</title>

    <style>
        body {
            font-family: 'DM Sans', 'Segoe UI', sans-serif;
            background: var(--neo-field);
            margin: 0;
            padding: 30px;
        }

        .container {
            max-width: 950px;
            margin: auto;
            background: var(--neo-bg);
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
        }

        h1 {
            margin-top: 0;
        }

        h2 {
            margin-top: 30px;
        }

        .section-description {
            color: var(--event-muted);
            margin-bottom: 15px;
        }

        .package-info {
            background: var(--neo-field);
            padding: 15px;
            border-radius: 6px;
            margin-bottom: 25px;
        }

        .package-info p {
            margin: 7px 0;
        }

        .item-row {
            display: grid;
            grid-template-columns: 1fr 150px auto;
            gap: 10px;
            align-items: end;
            margin-bottom: 12px;
        }

        .full-width-field {
            grid-column: 1 / -1;
        }

        .package-price-estimate {
            padding: 16px;
            margin: 18px 0;
            border-radius: 10px;
            background: var(--neo-field);
        }

        .package-price-estimate p {
            margin: 6px 0;
        }

        .item-price {
            display: block;
            margin-top: 5px;
            color: var(--event-muted);
            font-size: 13px;
        }

        .new-item-row {
            display: grid;
            grid-template-columns: 1fr 150px auto;
            gap: 10px;
            align-items: start;
            margin-bottom: 18px;
            padding: 15px;
            background: var(--neo-field);
            bordervar(--event-line);
            border-radius: 8px;
        }

        .full-width-field {
            grid-column: 1 / -1;
        }

        .item-field label {
            display: block;
            margin-bottom: 7px;
            font-weight: bold;
        }

        input,
        select,
        textarea {
            width: 100%;
            box-sizing: border-box;
            padding: 10px;
            bordervar(--event-line);
            border-radius: 6px;
            font-size: 14px;
        }

        textarea {
            min-height: 120px;
            resize: vertical;
        }

        .new-item-description {
            min-height: 90px;
        }

        .remove-button {
            background: rgba(237,61,129,0.20);
            color: white;
            border: none;
            padding: 10px 14px;
            border-radius: 6px;
            cursor: pointer;
        }

        .add-button {
            background: rgba(232,201,138,0.14);
            color: white;
            border: none;
            padding: 10px 14px;
            border-radius: 6px;
            cursor: pointer;
            margin-top: 5px;
        }

        .submit-button {
            background: rgba(232,201,138,0.14);
            color: white;
            border: none;
            padding: 12px 18px;
            border-radius: 6px;
            cursor: pointer;
            margin-top: 20px;
        }

        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 20px;
            padding: 9px 13px;
            bordervar(--event-line);
            border-radius: 8px;
            background: var(--neo-field);
            color: var(--event-gold);
            font-weight: bold;
            text-decoration: none;
            transition: background 160ms ease, border-color 160ms ease, transform 160ms ease;
        }

        .back-link:hover {
            border-color: var(--event-gold-dark);
            background: rgba(232,201,138,0.07);
            transform: translateX(-2px);
        }

        .back-link:focus-visible {
            outline: 3px solid rgba(227, 83, 47, 0.25);
            outline-offset: 2px;
        }

        .error-box {
            background: rgba(237,61,129,0.10);
            color: var(--event-danger);
            padding: 12px 16px;
            border-radius: 6px;
            margin-bottom: 20px;
        }

        .success-box {
            background: rgba(159,216,174,0.10);
            color: var(--event-ok);
            padding: 12px 16px;
            border-radius: 6px;
            margin-bottom: 20px;
        }

        .search-box {
            position: relative;
            margin-top: 10px;
        }

        .search-input {
            width: 100%;
            box-sizing: border-box;
            padding: 10px;
            bordervar(--event-line);
            border-radius: 6px;
        }

        .search-results {
            bordervar(--event-line);
            border-top: none;
            background: var(--neo-bg);
            max-height: 220px;
            overflow-y: auto;
            display: none;
        }

        .search-result {
            padding: 10px;
            cursor: pointer;
            bordervar(--event-line);
        }

        .search-result:hover {
            background: var(--neo-field);
        }

        .divider {
            margin: 35px 0;
            border: 0;
            bordervar(--event-line);
        }

        .file-note {
            font-size: 12px;
            color: var(--event-muted);
            margin-top: 5px;
        }

        .new-item-box {
            margin-top: 15px;
        }
    </style>
</head>

<body style="margin-left: 300px;">

    @include('layouts.navigation')

<div class="container">

    <a href="{{ route('customer.packages.show', $package->id) }}" class="back-link">
        ← Back to Package Details
    </a>

    <h1>Customize Package</h1>

    <div class="package-info">

        <p>
            <strong>Package:</strong>
            {{ $package->name }}
        </p>

        <p>
            <strong>Event Type:</strong>
            {{ $package->event_type }}
        </p>

        <p>
            <strong>Base Package Price:</strong>
            ₱{{ number_format($package->price, 2) }}
        </p>

        <p>
            <strong>Note:</strong>
            You are creating a customization request. The original package will not be changed.
        </p>

    </div>

    @if ($errors->any())
        <div class="error-box">
            <strong>Please correct the following:</strong>

            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if (session('success'))
        <div class="success-box">
            {{ session('success') }}
        </div>
    @endif

    <div class="package-info">
        <p><strong>Booking note:</strong> Submitting this form will reserve your selected available items as pending reservations while the admin reviews your request.</p>
    </div>

    @if ($package->packageItems->isNotEmpty() && $rentalItems->isEmpty())
        <div class="error-box" role="alert">
            No rental inventory is currently available to match this package. Please contact the store before submitting a reservation.
        </div>
    @endif

    <form
        action="{{ route('customer.customizations.store', $package->id) }}"
        method="POST"
        enctype="multipart/form-data"
    >

        @csrf

        <h2>Reservation Dates</h2>
        <p class="section-description">Select when you are reserving the package, when the event takes place, and when the items will be returned.</p>

        <div class="item-row">
            <div class="item-field">
                <label for="reservation_date">Reservation Date</label>
                <input type="date" id="reservation_date" name="reservation_date" value="{{ old('reservation_date') }}" min="{{ now()->toDateString() }}" required>
            </div>
            <div class="item-field">
                <label for="event_date">Event Date</label>
                <input type="date" id="event_date" name="event_date" value="{{ old('event_date') }}" required>
            </div>
            <div class="item-field">
                <label for="return_date">Return Date</label>
                <input type="date" id="return_date" name="return_date" value="{{ old('return_date') }}" required>
            </div>
        </div>

        <div class="package-price-estimate" aria-live="polite">
            <p>Package price: <strong>₱{{ number_format($package->price, 2) }}</strong></p>
            <p>Added available items: <strong id="additional-items-total">₱0.00</strong></p>
            <p>Estimated package total: <strong id="package-total-estimate">₱{{ number_format($package->price, 2) }}</strong></p>
            <small>New-item requests are submitted separately and are not included in this estimate.</small>
        </div>

        <!-- EXISTING RENTAL ITEMS -->
        <h2>Customize Package Items</h2>

        <p class="section-description">
            Choose one available rental item for each included package piece. The package must list separate pieces (for example, jacket, trousers, and shirt) as separate slots. Quantities for included pieces are set by the package.
        </p>

        @if ($package->packageItems->isNotEmpty())
            <div class="package-info">
                <strong>Included package pieces:</strong>
                <ul>
                    @foreach ($package->packageItems as $packageItem)
                        <li>{{ $packageItem->item_name ?? $packageItem->rentalItem?->name ?? 'Package piece' }} — {{ $packageItem->quantity }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div id="customization-items">

            @forelse ($package->packageItems as $index => $packageItem)

                <div class="item-row">

                    <div class="item-field">
                        <label for="package-item-{{ $packageItem->id }}">
                            Package piece: {{ $packageItem->item_name ?? $packageItem->rentalItem?->name ?? 'Included item' }}
                        </label>

                        <select
                            id="package-item-{{ $packageItem->id }}"
                            name="items[{{ $index }}][rental_item_id]"
                            required
                        >
                            <option value="">
                                Select Rental Item
                            </option>

                            @foreach($rentalItems as $item)
                                <option
                                    value="{{ $item->id }}"
                                    {{ old('items.' . $index . '.rental_item_id', $packageItem->rental_item_id) == $item->id ? 'selected' : '' }}
                                    data-price="{{ $item->rental_price }}"
                                >
                                    {{ $item->name }} ({{ $item->category->name ?? 'Uncategorized' }}) — ₱{{ number_format($item->rental_price, 2) }}
                                </option>
                            @endforeach

                        </select>
                        <input type="hidden" name="items[{{ $index }}][package_item_id]" value="{{ $packageItem->id }}">
                        <small class="item-price">Included in package price</small>
                    </div>

                    <div class="item-field">
                        <label for="item-size-{{ $packageItem->id }}">Size</label>
                        <input id="item-size-{{ $packageItem->id }}" type="text" name="items[{{ $index }}][size]" value="{{ old('items.' . $index . '.size') }}" placeholder="Your preferred size">
                    </div>

                    <div class="item-field">
                        <label for="item-color-{{ $packageItem->id }}">Color</label>
                        <input id="item-color-{{ $packageItem->id }}" type="text" name="items[{{ $index }}][color]" value="{{ old('items.' . $index . '.color') }}" placeholder="Your preferred color">
                    </div>

                    <div class="item-field">
                        <label>Quantity</label>

                        <input
                            type="number"
                            name="items[{{ $index }}][quantity]"
                            min="1"
                            value="{{ old('items.' . $index . '.quantity', $packageItem->quantity) }}"
                            readonly
                            required
                        >
                    </div>

                </div>

            @empty
                @unless ($package->packageItems->contains(fn ($item) => $item->rental_item_id === null))
                <div class="item-row">

                    <div class="item-field">
                        <label>Rental Item</label>

                        <select
                            name="items[0][rental_item_id]"
                            required
                        >
                            <option value="">
                                Select Rental Item
                            </option>

                            @foreach($rentalItems as $item)
                                <option value="{{ $item->id }}">
                                    {{ $item->name }} — ₱{{ number_format($item->rental_price, 2) }}
                                </option>
                            @endforeach

                        </select>
                    </div>

                    <div class="item-field">
                        <label>Quantity</label>

                        <input
                            type="number"
                            name="items[0][quantity]"
                            min="1"
                            value="1"
                            required
                        >
                    </div>

                    <button
                        type="button"
                        class="remove-button"
                        onclick="removeItem(this)"
                    >
                        Remove
                    </button>

                </div>
                @endunless
            @endforelse

        </div>

        <button
            type="button"
            class="add-button"
            onclick="showItemSearch()"
        >
            + Add Rental Item
        </button>

        <div
            id="item-search-area"
            class="search-box"
            style="display: none;"
        >

            <input
                type="text"
                id="item-search"
                class="search-input"
                placeholder="Search rental items..."
                oninput="searchItems()"
                autocomplete="off"
            >

            <div id="search-results" class="search-results"></div>

        </div>

        <h2>Customization Details</h2>

        <textarea
            name="customization_details"
            placeholder="Describe your overall preferences, event requirements, colors, sizes, styles, or other customization details..."
            required
        >{{ old('customization_details') }}</textarea>

        <label for="booking-notes">Additional booking notes</label>
        <textarea id="booking-notes" name="notes" placeholder="Optional notes for the admin...">{{ old('notes') }}</textarea>

        <br>

        <button
            type="submit"
            class="submit-button"
            {{ $package->packageItems->isNotEmpty() && $rentalItems->isEmpty() ? 'disabled' : '' }}
        >
            Submit Package Reservation
        </button>

    </form>

</div>

<script>
    let itemIndex = {{ $package->packageItems->count() }};

    const rentalItems = @json($rentalItemOptions);
    const packageBasePrice = {{ (float) $package->price }};

    function updatePackageEstimate() {
        let additionalTotal = 0;

        document.querySelectorAll('#customization-items .item-row').forEach(row => {
            if (row.querySelector('input[name*="[package_item_id]"]')) {
                return;
            }

            const rentalItemInput = row.querySelector('input[name*="[rental_item_id]"]');
            const quantityInput = row.querySelector('input[name*="[quantity]"]');
            const selectedItem = rentalItems.find(item => String(item.id) === rentalItemInput?.value);

            if (selectedItem && quantityInput) {
                additionalTotal += selectedItem.rentalPrice * (Number(quantityInput.value) || 0);
            }
        });

        document.getElementById('additional-items-total').textContent =
            '₱' + additionalTotal.toLocaleString('en-PH', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        document.getElementById('package-total-estimate').textContent =
            '₱' + (packageBasePrice + additionalTotal).toLocaleString('en-PH', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    }

    function showItemSearch() {
        const searchArea = document.getElementById('item-search-area');
        const searchInput = document.getElementById('item-search');

        searchArea.style.display = 'block';
        searchInput.focus();

        searchItems();
    }

    function searchItems() {
        const input = document.getElementById('item-search');
        const results = document.getElementById('search-results');

        const searchTerm = input.value.toLowerCase().trim();

        results.innerHTML = '';

        const matches = rentalItems.filter(item =>
            item.name.toLowerCase().includes(searchTerm) ||
            item.category.toLowerCase().includes(searchTerm)
        );

        if (matches.length === 0) {

            results.innerHTML = `
                <div class="search-result">
                    No matching rental items found.
                </div>
            `;

            results.style.display = 'block';

            return;
        }

        matches.forEach(item => {

            const result = document.createElement('div');

            result.className = 'search-result';

            result.innerHTML = `
                <strong>${escapeHtml(item.name)}</strong>
                <br>
                <small>${escapeHtml(item.category)} · ₱${item.rentalPrice.toFixed(2)} per item</small>
            `;

            result.onclick = function () {
                addItem(item);
            };

            results.appendChild(result);
        });

        results.style.display = 'block';
    }

    function addItem(item) {

        const container = document.getElementById('customization-items');

        const row = document.createElement('div');

        row.className = 'item-row';

        row.innerHTML = `
            <div class="item-field">
                <label>Rental Item</label>

                <input
                    type="hidden"
                    name="items[${itemIndex}][rental_item_id]"
                    value="${item.id}"
                >

                <input
                    type="text"
                    value="${escapeHtml(item.name)}"
                    readonly
                >
            </div>

            <div class="item-field">
                <label>Quantity</label>

                <input
                    type="number"
                    name="items[${itemIndex}][quantity]"
                    min="1"
                    value="1"
                    required
                >
                <small class="item-price">Added item: ₱${item.rentalPrice.toFixed(2)} each</small>
            </div>

            <div class="item-field">
                <label>Size</label>
                <input type="text" name="items[${itemIndex}][size]" placeholder="Preferred size">
            </div>

            <div class="item-field">
                <label>Color</label>
                <input type="text" name="items[${itemIndex}][color]" placeholder="Preferred color">
            </div>

            <button
                type="button"
                class="remove-button"
                onclick="removeItem(this)"
            >
                Remove
            </button>
        `;

        container.appendChild(row);

        itemIndex++;

        document.getElementById('item-search').value = '';
        document.getElementById('search-results').style.display = 'none';
        document.getElementById('item-search-area').style.display = 'none';
        container.querySelectorAll('.item-row input[name*="[quantity]"]').forEach(input => {
            input.addEventListener('input', updatePackageEstimate);
        });
        updatePackageEstimate();
    }

    function removeItem(button) {

        const rows = document.querySelectorAll('#customization-items .item-row');

        if (rows.length > 1) {

            button.closest('.item-row').remove();

        } else {

            alert('At least one existing rental item is required.');

        }

        updatePackageEstimate();
    }

    function escapeHtml(value) {

        const div = document.createElement('div');

        div.textContent = value;

        return div.innerHTML;
    }

    document.getElementById('customization-items').addEventListener('input', updatePackageEstimate);
    document.getElementById('customization-items').addEventListener('change', updatePackageEstimate);
    updatePackageEstimate();
</script>

</body>
</html>