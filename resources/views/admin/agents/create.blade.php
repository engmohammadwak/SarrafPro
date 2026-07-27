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

                    {{-- حقل الدولة مع بحث --}}
                    <div style="position:relative">
                        <label class="field-lbl">الدولة</label>
                        <input type="hidden" name="country" id="countryValue" value="{{ old('country') }}">
                        <div style="position:relative">
                            <i class="fas fa-globe" style="position:absolute;right:11px;top:50%;transform:translateY(-50%);color:var(--text-muted);font-size:13px;pointer-events:none;z-index:1"></i>
                            <input type="text" id="countrySearch"
                                placeholder="ابحث عن دولة..."
                                autocomplete="off"
                                class="field-inp"
                                value="{{ old('country') }}"
                                oninput="filterCountries(this.value)"
                                onfocus="showDropdown()"
                                style="padding-right:34px;padding-left:34px">
                            <i class="fas fa-chevron-down" style="position:absolute;left:11px;top:50%;transform:translateY(-50%);color:var(--text-muted);font-size:11px;pointer-events:none"></i>
                        </div>
                        <div id="countryDropdown"
                            style="display:none;position:absolute;top:calc(100% + 4px);left:0;right:0;background:#fff;border:1.5px solid var(--accent,#d4a017);border-radius:10px;box-shadow:0 8px 24px rgba(0,0,0,.12);z-index:999;max-height:220px;overflow-y:auto">
                            <div id="countryList"></div>
                            <div id="countryEmpty" style="display:none;padding:12px 14px;font-size:13px;color:var(--text-muted);text-align:center">لا توجد نتائج</div>
                        </div>
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
.field-inp { width:100%; padding:10px 14px; background:#f8f9fc; border:1.5px solid var(--border); border-radius:8px; font-family:Tajawal,sans-serif; font-size:14px; color:var(--text-dark); transition:border-color 0.2s; box-sizing:border-box; }
.field-inp:focus { outline:none; border-color:var(--accent); background:#fff; }
.field-inp[readonly] { background:#f0f0f0; color:#6b7280; cursor:not-allowed; border-style:dashed; }
.country-item { padding:9px 14px; font-size:13px; cursor:pointer; display:flex; align-items:center; gap:8px; transition:background .15s; border-bottom:1px solid #f3f4f6; }
.country-item:last-child { border-bottom:none; }
.country-item:hover { background:var(--accent,#d4a017); color:#fff; }
#countryDropdown::-webkit-scrollbar { width:5px; }
#countryDropdown::-webkit-scrollbar-thumb { background:#ddd; border-radius:4px; }
</style>

@push('scripts')
<script>
/* ============ قائمة الدول ============ */
const COUNTRIES = [
    {name:'أفغانستان',en:'Afghanistan',flag:'🇦🇫'},{name:'ألبانيا',en:'Albania',flag:'🇦🇱'},
    {name:'الجزائر',en:'Algeria',flag:'🇩🇿'},{name:'أندورا',en:'Andorra',flag:'🇦🇩'},
    {name:'أنغولا',en:'Angola',flag:'🇦🇴'},{name:'الأرجنتين',en:'Argentina',flag:'🇦🇷'},
    {name:'أرمينيا',en:'Armenia',flag:'🇦🇲'},{name:'أستراليا',en:'Australia',flag:'🇦🇺'},
    {name:'النمسا',en:'Austria',flag:'🇦🇹'},{name:'أذربيجان',en:'Azerbaijan',flag:'🇦🇿'},
    {name:'البحرين',en:'Bahrain',flag:'🇧🇭'},{name:'بنغلاديش',en:'Bangladesh',flag:'🇧🇩'},
    {name:'بلاروسيا',en:'Belarus',flag:'🇧🇾'},{name:'بلجيكا',en:'Belgium',flag:'🇧🇪'},
    {name:'بليز',en:'Belize',flag:'🇧🇿'},{name:'بنين',en:'Benin',flag:'🇧🇯'},
    {name:'بوتان',en:'Bhutan',flag:'🇧🇹'},{name:'بوليفيا',en:'Bolivia',flag:'🇧🇴'},
    {name:'البوسنة والهرسك',en:'Bosnia',flag:'🇧🇦'},{name:'بوتسوانا',en:'Botswana',flag:'🇧🇼'},
    {name:'البرازيل',en:'Brazil',flag:'🇧🇷'},{name:'بروناي',en:'Brunei',flag:'🇧🇳'},
    {name:'بلغاريا',en:'Bulgaria',flag:'🇧🇬'},{name:'بوركينا فاسو',en:'Burkina Faso',flag:'🇧🇫'},
    {name:'بوروندي',en:'Burundi',flag:'🇧🇮'},{name:'كمبوديا',en:'Cambodia',flag:'🇰🇭'},
    {name:'الكاميرون',en:'Cameroon',flag:'🇨🇲'},{name:'كندا',en:'Canada',flag:'🇨🇦'},
    {name:'أفريقيا الوسطى',en:'Central African Republic',flag:'🇨🇫'},{name:'تشاد',en:'Chad',flag:'🇹🇩'},
    {name:'تشيلي',en:'Chile',flag:'🇨🇱'},{name:'الصين',en:'China',flag:'🇨🇳'},
    {name:'كولومبيا',en:'Colombia',flag:'🇨🇴'},{name:'الكونغو',en:'Congo',flag:'🇨🇬'},
    {name:'كوستاريكا',en:'Costa Rica',flag:'🇨🇷'},{name:'كرواتيا',en:'Croatia',flag:'🇭🇷'},
    {name:'كوبا',en:'Cuba',flag:'🇨🇺'},{name:'قبرص',en:'Cyprus',flag:'🇨🇾'},
    {name:'التشيك',en:'Czech Republic',flag:'🇨🇿'},{name:'الدنمارك',en:'Denmark',flag:'🇩🇰'},
    {name:'جيبوتي',en:'Djibouti',flag:'🇩🇯'},{name:'الدومينيكان',en:'Dominican Republic',flag:'🇩🇴'},
    {name:'الإكوادور',en:'Ecuador',flag:'🇪🇨'},{name:'مصر',en:'Egypt',flag:'🇪🇬'},
    {name:'السلفادور',en:'El Salvador',flag:'🇸🇻'},{name:'إريتريا',en:'Eritrea',flag:'🇪🇷'},
    {name:'إستونيا',en:'Estonia',flag:'🇪🇪'},{name:'إثيوبيا',en:'Ethiopia',flag:'🇪🇹'},
    {name:'فيجي',en:'Fiji',flag:'🇫🇯'},{name:'فنلندا',en:'Finland',flag:'🇫🇮'},
    {name:'فرنسا',en:'France',flag:'🇫🇷'},{name:'الغابون',en:'Gabon',flag:'🇬🇦'},
    {name:'غامبيا',en:'Gambia',flag:'🇬🇲'},{name:'جورجيا',en:'Georgia',flag:'🇬🇪'},
    {name:'ألمانيا',en:'Germany',flag:'🇩🇪'},{name:'غانا',en:'Ghana',flag:'🇬🇭'},
    {name:'اليونان',en:'Greece',flag:'🇬🇷'},{name:'غواتيمالا',en:'Guatemala',flag:'🇬🇹'},
    {name:'غينيا',en:'Guinea',flag:'🇬🇳'},{name:'غيانا',en:'Guyana',flag:'🇬🇾'},
    {name:'هايتي',en:'Haiti',flag:'🇭🇹'},{name:'هندوراس',en:'Honduras',flag:'🇭🇳'},
    {name:'المجر',en:'Hungary',flag:'🇭🇺'},{name:'آيسلندا',en:'Iceland',flag:'🇮🇸'},
    {name:'الهند',en:'India',flag:'🇮🇳'},{name:'إندونيسيا',en:'Indonesia',flag:'🇮🇩'},
    {name:'إيران',en:'Iran',flag:'🇮🇷'},{name:'العراق',en:'Iraq',flag:'🇮🇶'},
    {name:'أيرلندا',en:'Ireland',flag:'🇮🇪'},{name:'إسرائيل',en:'Israel',flag:'🇮🇱'},
    {name:'إيطاليا',en:'Italy',flag:'🇮🇹'},{name:'ساحل العاج',en:'Ivory Coast',flag:'🇨🇮'},
    {name:'جامايكا',en:'Jamaica',flag:'🇯🇲'},{name:'اليابان',en:'Japan',flag:'🇯🇵'},
    {name:'الأردن',en:'Jordan',flag:'🇯🇴'},{name:'كازاخستان',en:'Kazakhstan',flag:'🇰🇿'},
    {name:'كينيا',en:'Kenya',flag:'🇰🇪'},{name:'كوريا الشمالية',en:'North Korea',flag:'🇰🇵'},
    {name:'كوريا الجنوبية',en:'South Korea',flag:'🇰🇷'},{name:'الكويت',en:'Kuwait',flag:'🇰🇼'},
    {name:'قيرغيزستان',en:'Kyrgyzstan',flag:'🇰🇬'},{name:'لاوس',en:'Laos',flag:'🇱🇦'},
    {name:'لاتفيا',en:'Latvia',flag:'🇱🇻'},{name:'لبنان',en:'Lebanon',flag:'🇱🇧'},
    {name:'ليبيريا',en:'Liberia',flag:'🇱🇷'},{name:'ليبيا',en:'Libya',flag:'🇱🇾'},
    {name:'ليتوانيا',en:'Lithuania',flag:'🇱🇹'},{name:'لوكسمبورغ',en:'Luxembourg',flag:'🇱🇺'},
    {name:'مدغشقر',en:'Madagascar',flag:'🇲🇬'},{name:'مالاوي',en:'Malawi',flag:'🇲🇼'},
    {name:'ماليزيا',en:'Malaysia',flag:'🇲🇾'},{name:'جزر المالديف',en:'Maldives',flag:'🇲🇻'},
    {name:'مالي',en:'Mali',flag:'🇲🇱'},{name:'مالطا',en:'Malta',flag:'🇲🇹'},
    {name:'موريتانيا',en:'Mauritania',flag:'🇲🇷'},{name:'المكسيك',en:'Mexico',flag:'🇲🇽'},
    {name:'مولدوفا',en:'Moldova',flag:'🇲🇩'},{name:'موناكو',en:'Monaco',flag:'🇲🇨'},
    {name:'منغوليا',en:'Mongolia',flag:'🇲🇳'},{name:'المغرب',en:'Morocco',flag:'🇲🇦'},
    {name:'موزمبيق',en:'Mozambique',flag:'🇲🇿'},{name:'ميانمار',en:'Myanmar',flag:'🇲🇲'},
    {name:'ناميبيا',en:'Namibia',flag:'🇳🇦'},{name:'نيبال',en:'Nepal',flag:'🇳🇵'},
    {name:'هولندا',en:'Netherlands',flag:'🇳🇱'},{name:'نيوزيلندا',en:'New Zealand',flag:'🇳🇿'},
    {name:'نيكاراغوا',en:'Nicaragua',flag:'🇳🇮'},{name:'النيجر',en:'Niger',flag:'🇳🇪'},
    {name:'نيجيريا',en:'Nigeria',flag:'🇳🇬'},{name:'النرويج',en:'Norway',flag:'🇳🇴'},
    {name:'عُمان',en:'Oman',flag:'🇴🇲'},{name:'باكستان',en:'Pakistan',flag:'🇵🇰'},
    {name:'فلسطين',en:'Palestine',flag:'🇵🇸'},{name:'بنما',en:'Panama',flag:'🇵🇦'},
    {name:'بيرو',en:'Peru',flag:'🇵🇪'},{name:'الفلبين',en:'Philippines',flag:'🇵🇭'},
    {name:'بولندا',en:'Poland',flag:'🇵🇱'},{name:'البرتغال',en:'Portugal',flag:'🇵🇹'},
    {name:'قطر',en:'Qatar',flag:'🇶🇦'},{name:'رومانيا',en:'Romania',flag:'🇷🇴'},
    {name:'روسيا',en:'Russia',flag:'🇷🇺'},{name:'رواندا',en:'Rwanda',flag:'🇷🇼'},
    {name:'المملكة العربية السعودية',en:'Saudi Arabia',flag:'🇸🇦'},{name:'السنغال',en:'Senegal',flag:'🇸🇳'},
    {name:'صربيا',en:'Serbia',flag:'🇷🇸'},{name:'سيراليون',en:'Sierra Leone',flag:'🇸🇱'},
    {name:'سنغافورة',en:'Singapore',flag:'🇸🇬'},{name:'سلوفاكيا',en:'Slovakia',flag:'🇸🇰'},
    {name:'سلوفينيا',en:'Slovenia',flag:'🇸🇮'},{name:'الصومال',en:'Somalia',flag:'🇸🇴'},
    {name:'جنوب أفريقيا',en:'South Africa',flag:'🇿🇦'},{name:'جنوب السودان',en:'South Sudan',flag:'🇸🇸'},
    {name:'إسبانيا',en:'Spain',flag:'🇪🇸'},{name:'سريلانكا',en:'Sri Lanka',flag:'🇱🇰'},
    {name:'السودان',en:'Sudan',flag:'🇸🇩'},{name:'السويد',en:'Sweden',flag:'🇸🇪'},
    {name:'سويسرا',en:'Switzerland',flag:'🇨🇭'},{name:'سوريا',en:'Syria',flag:'🇸🇾'},
    {name:'تايوان',en:'Taiwan',flag:'🇹🇼'},{name:'طاجيكستان',en:'Tajikistan',flag:'🇹🇯'},
    {name:'تنزانيا',en:'Tanzania',flag:'🇹🇿'},{name:'تايلاند',en:'Thailand',flag:'🇹🇭'},
    {name:'توغو',en:'Togo',flag:'🇹🇬'},{name:'ترينيداد وتوباغو',en:'Trinidad and Tobago',flag:'🇹🇹'},
    {name:'تونس',en:'Tunisia',flag:'🇹🇳'},{name:'تركيا',en:'Turkey',flag:'🇹🇷'},
    {name:'تركمانستان',en:'Turkmenistan',flag:'🇹🇲'},{name:'أوغندا',en:'Uganda',flag:'🇺🇬'},
    {name:'أوكرانيا',en:'Ukraine',flag:'🇺🇦'},{name:'الإمارات العربية المتحدة',en:'UAE',flag:'🇦🇪'},
    {name:'المملكة المتحدة',en:'United Kingdom',flag:'🇬🇧'},{name:'الولايات المتحدة الأمريكية',en:'USA',flag:'🇺🇸'},
    {name:'أوروغواي',en:'Uruguay',flag:'🇺🇾'},{name:'أوزبكستان',en:'Uzbekistan',flag:'🇺🇿'},
    {name:'فنزويلا',en:'Venezuela',flag:'🇻🇪'},{name:'فيتنام',en:'Vietnam',flag:'🇻🇳'},
    {name:'اليمن',en:'Yemen',flag:'🇾🇪'},{name:'زامبيا',en:'Zambia',flag:'🇿🇲'},
    {name:'زيمبابوي',en:'Zimbabwe',flag:'🇿🇼'},
];

function renderList(list) {
    const container = document.getElementById('countryList');
    const empty     = document.getElementById('countryEmpty');
    container.innerHTML = '';
    if (!list.length) { empty.style.display='block'; return; }
    empty.style.display = 'none';
    list.forEach(c => {
        const div = document.createElement('div');
        div.className = 'country-item';
        div.innerHTML = `<span style="font-size:18px;line-height:1">${c.flag}</span><span>${c.name}</span><span style="color:#aaa;font-size:11px;margin-right:auto">${c.en}</span>`;
        div.onclick = () => selectCountry(c);
        container.appendChild(div);
    });
}
function selectCountry(c) {
    document.getElementById('countryValue').value  = c.name;
    document.getElementById('countrySearch').value = c.name;
    hideDropdown();
}
function filterCountries(q) {
    document.getElementById('countryDropdown').style.display = 'block';
    document.getElementById('countryValue').value = q;
    if (!q.trim()) { renderList(COUNTRIES); return; }
    const lq = q.toLowerCase();
    renderList(COUNTRIES.filter(c => c.name.includes(q) || c.en.toLowerCase().includes(lq)));
}
function showDropdown() {
    document.getElementById('countryDropdown').style.display = 'block';
    renderList(COUNTRIES);
}
function hideDropdown() { document.getElementById('countryDropdown').style.display = 'none'; }
document.addEventListener('click', e => {
    if (!e.target.closest('#countrySearch') && !e.target.closest('#countryDropdown')) hideDropdown();
});

/* ============ ربط بحساب ============ */
const colors = { none:'#94a3b8', existing:'var(--info)', create:'var(--success)' };

function lockFields(lock) {
    ['f-name','f-phone','f-company','f-notes'].forEach(id => {
        const el = document.getElementById(id);
        if (el) el.readOnly = lock;
    });
    const cs = document.getElementById('countrySearch');
    if (cs) cs.readOnly = lock;
}

function fillFromUser(data) {
    // ملء الاسم دائماً من بيانات User
    const nameEl = document.getElementById('f-name');
    if (nameEl && data.name) nameEl.value = data.name;

    // ملء بقية الحقول فقط إذا وجدت بيانات agent
    const hasAgentData = data.phone || data.country || data.company || data.notes;

    if (hasAgentData) {
        // وجد بيانات مندوب → ملء الكل وقفل
        if (data.phone)   { const el = document.getElementById('f-phone');   if (el) el.value = data.phone;   }
        if (data.company) { const el = document.getElementById('f-company'); if (el) el.value = data.company; }
        if (data.notes)   { const el = document.getElementById('f-notes');   if (el) el.value = data.notes;   }
        if (data.country) {
            document.getElementById('countryValue').value  = data.country;
            document.getElementById('countrySearch').value = data.country;
        }
        lockFields(true);
    } else {
        // المستخدم موجود لكن بدون بيانات مندوب → الاسم مُملأ، الباقي مفتوح للإدخال
        lockFields(false);
        // فقط الاسم readonly لأنه جاء من User
        const nameEl2 = document.getElementById('f-name');
        if (nameEl2) nameEl2.readOnly = true;
    }
}

function clearAutoFill() {
    ['f-name','f-phone','f-company','f-notes'].forEach(id => {
        const el = document.getElementById(id); if (el) el.value = '';
    });
    document.getElementById('countryValue').value  = '';
    document.getElementById('countrySearch').value = '';
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
            fillFromUser(data);

            const hasAgent = data.phone || data.country || data.company;
            const agentInfo = hasAgent
                ? `<div style="font-size:12px;color:var(--success);margin-top:3px">✓ تم ملء البيانات تلقائياً — يمكنك تعديلها</div>`
                : `<div style="font-size:12px;color:var(--text-muted);margin-top:3px">✏️ تم ملء الاسم — أكمل رقم الهاتف والدولة والشركة</div>`;

            box.innerHTML = `
                <div style="padding:12px 16px;background:rgba(34,197,94,0.1);border:1px solid rgba(34,197,94,0.3);border-radius:8px;display:flex;align-items:center;justify-content:space-between;gap:10px">
                    <div style="display:flex;align-items:center;gap:10px">
                        <i class="fas fa-check-circle" style="color:var(--success);font-size:20px"></i>
                        <div>
                            <div style="font-weight:700;font-size:15px">${data.name}</div>
                            <div style="font-size:12px;color:var(--text-muted)">${data.username ?? ''} &bull; ${data.email}</div>
                            ${agentInfo}
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
