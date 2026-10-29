<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Return Schedule Reminder</title>
</head>

<body style="margin:0;padding:0;background:#fffaf7">
<div style="font-family:'DM Sans',Arial,sans-serif;line-height:1.55;color:#241321;max-width:600px;margin:0 auto;padding:24px 0">
    <div style="background:linear-gradient(135deg,#2d1725,#4a1d37 60%,#701d46);padding:22px 24px;border-radius:12px 12px 0 0;text-align:center">
        <img src="{{ asset('images/maribel-gowns-boutique.png') }}" width="100" height="100" alt="Maribel Gowns Boutique" style="display:block;width:100px;height:100px;margin:0 auto;border-radius:50%;object-fit:contain;background:#fff">
    </div>
    <div style="background:#fff;padding:26px 28px;border-radius:0 0 12px 12px;border:1px solid #f0dce4;border-top:none">
    <h2 style="margin:0 0 16px;font-family:Georgia,serif;font-size:22px;color:#241321;font-weight:600">Return Schedule Reminder</h2>

    <p>
        Hello {{ $reservation->customer->name ?? 'Customer' }},
    </p>

    <p>
        This is a reminder about the scheduled return of your rented item.
    </p>

    <p>
        <strong>Rental Item:</strong>
        {{ $reservation->rentalItem->name ?? 'Unknown Item' }}
    </p>

    <p>
        <strong>Quantity:</strong>
        {{ $reservation->quantity ?? 1 }}
    </p>

    <p>
        <strong>Event Date:</strong>
        {{ $reservation->event_date?->format('M d, Y') ?? 'Not set' }}
    </p>

    <p>
        <strong>Return Date:</strong>
        {{ $reservation->return_date?->format('M d, Y') ?? 'Not set' }}
    </p>

    <p>
        Please return the rented item on or before the scheduled return date.
    </p>

    <p>
        Thank you!
    </p>
    </div>
    <p style="text-align:center;font-size:12px;color:#a08a96;margin:16px 0 0">Maribel Gowns Boutique</p>
</div>
</body>
</html>