<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <title>Edit Customization Request Status</title>

    <style>
        body {
            font-family: 'DM Sans', 'Segoe UI', sans-serif;
            background: var(--neo-field);
            margin: 0;
            padding: 30px;
        }

        .container {
            max-width: 650px;
            margin: auto;
            background: var(--neo-bg);
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
        }

        .back-link {
            display: inline-block;
            margin-bottom: 20px;
            text-decoration: none;
            color: var(--event-ink);
        }

        h1 {
            margin-top: 0;
        }

        .info-box {
            background: var(--neo-field);
            padding: 18px;
            border-radius: 8px;
            margin-bottom: 25px;
        }

        .info-box p {
            margin: 8px 0;
        }

        .field {
            margin-bottom: 20px;
        }

        .field label {
            display: block;
            margin-bottom: 7px;
            font-weight: bold;
        }

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
            min-height: 110px;
            resize: vertical;
        }

        .button-row {
            display: flex;
            gap: 10px;
        }

        .save-button {
            background: rgba(232,201,138,0.14);
            color: white;
            border: none;
            padding: 11px 16px;
            border-radius: 6px;
            cursor: pointer;
        }

        .cancel-button {
            background: var(--neo-field);
            color: var(--event-ink);
            border: none;
            padding: 11px 16px;
            border-radius: 6px;
            text-decoration: none;
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

    <a
        href="{{ route('admin.customization-requests.show', $customizationRequest->id) }}"
        class="back-link"
    >
        ← Back to Request Details
    </a>

    <h1>Edit Customization Request Status</h1>

    <div class="info-box">

        <p>
            <strong>Request #:</strong>
            {{ $customizationRequest->id }}
        </p>

        <p>
            <strong>Customer:</strong>
            {{ $customizationRequest->customer->name ?? 'Unknown Customer' }}
        </p>

        <p>
            <strong>Package:</strong>
            {{ $customizationRequest->package->name ?? ($customizationRequest->request_type === 'New Item Request' ? 'New Item Request' : 'Custom Set') }}
        </p>

        <p>
            <strong>Current Status:</strong>
            {{ $customizationRequest->status }}
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

    <form
        action="{{ route('admin.customization-requests.status.correction', $customizationRequest->id) }}"
        method="POST"
    >

        @csrf
        @method('PATCH')

        <div class="field">

            <label for="status">
                Correct Status
            </label>

            <select
                id="status"
                name="status"
                required
            >
                <option
                    value="Pending"
                    {{ old('status', $customizationRequest->status) === 'Pending' ? 'selected' : '' }}
                >
                    Pending
                </option>

                <option
                    value="Confirmed"
                    {{ old('status', $customizationRequest->status) === 'Confirmed' ? 'selected' : '' }}
                >
                    Confirmed
                </option>

                <option
                    value="Completed"
                    {{ old('status', $customizationRequest->status) === 'Completed' ? 'selected' : '' }}
                >
                    Completed
                </option>

                <option
                    value="Cancelled"
                    {{ old('status', $customizationRequest->status) === 'Cancelled' ? 'selected' : '' }}
                >
                    Cancelled
                </option>
            </select>

        </div>

        <div class="field">

            <label for="correction_reason">
                Reason for Correction (Optional)
            </label>

            <textarea
                id="correction_reason"
                name="correction_reason"
                placeholder="Example: The request was accidentally confirmed."
            >{{ old('correction_reason') }}</textarea>

        </div>

        <div class="button-row">

            <button
                type="submit"
                class="save-button"
            >
                Save Correction
            </button>

            <a
                href="{{ route('admin.customization-requests.show', $customizationRequest->id) }}"
                class="cancel-button"
            >
                Cancel
            </a>

        </div>

    </form>

</div>

</body>
</html>