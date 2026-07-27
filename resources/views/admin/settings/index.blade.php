@extends('layouts.admin')
@section('title', 'إعدادات المحل')
@section('page-title', 'إعدادات المحل')
@section('content')
<div style="max-width:640px;display:flex;flex-direction:column;gap:20px">

    @if(session('success'))
    <div style="background:rgba(16,185,129,0.1);border:1px solid rgba(16,185,129,0.3);color:#065f46;padding:14px;border-radius:12px">
        <i class="fas fa-check-circle"></i> {{ session('success') }}
    </div>
    @endif

    @if($errors->any())
    <div style="background:rgba(239,68,68,0.07);border:1px solid rgba(239,68,68,0.25);color:#dc2626;padding:14px;border-radius:12px">
        <i class="fas fa-exclamation-circle"></i>
        <ul style="margin:8px 0 0 0;padding-right:18px">
            @foreach($errors->all() as $e)<li style="font-size:13px">{{ $e }}</li>@endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data">
        @csrf @method('PUT')

        {{-- لوجو المحل --}}
        <div class="card">
            <div class="card-header">
                <h3><i class="fas fa-image" style="color:var(--accent);margin-left:8px"></i> لوجو المحل</h3>
            </div>
            <div class="card-body">
                <div style="display:flex;align-items:center;gap:20px;flex-wrap:wrap">
                    <div id="logo-preview-wrap" style="width:80px;height:80px;border-radius:16px;border:2px dashed var(--border);background:#f8f9fc;display:flex;align-items:center;justify-content:center;overflow:hidden;flex-shrink:0">
                        @if($shop->logo)
                            <img id="logo-preview" src="{{ Storage::url($shop->logo) }}" alt="لوجو" style="width:100%;height:100%;object-fit:contain">
                        @else
                            <img id="logo-preview" src="" alt="" style="width:100%;height:100%;object-fit:contain;display:none">
                            <i id="logo-placeholder" class="fas fa-store" style="font-size:28px;color:#d1d5db"></i>
                        @endif
                    </div>
                    <div style="flex:1">
                        <label style="display:block;margin-bottom:6px;font-size:14px;font-weight:600;color:var(--text-muted)">رفع لوجو جديد</label>
                        <input type="file" name="logo" id="logo-input" accept="image/*"
                            style="font-size:13px;color:var(--text-muted)" onchange="previewLogo(this)">
                        <p style="font-size:11px;color:#9ca3af;margin-top:5px">PNG, JPG, WebP — حد أقصى 2MB</p>
                        @if($shop->logo)
                        <label style="display:flex;align-items:center;gap:6px;margin-top:8px;font-size:13px;color:#dc2626;cursor:pointer">
                            <input type="checkbox" name="remove_logo" value="1"> حذف اللوجو الحالي
                        </label>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- بيانات المحل --}}
        <div class="card">
            <div class="card-header">
                <h3><i class="fas fa-store" style="color:var(--accent);margin-left:8px"></i> بيانات المحل</h3>
            </div>
            <div class="card-body">
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px">
                    <div>
                        <label class="field-label">اسم المحل (عربي)</label>
                        <input type="text" name="name" value="{{ old('name',$shop->name) }}" required class="field-input">
                    </div>
                    <div>
                        <label class="field-label">اسم المحل (إنجليزي)</label>
                        <input type="text" name="name_en" value="{{ old('name_en',$shop->name_en) }}" class="field-input">
                    </div>
                    <div>
                        <label class="field-label">الهاتف</label>
                        <input type="text" name="phone" value="{{ old('phone',$shop->phone) }}" class="field-input">
                    </div>
                    <div>
                        <label class="field-label">المدينة</label>
                        <input type="text" name="city" value="{{ old('city',$shop->city) }}" class="field-input">
                    </div>
                    <div style="grid-column:1/-1">
                        <label class="field-label">رقم الترخيص</label>
                        <input type="text" value="{{ $shop->license_number ?? '-' }}" disabled
                            style="width:100%;padding:10px 14px;background:#f3f4f6;border:1px solid var(--border);border-radius:8px;font-family:Tajawal,sans-serif;font-size:14px;color:#9ca3af;cursor:not-allowed">
                        <p style="font-size:11px;color:#9ca3af;margin-top:4px"><i class="fas fa-lock" style="font-size:10px"></i> رقم الترخيص لا يمكن تعديله من هنا</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- بيانات الحساب --}}
        <div class="card">
            <div class="card-header">
                <h3><i class="fas fa-user-circle" style="color:var(--accent);margin-left:8px"></i> بيانات الحساب</h3>
            </div>
            <div class="card-body">
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px">
                    <div>
                        <label class="field-label">البريد الإلكتروني</label>
                        <input type="email" name="email" value="{{ old('email',$user->email) }}" class="field-input" placeholder="email@example.com">
                    </div>
                    <div>
                        <label class="field-label">Username</label>
                        <input type="text" name="username" value="{{ old('username',$user->username) }}" class="field-input" placeholder="username123">
                    </div>
                </div>
            </div>
        </div>

        {{-- تغيير كلمة السر --}}
        <div class="card">
            <div class="card-header">
                <h3><i class="fas fa-lock" style="color:var(--accent);margin-left:8px"></i> تغيير كلمة السر</h3>
            </div>
            <div class="card-body">
                <p style="font-size:13px;color:var(--text-muted);margin-bottom:16px">اترك الحقول فارغة إذا لا تريد تغيير كلمة السر</p>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px">
                    <div style="grid-column:1/-1">
                        <label class="field-label">كلمة السر الحالية</label>
                        <input type="password" name="current_password" class="field-input" placeholder="أدخل كلمة السر الحالية"
                            @error('current_password') style="border-color:#dc2626" @enderror>
                        @error('current_password')
                        <p style="color:#dc2626;font-size:12px;margin-top:4px">{{ $message }}</p>
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

        <div style="margin-top:4px">
            <button type="submit" class="btn btn-gold"><i class="fas fa-save"></i> حفظ الإعدادات</button>
        </div>
    </form>
</div>

<style>
.field-label{display:block;margin-bottom:6px;font-size:14px;font-weight:600;color:var(--text-muted)}
.field-input{width:100%;padding:10px 14px;background:#f8f9fc;border:1px solid var(--border);border-radius:8px;font-family:Tajawal,sans-serif;font-size:14px;color:var(--text);transition:border-color 0.2s}
.field-input:focus{outline:none;border-color:var(--accent);background:#fff}
</style>

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
