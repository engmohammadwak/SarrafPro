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
                        <label style="display:block;margin-bottom:6px;font-size:14px;color:var(--text-muted)">اسم المندوب *</label>
                        <input type="text" name="name" value="{{ old('name') }}" required
                            style="width:100%;padding:10px 14px;background:#f8f9fc;border:1px solid var(--border);border-radius:8px;font-family:Tajawal,sans-serif;font-size:14px">
                    </div>
                    <div>
                        <label style="display:block;margin-bottom:6px;font-size:14px;color:var(--text-muted)">رقم الهاتف</label>
                        <input type="text" name="phone" value="{{ old('phone') }}"
                            style="width:100%;padding:10px 14px;background:#f8f9fc;border:1px solid var(--border);border-radius:8px;font-family:Tajawal,sans-serif;font-size:14px">
                    </div>
                    <div>
                        <label style="display:block;margin-bottom:6px;font-size:14px;color:var(--text-muted)">الدولة</label>
                        <input type="text" name="country" value="{{ old('country') }}"
                            style="width:100%;padding:10px 14px;background:#f8f9fc;border:1px solid var(--border);border-radius:8px;font-family:Tajawal,sans-serif;font-size:14px">
                    </div>
                    <div style="grid-column:1/-1">
                        <label style="display:block;margin-bottom:6px;font-size:14px;color:var(--text-muted)">اسم الشركة</label>
                        <input type="text" name="company" value="{{ old('company') }}"
                            style="width:100%;padding:10px 14px;background:#f8f9fc;border:1px solid var(--border);border-radius:8px;font-family:Tajawal,sans-serif;font-size:14px">
                    </div>
                    <div style="grid-column:1/-1">
                        <label style="display:block;margin-bottom:6px;font-size:14px;color:var(--text-muted)">ملاحظات</label>
                        <textarea name="notes" rows="2" style="width:100%;padding:10px 14px;background:#f8f9fc;border:1px solid var(--border);border-radius:8px;font-family:Tajawal,sans-serif;font-size:14px">{{ old('notes') }}</textarea>
                    </div>
                </div>
            </div>
        </div>

        {{-- ربط بحساب --}}
        <div class="card" style="margin-bottom:20px">
            <div class="card-header"><h3><i class="fas fa-link" style="color:var(--accent);margin-left:8px"></i> ربط بحساب في النظام <span style="font-size:13px;font-weight:400;color:var(--text-muted)">اختياري</span></h3></div>
            <div class="card-body">

                {{-- خيارات --}}
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
                    <label style="display:block;margin-bottom:6px;font-size:14px;color:var(--text-muted)">اسم المستخدم أو البريد الإلكتروني</label>
                    <div style="display:flex;gap:8px">
                        <input type="text" id="usernameInput" placeholder="ابحث عن المستخدم..."
                            style="flex:1;padding:10px 14px;background:#f8f9fc;border:1px solid var(--border);border-radius:8px;font-family:Tajawal,sans-serif;font-size:14px">
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
                            <label style="display:block;margin-bottom:6px;font-size:14px;color:var(--text-muted)">البريد الإلكتروني *</label>
                            <input type="email" name="new_email" value="{{ old('new_email') }}" placeholder="agent@example.com"
                                style="width:100%;padding:10px 14px;background:#f8f9fc;border:1px solid var(--border);border-radius:8px;font-family:Tajawal,sans-serif;font-size:14px">
                        </div>
                        <div style="grid-column:1/-1">
                            <label style="display:block;margin-bottom:6px;font-size:14px;color:var(--text-muted)">كلمة المرور *</label>
                            <input type="password" name="new_password" placeholder="8 حروف على الأقل"
                                style="width:100%;padding:10px 14px;background:#f8f9fc;border:1px solid var(--border);border-radius:8px;font-family:Tajawal,sans-serif;font-size:14px">
                        </div>
                    </div>
                    <div style="margin-top:10px;padding:10px 14px;background:rgba(59,130,246,0.08);border-radius:8px;font-size:13px;color:var(--info)">
                        <i class="fas fa-info-circle"></i> سيتم إنشاء حساب مندوب جديد بصلاحية تسجيل الدخول ومتابعة عملياته.
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

@push('scripts')
<script>
const colors = { none:'#94a3b8', existing:'var(--info)', create:'var(--success)' };

function onLinkChange(val) {
    ['none','existing','create'].forEach(k => {
        const el = document.getElementById('lt-' + k);
        el.style.borderColor = k === val ? colors[k] : 'var(--border)';
        el.style.background  = k === val ? colors[k] + '18' : '';
        el.style.color       = k === val ? colors[k] : '';
    });
    document.getElementById('existingFields').style.display = val === 'existing' ? 'block' : 'none';
    document.getElementById('createFields').style.display   = val === 'create'   ? 'block' : 'none';
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
            box.innerHTML = `
                <div style="padding:12px 16px;background:rgba(34,197,94,0.1);border:1px solid rgba(34,197,94,0.3);border-radius:8px;display:flex;align-items:center;gap:10px">
                    <i class="fas fa-check-circle" style="color:var(--success);font-size:18px"></i>
                    <div>
                        <div style="font-weight:700">${data.name}</div>
                        <div style="font-size:13px;color:var(--text-muted)">${data.email}</div>
                        <div style="font-size:12px;color:var(--success);margin-top:2px">✓ سيتم إرسال طلب موافقة لصاحب الحساب</div>
                    </div>
                </div>`;
        } else {
            document.getElementById('userId').value = '';
            box.innerHTML = `
                <div style="padding:12px 16px;background:rgba(239,68,68,0.08);border:1px solid rgba(239,68,68,0.25);border-radius:8px;color:#ef4444">
                    <i class="fas fa-times-circle"></i> ${data.message}
                </div>`;
        }
    })
    .finally(() => { btn.disabled = false; btn.innerHTML = '<i class="fas fa-search"></i> تحقق'; });
}

window.addEventListener('DOMContentLoaded', () => {
    const checked = document.querySelector('.link-radio:checked');
    if (checked) onLinkChange(checked.value);
});
</script>
@endpush
@endsection
