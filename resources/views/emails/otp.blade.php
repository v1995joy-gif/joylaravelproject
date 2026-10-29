<!DOCTYPE html>
<html lang="en">
<body style="margin:0;padding:0;background:#fffaf7">
<div style="font-family:'DM Sans',Arial,Helvetica,sans-serif;line-height:1.5;color:#241321;max-width:600px;margin:0 auto;padding:24px 0">
    <div style="background:linear-gradient(135deg,#2d1725,#4a1d37 60%,#701d46);padding:30px 24px;border-radius:12px 12px 0 0;text-align:center">
        <img src="{{ asset('images/maribel-gowns-boutique.png') }}" width="100" height="100" alt="Maribel Gowns Boutique" style="display:block;width:100px;height:100px;margin:0 auto 12px;border-radius:50%;object-fit:contain;background:#fff">
        <h1 style="margin:0;color:#fff;font-size:24px;font-weight:600;font-family:Georgia,'Times New Roman',serif">{{ $heading }}</h1>
    </div>
    <div style="background:#fff;padding:30px 28px;border-radius:0 0 12px 12px;border:1px solid #f0dce4;border-top:none">
        <p style="margin:0 0 14px 0;font-size:15px;color:#4a3b45">Use this code to {{ $action }}:</p>
        <div style="text-align:center;margin:24px 0">
            <div style="display:inline-block;padding:16px 28px;border-radius:10px;background:#fffaf7;border:1px solid #c9a35f;font-size:28px;letter-spacing:9px;font-weight:700;color:#4a1d37">{{ $code }}</div>
        </div>
        <div style="background:#fdf4f8;padding:14px;border-radius:8px;margin:18px 0;border:1px solid #f0dce4">
            <p style="margin:0;font-size:13px;color:#795b68"><strong>Expires:</strong> {{ $expiresMinutes }} {{ $expiresMinutes === 1 ? 'minute' : 'minutes' }}</p>
        </div>
        <p style="margin:22px 0 0 0;font-size:12px;color:#a08a96">If you did not request this code, you can ignore this email.</p>
    </div>
</div>
</body>
</html>
