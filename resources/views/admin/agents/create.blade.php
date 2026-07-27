@extends('layouts.admin')
@section('title', 'إضافة مندوب')
@section('page-title', 'إضافة مندوب جديد')
@section('content')
<div style="max-width:640px">
    @if($errors->any())
    <div style="background:rgba(239,68,68,0.1);border:1px solid rgba(239,68,68,0.3);color:#ef4444;padding:14px;border-radius:12px;margin-bottom:20px">
        <ul style="margin:0;padding-right:18px">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
    </div>
    @endif

    <form action="{{ route('admin.agents.store') }}" method="POST" id="agentForm">
        @csrf

        {{-- بيانات أساسية --}}
        <div class="card" style="margin-bottom:20px">
            <div class="card-header"><h3><i class="fas fa-user-tie" style="color:var(--accent);margin-left:8px"></i> بيانات المندوب</h3></div>
            <div class="card-body">
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px">
                    <div style="grid-column:1/-1">
                        <label class="field-lbl">اسم المندوب *</label>
                        <input type="text" name="name" id="f-name" value="{{ old('name') }}" required class="field-inp">
                    </div>
                    <div>
                        <label class="field-lbl">رقم الهاتف</label>
                        <input type="text" name="phone" id="f-phone" value="{{ old('phone') }}" class="field-inp">
                    </div>
                    <div>
                        <label class="field-lbl">الدولة</label>
                        <input type="text" name="country" id="f-country" value="{{ old('country') }}" class="field-inp">
                    </div>
                    <div style="grid-column:1/-1">
                        <label class="field-lbl">اسم الشركة</label>
                        <input type="text" name="company" id="f-company" value="{{ old('company') }}" class="field-inp">
                    </div>
                    <div style="grid-column:1/-1">
                        <label class="field-lbl">ملاحظات</label>
                        <textarea name="notes" id="f-notes" rows="2" class="field-inp">{{ old('notes') }}</textarea>
                    </div>
                </div>
            </div>
        </div>

        {{-- ربط بحساب --}}
        <div class="card" style="margin-bottom:20px">
            <div class="card-header"><h3><i class="fas fa-link" style="color:var(--accent);margin-left:8px"></i> ربط بحساب في النظام <span style="font-size:13px;font-weight:400;color:var(--text-muted)">اختياري</span></h3></div>
            <div class="card-body">

                <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:10px;margin-bottom:20px">
                    @foreach(['none'=>['بدون حساب','fa-user-slash','#94a3b8'],'existing'=>['ربط بحساب موجود','fa-user-check','var(--info)'],'create'=>['إنشاء حساب جديد','fa-user-plus','var(--success)']] as $val=>[$lbl,$ico,$clr])
                    <label style="cursor:pointer">
                        <input type="radio" name="link_type" value="{{ $val }}" {{ old('link_type','none')===$val?'checked':'' }} style="display:none" class="link-radio" onchange="onLinkChange('{{ $val }}')">
                        <div id="lt-{{ $val }}" style="border:2px solid var(--border);border-radius:12px;padding:14px 8px;text-align:center;transition:all 0.2s">
                            <i class="fas {{ $ico }}" style="font-size:22px;margin-bottom:6px;display:block"></i>
                            <span style="font-size:13px;font-weight:600">{{ $lbl }}</span>
                        </div>
                    </label>
                    @endforeach
                </div>

                {{-- ربط بحساب موجود --}}
                <div id="existingFields" style="display:none">
                    <label class="field-lbl">اسم المستخدم أو البريد الإلكتروني</label>
                    <div style="display:flex;gap:8px">
                        <input type="text" id="usernameInput" placeholder="ابحث عن المستخدم..." class="field-inp" style="flex:1">
                        <button type="button" onclick="checkUser()" class="btn btn-gold">
                            <i class="fas fa-search"></i> تحقق
                        </button>
                    </div>
                    <div id="userResult" style="margin-top:10px"></div>
                    <input type="hidden" name="user_id" id="userId">
                </div>

                {{-- إنشاء حساب جديد --}}
                <div id="createFields" style="display:none">
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px">
                        <div style="grid-column:1/-1">
                            <label class="field-lbl">البريد الإلكتروني *</label>
                            <input type="email" name="new_email" value="{{ old('new_email') }}" placeholder="agent@example.com" class="field-inp">
                        </div>
                        <div style="grid-column:1/-1">
                            <label class="field-lbl">كلمة المرور *</label>
                            <input type="password" name="new_password" placeholder="8 حروف على الأقل" class="field-inp">
                        </div>
                    </div>
                    <div style="margin-top:10px;padding:10px 14px;background:rgba(59,130,246,0.08);border-radius:8px;font-size:13px;color:var(--info)">
                        <i class="fas fa-info-circle"></i> سيتم إنشاء حساب مندوب جديد بصلاحية تسجيل الدخول.
                    </div>
                </div>

            </div>
        </div>

        <div style="display:flex;gap:12px">
            <button type="submit" class="btn btn-gold"><i class="fas fa-plus"></i> إضافة المندوب</button>
            <a href="{{ route('admin.agents.index') }}" class="btn" style="background:var(--border);color:var(--text-dark)"><i class="fas fa-times"></i> إلغاء</a>
        </div>
    </form>
