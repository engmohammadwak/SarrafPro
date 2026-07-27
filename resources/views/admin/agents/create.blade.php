@extends('layouts.admin')
@section('title', 'إضافة مندوب / شريك')
@section('page-title', 'إضافة مندوب / شريك')
@section('content')
<div class="card" style="max-width:760px;margin:0 auto">
    <div class="card-header">
        <h3><i class="fas fa-user-plus" style="color:var(--accent);margin-left:8px"></i> إضافة مندوب / شريك</h3>
        <a href="{{ route('admin.agents.index') }}" class="btn btn-sm" style="background:rgba(0,0,0,0.06)">
            <i class="fas fa-arrow-right"></i> رجوع
        </a>
    </div>
    <div class="card-body">

        @if($errors->any())
        <div class="alert alert-danger" style="margin-bottom:20px;padding:12px 16px;border-radius:10px;background:rgba(239,68,68,0.1);color:#dc2626;border:1px solid rgba(239,68,68,0.25)">
            <ul style="margin:0;padding-right:20px">
                @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
            </ul>
        </div>
        @endif

        <form action="{{ route('admin.agents.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            {{-- نوع السجل --}}
            <div style="margin-bottom:22px">
                <label style="display:block;font-weight:700;font-size:13px;margin-bottom:10px;color:var(--text-muted)"><i class="fas fa-tag" style="margin-left:5px"></i> نوع السجل</label>
                <div style="display:flex;gap:12px">
                    <label style="flex:1;cursor:pointer">
                        <input type="radio" name="agent_type" value="agent" checked style="display:none" class="agent-type-radio">
                        <div class="type-card" data-val="agent" style="border:2px solid rgba(251,191,36,0.4);border-radius:12px;padding:14px 16px;text-align:center;transition:all .2s;background:rgba(251,191,36,0.06)">
                            <i class="fas fa-user-tie" style="font-size:22px;color:#b45309;margin-bottom:6px;display:block"></i>
                            <div style="font-weight:700;font-size:14px;color:#b45309">مندوب</div>
                            <div style="font-size:11px;color:var(--text-muted);margin-top:3px">حساب دوره agent</div>
                        </div>
                    </label>
                    <label style="flex:1;cursor:pointer">
                        <input type="radio" name="agent_type" value="partner" style="display:none" class="agent-type-radio">
                        <div class="type-card" data-val="partner" style="border:2px solid rgba(99,102,241,0.2);border-radius:12px;padding:14px 16px;text-align:center;transition:all .2s">
                            <i class="fas fa-handshake" style="font-size:22px;color:#6366f1;margin-bottom:6px;display:block"></i>
                            <div style="font-weight:700;font-size:14px;color:#4f46e5">شراكة</div>
                            <div style="font-size:11px;color:var(--text-muted);margin-top:3px">أي حساب (shop_admin ،…)</div>
                        </div>
                    </label>
                </div>
            </div>

            <hr style="border:none;border-top:1px solid var(--border-color,rgba(0,0,0,0.08));margin-bottom:22px">

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
                <label style="display:block;font-weight:700;font-size:13px;margin-bottom:10px;color:var(--text-muted)"><i class="fas fa-link" style="margin-left:5px"></i> ربط بحساب</label>
                <div style="display:flex;gap:10px;margin-bottom:14px">
                    <label style="display:flex;align-items:center;gap:7px;cursor:pointer;padding:8px 14px;border-radius:8px;border:1px solid rgba(0,0,0,0.1);font-size:13px" id="lbl-none">
                        <input type="radio" name="link_type" value="none" {{ old('link_type','none')==='none'?'checked':'' }} class="link-radio"> بدون حساب
                    </label>
                    <label style="display:flex;align-items:center;gap:7px;cursor:pointer;padding:8px 14px;border-radius:8px;border:1px solid rgba(0,0,0,0.1);font-size:13px" id="lbl-existing">
                        <input type="radio" name="link_type" value="existing" {{ old('link_type')==='existing'?'checked':'' }} class="link-radio"> ربط بحساب موجود
                    </label>
                    <label style="display:flex;align-items:center;gap:7px;cursor:pointer;padding:8px 14px;border-radius:8px;border:1px solid rgba(0,0,0,0.1);font-size:13px" id="lbl-create">
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
                    <div id="user_result" style="display:none;margin-top:12px;padding:14px;background:rgba(34,197,94,0.07);border:1px solid rgba(34,197,94,0.25);border-radius:10px">
                        <div style="font-weight:700;margin-bottom:4px" id="res_name"></div>
                        <div style="font-size:12px;color:var(--text-muted)" id="res_email"></div>
                    </div>
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
// سلوك بطاقة النوع
const typeCards = document.querySelectorAll('.agent-type-radio');
typeCards.forEach(r => {
    r.addEventListener('change', () => {
        document.querySelectorAll('.type-card').forEach(c => {
            const isActive = c.dataset.val === r.value;
            if (r.value === 'partner') {
                c.style.borderColor  = isActive ? 'rgba(99,102,241,0.7)' : 'rgba(0,0,0,0.1)';
                c.style.background   = isActive ? 'rgba(99,102,241,0.08)' : '';
                c.style.boxShadow    = isActive ? '0 0 0 3px rgba(99,102,241,0.12)' : '';
            } else {
                c.style.borderColor  = isActive ? 'rgba(251,191,36,0.7)' : 'rgba(0,0,0,0.1)';
                c.style.background   = isActive ? 'rgba(251,191,36,0.08)' : '';
                c.style.boxShadow    = isActive ? '0 0 0 3px rgba(251,191,36,0.12)' : '';
            }
        });
    });
});

