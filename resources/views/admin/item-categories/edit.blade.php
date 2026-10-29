<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <title>Edit Item Category</title>

    <style>
        body {
            font-family: 'DM Sans', 'Segoe UI', sans-serif;
            margin: 40px;
            background: var(--neo-field);
        }

        .container {
            background: var(--neo-bg);
            padding: 25px;
            border-radius: 10px;
            max-width: 600px;
        }

        h1 {
            margin-bottom: 20px;
        }

        input, textarea {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            box-sizing: border-box;
        }

        button {
            padding: 10px 18px;
            cursor: pointer;
        }

        .error {
            margin-bottom: 15px;
        }
    </style>
</head>

<body style="margin-left: 300px;">

    @include('layouts.navigation')

    <div class="container">

        <h1>Edit Item Category</h1>

        @if ($errors->any())
            <div class="error">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.item-categories.update', $category->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PATCH')

            <div>
                <label for="name">Category Name:</label>
                <input
                    type="text"
                    id="name"
                    name="name"
                    value="{{ old('name', $category->name) }}"
                    required
                >
            </div>

            <br>

            <div>
                <label for="description">Description:</label>
                <textarea
                    id="description"
                    name="description"
                    rows="4"
                >{{ old('description', $category->description) }}</textarea>
            </div>

            <br>

            <div>
                <label for="image">Category Image:</label>
                <input
                    type="file"
                    id="image"
                    name="image"
                    accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp"
                >
                <small>Optional. JPG, JPEG, PNG, WEBP. Maximum size: 5 MB.</small>

                @if ($category->image)
                    <div>
                        <p>Current image:</p>
                        <img
                            src="{{ asset('storage/' . $category->image) }}"
                            alt="{{ $category->name }}"
                            width="120"
                            height="120"
                            style="object-fit: cover; border-radius: 6px;"
                        >
                    </div>
                @endif
            </div>

            <br>

            <button type="submit">Update Category</button>
        </form>

        <br>

        <a href="{{ route('admin.item-categories.index') }}">
            Back to Categories
        </a>

    </div>

</body>
</html>