<!DOCTYPE html>
<html>
<body style="font-family:Arial,sans-serif;color:#0B1D4D;line-height:1.6;">
    <h2>Welcome to Horizon Lab, {{ $user->name }}!</h2>
    <p>Your {{ $accountType }} account has been created successfully with this email address: <strong>{{ $user->email }}</strong>.</p>
    @if ($user->role === 'engineer')
        <p>Your account is pending approval by an administrator. You will be able to receive assignments once it has been approved.</p>
    @endif
    <p><a href="{{ $loginUrl }}" style="display:inline-block;padding:10px 18px;background:#1F62F0;color:#fff;text-decoration:none;border-radius:6px;">Sign in</a></p>
    <p>If you did not create this account, please contact us immediately.</p>
    <p>Horizon Lab Biomedical Engineering</p>
</body>
</html>
