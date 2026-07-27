@extends('layouts.superadmin')
@section('title', 'تعديل مندوب')
@section('page-title', 'تعديل بيانات المندوب')
@section('content')
<div style="max-width:620px">

    @if($errors->any())
    <div style="background:rgba(239,68,68,0.08);border:1px solid rgba(239,68,68,0.25);color:#dc2626;padding:12px 16px;border-radius:10px;margin-bottom:20px;font-size:14px">
        @foreach($errors->all() as $e)<div>• {{ $e }}</div>@endforeach
    </div>
    @endif

    <form method="POST" action="{{ route('superadmin.agents.update', $agent) }}" enctype="multipart/form-data">
    @csrf @method('PUT')

    {{-- بيانات الحساب --}}
    <div class="card" style="margin-bottom:20px">
        <div class="card-header"><h3><i class="fas fa-user-circle" style="color:var(--accent);margin-left:8px"></i> بيانات الحساب</h3></div>
        <div class="card-body">
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px">
                <div>
                    <label class="sa-label">الاسم الكامل <span style="color:#ef4444">*</span></label>
                    <input type="text" name="name" value="{{ old('name', $agent->name) }}" required class="sa-input">
                </div>
                <div>
                    <label class="sa-label">Username</label>
                    <input type="text" name="username" value="{{ old('username', $agent->username) }}"
                        class="sa-input" style="font-family:monospace;direction:ltr" autocomplete="off">
                </div>
                <div>
                    <label class="sa-label">البريد الإلكتروني <span style="color:#ef4444">*</span></label>
                    <input type="email" name="email" value="{{ old('email', $agent->email) }}" required
                        class="sa-input" style="direction:ltr">
                </div>
                <div>
                    <label class="sa-label">كلمة المرور <span style="font-size:12px;font-weight:400;color:var(--text-muted)">فارغة = بدون تغيير</span></label>
                    <input type="password" name="password" class="sa-input" autocomplete="new-password">
                </div>
            </div>
        </div>
    </div>

    {{-- بيانات إضافية --}}
    <div class="card" style="margin-bottom:20px">
        <div class="card-header"><h3><i class="fas fa-id-badge" style="color:var(--accent);margin-left:8px"></i> بيانات إضافية <span style="font-size:12px;font-weight:400;color:var(--text-muted)">اختيارية</span></h3></div>
        <div class="card-body">
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px">

                <div>
                    <label class="sa-label">رقم الهاتف</label>
                    <input type="text" name="phone" value="{{ old('phone', $agent->phone) }}" placeholder="+968 XXXX XXXX" class="sa-input">
                </div>

                {{-- حقل الدولة مع بحث --}}
                <div style="position:relative">
                    <label class="sa-label">الدولة</label>
                    <input type="hidden" name="country" id="countryValue" value="{{ old('country', $agent->country) }}">
                    <div style="position:relative">
                        <input type="text" id="countrySearch"
                            placeholder="ابحث عن دولة..."
                            autocomplete="off"
                            class="sa-input"
                            value="{{ old('country', $agent->country) }}"
                            oninput="filterCountries(this.value)"
                            onfocus="showDropdown()"
                            style="padding-left:32px">
                        <i class="fas fa-globe" style="position:absolute;right:12px;top:50%;transform:translateY(-50%);color:var(--text-muted);font-size:13px;pointer-events:none"></i>
                        <i class="fas fa-chevron-down" style="position:absolute;left:12px;top:50%;transform:translateY(-50%);color:var(--text-muted);font-size:11px;pointer-events:none"></i>
                    </div>
                    <div id="countryDropdown"
                        style="display:none;position:absolute;top:calc(100% + 4px);left:0;right:0;background:#fff;border:1.5px solid var(--accent);border-radius:10px;box-shadow:0 8px 24px rgba(0,0,0,.12);z-index:999;max-height:220px;overflow-y:auto">
                        <div id="countryList"></div>
                        <div id="countryEmpty" style="display:none;padding:12px 14px;font-size:13px;color:var(--text-muted);text-align:center">لا توجد نتائج</div>
                    </div>
                </div>

                <div style="grid-column:1/-1">
                    <label class="sa-label">اسم الشركة</label>
                    <input type="text" name="company" value="{{ old('company', $agent->company) }}" placeholder="اسم الشركة أو المؤسسة" class="sa-input">
                </div>

                {{-- ملف مرفق --}}
                <div style="grid-column:1/-1">
                    <label class="sa-label">ملف مرفق <span style="font-size:12px;font-weight:400;color:var(--text-muted)">(PDF, صورة, Word)</span></label>

                    @if(!empty($agent->attachment))
                    @php
                        $ext     = strtolower(pathinfo($agent->attachment, PATHINFO_EXTENSION));
                        $isImg   = in_array($ext, ['jpg','jpeg','png','gif','webp']);
                        $isPdf   = $ext === 'pdf';
                        $fileUrl = asset('storage/' . $agent->attachment);
                        $fileName = basename($agent->attachment);
                    @endphp

                    <div style="border:1px solid var(--border);border-radius:10px;overflow:hidden;background:#f8f9fc">

                        @if($isImg)
                        <div style="background:#000;text-align:center;cursor:zoom-in" onclick="openLightbox('{{ $fileUrl }}')" title="اضغط للتكبير">
                            <img src="{{ $fileUrl }}" alt="مرفق"
                                style="max-height:160px;width:auto;max-width:100%;object-fit:contain;display:inline-block;opacity:.95;transition:opacity .2s"
                                onmouseover="this.style.opacity=1" onmouseout="this.style.opacity=.95">
                        </div>
                        @elseif($isPdf)
                        <div style="height:200px">
                            <iframe src="{{ $fileUrl }}" style="width:100%;height:100%;border:none;display:block"></iframe>
                        </div>
                        @else
                        <div style="padding:16px;display:flex;align-items:center;gap:12px">
                            <i class="fas fa-file-alt" style="font-size:30px;color:var(--text-muted)"></i>
                            <div>
                                <div style="font-size:13px;font-weight:600">{{ $fileName }}</div>
                                <div style="font-size:12px;color:var(--text-muted)">ملف مرفق</div>
                            </div>
                        </div>
                        @endif

                        <div style="padding:8px 14px;border-top:1px solid var(--border);display:flex;align-items:center;justify-content:space-between">
                            <span style="font-size:12px;color:var(--text-muted);font-family:monospace">{{ $fileName }}</span>
                            <div style="display:flex;gap:12px;align-items:center">
                                @if($isImg)
                                <button type="button" onclick="openLightbox('{{ $fileUrl }}')"
                                    style="background:none;border:none;color:var(--info);font-size:12px;cursor:pointer;padding:0">
                                    <i class="fas fa-expand-alt"></i> تكبير
                                </button>
                                @endif
                                <a href="{{ $fileUrl }}" target="_blank"
                                    style="font-size:12px;color:var(--text-muted);text-decoration:none">
                                    <i class="fas fa-external-link-alt"></i> فتح
                                </a>
                                <label style="font-size:12px;color:var(--accent);cursor:pointer;margin:0">
                                    <i class="fas fa-exchange-alt"></i> تغيير
                                    <input type="file" id="attachmentInput" name="attachment"
                                        accept=".pdf,.jpg,.jpeg,.png,.doc,.docx" style="display:none"
                                        onchange="handleFilePreview(this)">
                                </label>
                            </div>
                        </div>
                    </div>

                    @else
                    <div id="dropZone" onclick="document.getElementById('attachmentInput').click()"
                        style="border:2px dashed var(--border);border-radius:10px;padding:20px;text-align:center;background:#fafbfc;cursor:pointer;transition:border-color .2s">
                        <i class="fas fa-cloud-upload-alt" style="font-size:28px;color:var(--text-muted);margin-bottom:6px;display:block"></i>
                        <p style="font-size:13px;color:var(--text-muted);margin:0" id="attachmentLabel">اضغط لاختيار ملف &bull; الحجم الأقصى 5MB</p>
                        <input type="file" id="attachmentInput" name="attachment"
                            accept=".pdf,.jpg,.jpeg,.png,.doc,.docx" style="display:none"
                            onchange="handleFilePreview(this)">
                    </div>
                    @endif

                    <div id="previewArea" style="display:none;margin-top:12px;border:1px solid var(--border);border-radius:10px;overflow:hidden;background:#fff">
                        <div id="previewImage" style="display:none;background:#000;text-align:center">
                            <img id="previewImg" src="" alt="معاينة"
                                style="max-height:200px;width:auto;max-width:100%;object-fit:contain;display:inline-block;cursor:zoom-in"
                                onclick="openLightbox(this.src)">
                        </div>
                        <div id="previewPdf" style="display:none">
                            <iframe id="previewPdfFrame" src="" style="width:100%;height:340px;border:none;display:block"></iframe>
                        </div>
                        <div id="previewOther" style="display:none;padding:14px;align-items:center;gap:12px">
                            <i class="fas fa-file-alt" style="font-size:26px;color:var(--text-muted)"></i>
                            <div>
                                <p id="previewFileName" style="font-weight:600;font-size:14px;margin:0"></p>
                                <p id="previewFileSize" style="font-size:12px;color:var(--text-muted);margin:0"></p>
                            </div>
                        </div>
                        <div style="padding:8px 14px;background:#f8f9fc;border-top:1px solid var(--border);display:flex;align-items:center;justify-content:space-between">
                            <span id="previewFooterName" style="font-size:12px;color:var(--text-muted);font-family:monospace"></span>
                            <button type="button" onclick="clearPreview()" style="background:none;border:none;color:#ef4444;font-size:12px;cursor:pointer">
                                <i class="fas fa-times"></i> إلغاء
                            </button>
                        </div>
                    </div>
                </div>

                <div style="grid-column:1/-1">
                    <label class="sa-label">ملاحظة</label>
                    <textarea name="notes" rows="3" placeholder="أي معلومات إضافية..."
                        class="sa-input" style="resize:vertical">{{ old('notes', $agent->notes) }}</textarea>
                </div>

            </div>
        </div>
    </div>

    <div style="display:flex;gap:12px">
        <button type="submit" class="btn btn-gold"><i class="fas fa-save"></i> حفظ</button>
        <a href="{{ route('superadmin.agents.index') }}" class="btn" style="background:#f3f4f6;color:#374151">رجوع</a>
    </div>
    </form>
