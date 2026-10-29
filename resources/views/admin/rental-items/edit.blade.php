<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <title>Edit Rental Item</title>

    <style>
        body {
            font-family: 'DM Sans', 'Segoe UI', sans-serif;
            background: var(--neo-field);
            margin: 0;
            padding: 30px;
        }

        .container {
            max-width: 700px;
            margin: auto;
            background: var(--neo-bg);
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
        }

        h1 {
            margin-top: 0;
            margin-bottom: 25px;
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
        textarea,
        select {
            width: 100%;
            padding: 10px;
            bordervar(--event-line);
            border-radius: 6px;
            box-sizing: border-box;
            font-size: 14px;
        }

        textarea {
            min-height: 100px;
            resize: vertical;
        }

        .buttons {
            margin-top: 25px;
        }

        button,
        .back-button {
            padding: 10px 16px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            text-decoration: none;
            font-size: 14px;
        }

        button {
            background: rgba(232,201,138,0.14);
            color: white;
        }

        .back-button {
            background: var(--neo-field);
            color: var(--event-ink);
            margin-left: 8px;
        }

        .error {
            background: rgba(237,61,129,0.10);
            color: var(--event-danger);
            padding: 12px;
            border-radius: 6px;
            margin-bottom: 20px;
        }
    </style>
</head>

<body style="margin-left: 300px;">

    @include('layouts.navigation')

<div class="container">

    <h1>Edit Rental Item</h1>

    @if($errors->any())
        <div class="error">
            <strong>Please fix the following:</strong>

            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.rental-items.update', $item->id) }}" method="POST" enctype="multipart/form-data">

        @csrf
        @method('PATCH')

        <div class="form-group">
            <label for="item_code">Item Code</label>

            <input
                type="text"
                id="item_code"
                name="item_code"
                value="{{ old('item_code', $item->item_code) }}"
                required
            >
        </div>

        <div class="form-group">
            <label for="name">Item Name</label>

            <input
                type="text"
                id="name"
                name="name"
                value="{{ old('name', $item->name) }}"
                required
            >
        </div>

        <div class="form-group">
            <label for="description">Description</label>

            <textarea
                id="description"
                name="description"
            >{{ old('description', $item->description) }}</textarea>
        </div>

        <div class="form-group">
            <label for="image">Item Image</label>
            @if ($item->image)
                <img
                    src="{{ asset('storage/' . $item->image) }}"
                    alt="{{ $item->name }}"
                    width="120"
                    height="120"
                    style="display:block; object-fit:cover; border-radius:8px; margin-bottom:10px;"
                >
            @endif
            <input
                type="file"
                id="image"
                name="image"
                accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp"
            >
            <small>Optional. Upload a new image to replace the current one.</small>
        </div>

        <div class="form-group">
            <label for="category_id">Category</label>

            <select id="category_id" name="category_id" required>

                <option value="">-- Select Category --</option>

                @foreach($categories as $category)

                    <option
                        value="{{ $category->id }}"
                        {{ old('category_id', $item->category_id) == $category->id ? 'selected' : '' }}
                    >
                        {{ $category->name }}
                    </option>

                @endforeach

            </select>
        </div>

        <div class="form-group">
            <label for="rental_price">Rental Price</label>

            <input
                type="number"
                id="rental_price"
                name="rental_price"
                value="{{ old('rental_price', $item->rental_price) }}"
                step="0.01"
                min="0"
                required
            >
        </div>

        <div class="form-group">
            <label for="quantity">Quantity</label>

            <input
                type="number"
                id="quantity"
                name="quantity"
                value="{{ old('quantity', $item->quantity) }}"
                min="1"
                required
            >
        </div>

        <div class="form-group">
            <label>Quantity by Size</label>
            <small>Add sizes such as Small, Medium, or Large. The total quantity is calculated from these values.</small>
            <div id="size-rows">
                @forelse (old('size_quantities', $item->size_quantities ?? []) as $size => $sizeQuantity)
                    <div class="size-row">
                        <input type="text" name="size_names[]" value="{{ $size }}" placeholder="Size (e.g. Small)" data-size-name>
                        <input type="number" name="size_quantities[]" value="{{ $sizeQuantity }}" min="0" placeholder="Quantity" data-size-quantity>
                        <button type="button" onclick="this.parentElement.remove()">Remove</button>
                    </div>
                @empty
                    <div class="size-row">
                        <input type="text" name="size_names[]" placeholder="Size (e.g. Small)" data-size-name>
                        <input type="number" name="size_quantities[]" value="0" min="0" placeholder="Quantity" data-size-quantity>
                    </div>
                @endforelse
            </div>
            <button type="button" onclick="addSizeRow()">+ Add Size</button>
        </div>

        <div class="form-group">
            <label for="colors">Available Colors</label>
            <textarea id="colors" name="colors" placeholder="White, Black, Red">{{ old('colors', implode(', ', $item->colors ?? [])) }}</textarea>
            <small>Separate colors with commas.</small>
        </div>

        <div class="form-group">
            <label for="status">Status</label>

            <select id="status" name="status" required>

                <option value="Available"
                    {{ old('status', $item->status) == 'Available' ? 'selected' : '' }}>
                    Available
                </option>

                <option value="Rented"
                    {{ old('status', $item->status) == 'Rented' ? 'selected' : '' }}>
                    Rented
                </option>

                <option value="Reserved"
                    {{ old('status', $item->status) == 'Reserved' ? 'selected' : '' }}>
                    Reserved
                </option>

                <option value="Maintenance"
                    {{ old('status', $item->status) == 'Maintenance' ? 'selected' : '' }}>
                    Maintenance
                </option>

            </select>
        </div>

        <div class="buttons">

            <button type="submit">
                Update Rental Item
            </button>

            <a
                href="{{ route('admin.rental-items.show', $item->id) }}"
                class="back-button"
                title="Back to item details"
                aria-label="Back to item details"
                style="align-items:center; display:inline-flex; height:40px; justify-content:center; margin-left:8px; padding:0; width:40px;"
            >
                <x-lucide-arrow-left width="18" height="18" aria-hidden="true" />
            </a>

        </div>

    </form>

</div>

<script>
    function addSizeRow() {
        const row = document.createElement('div');
        row.className = 'size-row';
        row.innerHTML = '<input type="text" name="size_names[]" placeholder="Size (e.g. Medium)" data-size-name><input type="number" name="size_quantities[]" value="0" min="0" placeholder="Quantity" data-size-quantity><button type="button" onclick="this.parentElement.remove()">Remove</button>';
        document.getElementById('size-rows').appendChild(row);
    }

</script>

</body>
</html>