@extends('layouts.superadmin')
@section('title', 'إعدادات PIN')
@section('page-title', 'رمز PIN للدخول السريع')
@section('content')
<div style="max-width:480px">
    <div class="card">
        <div class="card-header">
            <h3><i class="fas fa-grid-2" style="color:var(--accent);margin-left:8px"></i> رمز PIN</h3>
        </div>
        <div class="card-body">

            @if(auth()->user()->hasPin())
            <div style="background:rgba(34,197,94,0.08);border:1px solid rgba(34,197,94,0.25);border-radius:10px;padding:14px 18px;margin-bottom:24px;display:flex;align-items:center;gap:10px;">
                <i class="fas fa-check-circle" style="color:#16a34a;font-size:18px"></i>
                <div>
                    <p style="font-weight:700;color:#15803d">رمز PIN مفعّل</p>
                    <p style="font-size:13px;color:#166534">يمكنك استخدامه لتسجيل الدخول بدلاً من كلمة المرور</p>
                </div>
            </div>
            @else
            <div style="background:rgba(245,158,11,0.08);border:1px solid rgba(245,158,11,0.25);border-radius:10px;padding:14px 18px;margin-bottom:24px;display:flex;align-items:center;gap:10px;">
                <i class="fas fa-info-circle" style="color:#d97706;font-size:18px"></i>
                <p style="font-size:13px;color:#92400e">لم تفعّل رمز PIN بعد. بعد التفعيل يمكنك اختياره عند تسجيل الدخول.</p>
            </div>
            @endif

            {{-- Set / Change PIN --}}
            <form method="POST" action="{{ route('superadmin.pin.save') }}">
                @csrf
                @if($errors->any())
                <div style="background:rgba(239,68,68,.08);border:1px solid rgba(239,68,68,.25);color:#dc2626;padding:12px 16px;border-radius:10px;margin-bottom:18px;font-size:14px">
                    @foreach($errors->all() as $e)<div>• {{ $e }}</div>@endforeach
                </div>
                @endif

                <div style="margin-bottom:18px">
                    <label style="display:block;font-size:14px;font-weight:600;margin-bottom:10px;">رمز PIN جديد (6 أرقام)</label>
                    <div style="display:flex;gap:10px;direction:ltr;justify-content:flex-start">
                        @for($i=0;$i<6;$i++)
                        <input type="text" maxlength="1" inputmode="numeric" pattern="[0-9]"
                            class="pin-setup-box"
                            style="width:52px;height:60px;background:#f8f9fc;border:1.5px solid var(--border);border-radius:12px;text-align:center;font-size:24px;font-weight:800;color:var(--text-dark);outline:none;transition:all .2s">
                        @endfor
                    </div>
                    <input type="hidden" name="pin" id="pinSetValue">
                </div>

                <div style="margin-bottom:24px">
                    <label style="display:block;font-size:14px;font-weight:600;margin-bottom:10px;">تأكيد الرمز</label>
                    <div style="display:flex;gap:10px;direction:ltr;justify-content:flex-start">
                        @for($i=0;$i<6;$i++)
                        <input type="text" maxlength="1" inputmode="numeric" pattern="[0-9]"
                            class="pin-confirm-box"
                            style="width:52px;height:60px;background:#f8f9fc;border:1.5px solid var(--border);border-radius:12px;text-align:center;font-size:24px;font-weight:800;color:var(--text-dark);outline:none;transition:all .2s">
                        @endfor
                    </div>
                    <input type="hidden" name="pin_confirmation" id="pinConfirmValue">
                </div>

                <button type="submit" class="btn btn-gold" id="pinSaveBtn" disabled>
                    <i class="fas fa-save"></i> {{ auth()->user()->hasPin() ? 'تغيير الرمز' : 'تفعيل رمز PIN' }}
                </button>
            </form>

            @if(auth()->user()->hasPin())
            <hr style="border:none;border-top:1px solid var(--border);margin:24px 0">
            <form method="POST" action="{{ route('superadmin.pin.remove') }}">
                @csrf @method('DELETE')
                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('حذف رمز PIN؟')">
                    <i class="fas fa-trash"></i> حذف رمز PIN
                </button>
            </form>
            @endif
        </div>
    </div>
</div>

<script>
function setupBoxes(cls, hiddenId) {
    const boxes = document.querySelectorAll('.' + cls);
    boxes.forEach((b, i) => {
        b.addEventListener('input', () => {
            b.value = b.value.replace(/[^0-9]/g,'');
            if (b.value && i < boxes.length-1) boxes[i+1].focus();
            sync();
        });
        b.addEventListener('keydown', e => {
            if (e.key==='Backspace' && !b.value && i>0) boxes[i-1].focus();
        });
        b.addEventListener('paste', e => {
            e.preventDefault();
            const t = (e.clipboardData||window.clipboardData).getData('text').replace(/[^0-9]/g,'').slice(0,6);
            t.split('').forEach((c,j) => { if(boxes[j]) boxes[j].value=c; });
            if(boxes[t.length-1]) boxes[t.length-1].focus();
            sync();
        });
        b.addEventListener('focus', () => b.style.borderColor='var(--accent)');
        b.addEventListener('blur',  () => b.style.borderColor='var(--border)');
    });
    function sync() {
        document.getElementById(hiddenId).value = Array.from(boxes).map(b=>b.value).join('');
        checkReady();
    }
}
function checkReady() {
    const p = document.getElementById('pinSetValue').value;
    const c = document.getElementById('pinConfirmValue').value;
    document.getElementById('pinSaveBtn').disabled = !(p.length===6 && c.length===6);
}
setupBoxes('pin-setup-box','pinSetValue');
setupBoxes('pin-confirm-box','pinConfirmValue');
</script>
@endsection
