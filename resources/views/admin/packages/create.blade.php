<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <title>Add Package</title>

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
            grid-template-columns: minmax(220px, 1fr) 150px auto;
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

    <h1>Add Package</h1>

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

    <form action="{{ route('admin.packages.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="form-group">
            <label for="name">Package Name</label>
            <input
                type="text"
                id="name"
                name="name"
                value="{{ old('name') }}"
                placeholder="Example: Wedding Entourage Package"
                required
            >
        </div>

        <div class="form-group">
            <label for="event_type">Event Type</label>
            <input
                type="text"
                id="event_type"
                name="event_type"
                value="{{ old('event_type') }}"
                placeholder="Example: Wedding"
                required
            >
        </div>

        <div class="form-group">
            <label for="price">Package Price</label>
            <input
                type="number"
                id="price"
                name="price"
                value="{{ old('price') }}"
                min="0"
                step="0.01"
                placeholder="20000.00"
                required
            >
        </div>

        <div class="form-group">
            <label for="description">Description</label>
            <textarea
                id="description"
                name="description"
                placeholder="Describe what this package includes..."
            >{{ old('description') }}</textarea>
        </div>

        <div class="form-group">
            <label for="image">Package Image</label>
            <input type="file" id="image" name="image" accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp">
            <img id="image-preview" src="#" alt="Selected package image preview" style="display:none; width:160px; height:100px; object-fit:cover; margin-top:10px;">
        </div>

        <div class="form-group">
            <label for="status">Status</label>
            <select id="status" name="status" required>
                <option value="Active"
                    {{ old('status', 'Active') === 'Active' ? 'selected' : '' }}>
                    Active
                </option>

                <option value="Inactive"
                    {{ old('status') === 'Inactive' ? 'selected' : '' }}>
                    Inactive
                </option>
            </select>
        </div>

        <hr>

        <h2>Included Package Pieces</h2>
        <p>Add one row for every included piece that the customer must choose. For a multi-piece outfit, create separate rows such as “Groom jacket,” “Groom trousers,” and “Groom shirt”—do not combine the outfit into one row. The quantity applies to that piece.</p>

        <div id="package-items">
            @foreach (old('items', [[]]) as $index => $oldItem)
                <div class="package-item">

                <div class="item-field">
                    <label for="package-item-name-{{ $index }}">Included Piece / Slot</label>
                    <input
                        type="text"
                        id="package-item-name-{{ $index }}"
                        name="items[{{ $index }}][item_name]"
                        value="{{ $oldItem['item_name'] ?? '' }}"
                        placeholder="Example: Groom jacket"
                        maxlength="255"
                        required
                    >
                </div>

                <div class="item-field">
                    <label>Quantity</label>

                    <input
                        type="number"
                        name="items[{{ $index }}][quantity]"
                        min="1"
                        value="{{ $oldItem['quantity'] ?? 1 }}"
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
            @endforeach
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
            Save Package
        </button>

    </form>

</div>

<script>
    document.getElementById('image').addEventListener('change', function (event) {
        const preview = document.getElementById('image-preview');
        const file = event.target.files[0];

        if (!file) {
            preview.style.display = 'none';
            preview.removeAttribute('src');
            return;
        }

        preview.src = URL.createObjectURL(file);
        preview.style.display = 'block';
    });

    let itemIndex = {{ count(old('items', [[]])) }};

    function addItem() {
        const container = document.getElementById('package-items');

        const row = document.createElement('div');
        row.className = 'package-item';

        row.innerHTML = `
            <div class="item-field">
                <label for="package-item-name-${itemIndex}">Included Piece / Slot</label>
                <input
                    type="text"
                    id="package-item-name-${itemIndex}"
                    name="items[${itemIndex}][item_name]"
                    placeholder="Example: Groom jacket"
                    maxlength="255"
                    required
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
            alert('A package must contain at least one item type.');
        }
    }
</script>

</body>
</html>