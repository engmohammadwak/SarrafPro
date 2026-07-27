@extends('layouts.admin')
@section('title', 'إضافة مندوب')
@section('page-title', 'إضافة مندوب')
@section('content')
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

            {{-- نوع مخفي يتحدد تلقائياً عند البحث --}}
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

            {{-- ربط بحساب --}}
            <div style="margin-bottom:18px">
                <label style="display:block;font-weight:700;font-size:13px;margin-bottom:10px;color:var(--text-muted)">
                    <i class="fas fa-link" style="margin-left:5px"></i> ربط بحساب
                </label>
                <div style="display:flex;gap:10px;margin-bottom:14px;flex-wrap:wrap">
                    <label style="display:flex;align-items:center;gap:7px;cursor:pointer;padding:8px 14px;border-radius:8px;border:1px solid rgba(0,0,0,0.1);font-size:13px">
                        <input type="radio" name="link_type" value="none" {{ old('link_type','none')==='none'?'checked':'' }} class="link-radio"> بدون حساب
                    </label>
                    <label style="display:flex;align-items:center;gap:7px;cursor:pointer;padding:8px 14px;border-radius:8px;border:1px solid rgba(0,0,0,0.1);font-size:13px">
                        <input type="radio" name="link_type" value="existing" {{ old('link_type')==='existing'?'checked':'' }} class="link-radio"> ربط بحساب موجود
                    </label>
                    <label style="display:flex;align-items:center;gap:7px;cursor:pointer;padding:8px 14px;border-radius:8px;border:1px solid rgba(0,0,0,0.1);font-size:13px">
                        <input type="radio" name="link_type" value="create" {{ old('link_type')==='create'?'checked':'' }} class="link-radio"> إنشاء حساب جديد
                    </label>
                </div>

                {{-- ربط بحساب موجود --}}
                <div id="section-existing" style="display:none">
                    <div style="display:flex;gap:8px;align-items:flex-end">
                        <div style="flex:1">
                            <label class="form-label">اسم المستخدم / البريد</label>
                            <input type="text" id="search_username" class="form-control" placeholder="اكتب للبحث...">
                        </div>
                        <button type="button" id="btn_search" class="btn btn-gold" style="padding:9px 18px">
                            <i class="fas fa-search"></i> بحث
                        </button>
                    </div>
                    <input type="hidden" name="user_id" id="found_user_id">

                    {{-- نتيجة البحث: نجاح --}}
                    <div id="user_result" style="display:none;margin-top:12px;padding:14px 16px;border-radius:10px;border:1px solid rgba(34,197,94,0.3);background:rgba(34,197,94,0.06)">
                        <div style="display:flex;align-items:center;gap:10px">
                            <div id="res_type_icon" style="width:36px;height:36px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:16px;flex-shrink:0"></div>
                            <div style="flex:1">
                                <div style="font-weight:700;font-size:14px" id="res_name"></div>
                                <div style="font-size:12px;color:var(--text-muted)" id="res_email"></div>
                            </div>
                            <div id="res_type_badge" style="font-size:11px;font-weight:700;padding:3px 10px;border-radius:20px"></div>
                        </div>
                    </div>

                    {{-- نتيجة البحث: خطأ --}}
                    <div id="user_error" style="display:none;margin-top:10px;padding:10px 14px;background:rgba(239,68,68,0.08);border:1px solid rgba(239,68,68,0.2);border-radius:8px;color:#dc2626;font-size:13px"></div>
                </div>

                {{-- إنشاء حساب جديد --}}
                <div id="section-create" style="display:none">
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px">
                        <div>
                            <label class="form-label">بريد إلكتروني</label>
                            <input type="email" name="new_email" class="form-control" value="{{ old('new_email') }}">
                        </div>
                        <div>
                            <label class="form-label">كلمة المرور</label>
                            <input type="password" name="new_password" class="form-control">
                        </div>
                    </div>
                    <div style="margin-top:8px;font-size:12px;color:var(--text-muted)">سيُنشأ حساب بدور <strong>agent</strong> تلقائياً.</div>
                </div>
            </div>

            <div style="display:flex;gap:10px;justify-content:flex-end;padding-top:10px">
                <a href="{{ route('admin.agents.index') }}" class="btn" style="background:rgba(0,0,0,0.06)"><i class="fas fa-times"></i> إلغاء</a>
                <button type="submit" class="btn btn-gold"><i class="fas fa-save"></i> حفظ</button>
            </div>
        </form>
    </div>
</div>

<script>
// جدول الأدوار التي تعني شراكة (partner)
const PARTNER_ROLES = ['shop_admin','super_admin','admin'];

function detectType(role) {
    return PARTNER_ROLES.includes(role) ? 'partner' : 'agent';
}

// تبديل نوع الربط
document.querySelectorAll('.link-radio').forEach(r => r.addEventListener('change', toggleSection));
function toggleSection() {
    const v = document.querySelector('.link-radio:checked').value;
    document.getElementById('section-existing').style.display = v === 'existing' ? 'block' : 'none';
    document.getElementById('section-create').style.display   = v === 'create'   ? 'block' : 'none';
}
toggleSection();

// بحث عن مستخدم
document.getElementById('btn_search').addEventListener('click', doSearch);
document.getElementById('search_username').addEventListener('keydown', e => { if(e.key==='Enter'){e.preventDefault();doSearch();} });

