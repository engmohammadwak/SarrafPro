@extends('layouts.admin')
@section('title', 'إضافة مندوب')
@section('page-title', 'إضافة مندوب جديد')
@section('content')
<div style="max-width:680px">
    @if($errors->any())
    <div style="background:rgba(239,68,68,0.1);border:1px solid rgba(239,68,68,0.3);color:#ef4444;padding:14px;border-radius:12px;margin-bottom:20px">
        <ul style="margin:0;padding-right:18px">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
    </div>
    @endif

    <form action="{{ route('admin.agents.store') }}" method="POST" enctype="multipart/form-data" id="agentForm">
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
                        <input type="text" name="phone" id="f-phone" value="{{ old('phone') }}" class="field-inp" placeholder="+966...">
                    </div>

                    <div>
                        <label class="field-lbl">رقم هاتف ثاني <span style="font-size:12px;color:var(--text-muted)">اختياري</span></label>
                        <input type="text" name="phone2" id="f-phone2" value="{{ old('phone2') }}" class="field-inp" placeholder="+966...">
                    </div>

                    {{-- حقل الدولة --}}
                    <div style="position:relative">
                        <label class="field-lbl">الدولة</label>
                        <input type="hidden" name="country" id="countryValue" value="{{ old('country') }}">
                        <div style="position:relative">
                            <i class="fas fa-globe" style="position:absolute;right:11px;top:50%;transform:translateY(-50%);color:var(--text-muted);font-size:13px;pointer-events:none;z-index:1"></i>
                            <input type="text" id="countrySearch" placeholder="ابحث عن دولة..." autocomplete="off" class="field-inp"
                                value="{{ old('country') }}" oninput="filterCountries(this.value)" onfocus="showDropdown()" style="padding-right:34px;padding-left:34px">
                            <i class="fas fa-chevron-down" style="position:absolute;left:11px;top:50%;transform:translateY(-50%);color:var(--text-muted);font-size:11px;pointer-events:none"></i>
                        </div>
                        <div id="countryDropdown" style="display:none;position:absolute;top:calc(100% + 4px);left:0;right:0;background:#fff;border:1.5px solid var(--accent,#d4a017);border-radius:10px;box-shadow:0 8px 24px rgba(0,0,0,.12);z-index:999;max-height:220px;overflow-y:auto">
                            <div id="countryList"></div>
                            <div id="countryEmpty" style="display:none;padding:12px 14px;font-size:13px;color:var(--text-muted);text-align:center">لا توجد نتائج</div>
                        </div>
                    </div>

                    <div>
                        <label class="field-lbl">اسم الشركة</label>
                        <input type="text" name="company" id="f-company" value="{{ old('company') }}" class="field-inp">
                    </div>

                    {{-- ملاحظات داخلية --}}
                    <div style="grid-column:1/-1">
                        <label class="field-lbl">
                            <i class="fas fa-lock" style="color:var(--warning,#d97706);font-size:12px;margin-left:4px"></i>
                            ملاحظات داخلية <span style="font-size:12px;color:var(--text-muted)">خاصة بك فقط — لا يراها المندوب</span>
                        </label>
                        <textarea name="admin_notes" rows="2" class="field-inp" style="border-color:rgba(217,119,6,0.4);background:rgba(254,243,199,0.4)" placeholder="ملاحظات سرية...">{{ old('admin_notes') }}</textarea>
                    </div>

                    {{-- رفع ملف --}}
                    <div style="grid-column:1/-1">
                        <label class="field-lbl">
                            <i class="fas fa-paperclip" style="color:var(--info);font-size:12px;margin-left:4px"></i>
                            ملف مرفق <span style="font-size:12px;color:var(--text-muted)">اختياري &mdash; PDF, صورة, Word (5MB كحد أقصى)</span>
                        </label>
                        <label id="fileDropArea" style="display:flex;align-items:center;gap:12px;padding:14px 16px;border:2px dashed var(--border);border-radius:10px;cursor:pointer;transition:border-color .2s;background:#fafafa">
                            <input type="file" name="attachment" id="attachmentInput" accept=".pdf,.jpg,.jpeg,.png,.doc,.docx" style="display:none" onchange="onFileChange(this)">
                            <i class="fas fa-cloud-upload-alt" style="font-size:22px;color:var(--text-muted)"></i>
                            <div>
                                <div id="fileLabel" style="font-size:14px;color:var(--text-dark);font-weight:600">اضغط لاختيار ملف أو اسحبه هنا</div>
                                <div style="font-size:12px;color:var(--text-muted)">يدعم PDF و JPG و PNG و Word</div>
                            </div>
                        </label>
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

                <div id="existingFields" style="display:none">
                    <label class="field-lbl">اسم المستخدم أو البريد الإلكتروني</label>
                    <div style="display:flex;gap:8px">
                        <input type="text" id="usernameInput" placeholder="ابحث عن المستخدم..." class="field-inp" style="flex:1">
                        <button type="button" onclick="checkUser()" class="btn btn-gold"><i class="fas fa-search"></i> تحقق</button>
                    </div>
                    <div id="userResult" style="margin-top:10px"></div>
                    <input type="hidden" name="user_id" id="userId">
                </div>

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
.field-lbl{display:block;margin-bottom:6px;font-size:14px;font-weight:600;color:var(--text-muted)}
.field-inp{width:100%;padding:10px 14px;background:#f8f9fc;border:1.5px solid var(--border);border-radius:8px;font-family:Tajawal,sans-serif;font-size:14px;color:var(--text-dark);transition:border-color .2s;box-sizing:border-box}
.field-inp:focus{outline:none;border-color:var(--accent);background:#fff}
.field-inp[readonly]{background:#f0f0f0;color:#6b7280;cursor:not-allowed;border-style:dashed}
.country-item{padding:9px 14px;font-size:13px;cursor:pointer;display:flex;align-items:center;gap:8px;transition:background .15s;border-bottom:1px solid #f3f4f6}
.country-item:last-child{border-bottom:none}
.country-item:hover{background:var(--accent,#d4a017);color:#fff}
#fileDropArea:hover{border-color:var(--accent)}
</style>

@push('scripts')
<script>
const COUNTRIES=[
    {name:'أفغانستان',en:'Afghanistan',flag:'🇦🇫'},{name:'ألبانيا',en:'Albania',flag:'🇦🇱'},
    {name:'الجزائر',en:'Algeria',flag:'🇩🇿'},{name:'أندورا',en:'Andorra',flag:'🇦🇩'},
    {name:'أنغولا',en:'Angola',flag:'🇦🇴'},{name:'الأرجنتين',en:'Argentina',flag:'🇦🇷'},
    {name:'أرمينيا',en:'Armenia',flag:'🇦🇲'},{name:'أستراليا',en:'Australia',flag:'🇦🇺'},
    {name:'النمسا',en:'Austria',flag:'🇦🇹'},{name:'أذربيجان',en:'Azerbaijan',flag:'🇦🇿'},
    {name:'البحرين',en:'Bahrain',flag:'🇧🇭'},{name:'بنغلاديش',en:'Bangladesh',flag:'🇧🇩'},
    {name:'بلاروسيا',en:'Belarus',flag:'🇧🇾'},{name:'بلجيكا',en:'Belgium',flag:'🇧🇪'},
    {name:'البرازيل',en:'Brazil',flag:'🇧🇷'},{name:'كندا',en:'Canada',flag:'🇨🇦'},
    {name:'مصر',en:'Egypt',flag:'🇪🇬'},{name:'فرنسا',en:'France',flag:'🇫🇷'},
    {name:'ألمانيا',en:'Germany',flag:'🇩🇪'},{name:'الهند',en:'India',flag:'🇮🇳'},
    {name:'إندونيسيا',en:'Indonesia',flag:'🇮🇩'},{name:'إيران',en:'Iran',flag:'🇮🇷'},
    {name:'العراق',en:'Iraq',flag:'🇮🇶'},{name:'إيطاليا',en:'Italy',flag:'🇮🇹'},
    {name:'اليابان',en:'Japan',flag:'🇯🇵'},{name:'الأردن',en:'Jordan',flag:'🇯🇴'},
    {name:'الكويت',en:'Kuwait',flag:'🇰🇼'},{name:'لبنان',en:'Lebanon',flag:'🇱🇧'},
    {name:'ليبيا',en:'Libya',flag:'🇱🇾'},{name:'ماليزيا',en:'Malaysia',flag:'🇲🇾'},
    {name:'المغرب',en:'Morocco',flag:'🇲🇦'},{name:'هولندا',en:'Netherlands',flag:'🇳🇱'},
    {name:'نيجيريا',en:'Nigeria',flag:'🇳🇬'},{name:'عُمان',en:'Oman',flag:'🇴🇲'},
    {name:'باكستان',en:'Pakistan',flag:'🇵🇰'},{name:'فلسطين',en:'Palestine',flag:'🇵🇸'},
    {name:'قطر',en:'Qatar',flag:'🇶🇦'},{name:'روسيا',en:'Russia',flag:'🇷🇺'},
    {name:'المملكة العربية السعودية',en:'Saudi Arabia',flag:'🇸🇦'},
    {name:'سوريا',en:'Syria',flag:'🇸🇾'},{name:'تونس',en:'Tunisia',flag:'🇹🇳'},
    {name:'تركيا',en:'Turkey',flag:'🇹🇷'},{name:'الإمارات العربية المتحدة',en:'UAE',flag:'🇦🇪'},
    {name:'المملكة المتحدة',en:'United Kingdom',flag:'🇬🇧'},
    {name:'الولايات المتحدة الأمريكية',en:'USA',flag:'🇺🇸'},
    {name:'اليمن',en:'Yemen',flag:'🇾🇪'},
];

function renderList(list){
    const c=document.getElementById('countryList'),e=document.getElementById('countryEmpty');
    c.innerHTML='';
    if(!list.length){e.style.display='block';return;}
    e.style.display='none';
    list.forEach(ct=>{
        const d=document.createElement('div');d.className='country-item';
        d.innerHTML=`<span style="font-size:18px">${ct.flag}</span><span>${ct.name}</span><span style="color:#aaa;font-size:11px;margin-right:auto">${ct.en}</span>`;
        d.onclick=()=>selectCountry(ct);c.appendChild(d);
    });
}
function selectCountry(c){document.getElementById('countryValue').value=c.name;document.getElementById('countrySearch').value=c.name;hideDropdown();}
function filterCountries(q){document.getElementById('countryDropdown').style.display='block';document.getElementById('countryValue').value=q;if(!q.trim()){renderList(COUNTRIES);return;}const lq=q.toLowerCase();renderList(COUNTRIES.filter(c=>c.name.includes(q)||c.en.toLowerCase().includes(lq)));}
function showDropdown(){document.getElementById('countryDropdown').style.display='block';renderList(COUNTRIES);}
function hideDropdown(){document.getElementById('countryDropdown').style.display='none';}
document.addEventListener('click',e=>{if(!e.target.closest('#countrySearch')&&!e.target.closest('#countryDropdown'))hideDropdown();});

function onFileChange(input){
    const label=document.getElementById('fileLabel');
    if(input.files&&input.files[0]){
        const f=input.files[0];
        const size=(f.size/1024/1024).toFixed(2);
        label.innerHTML=`<i class="fas fa-file" style="color:var(--info)"></i> ${f.name} <span style="color:var(--text-muted);font-size:12px">(${size} MB)</span>`;
    } else {
        label.textContent='اضغط لاختيار ملف أو اسحبه هنا';
    }
}

const colors={none:'#94a3b8',existing:'var(--info)',create:'var(--success)'};

function lockFields(lock){
    ['f-name','f-phone','f-phone2','f-company'].forEach(id=>{
        const el=document.getElementById(id);if(el)el.readOnly=lock;
    });
    const cs=document.getElementById('countrySearch');if(cs)cs.readOnly=lock;
}

function fillFromUser(data){
    const nameEl=document.getElementById('f-name');
    if(nameEl&&data.name)nameEl.value=data.name;

    const hasAgentData=data.phone||data.phone2||data.country||data.company;
    if(hasAgentData){
        if(data.phone)  document.getElementById('f-phone').value=data.phone;
        if(data.phone2) document.getElementById('f-phone2').value=data.phone2;
        if(data.company)document.getElementById('f-company').value=data.company;
        if(data.country){document.getElementById('countryValue').value=data.country;document.getElementById('countrySearch').value=data.country;}
        lockFields(true);
    } else {
        lockFields(false);
        if(nameEl)nameEl.readOnly=true;
    }
}

function clearAutoFill(){
    ['f-name','f-phone','f-phone2','f-company'].forEach(id=>{const el=document.getElementById(id);if(el)el.value='';});
    document.getElementById('countryValue').value='';
    document.getElementById('countrySearch').value='';
    lockFields(false);
}

function onLinkChange(val){
    ['none','existing','create'].forEach(k=>{
        const el=document.getElementById('lt-'+k);
        el.style.borderColor=k===val?colors[k]:'var(--border)';
        el.style.background=k===val?colors[k]+'18':'';
        el.style.color=k===val?colors[k]:'';
    });
    document.getElementById('existingFields').style.display=val==='existing'?'block':'none';
    document.getElementById('createFields').style.display=val==='create'?'block':'none';
    if(val!=='existing'){clearAutoFill();document.getElementById('userId').value='';document.getElementById('userResult').innerHTML='';document.getElementById('usernameInput').value='';}
}

function checkUser(){
    const username=document.getElementById('usernameInput').value.trim();
    if(!username)return;
    const btn=event.target.closest('button');
    btn.disabled=true;btn.innerHTML='<i class="fas fa-spinner fa-spin"></i>';
    fetch('{{ route("admin.agents.check-user") }}?username='+encodeURIComponent(username),{headers:{'X-Requested-With':'XMLHttpRequest','Accept':'application/json'}})
    .then(r=>r.json())
    .then(data=>{
        const box=document.getElementById('userResult');
        if(data.found){
            document.getElementById('userId').value=data.user_id;
            fillFromUser(data);
            const hasAgent=data.phone||data.country||data.company;
            const info=hasAgent
                ?`<div style="font-size:12px;color:var(--success);margin-top:3px">✓ تم ملء البيانات تلقائياً — يمكنك تعديلها</div>`
                :`<div style="font-size:12px;color:var(--text-muted);margin-top:3px">✏️ تم ملء الاسم — أكمل باقي البيانات</div>`;
            box.innerHTML=`<div style="padding:12px 16px;background:rgba(34,197,94,0.1);border:1px solid rgba(34,197,94,0.3);border-radius:8px;display:flex;align-items:center;justify-content:space-between;gap:10px">
                <div style="display:flex;align-items:center;gap:10px">
                    <i class="fas fa-check-circle" style="color:var(--success);font-size:20px"></i>
                    <div><div style="font-weight:700;font-size:15px">${data.name}</div>
                    <div style="font-size:12px;color:var(--text-muted)">${data.username??''} &bull; ${data.email}</div>${info}</div>
                </div>
                <button type="button" onclick="resetUser()" style="background:none;border:none;color:#94a3b8;cursor:pointer;font-size:18px"><i class="fas fa-times-circle"></i></button>
            </div>`;
        } else {
            document.getElementById('userId').value='';clearAutoFill();
            box.innerHTML=`<div style="padding:12px 16px;background:rgba(239,68,68,0.08);border:1px solid rgba(239,68,68,0.25);border-radius:8px;color:#ef4444"><i class="fas fa-times-circle"></i> ${data.message}</div>`;
        }
    })
    .finally(()=>{btn.disabled=false;btn.innerHTML='<i class="fas fa-search"></i> تحقق';});
}

function resetUser(){document.getElementById('userId').value='';document.getElementById('userResult').innerHTML='';document.getElementById('usernameInput').value='';clearAutoFill();}

window.addEventListener('DOMContentLoaded',()=>{const c=document.querySelector('.link-radio:checked');if(c)onLinkChange(c.value);});
</script>
@endpush
@endsection
