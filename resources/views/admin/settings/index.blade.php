@extends('layouts.admin')
@section('title', 'إعدادات المحل')
@section('page-title', 'إعدادات المحل')

@push('styles')
<style>
/* ===== Settings Page ===== */
.settings-wrap {
    max-width: 680px;
    display: flex;
    flex-direction: column;
    gap: 24px;
}

/* Cards */
.settings-card {
    background: #fff;
    border-radius: 16px;
    box-shadow: 0 2px 15px rgba(0,0,0,0.07);
    overflow: hidden;
}
.settings-card-header {
    padding: 18px 24px;
    border-bottom: 1px solid #e5e7eb;
    display: flex;
    align-items: center;
    gap: 10px;
}
.settings-card-header i {
    color: var(--accent);
    font-size: 16px;
    width: 20px;
    text-align: center;
}
.settings-card-header h3 {
    font-size: 15px;
    font-weight: 700;
    color: var(--text-dark);
    margin: 0;
}
.settings-card-body {
    padding: 24px;
    display: flex;
    flex-direction: column;
    gap: 20px;
}

/* Form Grid */
.form-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 16px;
}
.form-grid .full { grid-column: 1 / -1; }

/* Field */
.field-label {
    display: block;
    margin-bottom: 7px;
    font-size: 13px;
    font-weight: 600;
    color: #6b7280;
}
.field-input {
    width: 100%;
    padding: 11px 14px;
    background: #f8f9fc;
    border: 1.5px solid #e5e7eb;
    border-radius: 10px;
    font-family: 'Tajawal', sans-serif;
    font-size: 14px;
    color: #1a1f3c;
    transition: border-color 0.2s, background 0.2s;
    line-height: 1.5;
}
.field-input:focus {
    outline: none;
    border-color: var(--accent);
    background: #fff;
    box-shadow: 0 0 0 3px rgba(201,168,76,0.12);
}
.field-input:disabled {
    background: #f3f4f6;
    color: #9ca3af;
    cursor: not-allowed;
    border-color: #e5e7eb;
}
.field-hint {
    font-size: 11.5px;
    color: #9ca3af;
    margin-top: 5px;
    display: flex;
    align-items: center;
    gap: 4px;
}
.field-error {
    color: #dc2626;
    font-size: 12px;
    margin-top: 5px;
}

/* Logo Preview */
.logo-upload-row {
    display: flex;
    align-items: center;
    gap: 20px;
    flex-wrap: wrap;
}
.logo-preview-box {
    width: 88px;
    height: 88px;
    border-radius: 14px;
    border: 2px dashed #d1d5db;
    background: #f8f9fc;
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
    flex-shrink: 0;
    transition: border-color 0.2s;
}
.logo-preview-box:hover { border-color: var(--accent); }
.logo-preview-box img { width: 100%; height: 100%; object-fit: contain; }
.logo-upload-info { flex: 1; display: flex; flex-direction: column; gap: 8px; }
.logo-upload-info label.upload-label {
    font-size: 13px;
    font-weight: 600;
    color: #6b7280;
    margin-bottom: 2px;
}
.remove-logo-label {
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: 13px;
    color: #dc2626;
    cursor: pointer;
    margin-top: 4px;
}

/* Password section note */
.section-note {
    font-size: 13px;
    color: #9ca3af;
    padding: 10px 14px;
    background: #f8f9fc;
    border-radius: 8px;
    border-right: 3px solid #e5e7eb;
}

/* Submit */
.settings-submit {
    display: flex;
    align-items: center;
    gap: 12px;
    padding-top: 4px;
}

/* Responsive */
@media (max-width: 560px) {
    .form-grid { grid-template-columns: 1fr; }
    .form-grid .full { grid-column: 1; }
    .settings-card-body { padding: 18px; }
}
</style>
@endpush

