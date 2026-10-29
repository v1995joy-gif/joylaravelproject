<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <title>Edit Package</title>

    <style>
        body {
            font-family: 'DM Sans', 'Segoe UI', sans-serif;
            background: var(--neo-field);
            margin: 0;
            padding: 30px;
        }

        .container {
            max-width: 900px;
            margin: auto;
            background: var(--neo-bg);
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
        }

        h1 {
            margin-top: 0;
        }

        .form-group {
            margin-bottom: 18px;
        }

        label {
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
            min-height: 100px;
            resize: vertical;
        }

        .package-item {
            display: grid;
            grid-template-columns: 1fr 150px auto;
            gap: 10px;
            align-items: end;
            margin-bottom: 12px;
        }

        .item-field label {
            margin-bottom: 7px;
        }

        .remove-item {
            background: rgba(237,61,129,0.20);
            color: white;
            border: none;
            padding: 10px 14px;
            border-radius: 6px;
            cursor: pointer;
        }

        .add-item {
            background: rgba(232,201,138,0.14);
            color: white;
            border: none;
            padding: 10px 14px;
            border-radius: 6px;
            cursor: pointer;
            margin-bottom: 20px;
        }

        .save-button {
            background: rgba(232,201,138,0.14);
            color: white;
            border: none;
            padding: 11px 18px;
            border-radius: 6px;
            cursor: pointer;
        }

        .back-link {
            display: inline-block;
            margin-bottom: 20px;
            text-decoration: none;
            color: var(--event-ink);
        }

        .error-box {
            background: rgba(237,61,129,0.10);
            color: var(--event-danger);
            padding: 12px 16px;
            border-radius: 6px;
            margin-bottom: 20px;
        }
    </style>
</head>

<body style="margin-left: 300px;">

    @include('layouts.navigation')

<div class="container">

    <a href="{{ route('admin.packages.index') }}" class="back-link">
        ← Back to Package Management
    </a>

    <h1>Edit Package</h1>

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

    <form action="{{ route('admin.packages.update', $package->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PATCH')

        <div class="form-group">
            <label for="name">Package Name</label>
            <input
                type="text"
                id="name"
                name="name"
                value="{{ old('name', $package->name) }}"
                required
            >
        </div>

        <div class="form-group">
            <label for="event_type">Event Type</label>
            <input
                type="text"
                id="event_type"
                name="event_type"
                value="{{ old('event_type', $package->event_type) }}"
                required
            >
        </div>

        <div class="form-group">
            <label for="price">Package Price</label>
            <input
                type="number"
                id="price"
                name="price"
                value="{{ old('price', $package->price) }}"
                min="0"
                step="0.01"
                required
            >
        </div>

        <div class="form-group">
            <label for="description">Description</label>
            <textarea
                id="description"
                name="description"
                placeholder="Describe what this package includes..."
            >{{ old('description', $package->description) }}</textarea>
        </div>

        <div class="form-group">
            <label for="image">Package Image</label>
            @if ($package->image)
                <img id="image-preview" src="{{ asset('storage/' . $package->image) }}" alt="{{ $package->name }}" style="display:block; width:160px; height:100px; object-fit:cover; margin-bottom:10px;">
            @else
                <img id="image-preview" src="#" alt="Selected package image preview" style="display:none; width:160px; height:100px; object-fit:cover; margin-bottom:10px;">
            @endif
            <input type="file" id="image" name="image" accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp">
        </div>

        <div class="form-group">
            <label for="status">Status</label>
            <select id="status" name="status" required>
                <option value="Active"
                    {{ old('status', $package->status) === 'Active' ? 'selected' : '' }}>
                    Active
                </option>

                <option value="Inactive"
                    {{ old('status', $package->status) === 'Inactive' ? 'selected' : '' }}>
                    Inactive
                </option>
            </select>
        </div>

        <hr>

        <h2>Included Package Pieces</h2>
        <p>Use one row for each included piece customers must choose. Split multi-piece outfits into separate rows (for example, “Groom jacket,” “Groom trousers,” and “Groom shirt”) rather than grouping them as one outfit. The quantity applies to that piece.</p>

        <div id="package-items">

            @forelse ($package->packageItems as $index => $packageItem)

                <div class="package-item">

                    @if ($packageItem->rental_item_id)
                        <div class="item-field">
                            <label>Rental Item</label>
                            <select name="items[{{ $index }}][rental_item_id]" required>
                                <option value="">Select Rental Item</option>
                                @foreach ($rentalItems as $item)
                                    <option value="{{ $item->id }}" {{ $packageItem->rental_item_id == $item->id ? 'selected' : '' }}>
                                        {{ $item->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    @else
                        <div class="item-field">
                            <label>Included Piece / Slot</label>
                            <input
                                type="text"
                                name="items[{{ $index }}][item_name]"
                                value="{{ old('items.' . $index . '.item_name', $packageItem->item_name) }}"
                                placeholder="Example: Groom jacket"
                                maxlength="255"
                                required
                            >
                        </div>
                    @endif

                    <div class="item-field">
                        <label>Quantity</label>

                        <input
                            type="number"
                            name="items[{{ $index }}][quantity]"
                            min="1"
                            value="{{ old('items.' . $index . '.quantity', $packageItem->quantity) }}"
                            required
                        >
                    </div>

                    <button
                        type="button"
                        class="remove-item"
                        onclick="removeItem(this)"
                    >
                        Remove
                    </button>

                </div>

            @empty

                <div class="package-item">

                    <div class="item-field">
                        <label>Included Piece / Slot</label>
                        <input type="text" name="items[0][item_name]" placeholder="Example: Groom jacket" maxlength="255" required>
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
                        class="remove-item"
                        onclick="removeItem(this)"
                    >
                        Remove
                    </button>

                </div>

            @endforelse

        </div>

        <button
            type="button"
            class="add-item"
            onclick="addItem()"
        >
            + Add Included Piece
        </button>

        <br>

        <button type="submit" class="save-button">
            Update Package
        </button>

    </form>

</div>

<script>
    document.getElementById('image').addEventListener('change', function (event) {
        const preview = document.getElementById('image-preview');
        const file = event.target.files[0];

        if (!file) {
            return;
        }

        preview.src = URL.createObjectURL(file);
        preview.style.display = 'block';
    });

    let itemIndex = {{ $package->packageItems->count() }};

    function addItem() {
        const container = document.getElementById('package-items');

        const row = document.createElement('div');
        row.className = 'package-item';

        row.innerHTML = `
            <div class="item-field">
                <label>Included Piece / Slot</label>
                <input type="text" name="items[${itemIndex}][item_name]" placeholder="Example: Groom jacket" maxlength="255" required>
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
            </div>

            <button
                type="button"
                class="remove-item"
                onclick="removeItem(this)"
            >
                Remove
            </button>
        `;

        container.appendChild(row);

        itemIndex++;
    }

    function removeItem(button) {
        const row = button.closest('.package-item');
        const rows = document.querySelectorAll('.package-item');

        if (rows.length > 1) {
            row.remove();
        } else {
            alert('A package must contain at least one rental item.');
        }
    }
</script>

</body>
</html>