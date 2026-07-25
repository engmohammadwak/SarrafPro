<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تسجيل دخول المحل - صراف برو</title>
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        * { margin:0; padding:0; box-sizing:border-box; }
        body { font-family:'Tajawal',sans-serif; background:linear-gradient(135deg,#1a1f3c 0%,#12172b 100%); min-height:100vh; display:flex; align-items:center; justify-content:center; padding:20px; }
        .login-box { background:#fff; border-radius:20px; padding:48px 40px; width:100%; max-width:420px; box-shadow:0 20px 60px rgba(0,0,0,0.3); }
        .logo { text-align:center; margin-bottom:32px; }
        .logo-icon { width:64px; height:64px; background:linear-gradient(135deg,#c9a84c,#f0d080); border-radius:16px; display:inline-flex; align-items:center; justify-content:center; font-size:28px; color:#1a1f3c; font-weight:800; margin-bottom:12px; }
        .logo h2 { font-size:22px; font-weight:800; color:#1a1f3c; }
        .logo p { font-size:13px; color:#6b7280; margin-top:4px; }
        .form-group { margin-bottom:18px; }
        label { display:block; margin-bottom:7px; font-size:14px; font-weight:600; color:#374151; }
        input { width:100%; padding:12px 16px; border:1.5px solid #e5e7eb; border-radius:10px; font-family:'Tajawal',sans-serif; font-size:14px; color:#1a1f3c; transition:border-color 0.2s; background:#f9fafb; }
        input:focus { outline:none; border-color:#c9a84c; background:#fff; }
        .btn-login { width:100%; padding:14px; background:linear-gradient(135deg,#1a1f3c,#2d3561); color:#fff; border:none; border-radius:12px; font-family:'Tajawal',sans-serif; font-size:16px; font-weight:700; cursor:pointer; margin-top:8px; transition:opacity 0.2s; }
        .btn-login:hover { opacity:0.9; }
        .error { background:rgba(239,68,68,0.1); border:1px solid rgba(239,68,68,0.3); color:#dc2626; padding:12px 16px; border-radius:10px; margin-bottom:20px; font-size:14px; }
    </style>
</head>
<body>
<div class="login-box">
    <div class="logo">
        <div class="logo-icon">ص</div>
        <h2>صراف برو</h2>
        <p>تسجيل دخول مدير المحل</p>
    </div>
    @if($errors->any())
    <div class="error"><i class="fas fa-exclamation-circle" style="margin-left:6px;"></i>{{ $errors->first() }}</div>
    @endif
    <form method="POST" action="{{ route('admin.login.post') }}">
        @csrf
        <div class="form-group">
            <label>البريد الإلكتروني</label>
            <input type="email" name="email" value="{{ old('email') }}" required autofocus placeholder="admin@shop.com">
        </div>
        <div class="form-group">
            <label>كلمة المرور</label>
            <input type="password" name="password" required placeholder="••••••••">
        </div>
        <button type="submit" class="btn-login">تسجيل الدخول</button>
    </form>
</div>
</body>
</html>