@section('content')
<div class="settings-wrap">

    {{-- Errors --}}
    @if($errors->any())
    <div style="background:rgba(239,68,68,0.07);border:1px solid rgba(239,68,68,0.25);color:#dc2626;padding:14px 18px;border-radius:12px;display:flex;flex-direction:column;gap:6px">
        <div style="font-weight:600;font-size:14px"><i class="fas fa-exclamation-circle"></i> يرجى مراجعة الأخطاء التالية:</div>
        <ul style="padding-right:20px;margin:0">
            @foreach($errors->all() as $e)
                <li style="font-size:13px">{{ $e }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data" style="display:flex;flex-direction:column;gap:24px">
        @csrf @method('PUT')

        {{-- لوجو المحل --}}
        <div class="settings-card">
            <div class="settings-card-header">
                <i class="fas fa-image"></i>
                <h3>لوجو المحل</h3>
            </div>
            <div class="settings-card-body">
                <div class="logo-upload-row">
                    <div class="logo-preview-box" id="logo-preview-wrap">
                        @if($shop->logo)
                            <img id="logo-preview" src="{{ Storage::url($shop->logo) }}" alt="لوجو">
                        @else
                            <img id="logo-preview" src="" alt="" style="display:none">
                            <i id="logo-placeholder" class="fas fa-store" style="font-size:26px;color:#d1d5db"></i>
                        @endif
                    </div>
                    <div class="logo-upload-info">
                        <label class="upload-label">رفع لوجو جديد</label>
                        <input type="file" name="logo" id="logo-input" accept="image/*"
                            style="font-size:13px;color:#6b7280" onchange="previewLogo(this)">
                        <span class="field-hint">PNG, JPG, WebP &mdash; حد أقصى 2MB</span>
                        @if($shop->logo)
                        <label class="remove-logo-label">
                            <input type="checkbox" name="remove_logo" value="1">
                            حذف اللوجو الحالي
                        </label>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- بيانات المحل --}}
        <div class="settings-card">
            <div class="settings-card-header">
                <i class="fas fa-store"></i>
                <h3>بيانات المحل</h3>
            </div>
            <div class="settings-card-body">
                <div class="form-grid">
                    <div>
                        <label class="field-label">اسم المحل (عربي)</label>
                        <input type="text" name="name" value="{{ old('name', $shop->name) }}" required class="field-input" placeholder="مثال: الصراف الذهبي">
                    </div>
                    <div>
                        <label class="field-label">اسم المحل (إنجليزي)</label>
                        <input type="text" name="name_en" value="{{ old('name_en', $shop->name_en) }}" class="field-input" placeholder="Golden Exchange">
                    </div>
                    <div>
                        <label class="field-label">الهاتف</label>
                        <input type="text" name="phone" value="{{ old('phone', $shop->phone) }}" class="field-input" placeholder="+968 XXXX XXXX">
                    </div>
                    <div>
                        <label class="field-label">المدينة</label>
                        <input type="text" name="city" value="{{ old('city', $shop->city) }}" class="field-input" placeholder="مسقط">
                    </div>
                    <div class="full">
                        <label class="field-label">رقم الترخيص</label>
                        <input type="text" value="{{ $shop->license_number ?? '-' }}" disabled class="field-input">
                        <span class="field-hint"><i class="fas fa-lock" style="font-size:10px"></i> رقم الترخيص لا يمكن تعديله من هنا</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- بيانات الحساب --}}
        <div class="settings-card">
            <div class="settings-card-header">
                <i class="fas fa-user-circle"></i>
                <h3>بيانات الحساب</h3>
            </div>
            <div class="settings-card-body">
                <div class="form-grid">
                    <div>
                        <label class="field-label">البريد الإلكتروني</label>
                        <input type="email" name="email" value="{{ old('email', $user->email) }}" class="field-input" placeholder="email@example.com">
                    </div>
                    <div>
                        <label class="field-label">اسم المستخدم</label>
                        <input type="text" name="username" value="{{ old('username', $user->username) }}" class="field-input" placeholder="username123">
                    </div>
                </div>
            </div>
        </div>

        {{-- تغيير كلمة السر --}}
        <div class="settings-card">
            <div class="settings-card-header">
                <i class="fas fa-lock"></i>
                <h3>تغيير كلمة السر</h3>
            </div>
            <div class="settings-card-body">
                <p class="section-note">اترك الحقول فارغة إذا لا تريد تغيير كلمة السر</p>
                <div class="form-grid">
                    <div class="full">
                        <label class="field-label">كلمة السر الحالية</label>
                        <input type="password" name="current_password" class="field-input" placeholder="أدخل كلمة السر الحالية"
                            @error('current_password') style="border-color:#dc2626" @enderror>
                        @error('current_password')
                            <span class="field-error"><i class="fas fa-exclamation-circle"></i> {{ $message }}</span>
                        @enderror
                    </div>
                    <div>
                        <label class="field-label">كلمة السر الجديدة</label>
                        <input type="password" name="new_password" class="field-input" placeholder="٦ أحرف على الأقل">
                    </div>
                    <div>
                        <label class="field-label">تأكيد كلمة السر</label>
                        <input type="password" name="new_password_confirmation" class="field-input" placeholder="أعد كتابة كلمة السر">
                    </div>
                </div>
            </div>
        </div>

        {{-- Submit --}}
        <div class="settings-submit">
            <button type="submit" class="btn btn-gold" style="padding:12px 28px;font-size:15px">
                <i class="fas fa-save"></i> حفظ الإعدادات
            </button>
            <span style="font-size:13px;color:#9ca3af">سيتم تطبيق التغييرات فوراً</span>
        </div>

    </form>
</div>

<script>
function previewLogo(input) {
    const preview = document.getElementById('logo-preview');
    const placeholder = document.getElementById('logo-placeholder');
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => {
            preview.src = e.target.result;
            preview.style.display = 'block';
            if (placeholder) placeholder.style.display = 'none';
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endsection
