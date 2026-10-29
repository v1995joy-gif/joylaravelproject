<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Add Item Category') }}
        </h2>
    </x-slot>

    <div class="container category-page">
        <div class="category-page-heading">
            <div>
                <p class="page-kicker">Inventory setup</p>
                <h1>Add Category</h1>
                <p class="page-description">Create a category to organize your rental items.</p>
            </div>
            <a href="{{ route('admin.item-categories.index') }}" class="category-action category-action-edit">
                Back to Categories
            </a>
        </div>

        <section class="category-form-panel">
            <div class="category-section-heading">
                <div>
                    <p class="page-kicker">New record</p>
                    <h2>Category Details</h2>
                </div>
            </div>

            @if ($errors->any())
                <div class="error category-error" role="alert">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('admin.item-categories.store') }}" method="POST" enctype="multipart/form-data" class="category-form">
                @csrf

                <div class="category-form-field category-form-field-wide">
                    <label for="image">Category Image</label>
                    <input
                        type="file"
                        id="image"
                        name="image"
                        accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp"
                    >
                    <small class="category-help">Optional. JPG, JPEG, PNG, or WEBP. Maximum size: 5 MB.</small>
                </div>

                <div class="category-form-field">
                    <label for="name">Category Name</label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}" required>
                </div>

                <div class="category-form-field category-form-field-wide">
                    <label for="description">Description</label>
                    <textarea id="description" name="description" rows="4">{{ old('description') }}</textarea>
                </div>

                <div class="category-form-field category-form-field-wide flex flex-wrap items-center gap-3">
                    <button type="submit" class="category-submit">Add Category</button>
                    <a href="{{ route('admin.item-categories.index') }}" class="category-action category-action-edit">
                        Cancel
                    </a>
                </div>
            </form>
        </section>
    </div>
</x-app-layout>
