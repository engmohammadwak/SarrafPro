@extends('layouts.admin')
@section('title', 'إضافة مندوب')
@section('page-title', 'إضافة مندوب')
@section('content')

<style>
/* ======================================================
   Link Type Card Selector — SarrafPro Dashboard Theme
   ====================================================== */

.link-type-selector {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 10px;
    margin-bottom: 18px;
}

.link-type-card {
    position: relative;
    cursor: pointer;
}

.link-type-card input[type="radio"] {
    position: absolute;
    opacity: 0;
    width: 0;
    height: 0;
    pointer-events: none;
}

.link-type-card-body {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 8px;
    padding: 16px 12px 14px;
    border-radius: 12px;
    border: 2px solid var(--border-color, rgba(0,0,0,0.09));
    background: var(--card-bg, rgba(255,255,255,0.5));
    text-align: center;
    cursor: pointer;
    transition: all 0.18s cubic-bezier(0.16, 1, 0.3, 1);
    user-select: none;
    position: relative;
}

.link-type-card-body:hover {
    border-color: var(--accent, #f59e0b);
    background: rgba(245, 158, 11, 0.05);
    transform: translateY(-1px);
    box-shadow: 0 4px 14px rgba(245,158,11,0.12);
}

.link-type-card-icon {
    width: 42px;
    height: 42px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 18px;
    transition: all 0.18s ease;
}

.link-type-card-label {
    font-size: 13px;
    font-weight: 600;
    color: var(--text-primary, #1a1a2e);
    line-height: 1.3;
    transition: color 0.18s;
}

.link-type-card-desc {
    font-size: 11px;
    color: var(--text-muted, #8b8fa8);
    line-height: 1.4;
    margin-top: 2px;
}

/* بدون حساب → grey */
.link-type-card.card-none input:checked ~ .link-type-card-body {
    border-color: rgba(100,116,139,0.5);
    background: rgba(100,116,139,0.06);
    box-shadow: 0 0 0 3px rgba(100,116,139,0.12);
}

.link-type-card.card-none input:checked ~ .link-type-card-body .link-type-card-icon {
    background: rgba(100,116,139,0.12);
    color: #475569;
}

.link-type-card.card-none input:checked ~ .link-type-card-body .link-type-card-label { color: #334155; }

/* ربط موجود → blue/teal */
.link-type-card.card-existing input:checked ~ .link-type-card-body {
    border-color: rgba(14,165,233,0.55);
    background: rgba(14,165,233,0.06);
    box-shadow: 0 0 0 3px rgba(14,165,233,0.14);
}

.link-type-card.card-existing input:checked ~ .link-type-card-body .link-type-card-icon {
    background: rgba(14,165,233,0.12);
    color: #0284c7;
}

.link-type-card.card-existing input:checked ~ .link-type-card-body .link-type-card-label { color: #0369a1; }

/* إنشاء جديد → green */
.link-type-card.card-create input:checked ~ .link-type-card-body {
    border-color: rgba(34,197,94,0.45);
    background: rgba(34,197,94,0.06);
    box-shadow: 0 0 0 3px rgba(34,197,94,0.13);
}

.link-type-card.card-create input:checked ~ .link-type-card-body .link-type-card-icon {
    background: rgba(34,197,94,0.12);
    color: #16a34a;
}

.link-type-card.card-create input:checked ~ .link-type-card-body .link-type-card-label { color: #15803d; }

/* Default icon bg (unselected) */
.link-type-card.card-none .link-type-card-icon     { background: rgba(100,116,139,0.08); color: #94a3b8; }
.link-type-card.card-existing .link-type-card-icon { background: rgba(14,165,233,0.08);  color: #38bdf8; }
.link-type-card.card-create .link-type-card-icon   { background: rgba(34,197,94,0.08);   color: #4ade80; }

/* checkmark badge on selected */
.link-type-card-body::after {
    content: "";
    position: absolute;
    top: 9px;
    left: 9px;
    width: 16px;
    height: 16px;
    border-radius: 50%;
    background: transparent;
    border: 2px solid var(--border-color, rgba(0,0,0,0.09));
    transition: all 0.18s ease;
}

.link-type-card.card-existing input:checked ~ .link-type-card-body::after {
    background: #0ea5e9;
    border-color: #0ea5e9;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 12 12' fill='none'%3E%3Cpath d='M2 6l3 3 5-5' stroke='white' stroke-width='1.8' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E");
    background-size: 10px;
    background-repeat: no-repeat;
    background-position: center;
}

.link-type-card.card-create input:checked ~ .link-type-card-body::after {
    background: #22c55e;
    border-color: #22c55e;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 12 12' fill='none'%3E%3Cpath d='M2 6l3 3 5-5' stroke='white' stroke-width='1.8' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E");
    background-size: 10px;
    background-repeat: no-repeat;
    background-position: center;
}

.link-type-card.card-none input:checked ~ .link-type-card-body::after {
    background: #64748b;
    border-color: #64748b;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 12 12' fill='none'%3E%3Cpath d='M2 6l3 3 5-5' stroke='white' stroke-width='1.8' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E");
    background-size: 10px;
    background-repeat: no-repeat;
    background-position: center;
}

/* ── Result panels ── */
.link-panel {
    display: none;
}

.link-panel.active {
    display: block;
    animation: panelSlideIn 0.22s cubic-bezier(0.16,1,0.3,1) both;
}

@keyframes panelSlideIn {
    from { opacity: 0; transform: translateY(-6px); }
    to   { opacity: 1; transform: translateY(0); }
}

.user-found-card {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 14px 16px;
    border-radius: 10px;
    border: 1px solid rgba(34,197,94,0.25);
    background: rgba(34,197,94,0.05);
    margin-top: 12px;
    animation: panelSlideIn 0.2s ease both;
}

.user-found-avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 16px;
    flex-shrink: 0;
}

.user-not-found {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-top: 10px;
    padding: 10px 14px;
    background: rgba(239,68,68,0.07);
    border: 1px solid rgba(239,68,68,0.2);
    border-radius: 8px;
    color: #dc2626;
    font-size: 13px;
    animation: panelSlideIn 0.2s ease both;
}

@media (max-width: 640px) {
    .link-type-selector { grid-template-columns: 1fr; }
}
</style>

<div class="card" style="max-width:760px;margin:0 auto">
    <div class="card-header">
        <h3><i class="fas fa-user-plus" style="color:var(--accent);margin-left:8px"></i> إضافة مندوب</h3>
        <a href="{{ route('admin.agents.index') }}" class="btn btn-sm" style="background:rgba(0,0,0,0.06)">
            <i class="fas fa-arrow-right"></i> رجوع
        </a>
    </div>
    <div class="card-body">

        @if($errors->any())
        <div style="margin-bottom:20px;padding:12px 16px;border-radius:10px;background:rgba(239,68,68,0.1);color:#dc2626;border:1px solid rgba(239,68,68,0.25)">
            <ul style="margin:0;padding-right:20px">
                @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
            </ul>
        </div>
        @endif

        <form action="{{ route('admin.agents.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="agent_type" id="agent_type_hidden" value="agent">

            {{-- البيانات الأساسية --}}
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:16px">
                <div>
                    <label class="form-label">الاسم <span style="color:red">*</span></label>
                    <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                </div>
                <div>
                    <label class="form-label">الهاتف</label>
                    <input type="text" name="phone" class="form-control" value="{{ old('phone') }}">
                </div>
                <div>
                    <label class="form-label">هاتف 2</label>
                    <input type="text" name="phone2" class="form-control" value="{{ old('phone2') }}">
                </div>
                <div>
                    <label class="form-label">الدولة</label>
                    <div style="position:relative">
                        <span style="position:absolute;right:10px;top:50%;transform:translateY(-50%);font-size:16px;pointer-events:none">🌐</span>
                        <input type="text" id="country_search" placeholder="ابحث عن دولة..." autocomplete="off" class="form-control" style="padding-right:34px">
                        <input type="hidden" name="country" id="country_value" value="{{ old('country') }}">
                        <div id="country_dropdown" style="display:none;position:absolute;top:100%;right:0;left:0;background:#fff;border:1px solid rgba(0,0,0,0.1);border-radius:8px;max-height:200px;overflow-y:auto;z-index:999;box-shadow:0 4px 15px rgba(0,0,0,0.1)"></div>
                    </div>
                </div>
                <div>
                    <label class="form-label">الشركة</label>
                    <input type="text" name="company" class="form-control" value="{{ old('company') }}">
                </div>
            </div>

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:16px">
                <div>
                    <label class="form-label">ملاحظات</label>
                    <textarea name="notes" class="form-control" rows="2">{{ old('notes') }}</textarea>
                </div>
                <div>
                    <label class="form-label">ملاحظات داخلية</label>
                    <textarea name="admin_notes" class="form-control" rows="2">{{ old('admin_notes') }}</textarea>
                </div>
            </div>

            <div style="margin-bottom:22px">
                <label class="form-label">ملف مرفق</label>
                <input type="file" name="attachment" class="form-control" accept=".pdf,.jpg,.jpeg,.png,.doc,.docx">
            </div>

            <hr style="border:none;border-top:1px solid var(--border-color,rgba(0,0,0,0.08));margin-bottom:22px">

            {{-- ────────────────────────────────────────
                 ربط بحساب — Card Selector
            ──────────────────────────────────────── --}}
            <div style="margin-bottom:20px">
                <label style="display:block;font-weight:700;font-size:13px;margin-bottom:12px;color:var(--text-muted)">
                    <i class="fas fa-link" style="margin-left:5px"></i> ربط بحساب
                </label>

                <div class="link-type-selector">

                    {{-- بدون حساب --}}
                    <label class="link-type-card card-none">
                        <input type="radio" name="link_type" value="none"
                               {{ old('link_type','none')==='none'?'checked':'' }}
                               class="link-radio">
                        <div class="link-type-card-body">
                            <div class="link-type-card-icon">
                                <i class="fas fa-user-slash"></i>
                            </div>
                            <div>
                                <div class="link-type-card-label">بدون حساب</div>
                                <div class="link-type-card-desc">تسجيل مندوب بدون ربط بالنظام</div>
                            </div>
                        </div>
                    </label>

                    {{-- ربط بحساب موجود --}}
                    <label class="link-type-card card-existing">
                        <input type="radio" name="link_type" value="existing"
                               {{ old('link_type')==='existing'?'checked':'' }}
                               class="link-radio">
                        <div class="link-type-card-body">
                            <div class="link-type-card-icon">
                                <i class="fas fa-link"></i>
                            </div>
                            <div>
                                <div class="link-type-card-label">ربط بحساب موجود</div>
                                <div class="link-type-card-desc">ابحث عن مستخدم موجود في النظام</div>
                            </div>
                        </div>
                    </label>

                    {{-- إنشاء حساب جديد --}}
                    <label class="link-type-card card-create">
                        <input type="radio" name="link_type" value="create"
                               {{ old('link_type')==='create'?'checked':'' }}
                               class="link-radio">
                        <div class="link-type-card-body">
                            <div class="link-type-card-icon">
                                <i class="fas fa-user-plus"></i>
                            </div>
                            <div>
                                <div class="link-type-card-label">إنشاء حساب جديد</div>
                                <div class="link-type-card-desc">إنشاء حساب جديد وربطه تلقائياً</div>
                            </div>
                        </div>
                    </label>

                </div><!-- end .link-type-selector -->

                {{-- Panel: ربط بحساب موجود --}}
                <div id="section-existing" class="link-panel">
                    <div style="padding:16px;background:rgba(14,165,233,0.04);border:1px solid rgba(14,165,233,0.15);border-radius:12px">
                        <div style="display:flex;gap:8px;align-items:flex-end">
                            <div style="flex:1">
                                <label class="form-label" style="color:#0369a1">
                                    <i class="fas fa-search" style="margin-left:5px;font-size:11px"></i>
                                    اسم المستخدم / البريد الإلكتروني
                                </label>
                                <input type="text" id="search_username" class="form-control"
                                       placeholder="ابحث بالاسم أو البريد أو اسم المستخدم..."
                                       style="border-color:rgba(14,165,233,0.3)">
                            </div>
                            <button type="button" id="btn_search" class="btn btn-gold" style="padding:9px 18px;white-space:nowrap">
                                <i class="fas fa-search"></i> بحث
                            </button>
                        </div>
                        <input type="hidden" name="user_id" id="found_user_id">

                        <div id="user_result" class="user-found-card" style="display:none">
                            <div id="res_type_icon" class="user-found-avatar"></div>
                            <div style="flex:1">
                                <div style="font-weight:700;font-size:14px" id="res_name"></div>
                                <div style="font-size:12px;color:var(--text-muted)" id="res_email"></div>
                            </div>
                            <span id="res_type_badge" style="font-size:11px;font-weight:700;padding:4px 12px;border-radius:20px"></span>
                            <button type="button" id="btn_clear_user"
                                    style="padding:4px 8px;border-radius:6px;background:rgba(239,68,68,0.08);color:#dc2626;font-size:11px;border:none;cursor:pointer">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>

                        <div id="user_error" class="user-not-found" style="display:none">
                            <i class="fas fa-exclamation-circle"></i>
                            <span id="user_error_msg"></span>
                        </div>
                    </div>
                </div>

                {{-- Panel: إنشاء حساب جديد --}}
                <div id="section-create" class="link-panel">
                    <div style="padding:16px;background:rgba(34,197,94,0.04);border:1px solid rgba(34,197,94,0.15);border-radius:12px">
                        <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px">
                            <div>
                                <label class="form-label" style="color:#15803d">
                                    <i class="fas fa-envelope" style="margin-left:5px;font-size:11px"></i>
                                    البريد الإلكتروني
                                </label>
                                <input type="email" name="new_email" class="form-control"
                                       value="{{ old('new_email') }}"
                                       placeholder="example@email.com"
                                       style="border-color:rgba(34,197,94,0.3)">
                            </div>
                            <div>
                                <label class="form-label" style="color:#15803d">
                                    <i class="fas fa-lock" style="margin-left:5px;font-size:11px"></i>
                                    كلمة المرور
                                </label>
                                <div style="position:relative">
                                    <input type="password" name="new_password" id="new_password_input"
                                           class="form-control" placeholder="8 أحرف على الأقل"
                                           style="border-color:rgba(34,197,94,0.3);padding-left:36px">
                                    <button type="button" id="toggle_pw"
                                            style="position:absolute;left:8px;top:50%;transform:translateY(-50%);background:none;border:none;cursor:pointer;color:var(--text-muted);padding:0;font-size:14px">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div style="margin-top:10px;font-size:12px;color:var(--text-muted);display:flex;align-items:center;gap:6px">
                            <i class="fas fa-info-circle" style="color:#22c55e"></i>
                            سيُنشأ حساب تلقائياً بدور <strong style="color:#15803d">مندوب (agent)</strong> ويُربط فوراً.
                        </div>
                    </div>
                </div>

            </div><!-- end ربط بحساب -->

            <div style="display:flex;gap:10px;justify-content:flex-end;padding-top:10px">
                <a href="{{ route('admin.agents.index') }}" class="btn" style="background:rgba(0,0,0,0.06)">
                    <i class="fas fa-times"></i> إلغاء
                </a>
                <button type="submit" class="btn btn-gold">
                    <i class="fas fa-save"></i> حفظ
                </button>
            </div>
        </form>
    </div>
</div>

<script>
const PARTNER_ROLES = ['shop_admin','super_admin','admin'];

function detectType(role) {
    return PARTNER_ROLES.includes(role) ? 'partner' : 'agent';
}

function toggleSection() {
    const checked = document.querySelector('.link-radio:checked');
    if (!checked) return;
    const val = checked.value;
    document.querySelectorAll('.link-panel').forEach(p => p.classList.remove('active'));
    if (val === 'existing') document.getElementById('section-existing').classList.add('active');
    if (val === 'create')   document.getElementById('section-create').classList.add('active');
}

document.querySelectorAll('.link-radio').forEach(r => r.addEventListener('change', toggleSection));
toggleSection();

document.getElementById('toggle_pw')?.addEventListener('click', function() {
    const inp  = document.getElementById('new_password_input');
    const icon = this.querySelector('i');
    if (inp.type === 'password') {
        inp.type = 'text';
        icon.className = 'fas fa-eye-slash';
    } else {
        inp.type = 'password';
        icon.className = 'fas fa-eye';
    }
});

document.getElementById('btn_search')?.addEventListener('click', doSearch);
document.getElementById('search_username')?.addEventListener('keydown', e => {
    if (e.key === 'Enter') { e.preventDefault(); doSearch(); }
});

document.getElementById('btn_clear_user')?.addEventListener('click', () => {
    document.getElementById('user_result').style.display = 'none';
    document.getElementById('found_user_id').value = '';
    document.getElementById('agent_type_hidden').value = 'agent';
    document.getElementById('search_username').value = '';
    document.getElementById('search_username').focus();
});

async function doSearch() {
    const username = document.getElementById('search_username').value.trim();
    if (!username) return;

    const errEl = document.getElementById('user_error');
    const resEl = document.getElementById('user_result');
    errEl.style.display = 'none';
    resEl.style.display = 'none';
    document.getElementById('found_user_id').value = '';
    document.getElementById('agent_type_hidden').value = 'agent';

    const btn = document.getElementById('btn_search');
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';

    try {
        const res  = await fetch('{{ route("admin.agents.check-user") }}?username=' + encodeURIComponent(username));
        const data = await res.json();

        if (!data.found) {
            document.getElementById('user_error_msg').textContent = data.message;
            errEl.style.display = 'flex';
            return;
        }

        const detectedType = detectType(data.role ?? '');
        document.getElementById('agent_type_hidden').value = detectedType;
        document.getElementById('found_user_id').value      = data.user_id;

        const icon  = document.getElementById('res_type_icon');
        const badge = document.getElementById('res_type_badge');

        if (detectedType === 'partner') {
            icon.style.background = 'linear-gradient(135deg,#6366f1,#8b5cf6)';
            icon.innerHTML = '<i class="fas fa-handshake" style="color:#fff"></i>';
            badge.style.background = 'rgba(99,102,241,0.12)';
            badge.style.color      = '#4f46e5';
            badge.textContent      = 'شراكة';
        } else {
            icon.style.background = 'linear-gradient(135deg,#fbbf24,#f59e0b)';
            icon.innerHTML = '<i class="fas fa-user-tie" style="color:#fff"></i>';
            badge.style.background = 'rgba(251,191,36,0.15)';
            badge.style.color      = '#b45309';
            badge.textContent      = 'مندوب';
        }

        document.getElementById('res_name').textContent  = data.name;
        document.getElementById('res_email').textContent = data.email;
        resEl.style.display = 'flex';

    } catch(e) {
        document.getElementById('user_error_msg').textContent = 'حدث خطأ أثناء البحث. حاول مجدداً.';
        errEl.style.display = 'flex';
    } finally {
        btn.disabled = false;
        btn.innerHTML = '<i class="fas fa-search"></i> بحث';
    }
}

// Country search
const countries = [
    {name:'الأردن',en:'Jordan'},{name:'فلسطين',en:'Palestine'},{name:'سوريا',en:'Syria'},{name:'لبنان',en:'Lebanon'},{name:'مصر',en:'Egypt'},
    {name:'المملكة العربية السعودية',en:'Saudi Arabia'},{name:'الإمارات',en:'UAE'},{name:'الكويت',en:'Kuwait'},{name:'قطر',en:'Qatar'},{name:'البحرين',en:'Bahrain'},
    {name:'عمان',en:'Oman'},{name:'اليمن',en:'Yemen'},{name:'العراق',en:'Iraq'},{name:'تركيا',en:'Turkey'},
    {name:'ألمانيا',en:'Germany'},{name:'المملكة المتحدة',en:'United Kingdom'},{name:'فرنسا',en:'France'},{name:'إيطاليا',en:'Italy'},
    {name:'أستراليا',en:'Australia'},{name:'كندا',en:'Canada'},{name:'الولايات المتحدة',en:'United States'},
    {name:'الصين',en:'China'},{name:'اليابان',en:'Japan'},{name:'الهند',en:'India'},{name:'باكستان',en:'Pakistan'},
    {name:'المغرب',en:'Morocco'},{name:'الجزائر',en:'Algeria'},{name:'تونس',en:'Tunisia'},{name:'السودان',en:'Sudan'},
    {name:'إندونيسيا',en:'Indonesia'},{name:'ماليزيا',en:'Malaysia'},{name:'روسيا',en:'Russia'},
    {name:'أوكرانيا',en:'Ukraine'},{name:'اليونان',en:'Greece'},{name:'البرتغال',en:'Portugal'}
];
const inp = document.getElementById('country_search');
const dd  = document.getElementById('country_dropdown');
const hid = document.getElementById('country_value');
if (hid.value) inp.value = hid.value;
inp.addEventListener('input', () => {
    const q = inp.value.trim().toLowerCase();
    dd.innerHTML = '';
    if (!q) { dd.style.display='none'; return; }
    const r = countries.filter(c => c.name.includes(q) || c.en.toLowerCase().includes(q)).slice(0,8);
    if (!r.length) { dd.style.display='none'; return; }
    r.forEach(c => {
        const d = document.createElement('div');
        d.style.cssText = 'padding:9px 12px;cursor:pointer;font-size:13px;border-bottom:1px solid rgba(0,0,0,0.05)';
        d.innerHTML = `🌐 <strong>${c.name}</strong> <span style="color:#aaa;font-size:11px">${c.en}</span>`;
        d.addEventListener('click',()=>{ inp.value=c.name; hid.value=c.name; dd.style.display='none'; });
        d.addEventListener('mouseover',()=>d.style.background='rgba(0,0,0,0.04)');
        d.addEventListener('mouseout', ()=>d.style.background='');
        dd.appendChild(d);
    });
    dd.style.display = 'block';
});
document.addEventListener('click', e => {
    if (!e.target.closest('#country_search') && !e.target.closest('#country_dropdown')) dd.style.display='none';
});
</script>
@endsection
