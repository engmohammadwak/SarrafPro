<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>دخول - صراف برو</title>
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        *{margin:0;padding:0;box-sizing:border-box}
        body{font-family:'Tajawal',sans-serif;min-height:100vh;background:#12172b;display:flex;align-items:center;justify-content:center;padding:20px;position:relative;overflow:hidden}
        body::before{content:'';position:absolute;width:600px;height:600px;background:radial-gradient(circle,rgba(201,168,76,.08) 0%,transparent 70%);top:-100px;right:-100px;border-radius:50%}
        .login-card{background:#1a1f3c;border-radius:24px;padding:48px 40px;width:100%;max-width:420px;box-shadow:0 20px 60px rgba(0,0,0,.5);border:1px solid rgba(201,168,76,.15);position:relative;z-index:1}
        .login-logo{text-align:center;margin-bottom:32px}
        .logo-circle{width:72px;height:72px;background:linear-gradient(135deg,#c9a84c,#f0d080);border-radius:20px;display:flex;align-items:center;justify-content:center;font-size:30px;font-weight:800;color:#12172b;margin:0 auto 16px;box-shadow:0 8px 24px rgba(201,168,76,.3)}
        .login-logo h1{color:#fff;font-size:24px;font-weight:800}
        .badge-superadmin{display:inline-flex;align-items:center;gap:6px;background:rgba(201,168,76,.12);border:1px solid rgba(201,168,76,.25);color:#c9a84c;padding:5px 14px;border-radius:50px;font-size:12px;font-weight:700;margin-top:10px}
        /* method tabs */
        .method-tabs{display:flex;gap:10px;margin-bottom:28px}
        .method-tab{flex:1;padding:14px;border-radius:14px;border:1.5px solid rgba(255,255,255,.1);background:rgba(255,255,255,.04);color:rgba(255,255,255,.5);font-family:'Tajawal',sans-serif;font-size:14px;font-weight:700;cursor:pointer;transition:all .2s;text-align:center;display:flex;flex-direction:column;align-items:center;gap:6px}
        .method-tab i{font-size:20px}
        .method-tab.active{border-color:#c9a84c;background:rgba(201,168,76,.1);color:#c9a84c}
        .method-tab:hover:not(.active){border-color:rgba(201,168,76,.3);color:rgba(255,255,255,.8)}
        /* forms */
        .form-section{display:none}
        .form-section.active{display:block}
        .form-group{margin-bottom:20px}
        .form-group label{display:block;color:rgba(255,255,255,.7);font-size:13.5px;font-weight:600;margin-bottom:8px}
        .input-wrapper{position:relative}
        .input-wrapper i.icon{position:absolute;right:16px;top:50%;transform:translateY(-50%);color:rgba(255,255,255,.3);font-size:15px}
        .form-group input{width:100%;background:rgba(255,255,255,.06);border:1px solid rgba(255,255,255,.1);border-radius:12px;padding:14px 46px 14px 16px;color:#fff;font-family:'Tajawal',sans-serif;font-size:15px;transition:all .2s;outline:none}
        .form-group input:focus{border-color:rgba(201,168,76,.5);background:rgba(201,168,76,.05);box-shadow:0 0 0 3px rgba(201,168,76,.1)}
        .form-group input::placeholder{color:rgba(255,255,255,.25)}
        /* PIN boxes */
        .pin-boxes{display:flex;gap:10px;justify-content:center;direction:ltr}
        .pin-box{width:52px;height:60px;background:rgba(255,255,255,.06);border:1.5px solid rgba(255,255,255,.12);border-radius:12px;text-align:center;font-size:24px;font-weight:800;color:#fff;outline:none;transition:all .2s;font-family:'Tajawal',sans-serif}
        .pin-box:focus{border-color:#c9a84c;background:rgba(201,168,76,.08);box-shadow:0 0 0 3px rgba(201,168,76,.15)}
        .pin-hidden{position:absolute;opacity:0;pointer-events:none}
        /* btn */
        .btn-login{width:100%;background:linear-gradient(135deg,#c9a84c,#f0d080);color:#12172b;border:none;border-radius:12px;padding:15px;font-family:'Tajawal',sans-serif;font-size:16px;font-weight:800;cursor:pointer;transition:all .2s;margin-top:8px;display:flex;align-items:center;justify-content:center;gap:10px;box-shadow:0 6px 20px rgba(201,168,76,.3)}
        .btn-login:hover{opacity:.9;transform:translateY(-1px)}
        .back-link{text-align:center;margin-top:18px}
        .back-link a{color:rgba(255,255,255,.35);font-size:13px;text-decoration:none;transition:color .2s}
        .back-link a:hover{color:rgba(255,255,255,.7)}
        .error-box{background:rgba(239,68,68,.1);border:1px solid rgba(239,68,68,.25);border-radius:12px;padding:12px 16px;color:#fca5a5;font-size:13.5px;margin-bottom:20px;display:flex;align-items:center;gap:8px}
    </style>
</head>
<body>
<div class="login-card">
    <div class="login-logo">
        <div class="logo-circle">ص</div>
        <h1>صراف برو</h1>
        <div class="badge-superadmin"><i class="fas fa-shield-alt"></i> Super Admin</div>
    </div>

    @if($errors->any())
    <div class="error-box">
        <i class="fas fa-exclamation-circle"></i>
        {{ $errors->first() }}
    </div>
    @endif

    @if($hasPIN)
    {{-- Tabs --}}
    <div class="method-tabs">
        <button type="button" class="method-tab active" onclick="switchMethod('password')" id="tab-password">
            <i class="fas fa-lock"></i> كلمة المرور
        </button>
        <button type="button" class="method-tab" onclick="switchMethod('pin')" id="tab-pin">
            <i class="fas fa-grid-2"></i> رمز PIN
        </button>
    </div>
    @endif

    {{-- Password Form --}}
    <form method="POST" action="{{ route('superadmin.login.post') }}" id="form-password" class="form-section {{ $errors->has('password') ? 'active' : 'active' }}">
        @csrf
        <input type="hidden" name="method" value="password">
        <div class="form-group">
            <label>كلمة المرور</label>
            <div class="input-wrapper">
                <i class="fas fa-lock icon"></i>
                <input type="password" name="password" placeholder="••••••••" required autofocus>
            </div>
        </div>
        <button type="submit" class="btn-login">
            <i class="fas fa-sign-in-alt"></i> دخول
        </button>
    </form>

    @if($hasPIN)
    {{-- PIN Form --}}
    <form method="POST" action="{{ route('superadmin.login.post') }}" id="form-pin" class="form-section">
        @csrf
        <input type="hidden" name="method" value="pin">
        <input type="hidden" name="pin" id="pinValue">
        <div class="form-group">
            <label style="text-align:center;display:block;margin-bottom:16px">أدخل رمز PIN المكون من 6 أرقام</label>
            <div class="pin-boxes">
                <input type="text" maxlength="1" class="pin-box" inputmode="numeric" pattern="[0-9]">
                <input type="text" maxlength="1" class="pin-box" inputmode="numeric" pattern="[0-9]">
                <input type="text" maxlength="1" class="pin-box" inputmode="numeric" pattern="[0-9]">
                <input type="text" maxlength="1" class="pin-box" inputmode="numeric" pattern="[0-9]">
                <input type="text" maxlength="1" class="pin-box" inputmode="numeric" pattern="[0-9]">
                <input type="text" maxlength="1" class="pin-box" inputmode="numeric" pattern="[0-9]">
            </div>
        </div>
        <button type="submit" class="btn-login" id="pinSubmit" disabled>
            <i class="fas fa-sign-in-alt"></i> دخول
        </button>
    </form>
    @endif

    <div class="back-link">
        <a href="{{ route('superadmin.login') }}"><i class="fas fa-arrow-right" style="margin-left:4px"></i> تغيير الحساب</a>
    </div>
</div>

<script>
function switchMethod(m) {
    document.querySelectorAll('.form-section').forEach(f => f.classList.remove('active'));
    document.querySelectorAll('.method-tab').forEach(t => t.classList.remove('active'));
    document.getElementById('form-' + m).classList.add('active');
    document.getElementById('tab-' + m).classList.add('active');
    // focus first field
    const first = document.getElementById('form-' + m).querySelector('input:not([type=hidden])');
    if (first) first.focus();
}

// PIN boxes auto-advance
const boxes = document.querySelectorAll('.pin-box');
boxes.forEach((box, i) => {
    box.addEventListener('input', () => {
        box.value = box.value.replace(/[^0-9]/g,'');
        if (box.value && i < boxes.length - 1) boxes[i+1].focus();
        updatePIN();
    });
    box.addEventListener('keydown', e => {
        if (e.key === 'Backspace' && !box.value && i > 0) boxes[i-1].focus();
    });
    box.addEventListener('paste', e => {
        e.preventDefault();
        const txt = (e.clipboardData || window.clipboardData).getData('text').replace(/[^0-9]/g,'').slice(0,6);
        txt.split('').forEach((c,j) => { if(boxes[j]) boxes[j].value = c; });
        if(boxes[txt.length-1]) boxes[txt.length-1].focus();
        updatePIN();
    });
});
function updatePIN() {
    const v = Array.from(boxes).map(b=>b.value).join('');
    document.getElementById('pinValue').value = v;
    document.getElementById('pinSubmit').disabled = v.length < 6;
}

// if error on pin, switch to pin tab
@if($errors->has('pin'))
switchMethod('pin');
@endif
</script>
</body>
</html>
