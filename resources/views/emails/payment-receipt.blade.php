<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Payment Receipt</title>
</head>
<body style="margin:0;padding:0;background:#fffaf7">
<div style="font-family:'DM Sans',Arial,sans-serif;line-height:1.55;color:#241321;max-width:600px;margin:0 auto;padding:24px 0">
    <div style="background:linear-gradient(135deg,#2d1725,#4a1d37 60%,#701d46);padding:22px 24px;border-radius:12px 12px 0 0;text-align:center">
        <img src="{{ asset('images/maribel-gowns-boutique.png') }}" width="100" height="100" alt="Maribel Gowns Boutique" style="display:block;width:100px;height:100px;margin:0 auto;border-radius:50%;object-fit:contain;background:#fff">
    </div>
    <div style="background:#fff;padding:26px 28px;border-radius:0 0 12px 12px;border:1px solid #f0dce4;border-top:none">
    <h2 style="margin:0 0 16px;font-family:Georgia,serif;font-size:22px;color:#241321;font-weight:600">Payment Receipt</h2>

    <p>Hello {{ $payment->reservation->customer->name ?? 'Customer' }},</p>

    <p>Your payment has been confirmed. Please keep this receipt for your records.</p>

    <p><strong>Receipt No.:</strong> PAY-{{ str_pad($payment->id, 5, '0', STR_PAD_LEFT) }}</p>
    <p><strong>Rental Item:</strong> {{ $payment->reservation->rentalItem->name ?? 'Unknown Item' }}</p>
    <p><strong>Event Date:</strong> {{ $payment->reservation->event_date?->format('M d, Y') ?? 'Not set' }}</p>
    <p><strong>Amount Paid:</strong> ₱{{ number_format((float) $payment->amount, 2) }}</p>
    <p><strong>Total Due:</strong> ₱{{ number_format($totalDue, 2) }}</p>
    <p><strong>Total Confirmed Paid:</strong> ₱{{ number_format($totalPaid, 2) }}</p>
    <p><strong>Remaining Balance:</strong> ₱{{ number_format($balance, 2) }}</p>
    <p><strong>Payment Method:</strong> {{ $payment->payment_method }}</p>
    <p><strong>Payment Date:</strong> {{ $payment->payment_date?->format('M d, Y') ?? 'Not set' }}</p>
    <p><strong>Reference Number:</strong> {{ $payment->reference_number ?? 'None' }}</p>
    <p><strong>Status:</strong> {{ $payment->status }}</p>

    <p>Thank you!</p>
    </div>
    <p style="text-align:center;font-size:12px;color:#a08a96;margin:16px 0 0">Maribel Gowns Boutique</p>
</div>
</body>
</html>