</div>

<style>
.field-lbl { display:block; margin-bottom:6px; font-size:14px; font-weight:600; color:var(--text-muted); }
.field-inp { width:100%; padding:10px 14px; background:#f8f9fc; border:1.5px solid var(--border); border-radius:8px; font-family:Tajawal,sans-serif; font-size:14px; color:var(--text-dark); transition:border-color 0.2s; }
.field-inp:focus { outline:none; border-color:var(--accent); background:#fff; }
.field-inp[readonly] { background:#f0f0f0; color:#6b7280; cursor:not-allowed; border-style:dashed; }
.locked-badge { display:inline-flex; align-items:center; gap:4px; font-size:11px; color:#94a3b8; margin-bottom:4px; }
</style>

@push('scripts')
<script>
const colors = { none:'#94a3b8', existing:'var(--info)', create:'var(--success)' };

// الحقول اللي تتملأ تلقائياً وتنقفل
const autoFields = ['name','phone','country','company'];

function lockFields(lock) {
    autoFields.forEach(key => {
        const el = document.getElementById('f-' + key);
        if (!el) return;
        el.readOnly = lock;
        // اظهر/خفي شارة القفل
        const badge = document.getElementById('badge-' + key);
        if (badge) badge.style.display = lock ? 'inline-flex' : 'none';
    });
}

function fillFromUser(data) {
    // الاسم
    const nameEl = document.getElementById('f-name');
    if (nameEl && data.name) nameEl.value = data.name;

    // تفاصيل إضافية لو موجودة في الـ response
    if (data.phone)   { const el = document.getElementById('f-phone');   if (el) el.value = data.phone; }
    if (data.country) { const el = document.getElementById('f-country'); if (el) el.value = data.country; }
    if (data.company) { const el = document.getElementById('f-company'); if (el) el.value = data.company; }

    lockFields(true);
}

function clearAutoFill() {
    autoFields.forEach(key => {
        const el = document.getElementById('f-' + key);
        if (el) el.value = '';
    });
    lockFields(false);
}

function onLinkChange(val) {
    ['none','existing','create'].forEach(k => {
        const el = document.getElementById('lt-' + k);
        el.style.borderColor = k === val ? colors[k] : 'var(--border)';
        el.style.background  = k === val ? colors[k] + '18' : '';
        el.style.color       = k === val ? colors[k] : '';
    });
    document.getElementById('existingFields').style.display = val === 'existing' ? 'block' : 'none';
    document.getElementById('createFields').style.display   = val === 'create'   ? 'block' : 'none';

    // لو غير عن existing — امسح البيانات المملوءة وافتح الحقول
    if (val !== 'existing') {
        clearAutoFill();
        document.getElementById('userId').value = '';
        document.getElementById('userResult').innerHTML = '';
        document.getElementById('usernameInput').value = '';
    }
}

function checkUser() {
    const username = document.getElementById('usernameInput').value.trim();
    if (!username) return;
    const btn = event.target.closest('button');
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';

    fetch('{{ route("admin.agents.check-user") }}?username=' + encodeURIComponent(username), {
        headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
    })
    .then(r => r.json())
    .then(data => {
        const box = document.getElementById('userResult');
        if (data.found) {
            document.getElementById('userId').value = data.user_id;

            // ← تملأ الحقول وتقفلها
            fillFromUser(data);

            box.innerHTML = `
                <div style="padding:12px 16px;background:rgba(34,197,94,0.1);border:1px solid rgba(34,197,94,0.3);border-radius:8px;display:flex;align-items:center;justify-content:space-between;gap:10px">
                    <div style="display:flex;align-items:center;gap:10px">
                        <i class="fas fa-check-circle" style="color:var(--success);font-size:20px"></i>
                        <div>
                            <div style="font-weight:700;font-size:15px">${data.name}</div>
                            <div style="font-size:12px;color:var(--text-muted)">${data.username ?? ''} &bull; ${data.email}</div>
                            <div style="font-size:12px;color:var(--success);margin-top:2px">✓ تم ملء البيانات تلقائياً &mdash; بإمكانك تعديل الملاحظات فقط</div>
                        </div>
                    </div>
                    <button type="button" onclick="resetUser()" style="background:none;border:none;color:#94a3b8;cursor:pointer;font-size:18px" title="تغيير">
                        <i class="fas fa-times-circle"></i>
                    </button>
                </div>`;
        } else {
            document.getElementById('userId').value = '';
            clearAutoFill();
            box.innerHTML = `
                <div style="padding:12px 16px;background:rgba(239,68,68,0.08);border:1px solid rgba(239,68,68,0.25);border-radius:8px;color:#ef4444">
                    <i class="fas fa-times-circle"></i> ${data.message}
                </div>`;
        }
    })
    .finally(() => { btn.disabled = false; btn.innerHTML = '<i class="fas fa-search"></i> تحقق'; });
}

function resetUser() {
    document.getElementById('userId').value = '';
    document.getElementById('userResult').innerHTML = '';
    document.getElementById('usernameInput').value = '';
    clearAutoFill();
}

window.addEventListener('DOMContentLoaded', () => {
    const checked = document.querySelector('.link-radio:checked');
    if (checked) onLinkChange(checked.value);
});
</script>
@endpush
@endsection
