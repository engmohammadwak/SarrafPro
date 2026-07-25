<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>دخول السوبر أدمن - صراف برو</title>
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        * { margin:0; padding:0; box-sizing:border-box; }
        body {
            font-family: 'Tajawal', sans-serif;
            min-height: 100vh;
            background: #12172b;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            position: relative;
            overflow: hidden;
        }
        body::before {
            content: '';
            position: absolute;
            width: 600px; height: 600px;
            background: radial-gradient(circle, rgba(201,168,76,0.08) 0%, transparent 70%);
            top: -100px; right: -100px;
            border-radius: 50%;
        }
        body::after {
            content: '';
            position: absolute;
            width: 400px; height: 400px;
            background: radial-gradient(circle, rgba(59,130,246,0.06) 0%, transparent 70%);
            bottom: -50px; left: -50px;
            border-radius: 50%;
        }
        .login-card {
            background: #1a1f3c;
            border-radius: 24px;
            padding: 48px 40px;
            width: 100%;
            max-width: 420px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.5);
            border: 1px solid rgba(201,168,76,0.15);
            position: relative;
            z-index: 1;
        }
        .login-logo {
            text-align: center;
            margin-bottom: 36px;
        }
        .logo-circle {
            width: 72px; height: 72px;
            background: linear-gradient(135deg, #c9a84c, #f0d080);
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 30px;
            font-weight: 800;
            color: #12172b;
            margin: 0 auto 16px;
            box-shadow: 0 8px 24px rgba(201,168,76,0.3);
        }
        .login-logo h1 {
            color: #fff;
            font-size: 24px;
            font-weight: 800;
        }
        .login-logo p {
            color: rgba(255,255,255,0.45);
            font-size: 13px;
            margin-top: 6px;
        }
        .badge-superadmin {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: rgba(201,168,76,0.12);
            border: 1px solid rgba(201,168,76,0.25);
            color: #c9a84c;
            padding: 5px 14px;
            border-radius: 50px;
            font-size: 12px;
            font-weight: 700;
            margin-top: 10px;
        }
        .form-group { margin-bottom: 20px; }
        .form-group label {
            display: block;
            color: rgba(255,255,255,0.7);
            font-size: 13.5px;
            font-weight: 600;
            margin-bottom: 8px;
        }
        .input-wrapper { position: relative; }
        .input-wrapper i {
            position: absolute;
            right: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: rgba(255,255,255,0.3);
            font-size: 15px;
        }
        .form-group input {
            width: 100%;
            background: rgba(255,255,255,0.06);
            border: 1px solid rgba(255,255,255,0.1);
            border-radius: 12px;
            padding: 14px 46px 14px 16px;
            color: #fff;
            font-family: 'Tajawal', sans-serif;
            font-size: 15px;
            transition: all 0.2s;
            outline: none;
        }
        .form-group input:focus {
            border-color: rgba(201,168,76,0.5);
            background: rgba(201,168,76,0.05);
            box-shadow: 0 0 0 3px rgba(201,168,76,0.1);
        }
        .form-group input::placeholder { color: rgba(255,255,255,0.25); }
        .btn-login {
            width: 100%;
            background: linear-gradient(135deg, #c9a84c, #f0d080);
            color: #12172b;
            border: none;
            border-radius: 12px;
            padding: 15px;
            font-family: 'Tajawal', sans-serif;
            font-size: 16px;
            font-weight: 800;
            cursor: pointer;
            transition: all 0.2s;
            margin-top: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            box-shadow: 0 6px 20px rgba(201,168,76,0.3);
        }
        .btn-login:hover { opacity: 0.9; transform: translateY(-1px); }
        .error-box {
            background: rgba(239,68,68,0.1);
            border: 1px solid rgba(239,68,68,0.25);
            border-radius: 12px;
            padding: 12px 16px;
            color: #fca5a5;
            font-size: 13.5px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        @media (max-width: 480px) {
            .login-card { padding: 32px 24px; }
        }
    </style>
</head>
<body>
<div class="login-card">
    <div class="login-logo">
        <div class="logo-circle">ص</div>
        <h1>صراف برو</h1>
        <p>نظام إدارة محلات الصرافة</p>
        <div class="badge-superadmin">
            <i class="fas fa-shield-alt"></i> Super Admin
        </div>
    </div>

    @if($errors->any())
    <div class="error-box">
        <i class="fas fa-exclamation-circle"></i>
        {{ $errors->first() }}
    </div>
    @endif

    <form method="POST" action="{{ route('superadmin.login.post') }}">
        @csrf
        <div class="form-group">
            <label>البريد الإلكتروني</label>
            <div class="input-wrapper">
                <i class="fas fa-envelope"></i>
                <input type="email" name="email" value="{{ old('email') }}" placeholder="admin@sarrafpro.com" required autofocus>
            </div>
        </div>
        <div class="form-group">
            <label>كلمة المرور</label>
            <div class="input-wrapper">
                <i class="fas fa-lock"></i>
                <input type="password" name="password" placeholder="••••••••" required>
            </div>
        </div>
        <button type="submit" class="btn-login">
            <i class="fas fa-sign-in-alt"></i> دخول
        </button>
    </form>
</div>
</body>
</html>
