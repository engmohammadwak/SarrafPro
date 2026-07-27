@extends('layouts.superadmin')
@section('title', 'إضافة محل جديد - صراف برو')
@section('page-title', 'إضافة محل جديد')
@section('content')
<div style="max-width:700px;">

    @if($errors->any())
    <div style="background:rgba(239,68,68,0.1);border:1px solid rgba(239,68,68,0.3);color:#ef4444;padding:14px 20px;border-radius:12px;margin-bottom:20px;">
        <ul style="margin:0;padding-right:18px;">
            @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route('superadmin.shops.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        {{-- بيانات المحل --}}
        <div class="card" style="margin-bottom:20px;">
            <div class="card-header">
                <h3><i class="fas fa-store" style="color:var(--accent);margin-left:8px;"></i> بيانات المحل</h3>
            </div>
            <div class="card-body">
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">

                    <div>
                        <label style="display:block;margin-bottom:6px;font-size:14px;color:var(--text-muted);">اسم المحل (عربي) <span style="color:#ef4444;">*</span></label>
                        <input type="text" name="name" value="{{ old('name') }}" required
                            style="width:100%;padding:10px 14px;background:#f8f9fc;border:1px solid var(--border);border-radius:8px;font-family:Tajawal,sans-serif;font-size:14px;color:var(--text-dark);box-sizing:border-box">
                    </div>

                    <div>
                        <label style="display:block;margin-bottom:6px;font-size:14px;color:var(--text-muted);">اسم المحل (إنجليزي)</label>
                        <input type="text" name="name_en" value="{{ old('name_en') }}"
                            style="width:100%;padding:10px 14px;background:#f8f9fc;border:1px solid var(--border);border-radius:8px;font-family:Tajawal,sans-serif;font-size:14px;color:var(--text-dark);box-sizing:border-box">
                    </div>

                    <div>
                        <label style="display:block;margin-bottom:6px;font-size:14px;color:var(--text-muted);">رقم الترخيص</label>
                        <input type="text" name="license_number" value="{{ old('license_number') }}"
                            style="width:100%;padding:10px 14px;background:#f8f9fc;border:1px solid var(--border);border-radius:8px;font-family:Tajawal,sans-serif;font-size:14px;color:var(--text-dark);box-sizing:border-box">
                    </div>

                    <div>
                        <label style="display:block;margin-bottom:6px;font-size:14px;color:var(--text-muted);">رقم الهاتف</label>
                        <input type="text" name="phone" value="{{ old('phone') }}"
                            style="width:100%;padding:10px 14px;background:#f8f9fc;border:1px solid var(--border);border-radius:8px;font-family:Tajawal,sans-serif;font-size:14px;color:var(--text-dark);box-sizing:border-box">
                    </div>

                    <div>
                        <label style="display:block;margin-bottom:6px;font-size:14px;color:var(--text-muted);">البريد الإلكتروني</label>
                        <input type="email" name="email" value="{{ old('email') }}"
                            style="width:100%;padding:10px 14px;background:#f8f9fc;border:1px solid var(--border);border-radius:8px;font-family:Tajawal,sans-serif;font-size:14px;color:var(--text-dark);box-sizing:border-box">
                    </div>

                    {{-- حقل الدولة مع بحث --}}
                    <div style="position:relative">
                        <label style="display:block;margin-bottom:6px;font-size:14px;color:var(--text-muted);">الدولة / المدينة</label>
                        <input type="hidden" name="city" id="cityValue" value="{{ old('city') }}">
                        <div style="position:relative">
                            <i class="fas fa-globe" style="position:absolute;right:11px;top:50%;transform:translateY(-50%);color:var(--text-muted);font-size:13px;pointer-events:none;z-index:1"></i>
                            <input type="text" id="citySearch"
                                placeholder="ابحث عن دولة..."
                                autocomplete="off"
                                value="{{ old('city') }}"
                                oninput="filterCountries(this.value)"
                                onfocus="showCountryDropdown()"
                                style="width:100%;padding:10px 34px 10px 34px;background:#f8f9fc;border:1.5px solid var(--border);border-radius:8px;font-family:Tajawal,sans-serif;font-size:14px;color:var(--text-dark);box-sizing:border-box;transition:border-color .2s">
                            <i class="fas fa-chevron-down" style="position:absolute;left:11px;top:50%;transform:translateY(-50%);color:var(--text-muted);font-size:11px;pointer-events:none"></i>
                        </div>
                        <div id="cityDropdown"
                            style="display:none;position:absolute;top:calc(100% + 4px);left:0;right:0;background:#fff;border:1.5px solid var(--accent);border-radius:10px;box-shadow:0 8px 24px rgba(0,0,0,.12);z-index:999;max-height:220px;overflow-y:auto">
                            <div id="cityList"></div>
                            <div id="cityEmpty" style="display:none;padding:12px 14px;font-size:13px;color:var(--text-muted);text-align:center">لا توجد نتائج</div>
                        </div>
                    </div>

                    {{-- منطقة رفع الملف مع بريفيو للصور --}}
                    <div style="grid-column:1/-1">
                        <label style="display:block;margin-bottom:6px;font-size:14px;color:var(--text-muted);">ملف مرفق <span style="font-size:12px;color:var(--text-muted);font-weight:400">(اختياري — PDF, صورة, Word)</span></label>
                        <div id="uploadZone"
                             style="border:2px dashed var(--border);border-radius:10px;padding:20px;text-align:center;background:#fafbfc;cursor:pointer;transition:border-color .2s"
                             onclick="document.getElementById('shopAttachment').click()"
                             ondragover="event.preventDefault();this.style.borderColor='var(--accent)'"
                             ondragleave="this.style.borderColor='var(--border)'"
                             ondrop="handleDrop(event)">
                            <i class="fas fa-cloud-upload-alt" style="font-size:28px;color:var(--text-muted);margin-bottom:8px;display:block"></i>
                            <p style="font-size:13px;color:var(--text-muted);margin:0" id="shopAttachmentLabel">اضغط أو اسحب وأفلت ملف • الحجم الأقصى 5MB</p>
                            <input type="file" id="shopAttachment" name="attachment"
                                   accept=".pdf,.jpg,.jpeg,.png,.doc,.docx"
                                   style="display:none"
                                   onchange="handleFile(this.files[0])">
                        </div>
                        <div id="imgPreviewWrap" style="display:none;margin-top:12px;position:relative;">
                            <img id="imgPreview" src="" alt="معاينة مسبقة"
                                 style="max-width:100%;max-height:300px;border-radius:10px;border:1px solid var(--border);display:block">
                            <button type="button" onclick="clearFile()"
                                    style="position:absolute;top:8px;left:8px;background:rgba(239,68,68,.85);color:#fff;border:none;border-radius:50%;width:30px;height:30px;font-size:16px;cursor:pointer;display:flex;align-items:center;justify-content:center;">&times;</button>
                        </div>
                        <div id="filePreviewWrap" style="display:none;margin-top:12px;background:#f8f9fc;border:1px solid var(--border);border-radius:10px;padding:12px 16px;align-items:center;gap:12px">
                            <i class="fas fa-file-alt" style="font-size:24px;color:var(--accent)"></i>
                            <div style="flex:1">
                                <p id="fileName" style="font-weight:600;font-size:14px;margin:0"></p>
                                <p id="fileSize" style="font-size:12px;color:var(--text-muted);margin:0"></p>
                            </div>
                            <button type="button" onclick="clearFile()"
                                    style="background:rgba(239,68,68,.1);color:#ef4444;border:1px solid rgba(239,68,68,.3);border-radius:8px;padding:6px 12px;cursor:pointer;font-family:Tajawal,sans-serif;font-size:13px">
                                <i class="fas fa-times"></i> حذف
                            </button>
                        </div>
                    </div>

                    <div style="grid-column:1/-1">
                        <label style="display:block;margin-bottom:6px;font-size:14px;color:var(--text-muted);">ملاحظة</label>
                        <textarea name="notes" rows="3" placeholder="أي معلومات إضافية..."
                            style="width:100%;padding:10px 14px;background:#f8f9fc;border:1px solid var(--border);border-radius:8px;font-family:Tajawal,sans-serif;font-size:14px;color:var(--text-dark);resize:vertical;box-sizing:border-box">{{ old('notes') }}</textarea>
                    </div>

                </div>
            </div>
        </div>

        {{-- حساب مدير المحل --}}
        <div class="card" style="margin-bottom:24px;">
            <div class="card-header">
                <h3><i class="fas fa-user-tie" style="color:var(--accent);margin-left:8px;"></i> حساب مدير المحل</h3>
            </div>
            <div class="card-body">
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">

                    <div>
                        <label style="display:block;margin-bottom:6px;font-size:14px;color:var(--text-muted);">اسم المدير <span style="color:#ef4444;">*</span></label>
                        <input type="text" name="admin_name" value="{{ old('admin_name') }}" required
                            style="width:100%;padding:10px 14px;background:#f8f9fc;border:1px solid var(--border);border-radius:8px;font-family:Tajawal,sans-serif;font-size:14px;color:var(--text-dark);box-sizing:border-box">
                    </div>

                    <div>
                        <label style="display:block;margin-bottom:6px;font-size:14px;color:var(--text-muted);">إيميل المدير <span style="color:#ef4444;">*</span></label>
                        <input type="email" name="admin_email" value="{{ old('admin_email') }}" required
                            style="width:100%;padding:10px 14px;background:#f8f9fc;border:1px solid var(--border);border-radius:8px;font-family:Tajawal,sans-serif;font-size:14px;color:var(--text-dark);box-sizing:border-box">
                    </div>

                    {{-- Username بدون أيقونة @ --}}
                    <div style="grid-column:1/-1">
                        <label style="display:block;margin-bottom:6px;font-size:14px;color:var(--text-muted);">Username <span style="color:#ef4444;">*</span></label>
                        <input type="text" name="username" value="{{ old('username') }}" required
                            placeholder="shop_username"
                            style="width:100%;padding:10px 14px;background:#f8f9fc;border:1px solid var(--border);border-radius:8px;font-family:monospace;font-size:14px;direction:ltr;color:var(--text-dark);box-sizing:border-box"
                            autocomplete="off">
                        <p style="font-size:12px;color:var(--text-muted);margin-top:4px">حروف وأرقام وشرطة سفلية فقط — يُستخدم لتسجيل دخول مدير المحل</p>
                    </div>

                    <div style="grid-column:1/-1">
                        <label style="display:block;margin-bottom:6px;font-size:14px;color:var(--text-muted);">كلمة المرور <span style="color:#ef4444;">*</span></label>
                        <input type="text" name="admin_password" value="{{ old('admin_password') }}" required
                            style="width:100%;padding:10px 14px;background:#f8f9fc;border:1px solid var(--border);border-radius:8px;font-family:Tajawal,sans-serif;font-size:14px;color:var(--text-dark);box-sizing:border-box">
                        <p style="font-size:12px;color:var(--text-muted);margin-top:6px;"><i class="fas fa-info-circle"></i> سيستخدم المدير هذه البيانات لتسجيل الدخول.</p>
                    </div>

                </div>
            </div>
        </div>

        <div style="display:flex;gap:12px;">
            <button type="submit" class="btn btn-gold"><i class="fas fa-plus"></i> إنشاء المحل</button>
            <a href="{{ route('superadmin.shops.index') }}" class="btn" style="background:var(--border);color:var(--text-dark);"><i class="fas fa-times"></i> إلغاء</a>
        </div>

    </form>
</div>

<style>
.country-item { padding:9px 14px; font-size:13px; cursor:pointer; display:flex; align-items:center; gap:8px; transition:background .15s; border-bottom:1px solid #f3f4f6; }
.country-item:last-child { border-bottom:none; }
.country-item:hover { background:var(--accent,#d4a017); color:#fff; }
#cityDropdown::-webkit-scrollbar { width:5px; }
#cityDropdown::-webkit-scrollbar-thumb { background:#ddd; border-radius:4px; }
#citySearch:focus { outline:none; border-color:var(--accent) !important; background:#fff; }
</style>

<script>
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

function renderCityList(list) {
    const container = document.getElementById('cityList');
    const empty     = document.getElementById('cityEmpty');
    container.innerHTML = '';
    if (!list.length) { empty.style.display='block'; return; }
    empty.style.display = 'none';
    list.forEach(c => {
        const div = document.createElement('div');
        div.className = 'country-item';
        div.innerHTML = `<span style="font-size:18px;line-height:1">${c.flag}</span><span>${c.name}</span><span style="color:#aaa;font-size:11px;margin-right:auto">${c.en}</span>`;
        div.onclick = () => selectCity(c);
        container.appendChild(div);
    });
}

function selectCity(c) {
    document.getElementById('cityValue').value  = c.name;
    document.getElementById('citySearch').value = c.name;
    hideCityDropdown();
}

function filterCountries(q) {
    document.getElementById('cityDropdown').style.display = 'block';
    document.getElementById('cityValue').value = q;
    if (!q.trim()) { renderCityList(COUNTRIES); return; }
    const lq = q.toLowerCase();
    renderCityList(COUNTRIES.filter(c => c.name.includes(q) || c.en.toLowerCase().includes(lq)));
}

function showCountryDropdown() {
    document.getElementById('cityDropdown').style.display = 'block';
    renderCityList(COUNTRIES);
}
function hideCityDropdown() {
    document.getElementById('cityDropdown').style.display = 'none';
}

document.addEventListener('click', e => {
    if (!e.target.closest('#citySearch') && !e.target.closest('#cityDropdown'))
        hideCityDropdown();
});

// ===== File Upload =====
const imageExts = ['jpg','jpeg','png','gif','webp'];

function handleFile(file) {
    if (!file) return;
    const ext = file.name.split('.').pop().toLowerCase();
    document.getElementById('shopAttachmentLabel').textContent = file.name;
    if (imageExts.includes(ext)) {
        const reader = new FileReader();
        reader.onload = e => {
            document.getElementById('imgPreview').src = e.target.result;
            document.getElementById('imgPreviewWrap').style.display = 'block';
            document.getElementById('filePreviewWrap').style.display = 'none';
        };
        reader.readAsDataURL(file);
    } else {
        document.getElementById('imgPreviewWrap').style.display = 'none';
        document.getElementById('fileName').textContent = file.name;
        document.getElementById('fileSize').textContent = (file.size / 1024 / 1024).toFixed(2) + ' MB';
        document.getElementById('filePreviewWrap').style.display = 'flex';
    }
}

function clearFile() {
    document.getElementById('shopAttachment').value = '';
    document.getElementById('shopAttachmentLabel').textContent = 'اضغط أو اسحب وأفلت ملف • الحجم الأقصى 5MB';
    document.getElementById('imgPreviewWrap').style.display = 'none';
    document.getElementById('filePreviewWrap').style.display = 'none';
}

function handleDrop(e) {
    e.preventDefault();
    document.getElementById('uploadZone').style.borderColor = 'var(--border)';
    const file = e.dataTransfer.files[0];
    if (!file) return;
    const dt = new DataTransfer();
    dt.items.add(file);
    document.getElementById('shopAttachment').files = dt.files;
    handleFile(file);
}
</script>
@endsection