// سلوك نوع الربط
document.querySelectorAll('.link-radio').forEach(r => {
    r.addEventListener('change', toggle);
});
function toggle() {
    const v = document.querySelector('.link-radio:checked').value;
    document.getElementById('section-existing').style.display = v === 'existing' ? 'block' : 'none';
    document.getElementById('section-create').style.display   = v === 'create'   ? 'block' : 'none';
}
toggle();

// بحث عن مستخدم
document.getElementById('btn_search').addEventListener('click', async () => {
    const username  = document.getElementById('search_username').value.trim();
    const agentType = document.querySelector('.agent-type-radio:checked')?.value ?? 'agent';
    if (!username) return;
    const res  = await fetch('{{ route("admin.agents.check-user") }}?username=' + encodeURIComponent(username) + '&agent_type=' + agentType);
    const data = await res.json();
    document.getElementById('user_result').style.display = 'none';
    document.getElementById('user_error').style.display  = 'none';
    document.getElementById('found_user_id').value       = '';
    if (data.found) {
        document.getElementById('res_name').textContent  = data.name;
        document.getElementById('res_email').textContent = data.email;
        document.getElementById('found_user_id').value   = data.user_id;
        document.getElementById('user_result').style.display = 'block';
    } else {
        document.getElementById('user_error').textContent     = data.message;
        document.getElementById('user_error').style.display   = 'block';
    }
});

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
    {name:'سلوفاكيا',en:'Slovakia'},{name:'تركمانستان',en:'Turkmenistan'},{name:'كازاخستان',en:'Kazakhstan'},{name:'أذربيجان',en:'Azerbaijan'},{name:'جورجيا',en:'Georgia'},
    {name:'أرمينيا',en:'Armenia'}
];
const inp = document.getElementById('country_search');
const dd  = document.getElementById('country_dropdown');
const hid = document.getElementById('country_value');
if (hid.value) inp.value = hid.value;
inp.addEventListener('input', () => {
    const q = inp.value.trim().toLowerCase();
    dd.innerHTML = '';
    if (!q) { dd.style.display='none'; return; }
    const res = countries.filter(c => c.name.includes(q) || c.en.toLowerCase().includes(q)).slice(0,8);
    if (!res.length) { dd.style.display='none'; return; }
    res.forEach(c => {
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
