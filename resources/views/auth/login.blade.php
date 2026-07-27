<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>صراف برو — تسجيل الدخول</title>
<link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<style>
*{margin:0;padding:0;box-sizing:border-box}
body{font-family:Tajawal,sans-serif;background:linear-gradient(135deg,#1a1f3c 0%,#12172b 100%);min-height:100vh;display:flex;align-items:center;justify-content:center;padding:20px}
.box{background:#fff;border-radius:24px;padding:48px 40px;width:100%;max-width:440px;box-shadow:0 24px 64px rgba(0,0,0,0.35)}
.logo{text-align:center;margin-bottom:36px}
.logo-icon{width:72px;height:72px;background:linear-gradient(135deg,#c9a84c,#f0d080);border-radius:20px;display:inline-flex;align-items:center;justify-content:center;font-size:32px;color:#1a1f3c;font-weight:800;margin-bottom:14px;box-shadow:0 8px 24px rgba(201,168,76,0.4)}
.logo h2{font-size:24px;font-weight:800;color:#1a1f3c}
.logo p{font-size:13px;color:#6b7280;margin-top:5px}
label{display:block;margin-bottom:7px;font-size:14px;font-weight:600;color:#374151}
.input-wrap{position:relative;margin-bottom:18px}
.input-wrap i{position:absolute;right:14px;top:50%;transform:translateY(-50%);color:#9ca3af;font-size:15px}
input{width:100%;padding:12px 42px 12px 16px;border:1.5px solid #e5e7eb;border-radius:10px;font-family:Tajawal,sans-serif;font-size:14px;color:#1a1f3c;background:#f9fafb;transition:border-color 0.2s}
input:focus{outline:none;border-color:#c9a84c;background:#fff;box-shadow:0 0 0 3px rgba(201,168,76,0.1)}
.btn{width:100%;padding:14px;background:linear-gradient(135deg,#1a1f3c,#2d3561);color:#fff;border:none;border-radius:12px;font-family:Tajawal,sans-serif;font-size:16px;font-weight:700;cursor:pointer;margin-top:6px;transition:opacity 0.2s;display:flex;align-items:center;justify-content:center;gap:8px}
.btn:hover{opacity:0.9}
.error{background:rgba(239,68,68,0.08);border:1px solid rgba(239,68,68,0.25);color:#dc2626;padding:12px 16px;border-radius:10px;margin-bottom:20px;font-size:14px}
.remember{display:flex;align-items:center;gap:8px;font-size:13px;color:#6b7280;margin-bottom:20px;cursor:pointer}
.remember input{width:auto;margin:0;padding:0}
.hint{font-size:12px;color:#9ca3af;margin-top:-12px;margin-bottom:16px;padding-right:4px}
</style>
</head>
<body>
<div class="box">
    <div class="logo">
        <div class="logo-icon">ص</div>
        <h2>صراف برو</h2>
        <p>منصة إدارة عمليات الصرف</p>
    </div>

    @if($errors->any())
    <div class="error"><i class="fas fa-exclamation-circle" style="margin-left:7px"></i>{{ $errors->first() }}</div>
    @endif

    <form method="POST" action="{{ route('login.post') }}">
        @csrf
        <label>البريد الإلكتروني أو اسم المستخدم</label>
        <div class="input-wrap">
            <i class="fas fa-user"></i>
            <input type="text" name="login" value="{{ old('login') }}" required autofocus
                placeholder="example@email.com أو username" autocomplete="username">
        </div>
        <p class="hint"><i class="fas fa-info-circle"></i> يمكنك الدخول بالبريد الإلكتروني أو باسم المستخدم</p>

        <label>كلمة المرور</label>
        <div class="input-wrap">
            <i class="fas fa-lock"></i>
            <input type="password" name="password" required placeholder="••••••••" autocomplete="current-password">
        </div>

        <label class="remember">
            <input type="checkbox" name="remember"> تذكرني
        </label>

        <button type="submit" class="btn">
            <i class="fas fa-sign-in-alt"></i> دخول
        </button>
    </form>
</div>
</body>
</html>