async function doSearch() {
    const username = document.getElementById('search_username').value.trim();
    if (!username) return;

    // نبحث بـ agent_type=partner لتجاوز التحقق ونحدد النوع بعد الاستجابة
    const res  = await fetch('{{ route("admin.agents.check-user") }}?username=' + encodeURIComponent(username) + '&agent_type=partner');
    const data = await res.json();

    document.getElementById('user_result').style.display = 'none';
    document.getElementById('user_error').style.display  = 'none';
    document.getElementById('found_user_id').value = '';
    document.getElementById('agent_type_hidden').value = 'agent'; // إعادة التعيين

    if (!data.found) {
        document.getElementById('user_error').textContent   = data.message;
        document.getElementById('user_error').style.display = 'block';
        return;
    }

    // تحديد النوع تلقائياً
    const detectedType = detectType(data.role ?? '');
    document.getElementById('agent_type_hidden').value = detectedType;

    const isPartner = detectedType === 'partner';
    const icon   = document.getElementById('res_type_icon');
    const badge  = document.getElementById('res_type_badge');

    if (isPartner) {
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
    document.getElementById('found_user_id').value   = data.user_id;
    document.getElementById('user_result').style.display = 'block';
}

// بحث الدولة
const countries = [
    {name:'الأردن',en:'Jordan'},{name:'فلسطين',en:'Palestine'},{name:'سوريا',en:'Syria'},{name:'لبنان',en:'Lebanon'},{name:'مصر',en:'Egypt'},
    {name:'المملكة العربية السعودية',en:'Saudi Arabia'},{name:'الإمارات',en:'UAE'},{name:'الكويت',en:'Kuwait'},{name:'قطر',en:'Qatar'},{name:'البحرين',en:'Bahrain'},
    {name:'عمان',en:'Oman'},{name:'اليمن',en:'Yemen'},{name:'العراق',en:'Iraq'},{name:'تركيا',en:'Turkey'},{name:'تركيا',en:'Türkiye'},
    {name:'ألمانيا',en:'Germany'},{name:'المملكة المتحدة',en:'United Kingdom'},{name:'فرنسا',en:'France'},{name:'إيطاليا',en:'Italy'},{name:'إسبانيا',en:'Spain'},
    {name:'هولندا',en:'Netherlands'},{name:'بلجيكا',en:'Belgium'},{name:'سويسرا',en:'Switzerland'},{name:'السويد',en:'Sweden'},{name:'النرويج',en:'Norway'},
    {name:'الدنمارك',en:'Denmark'},{name:'فنلندا',en:'Finland'},{name:'بولندا',en:'Poland'},{name:'أستراليا',en:'Australia'},{name:'كندا',en:'Canada'},
    {name:'الولايات المتحدة',en:'United States'},{name:'البرازيل',en:'Brazil'},{name:'المكسيك',en:'Mexico'},{name:'الصين',en:'China'},{name:'اليابان',en:'Japan'},
    {name:'كوريا الجنوبية',en:'South Korea'},{name:'الهند',en:'India'},{name:'باكستان',en:'Pakistan'},{name:'إيران',en:'Iran'},{name:'أفغانستان',en:'Afghanistan'},
    {name:'المغرب',en:'Morocco'},{name:'الجزائر',en:'Algeria'},{name:'تونس',en:'Tunisia'},{name:'ليبيا',en:'Libya'},{name:'السودان',en:'Sudan'},
    {name:'الصومال',en:'Somalia'},{name:'إثيوبيا',en:'Ethiopia'},{name:'كينيا',en:'Kenya'},{name:'نيجيريا',en:'Nigeria'},{name:'جنوب أفريقيا',en:'South Africa'},
    {name:'إندونيسيا',en:'Indonesia'},{name:'ماليزيا',en:'Malaysia'},{name:'سنغافورة',en:'Singapore'},{name:'تايلاند',en:'Thailand'},{name:'فيتنام',en:'Vietnam'},
    {name:'أوكرانيا',en:'Ukraine'},{name:'روسيا',en:'Russia'},{name:'بيلاروسيا',en:'Belarus'},{name:'رومانيا',en:'Romania'},{name:'بلغاريا',en:'Bulgaria'},
    {name:'اليونان',en:'Greece'},{name:'البرتغال',en:'Portugal'},{name:'النمسا',en:'Austria'},{name:'تشيكيا',en:'Czech Republic'},{name:'المجر',en:'Hungary'},
    {name:'سلوفاكيا',en:'Slovakia'},{name:'كازاخستان',en:'Kazakhstan'},{name:'أذربيجان',en:'Azerbaijan'},{name:'جورجيا',en:'Georgia'},{name:'أرمينيا',en:'Armenia'}
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
        d.addEventListener('click',() => { inp.value=c.name; hid.value=c.name; dd.style.display='none'; });
        d.addEventListener('mouseover',()=>d.style.background='rgba(0,0,0,0.04)');
        d.addEventListener('mouseout', ()=>d.style.background='');
        dd.appendChild(d);
    });
    dd.style.display = 'block';
});
document.addEventListener('click', e => { if (!e.target.closest('#country_search') && !e.target.closest('#country_dropdown')) dd.style.display='none'; });
</script>
@endsection
