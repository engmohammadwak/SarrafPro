<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>تسجيل دخول المندوب</title>
<link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;600;700&display=swap" rel="stylesheet">
<style>
*{margin:0;padding:0;box-sizing:border-box}
body{font-family:Tajawal,sans-serif;background:#0f172a;min-height:100vh;display:flex;align-items:center;justify-content:center}
.box{background:#1e293b;border:1px solid #334155;border-radius:20px;padding:40px;width:100%;max-width:420px}
.logo{text-align:center;margin-bottom:32px}
.logo i{font-size:48px;color:#f59e0b;margin-bottom:12px;display:block}
.logo h1{color:#f1f5f9;font-size:22px}
.logo p{color:#94a3b8;font-size:14px;margin-top:4px}
label{display:block;margin-bottom:6px;font-size:14px;color:#94a3b8}
input{width:100%;padding:11px 14px;background:#0f172a;border:1px solid #334155;border-radius:8px;color:#f1f5f9;font-family:Tajawal,sans-serif;font-size:14px;margin-bottom:16px}
input:focus{outline:none;border-color:#f59e0b}
.btn{width:100%;padding:12px;background:#f59e0b;color:#0f172a;border:none;border-radius:8px;font-family:Tajawal,sans-serif;font-size:15px;font-weight:700;cursor:pointer}
.error{background:rgba(239,68,68,0.1);border:1px solid rgba(239,68,68,0.3);color:#ef4444;padding:10px 14px;border-radius:8px;font-size:13px;margin-bottom:16px}
</style>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>
<div class="box">
    <div class="logo">
        <i class="fas fa-user-tie"></i>
        <h1>صراف برو</h1>
        <p>بوابة المندوبين</p>
    </div>
    @if($errors->any())
    <div class="error">{{ $errors->first() }}</div>
    @endif
    <form action="{{ route('agent.login.post') }}" method="POST">
        @csrf
        <label>البريد الإلكتروني</label>
        <input type="email" name="email" value="{{ old('email') }}" required autofocus>
        <label>كلمة المرور</label>
        <input type="password" name="password" required>
        <button type="submit" class="btn"><i class="fas fa-sign-in-alt"></i> دخول</button>
    </form>
</div>
</body>
</html>
