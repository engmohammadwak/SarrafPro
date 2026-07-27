@extends('layouts.admin')
@section('title', 'إضافة مندوب')
@section('page-title', 'إضافة مندوب')
@section('content')

<style>
/* ===== Link Type Selector ===== */
.link-type-selector {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 12px;
    margin-bottom: 20px;
}
.link-type-card { position: relative; cursor: pointer; }
.link-type-card input[type="radio"] {
    position: absolute; opacity: 0; width: 0; height: 0; pointer-events: none;
}
.link-type-card-body {
    display: flex; flex-direction: column; align-items: center; justify-content: center;
    gap: 8px; padding: 18px 14px 16px; border-radius: 14px;
    border: 2px solid var(--border-color, rgba(0,0,0,0.09));
    background: var(--card-bg, #fff);
    text-align: center; cursor: pointer;
    transition: all 0.18s cubic-bezier(0.16, 1, 0.3, 1);
    user-select: none; position: relative;
    box-shadow: 0 1px 4px rgba(0,0,0,0.04);
}
.link-type-card-body:hover {
    border-color: var(--accent, #f59e0b);
    background: rgba(245,158,11,0.04);
    transform: translateY(-2px);
    box-shadow: 0 6px 18px rgba(245,158,11,0.13);
}
.link-type-card-icon {
    width: 46px; height: 46px; border-radius: 12px;
    display: flex; align-items: center; justify-content: center;
    font-size: 20px; transition: all 0.18s ease;
}
.link-type-card-label { font-size: 13px; font-weight: 700; color: var(--text-primary,#1a1a2e); line-height: 1.3; transition: color 0.18s; }
.link-type-card-desc  { font-size: 11px; color: var(--text-muted,#8b8fa8); line-height: 1.4; margin-top: 2px; }

/* بدون حساب */
.link-type-card.card-none input:checked ~ .link-type-card-body { border-color:rgba(100,116,139,0.5); background:rgba(100,116,139,0.06); box-shadow:0 0 0 3px rgba(100,116,139,0.12); }
.link-type-card.card-none input:checked ~ .link-type-card-body .link-type-card-icon { background:rgba(100,116,139,0.12); color:#475569; }
.link-type-card.card-none input:checked ~ .link-type-card-body .link-type-card-label { color:#334155; }
/* ربط موجود */
.link-type-card.card-existing input:checked ~ .link-type-card-body { border-color:rgba(14,165,233,0.55); background:rgba(14,165,233,0.06); box-shadow:0 0 0 3px rgba(14,165,233,0.14); }
.link-type-card.card-existing input:checked ~ .link-type-card-body .link-type-card-icon { background:rgba(14,165,233,0.12); color:#0284c7; }
.link-type-card.card-existing input:checked ~ .link-type-card-body .link-type-card-label { color:#0369a1; }
/* إنشاء جديد */
.link-type-card.card-create input:checked ~ .link-type-card-body { border-color:rgba(34,197,94,0.45); background:rgba(34,197,94,0.06); box-shadow:0 0 0 3px rgba(34,197,94,0.13); }
.link-type-card.card-create input:checked ~ .link-type-card-body .link-type-card-icon { background:rgba(34,197,94,0.12); color:#16a34a; }
.link-type-card.card-create input:checked ~ .link-type-card-body .link-type-card-label { color:#15803d; }

/* Default icon colours */
.link-type-card.card-none .link-type-card-icon     { background:rgba(100,116,139,0.08); color:#94a3b8; }
.link-type-card.card-existing .link-type-card-icon { background:rgba(14,165,233,0.08);  color:#38bdf8; }
.link-type-card.card-create .link-type-card-icon   { background:rgba(34,197,94,0.08);   color:#4ade80; }

/* Checkmark badge */
.link-type-card-body::after {
    content:""; position:absolute; top:10px; left:10px; width:18px; height:18px;
    border-radius:50%; background:transparent;
    border:2px solid var(--border-color,rgba(0,0,0,0.09)); transition:all 0.18s ease;
}
.link-type-card.card-none input:checked ~ .link-type-card-body::after,
.link-type-card.card-existing input:checked ~ .link-type-card-body::after,
.link-type-card.card-create input:checked ~ .link-type-card-body::after {
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 12 12' fill='none'%3E%3Cpath d='M2 6l3 3 5-5' stroke='white' stroke-width='1.8' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E");
    background-size:10px; background-repeat:no-repeat; background-position:center;
}
.link-type-card.card-none     input:checked ~ .link-type-card-body::after { background-color:#64748b; border-color:#64748b; }
.link-type-card.card-existing input:checked ~ .link-type-card-body::after { background-color:#0ea5e9; border-color:#0ea5e9; }
.link-type-card.card-create   input:checked ~ .link-type-card-body::after { background-color:#22c55e; border-color:#22c55e; }

/* Panels */
.link-panel { display:none; }
.link-panel.active { display:block; animation:panelSlideIn 0.22s cubic-bezier(0.16,1,0.3,1) both; }
@keyframes panelSlideIn { from{opacity:0;transform:translateY(-6px)} to{opacity:1;transform:translateY(0)} }

/* User result card */
.user-found-card {
    display:flex; align-items:center; gap:12px; padding:14px 16px;
    border-radius:10px; border:1px solid rgba(34,197,94,0.25);
    background:rgba(34,197,94,0.05); margin-top:12px;
    animation:panelSlideIn 0.2s ease both;
}
.user-found-avatar { width:40px; height:40px; border-radius:50%; display:flex; align-items:center; justify-content:center; font-size:16px; flex-shrink:0; }
.user-not-found {
    display:flex; align-items:center; gap:8px; margin-top:10px; padding:10px 14px;
    background:rgba(239,68,68,0.07); border:1px solid rgba(239,68,68,0.2);
    border-radius:8px; color:#dc2626; font-size:13px; animation:panelSlideIn 0.2s ease both;
}

/* ===== Form Fields Fix ===== */
.form-section-title {
    font-size: 12px;
    font-weight: 700;
    color: var(--text-muted, #8b8fa8);
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 14px;
    display: flex;
    align-items: center;
    gap: 8px;
}
.form-section-title::after {
    content: '';
    flex: 1;
    height: 1px;
    background: var(--border-color, rgba(0,0,0,0.08));
}
.form-row {
    display: grid;
    gap: 16px;
    margin-bottom: 16px;
}
.form-row.cols-2 { grid-template-columns: 1fr 1fr; }
.form-row.cols-1 { grid-template-columns: 1fr; }

.field-group { display: flex; flex-direction: column; gap: 6px; }
.field-label {
    font-size: 13px;
    font-weight: 600;
    color: var(--text-primary, #1a1a2e);
    display: flex;
    align-items: center;
    gap: 6px;
}
.field-label .required { color: #ef4444; }
.field-label i { font-size: 11px; color: var(--text-muted, #8b8fa8); }

.field-input {
    width: 100%;
    padding: 10px 14px;
    border-radius: 10px;
    border: 1.5px solid var(--border-color, rgba(0,0,0,0.1));
    background: var(--input-bg, rgba(255,255,255,0.8));
    color: var(--text-primary, #1a1a2e);
    font-size: 14px;
    transition: border-color 0.15s, box-shadow 0.15s;
    font-family: inherit;
    box-sizing: border-box;
}
.field-input:focus {
    outline: none;
    border-color: var(--accent, #f59e0b);
    box-shadow: 0 0 0 3px rgba(245,158,11,0.12);
    background: var(--card-bg, #fff);
}
.field-input::placeholder { color: var(--text-muted, #aaa); font-size: 13px; }
textarea.field-input { resize: vertical; min-height: 90px; }

/* File input custom */
.file-input-wrapper {
    position: relative;
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 10px 14px;
    border-radius: 10px;
    border: 1.5px dashed var(--border-color, rgba(0,0,0,0.15));
    background: var(--input-bg, rgba(0,0,0,0.02));
    cursor: pointer;
    transition: border-color 0.15s;
}
.file-input-wrapper:hover { border-color: var(--accent, #f59e0b); }
.file-input-wrapper input[type="file"] { position:absolute; inset:0; opacity:0; cursor:pointer; width:100%; }
.file-input-label { font-size:13px; color:var(--text-muted,#8b8fa8); display:flex; align-items:center; gap:8px; }
.file-input-label i { font-size:16px; color:var(--accent,#f59e0b); }

/* Password toggle */
.pw-wrapper { position: relative; }
.pw-wrapper .field-input { padding-left: 40px; }
.pw-toggle {
    position: absolute; left: 10px; top: 50%; transform: translateY(-50%);
    background: none; border: none; cursor: pointer;
    color: var(--text-muted, #aaa); padding: 0; font-size: 15px;
    transition: color 0.15s;
}
.pw-toggle:hover { color: var(--accent, #f59e0b); }

/* Country dropdown */
.country-wrapper { position: relative; }
.country-flag { position:absolute; right:12px; top:50%; transform:translateY(-50%); font-size:16px; pointer-events:none; }
.country-wrapper .field-input { padding-right: 38px; }
#country_dropdown {
    display:none; position:absolute; top:calc(100% + 4px); right:0; left:0;
    background: var(--card-bg, #fff);
    border: 1px solid var(--border-color, rgba(0,0,0,0.1));
    border-radius: 10px;
    max-height: 200px; overflow-y: auto;
    z-index: 999;
    box-shadow: 0 8px 24px rgba(0,0,0,0.1);
}
.country-item {
    padding: 9px 14px; cursor: pointer; font-size: 13px;
    border-bottom: 1px solid rgba(0,0,0,0.04);
    transition: background 0.12s;
    display: flex; align-items: center; gap: 8px;
}
.country-item:hover { background: rgba(245,158,11,0.06); }
.country-item:last-child { border-bottom: none; }
.country-en { font-size: 11px; color: var(--text-muted, #aaa); margin-right: auto; }

@media(max-width:640px){
    .link-type-selector { grid-template-columns: 1fr; }
    .form-row.cols-2 { grid-template-columns: 1fr; }
}
</style>

<div class="card" style="max-width:800px;margin:0 auto">
    <div class="card-header">
        <h3><i class="fas fa-user-plus" style="color:var(--accent);margin-left:8px"></i> إضافة مندوب</h3>
        <a href="{{ route('admin.agents.index') }}" class="btn btn-sm" style="background:rgba(0,0,0,0.06)">
            <i class="fas fa-arrow-right"></i> رجوع
        </a>
    </div>
    <div class="card-body">

        @if($errors->any())
        <div style="margin-bottom:20px;padding:12px 16px;border-radius:10px;background:rgba(239,68,68,0.08);color:#dc2626;border:1px solid rgba(239,68,68,0.2)">
            <div style="font-weight:700;margin-bottom:6px;font-size:13px"><i class="fas fa-exclamation-circle" style="margin-left:6px"></i> يرجى تصحيح الأخطاء التالية:</div>
            <ul style="margin:0;padding-right:20px;font-size:13px">
                @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
            </ul>
        </div>
        @endif

        <form action="{{ route('admin.agents.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="agent_type" id="agent_type_hidden" value="agent">

            {{-- ===== ربط بحساب ===== --}}
            <div style="margin-bottom:24px">
                <div class="form-section-title">
                    <i class="fas fa-link"></i> ربط بحساب
                </div>

                <div class="link-type-selector">
                    {{-- 1: بدون حساب --}}
                    <label class="link-type-card card-none">
                        <input type="radio" name="link_type" value="none"
                               {{ old('link_type','none')==='none'?'checked':'' }} class="link-radio">
                        <div class="link-type-card-body">
                            <div class="link-type-card-icon"><i class="fas fa-user-slash"></i></div>
                            <div>
                                <div class="link-type-card-label">بدون حساب</div>
                                <div class="link-type-card-desc">تسجيل مندوب بدون ربط بالنظام</div>
                            </div>
                        </div>
                    </label>

                    {{-- 2: ربط بحساب موجود --}}
                    <label class="link-type-card card-existing">
                        <input type="radio" name="link_type" value="existing"
                               {{ old('link_type')==='existing'?'checked':'' }} class="link-radio">
                        <div class="link-type-card-body">
                            <div class="link-type-card-icon"><i class="fas fa-link"></i></div>
                            <div>
                                <div class="link-type-card-label">ربط بحساب موجود</div>
                                <div class="link-type-card-desc">ابحث عن مستخدم موجود في النظام</div>
                            </div>
                        </div>
                    </label>

                    {{-- 3: إنشاء حساب جديد --}}
                    <label class="link-type-card card-create">
                        <input type="radio" name="link_type" value="create"
                               {{ old('link_type')==='create'?'checked':'' }} class="link-radio">
                        <div class="link-type-card-body">
                            <div class="link-type-card-icon"><i class="fas fa-user-plus"></i></div>
                            <div>
                                <div class="link-type-card-label">إنشاء حساب جديد</div>
                                <div class="link-type-card-desc">إنشاء حساب جديد وربطه تلقائياً</div>
                            </div>
                        </div>
                    </label>
                </div>

                {{-- Panel: ربط بحساب موجود --}}
                <div id="section-existing" class="link-panel">
                    <div style="padding:16px;background:rgba(14,165,233,0.04);border:1px solid rgba(14,165,233,0.15);border-radius:12px">
                        <div style="display:flex;gap:10px;align-items:flex-end">
                            <div style="flex:1">
                                <label class="field-label" style="color:#0369a1;margin-bottom:6px">
                                    <i class="fas fa-search"></i> اسم المستخدم / البريد الإلكتروني
                                </label>
                                <input type="text" id="search_username" class="field-input"
                                       placeholder="ابحث بالاسم أو البريد أو اسم المستخدم..."
                                       style="border-color:rgba(14,165,233,0.3)">
                            </div>
                            <button type="button" id="btn_search" class="btn btn-gold" style="padding:10px 20px;white-space:nowrap;margin-bottom:1px">
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
                                    style="padding:5px 10px;border-radius:8px;background:rgba(239,68,68,0.08);color:#dc2626;font-size:12px;border:1px solid rgba(239,68,68,0.15);cursor:pointer">
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
                        <div class="form-row cols-2">
                            <div class="field-group">
                                <label class="field-label" style="color:#15803d">
                                    <i class="fas fa-envelope"></i> البريد الإلكتروني
                                </label>
                                <input type="email" name="new_email" class="field-input"
                                       value="{{ old('new_email') }}" placeholder="example@email.com"
                                       style="border-color:rgba(34,197,94,0.3)">
                            </div>
                            <div class="field-group">
                                <label class="field-label" style="color:#15803d">
                                    <i class="fas fa-lock"></i> كلمة المرور
                                </label>
                                <div class="pw-wrapper">
                                    <input type="password" name="new_password" id="new_password_input"
                                           class="field-input" placeholder="8 أحرف على الأقل"
                                           style="border-color:rgba(34,197,94,0.3)">
                                    <button type="button" id="toggle_pw" class="pw-toggle">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div style="margin-top:8px;font-size:12px;color:var(--text-muted);display:flex;align-items:center;gap:6px">
                            <i class="fas fa-info-circle" style="color:#22c55e"></i>
                            سيُنشأ حساب تلقائياً بدور <strong style="color:#15803d">مندوب (agent)</strong> ويُربط فوراً.
                        </div>
                    </div>
                </div>
            </div>

            <div style="border-top:1px solid var(--border-color,rgba(0,0,0,0.08));margin-bottom:24px"></div>

            {{-- ===== البيانات الأساسية ===== --}}
            <div class="form-section-title">
                <i class="fas fa-id-card"></i> البيانات الأساسية
            </div>

            {{-- صف 1: الاسم + الهاتف --}}
            <div class="form-row cols-2">
                <div class="field-group">
                    <label class="field-label">
                        <i class="fas fa-user"></i> الاسم <span class="required">*</span>
                    </label>
                    <input type="text" name="name" class="field-input" value="{{ old('name') }}" required placeholder="اسم المندوب">
                </div>
                <div class="field-group">
                    <label class="field-label">
                        <i class="fas fa-phone"></i> الهاتف
                    </label>
                    <input type="text" name="phone" class="field-input" value="{{ old('phone') }}" placeholder="+968 XXXX XXXX">
                </div>
            </div>

            {{-- صف 2: هاتف 2 + الدولة --}}
            <div class="form-row cols-2">
                <div class="field-group">
                    <label class="field-label">
                        <i class="fas fa-phone-alt"></i> هاتف 2
                    </label>
                    <input type="text" name="phone2" class="field-input" value="{{ old('phone2') }}" placeholder="رقم بديل">
                </div>
                <div class="field-group">
                    <label class="field-label">
                        <i class="fas fa-globe"></i> الدولة
                    </label>
                    <div class="country-wrapper">
                        <span class="country-flag">🌐</span>
                        <input type="text" id="country_search" placeholder="ابحث عن دولة..." autocomplete="off" class="field-input">
                        <input type="hidden" name="country" id="country_value" value="{{ old('country') }}">
                        <div id="country_dropdown"></div>
                    </div>
                </div>
            </div>

            {{-- صف 3: الشركة --}}
            <div class="form-row cols-1">
                <div class="field-group">
                    <label class="field-label">
                        <i class="fas fa-building"></i> الشركة
                    </label>
                    <input type="text" name="company" class="field-input" value="{{ old('company') }}" placeholder="اسم الشركة (اختياري)">
                </div>
            </div>

            {{-- صف 4: ملاحظات + ملاحظات داخلية --}}
            <div class="form-row cols-2">
                <div class="field-group">
                    <label class="field-label">
                        <i class="fas fa-comment-alt"></i> ملاحظات
                    </label>
                    <textarea name="notes" class="field-input" rows="3" placeholder="ملاحظات عامة...">{{ old('notes') }}</textarea>
                </div>
                <div class="field-group">
                    <label class="field-label">
                        <i class="fas fa-lock"></i> ملاحظات داخلية
                    </label>
                    <textarea name="admin_notes" class="field-input" rows="3" placeholder="ملاحظات للإدارة فقط...">{{ old('admin_notes') }}</textarea>
                </div>
            </div>

            {{-- ملف مرفق --}}
            <div class="form-row cols-1" style="margin-bottom:24px">
                <div class="field-group">
                    <label class="field-label">
                        <i class="fas fa-paperclip"></i> ملف مرفق
                    </label>
                    <div class="file-input-wrapper">
                        <input type="file" name="attachment" accept=".pdf,.jpg,.jpeg,.png,.doc,.docx">
                        <div class="file-input-label">
                            <i class="fas fa-cloud-upload-alt"></i>
                            <span id="file_name_label">اختر ملف (PDF, صورة, Word)</span>
                        </div>
                    </div>
                </div>
            </div>

            <div style="display:flex;gap:10px;justify-content:flex-end;padding-top:4px">
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
function detectType(role){ return PARTNER_ROLES.includes(role)?'partner':'agent'; }

function toggleSection(){
    const checked = document.querySelector('.link-radio:checked');
    if(!checked) return;
    document.querySelectorAll('.link-panel').forEach(p=>p.classList.remove('active'));
    if(checked.value==='existing') document.getElementById('section-existing').classList.add('active');
    if(checked.value==='create')   document.getElementById('section-create').classList.add('active');
}
document.querySelectorAll('.link-radio').forEach(r=>r.addEventListener('change',toggleSection));
toggleSection();

document.getElementById('toggle_pw')?.addEventListener('click',function(){
    const inp=document.getElementById('new_password_input');
    const icon=this.querySelector('i');
    if(inp.type==='password'){ inp.type='text'; icon.className='fas fa-eye-slash'; }
    else{ inp.type='password'; icon.className='fas fa-eye'; }
});

document.getElementById('btn_search')?.addEventListener('click',doSearch);
document.getElementById('search_username')?.addEventListener('keydown',e=>{ if(e.key==='Enter'){e.preventDefault();doSearch();} });
document.getElementById('btn_clear_user')?.addEventListener('click',()=>{
    document.getElementById('user_result').style.display='none';
    document.getElementById('found_user_id').value='';
    document.getElementById('agent_type_hidden').value='agent';
    document.getElementById('search_username').value='';
    document.getElementById('search_username').focus();
});

async function doSearch(){
    const username=document.getElementById('search_username').value.trim();
    if(!username) return;
    const errEl=document.getElementById('user_error');
    const resEl=document.getElementById('user_result');
    errEl.style.display='none'; resEl.style.display='none';
    document.getElementById('found_user_id').value='';
    document.getElementById('agent_type_hidden').value='agent';
    const btn=document.getElementById('btn_search');
    btn.disabled=true; btn.innerHTML='<i class="fas fa-spinner fa-spin"></i>';
    try{
        const res=await fetch('{{ route("admin.agents.check-user") }}?username='+encodeURIComponent(username));
        const data=await res.json();
        if(!data.found){ document.getElementById('user_error_msg').textContent=data.message; errEl.style.display='flex'; return; }
        const detectedType=detectType(data.role?? '');
        document.getElementById('agent_type_hidden').value=detectedType;
        document.getElementById('found_user_id').value=data.user_id;
        const icon=document.getElementById('res_type_icon');
        const badge=document.getElementById('res_type_badge');
        if(detectedType==='partner'){
            icon.style.background='linear-gradient(135deg,#6366f1,#8b5cf6)';
            icon.innerHTML='<i class="fas fa-handshake" style="color:#fff"></i>';
            badge.style.background='rgba(99,102,241,0.12)'; badge.style.color='#4f46e5'; badge.textContent='شراكة';
        } else {
            icon.style.background='linear-gradient(135deg,#fbbf24,#f59e0b)';
            icon.innerHTML='<i class="fas fa-user-tie" style="color:#fff"></i>';
            badge.style.background='rgba(251,191,36,0.15)'; badge.style.color='#b45309'; badge.textContent='مندوب';
        }
        document.getElementById('res_name').textContent=data.name;
        document.getElementById('res_email').textContent=data.email;
        resEl.style.display='flex';
    } catch(e){
        document.getElementById('user_error_msg').textContent='حدث خطأ أثناء البحث. حاول مجدداً.';
        errEl.style.display='flex';
    } finally{
        btn.disabled=false; btn.innerHTML='<i class="fas fa-search"></i> بحث';
    }
}

// File input label
document.querySelector('input[name="attachment"]')?.addEventListener('change',function(){
    const label=document.getElementById('file_name_label');
    if(this.files.length) label.textContent=this.files[0].name;
    else label.textContent='اختر ملف (PDF, صورة, Word)';
});

// Country search
const countries=[
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
const cInp=document.getElementById('country_search');
const cDd=document.getElementById('country_dropdown');
const cHid=document.getElementById('country_value');
if(cHid.value) cInp.value=cHid.value;
cInp.addEventListener('input',()=>{
    const q=cInp.value.trim().toLowerCase();
    cDd.innerHTML='';
    if(!q){cDd.style.display='none';return;}
    const r=countries.filter(c=>c.name.includes(q)||c.en.toLowerCase().includes(q)).slice(0,8);
    if(!r.length){cDd.style.display='none';return;}
    r.forEach(c=>{
        const d=document.createElement('div');
        d.className='country-item';
        d.innerHTML=`<span>🌐</span><strong>${c.name}</strong><span class="country-en">${c.en}</span>`;
        d.addEventListener('click',()=>{cInp.value=c.name;cHid.value=c.name;cDd.style.display='none';});
        cDd.appendChild(d);
    });
    cDd.style.display='block';
});
document.addEventListener('click',e=>{
    if(!e.target.closest('#country_search')&&!e.target.closest('#country_dropdown')) cDd.style.display='none';
});
</script>
@endsection
