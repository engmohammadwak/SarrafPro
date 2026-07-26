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

    <form action="{{ route('superadmin.shops.store') }}" method="POST">
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
                        <label style="display:block;margin-bottom:6px;font-size:14px;color:var(--text-muted);">Username</label>
                        <div style="position:relative">
                            <span style="position:absolute;right:12px;top:50%;transform:translateY(-50%);color:var(--text-muted);font-size:15px;">&#64;</span>
                            <input type="text" name="username" value="{{ old('username') }}" placeholder="shop_username"
                                style="width:100%;padding:10px 14px;padding-right:32px;background:#f8f9fc;border:1px solid var(--border);border-radius:8px;font-family:monospace;font-size:14px;direction:ltr;color:var(--text-dark);box-sizing:border-box">
                        </div>
                        <p style="font-size:12px;color:var(--text-muted);margin-top:4px">اختياري — حروف وأرقام وشرطة سفلية فقط</p>
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

                    <div>
                        <label style="display:block;margin-bottom:6px;font-size:14px;color:var(--text-muted);">المدينة</label>
                        <input type="text" name="city" value="{{ old('city') }}"
                            style="width:100%;padding:10px 14px;background:#f8f9fc;border:1px solid var(--border);border-radius:8px;font-family:Tajawal,sans-serif;font-size:14px;color:var(--text-dark);box-sizing:border-box">
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
@endsection