</div>

{{-- Lightbox --}}
<div id="lightbox" onclick="closeLightbox()"
    style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.88);z-index:9999;align-items:center;justify-content:center;cursor:zoom-out">
    <img id="lightboxImg" src="" alt="صورة كاملة"
        style="max-width:92vw;max-height:92vh;object-fit:contain;border-radius:8px;box-shadow:0 8px 40px rgba(0,0,0,.6)">
    <button onclick="closeLightbox()" style="position:fixed;top:18px;left:18px;background:rgba(255,255,255,.15);border:none;color:#fff;width:38px;height:38px;border-radius:50%;font-size:18px;cursor:pointer;backdrop-filter:blur(4px)">
        &times;
    </button>
</div>

<style>
.sa-label { display:block; margin-bottom:6px; font-size:14px; font-weight:600; color:var(--text-muted); }
.sa-input  { width:100%; padding:10px 14px; background:#f8f9fc; border:1.5px solid var(--border); border-radius:8px; font-family:Tajawal,sans-serif; font-size:14px; box-sizing:border-box; transition:border-color .2s; }
.sa-input:focus { outline:none; border-color:var(--accent); background:#fff; }
#lightbox { display:none; }
#lightbox.open { display:flex; }
.country-item { padding:9px 14px; font-size:13px; cursor:pointer; display:flex; align-items:center; gap:8px; transition:background .15s; border-bottom:1px solid #f3f4f6; }
.country-item:last-child { border-bottom:none; }
.country-item:hover, .country-item.active { background:var(--accent,#d4a017); color:#fff; }
#countryDropdown::-webkit-scrollbar { width:5px; }
#countryDropdown::-webkit-scrollbar-thumb { background:#ddd; border-radius:4px; }
</style>

<script>
/* ========== قائمة دول العالم ========== */
const COUNTRIES = [
    {name:'أفغانستان',en:'Afghanistan',flag:'🇦🇫'},
    {name:'ألبانيا',en:'Albania',flag:'🇦🇱'},
    {name:'الجزائر',en:'Algeria',flag:'🇩🇿'},
    {name:'أندورا',en:'Andorra',flag:'🇦🇩'},
    {name:'أنغولا',en:'Angola',flag:'🇦🇴'},
    {name:'الأرجنتين',en:'Argentina',flag:'🇦🇷'},
    {name:'أرمينيا',en:'Armenia',flag:'🇦🇲'},
    {name:'أستراليا',en:'Australia',flag:'🇦🇺'},
    {name:'النمسا',en:'Austria',flag:'🇦🇹'},
    {name:'أذربيجان',en:'Azerbaijan',flag:'🇦🇿'},
    {name:'البحرين',en:'Bahrain',flag:'🇧🇭'},
    {name:'بنغلاديش',en:'Bangladesh',flag:'🇧🇩'},
    {name:'بلاروسيا',en:'Belarus',flag:'🇧🇾'},
    {name:'بلجيكا',en:'Belgium',flag:'🇧🇪'},
    {name:'بليز',en:'Belize',flag:'🇧🇿'},
    {name:'بنين',en:'Benin',flag:'🇧🇯'},
    {name:'بوتان',en:'Bhutan',flag:'🇧🇹'},
    {name:'بوليفيا',en:'Bolivia',flag:'🇧🇴'},
    {name:'البوسنة والهرسك',en:'Bosnia',flag:'🇧🇦'},
    {name:'بوتسوانا',en:'Botswana',flag:'🇧🇼'},
    {name:'البرازيل',en:'Brazil',flag:'🇧🇷'},
    {name:'بروناي',en:'Brunei',flag:'🇧🇳'},
    {name:'بلغاريا',en:'Bulgaria',flag:'🇧🇬'},
    {name:'بوركينا فاسو',en:'Burkina Faso',flag:'🇧🇫'},
    {name:'بوروندي',en:'Burundi',flag:'🇧🇮'},
    {name:'كمبوديا',en:'Cambodia',flag:'🇰🇭'},
    {name:'الكاميرون',en:'Cameroon',flag:'🇨🇲'},
    {name:'كندا',en:'Canada',flag:'🇨🇦'},
    {name:'الرأس الأخضر',en:'Cape Verde',flag:'🇨🇻'},
    {name:'أفريقيا الوسطى',en:'Central African Republic',flag:'🇨🇫'},
    {name:'تشاد',en:'Chad',flag:'🇹🇩'},
    {name:'تشيلي',en:'Chile',flag:'🇨🇱'},
    {name:'الصين',en:'China',flag:'🇨🇳'},
    {name:'كولومبيا',en:'Colombia',flag:'🇨🇴'},
    {name:'جزر القمر',en:'Comoros',flag:'🇰🇲'},
    {name:'الكونغو',en:'Congo',flag:'🇨🇬'},
    {name:'كوستاريكا',en:'Costa Rica',flag:'🇨🇷'},
    {name:'كرواتيا',en:'Croatia',flag:'🇭🇷'},
    {name:'كوبا',en:'Cuba',flag:'🇨🇺'},
    {name:'قبرص',en:'Cyprus',flag:'🇨🇾'},
    {name:'التشيك',en:'Czech Republic',flag:'🇨🇿'},
    {name:'الدنمارك',en:'Denmark',flag:'🇩🇰'},
    {name:'جيبوتي',en:'Djibouti',flag:'🇩🇯'},
    {name:'الدومينيكان',en:'Dominican Republic',flag:'🇩🇴'},
    {name:'الإكوادور',en:'Ecuador',flag:'🇪🇨'},
    {name:'مصر',en:'Egypt',flag:'🇪🇬'},
    {name:'السلفادور',en:'El Salvador',flag:'🇸🇻'},
    {name:'غينيا الاستوائية',en:'Equatorial Guinea',flag:'🇬🇶'},
    {name:'إريتريا',en:'Eritrea',flag:'🇪🇷'},
    {name:'إستونيا',en:'Estonia',flag:'🇪🇪'},
    {name:'إثيوبيا',en:'Ethiopia',flag:'🇪🇹'},
    {name:'فيجي',en:'Fiji',flag:'🇫🇯'},
    {name:'فنلندا',en:'Finland',flag:'🇫🇮'},
    {name:'فرنسا',en:'France',flag:'🇫🇷'},
    {name:'الغابون',en:'Gabon',flag:'🇬🇦'},
    {name:'غامبيا',en:'Gambia',flag:'🇬🇲'},
    {name:'جورجيا',en:'Georgia',flag:'🇬🇪'},
    {name:'ألمانيا',en:'Germany',flag:'🇩🇪'},
    {name:'غانا',en:'Ghana',flag:'🇬🇭'},
    {name:'اليونان',en:'Greece',flag:'🇬🇷'},
    {name:'غواتيمالا',en:'Guatemala',flag:'🇬🇹'},
    {name:'غينيا',en:'Guinea',flag:'🇬🇳'},
    {name:'غيانا',en:'Guyana',flag:'🇬🇾'},
    {name:'هايتي',en:'Haiti',flag:'🇭🇹'},
    {name:'هندوراس',en:'Honduras',flag:'🇭🇳'},
    {name:'المجر',en:'Hungary',flag:'🇭🇺'},
    {name:'آيسلندا',en:'Iceland',flag:'🇮🇸'},
    {name:'الهند',en:'India',flag:'🇮🇳'},
    {name:'إندونيسيا',en:'Indonesia',flag:'🇮🇩'},
    {name:'إيران',en:'Iran',flag:'🇮🇷'},
    {name:'العراق',en:'Iraq',flag:'🇮🇶'},
    {name:'أيرلندا',en:'Ireland',flag:'🇮🇪'},
    {name:'إسرائيل',en:'Israel',flag:'🇮🇱'},
    {name:'إيطاليا',en:'Italy',flag:'🇮🇹'},
    {name:'ساحل العاج',en:'Ivory Coast',flag:'🇨🇮'},
    {name:'جامايكا',en:'Jamaica',flag:'🇯🇲'},
    {name:'اليابان',en:'Japan',flag:'🇯🇵'},
    {name:'الأردن',en:'Jordan',flag:'🇯🇴'},
    {name:'كازاخستان',en:'Kazakhstan',flag:'🇰🇿'},
    {name:'كينيا',en:'Kenya',flag:'🇰🇪'},
    {name:'كوريا الشمالية',en:'North Korea',flag:'🇰🇵'},
    {name:'كوريا الجنوبية',en:'South Korea',flag:'🇰🇷'},
    {name:'الكويت',en:'Kuwait',flag:'🇰🇼'},
    {name:'قيرغيزستان',en:'Kyrgyzstan',flag:'🇰🇬'},
    {name:'لاوس',en:'Laos',flag:'🇱🇦'},
    {name:'لاتفيا',en:'Latvia',flag:'🇱🇻'},
    {name:'لبنان',en:'Lebanon',flag:'🇱🇧'},
    {name:'ليسوتو',en:'Lesotho',flag:'🇱🇸'},
    {name:'ليبيريا',en:'Liberia',flag:'🇱🇷'},
    {name:'ليبيا',en:'Libya',flag:'🇱🇾'},
    {name:'ليختنشتاين',en:'Liechtenstein',flag:'🇱🇮'},
    {name:'ليتوانيا',en:'Lithuania',flag:'🇱🇹'},
    {name:'لوكسمبورغ',en:'Luxembourg',flag:'🇱🇺'},
    {name:'مدغشقر',en:'Madagascar',flag:'🇲🇬'},
    {name:'مالاوي',en:'Malawi',flag:'🇲🇼'},
    {name:'ماليزيا',en:'Malaysia',flag:'🇲🇾'},
    {name:'جزر المالديف',en:'Maldives',flag:'🇲🇻'},
    {name:'مالي',en:'Mali',flag:'🇲🇱'},
    {name:'مالطا',en:'Malta',flag:'🇲🇹'},
    {name:'موريتانيا',en:'Mauritania',flag:'🇲🇷'},
    {name:'موريشيوس',en:'Mauritius',flag:'🇲🇺'},
    {name:'المكسيك',en:'Mexico',flag:'🇲🇽'},
    {name:'مولدوفا',en:'Moldova',flag:'🇲🇩'},
    {name:'موناكو',en:'Monaco',flag:'🇲🇨'},
    {name:'منغوليا',en:'Mongolia',flag:'🇲🇳'},
    {name:'الجبل الأسود',en:'Montenegro',flag:'🇲🇪'},
    {name:'المغرب',en:'Morocco',flag:'🇲🇦'},
    {name:'موزمبيق',en:'Mozambique',flag:'🇲🇿'},
    {name:'ميانمار',en:'Myanmar',flag:'🇲🇲'},
    {name:'ناميبيا',en:'Namibia',flag:'🇳🇦'},
    {name:'نيبال',en:'Nepal',flag:'🇳🇵'},
    {name:'هولندا',en:'Netherlands',flag:'🇳🇱'},
    {name:'نيوزيلندا',en:'New Zealand',flag:'🇳🇿'},
    {name:'نيكاراغوا',en:'Nicaragua',flag:'🇳🇮'},
    {name:'النيجر',en:'Niger',flag:'🇳🇪'},
    {name:'نيجيريا',en:'Nigeria',flag:'🇳🇬'},
    {name:'مقدونيا الشمالية',en:'North Macedonia',flag:'🇲🇰'},
    {name:'النرويج',en:'Norway',flag:'🇳🇴'},
    {name:'عُمان',en:'Oman',flag:'🇴🇲'},
    {name:'باكستان',en:'Pakistan',flag:'🇵🇰'},
    {name:'بنما',en:'Panama',flag:'🇵🇦'},
    {name:'بابوا غينيا الجديدة',en:'Papua New Guinea',flag:'🇵🇬'},
    {name:'باراغواي',en:'Paraguay',flag:'🇵🇾'},
    {name:'بيرو',en:'Peru',flag:'🇵🇪'},
    {name:'الفلبين',en:'Philippines',flag:'🇵🇭'},
    {name:'بولندا',en:'Poland',flag:'🇵🇱'},
    {name:'البرتغال',en:'Portugal',flag:'🇵🇹'},
    {name:'قطر',en:'Qatar',flag:'🇶🇦'},
    {name:'رومانيا',en:'Romania',flag:'🇷🇴'},
    {name:'روسيا',en:'Russia',flag:'🇷🇺'},
    {name:'رواندا',en:'Rwanda',flag:'🇷🇼'},
    {name:'المملكة العربية السعودية',en:'Saudi Arabia',flag:'🇸🇦'},
    {name:'السنغال',en:'Senegal',flag:'🇸🇳'},
    {name:'صربيا',en:'Serbia',flag:'🇷🇸'},
    {name:'سيراليون',en:'Sierra Leone',flag:'🇸🇱'},
    {name:'سنغافورة',en:'Singapore',flag:'🇸🇬'},
    {name:'سلوفاكيا',en:'Slovakia',flag:'🇸🇰'},
    {name:'سلوفينيا',en:'Slovenia',flag:'🇸🇮'},
    {name:'الصومال',en:'Somalia',flag:'🇸🇴'},
    {name:'جنوب أفريقيا',en:'South Africa',flag:'🇿🇦'},
    {name:'جنوب السودان',en:'South Sudan',flag:'🇸🇸'},
    {name:'إسبانيا',en:'Spain',flag:'🇪🇸'},
    {name:'سريلانكا',en:'Sri Lanka',flag:'🇱🇰'},
    {name:'السودان',en:'Sudan',flag:'🇸🇩'},
    {name:'سورينام',en:'Suriname',flag:'🇸🇷'},
    {name:'السويد',en:'Sweden',flag:'🇸🇪'},
    {name:'سويسرا',en:'Switzerland',flag:'🇨🇭'},
    {name:'سوريا',en:'Syria',flag:'🇸🇾'},
    {name:'تايوان',en:'Taiwan',flag:'🇹🇼'},
    {name:'طاجيكستان',en:'Tajikistan',flag:'🇹🇯'},
    {name:'تنزانيا',en:'Tanzania',flag:'🇹🇿'},
    {name:'تايلاند',en:'Thailand',flag:'🇹🇭'},
    {name:'تيمور الشرقية',en:'Timor-Leste',flag:'🇹🇱'},
    {name:'توغو',en:'Togo',flag:'🇹🇬'},
    {name:'ترينيداد وتوباغو',en:'Trinidad and Tobago',flag:'🇹🇹'},
    {name:'تونس',en:'Tunisia',flag:'🇹🇳'},
    {name:'تركيا',en:'Turkey',flag:'🇹🇷'},
    {name:'تركمانستان',en:'Turkmenistan',flag:'🇹🇲'},
    {name:'أوغندا',en:'Uganda',flag:'🇺🇬'},
    {name:'أوكرانيا',en:'Ukraine',flag:'🇺🇦'},
    {name:'الإمارات العربية المتحدة',en:'UAE',flag:'🇦🇪'},
    {name:'المملكة المتحدة',en:'United Kingdom',flag:'🇬🇧'},
    {name:'الولايات المتحدة الأمريكية',en:'USA',flag:'🇺🇸'},
    {name:'أوروغواي',en:'Uruguay',flag:'🇺🇾'},
    {name:'أوزبكستان',en:'Uzbekistan',flag:'🇺🇿'},
    {name:'فنزويلا',en:'Venezuela',flag:'🇻🇪'},
    {name:'فيتنام',en:'Vietnam',flag:'🇻🇳'},
    {name:'اليمن',en:'Yemen',flag:'🇾🇪'},
    {name:'زامبيا',en:'Zambia',flag:'🇿🇲'},
    {name:'زيمبابوي',en:'Zimbabwe',flag:'🇿🇼'},
    {name:'فلسطين',en:'Palestine',flag:'🇵🇸'},
];

let selectedCountry = document.getElementById('countryValue').value;

function renderList(list) {
    const container = document.getElementById('countryList');
    const empty     = document.getElementById('countryEmpty');
    container.innerHTML = '';
    if (!list.length) { empty.style.display='block'; return; }
    empty.style.display = 'none';
    list.forEach(c => {
        const div = document.createElement('div');
        div.className = 'country-item';
        div.innerHTML = `<span style="font-size:18px">${c.flag}</span><span>${c.name}</span><span style="color:#aaa;font-size:11px;margin-right:auto">${c.en}</span>`;
        div.onclick = () => selectCountry(c);
        container.appendChild(div);
    });
}

function selectCountry(c) {
    selectedCountry = c.name;
    document.getElementById('countryValue').value  = c.name;
    document.getElementById('countrySearch').value = c.name;
    hideDropdown();
}

function filterCountries(q) {
    document.getElementById('countryDropdown').style.display = 'block';
    document.getElementById('countryValue').value = q; // fallback نص حر
    if (!q.trim()) { renderList(COUNTRIES); return; }
    const lq = q.toLowerCase();
    renderList(COUNTRIES.filter(c =>
        c.name.includes(q) || c.en.toLowerCase().includes(lq)
    ));
}

function showDropdown() {
    document.getElementById('countryDropdown').style.display = 'block';
    renderList(COUNTRIES);
}
function hideDropdown() {
    document.getElementById('countryDropdown').style.display = 'none';
}

document.addEventListener('click', e => {
    if (!e.target.closest('#countrySearch') && !e.target.closest('#countryDropdown'))
        hideDropdown();
});

/* ========== Lightbox ========== */
function openLightbox(src) {
    document.getElementById('lightboxImg').src = src;
    document.getElementById('lightbox').classList.add('open');
    document.body.style.overflow = 'hidden';
}
function closeLightbox() {
    document.getElementById('lightbox').classList.remove('open');
    document.body.style.overflow = '';
}
document.addEventListener('keydown', e => { if (e.key === 'Escape') closeLightbox(); });

/* ========== File Preview ========== */
function handleFilePreview(input) {
    const file = input.files[0];
    if (!file) return;
    const name = file.name;
    const ext  = name.split('.').pop().toLowerCase();
    const size = (file.size / 1024).toFixed(0) + ' KB';
    const isImage = ['jpg','jpeg','png','gif','webp'].includes(ext);
    const isPdf   = ext === 'pdf';
    const lbl = document.getElementById('attachmentLabel');
    if (lbl) lbl.textContent = name;
    document.getElementById('previewImage').style.display = 'none';
    document.getElementById('previewPdf').style.display   = 'none';
    document.getElementById('previewOther').style.display = 'none';
    document.getElementById('previewArea').style.display  = 'block';
    document.getElementById('previewFooterName').textContent = name;
    const reader = new FileReader();
    if (isImage) {
        reader.onload = e => {
            document.getElementById('previewImg').src = e.target.result;
            document.getElementById('previewImage').style.display = 'block';
        };
        reader.readAsDataURL(file);
    } else if (isPdf) {
        reader.onload = e => {
            document.getElementById('previewPdfFrame').src = e.target.result;
            document.getElementById('previewPdf').style.display = 'block';
        };
        reader.readAsDataURL(file);
    } else {
        document.getElementById('previewFileName').textContent = name;
        document.getElementById('previewFileSize').textContent = size;
        document.getElementById('previewOther').style.display  = 'flex';
    }
}
function clearPreview() {
    document.getElementById('attachmentInput').value = '';
    const lbl = document.getElementById('attachmentLabel');
    if (lbl) lbl.textContent = 'اضغط لاختيار ملف • الحجم الأقصى 5MB';
    document.getElementById('previewArea').style.display = 'none';
    document.getElementById('previewImg').src = '';
    document.getElementById('previewPdfFrame').src = '';
}
</script>
@endsection
