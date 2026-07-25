@extends('layouts.superadmin')
@section('title', 'تعديل ' . $shop->name . ' - صراف برو')
@section('page-title', 'تعديل المحل')

@section('content')
<div style="max-width:750px;">

    @if($errors->any())
    <div style="background:rgba(239,68,68,0.1);border:1px solid rgba(239,68,68,0.3);color:#ef4444;padding:14px 20px;border-radius:12px;margin-bottom:20px;">
        <ul style="margin:0;padding-right:18px;">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route('superadmin.shops.update', $shop) }}" method="POST">
        @csrf
        @method('PUT')

        {{-- Shop Info --}}
        <div class="card" style="margin-bottom:20px;">
            <div class="card-header">
                <h3><i class="fas fa-store" style="color:var(--accent);margin-left:8px;"></i> بيانات المحل</h3>
            </div>
            <div class="card-body">
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">

                    <div>
                        <label style="display:block;margin-bottom:6px;font-size:14px;color:var(--text-muted);">اسم المحل (عربي) <span style="color:#ef4444;">*</span></label>
                        <input type="text" name="name" value="{{ old('name', $shop->name) }}"
                            style="width:100%;padding:10px 14px;background:#f8f9fc;border:1px solid var(--border);border-radius:8px;font-family:Tajawal,sans-serif;font-size:14px;" required>
                    </div>

                    <div>
                        <label style="display:block;margin-bottom:6px;font-size:14px;color:var(--text-muted);">اسم المحل (إنجليزي)</label>
                        <input type="text" name="name_en" value="{{ old('name_en', $shop->name_en) }}"
                            style="width:100%;padding:10px 14px;background:#f8f9fc;border:1px solid var(--border);border-radius:8px;font-family:Tajawal,sans-serif;font-size:14px;">
                    </div>

                    <div>
                        <label style="display:block;margin-bottom:6px;font-size:14px;color:var(--text-muted);">رقم الترخيص</label>
                        <input type="text" name="license_number" value="{{ old('license_number', $shop->license_number) }}"
                            style="width:100%;padding:10px 14px;background:#f8f9fc;border:1px solid var(--border);border-radius:8px;font-family:Tajawal,sans-serif;font-size:14px;">
                    </div>

                    <div>
                        <label style="display:block;margin-bottom:6px;font-size:14px;color:var(--text-muted);">الهاتف</label>
                        <input type="text" name="phone" value="{{ old('phone', $shop->phone) }}"
                            style="width:100%;padding:10px 14px;background:#f8f9fc;border:1px solid var(--border);border-radius:8px;font-family:Tajawal,sans-serif;font-size:14px;">
                    </div>

                    <div>
                        <label style="display:block;margin-bottom:6px;font-size:14px;color:var(--text-muted);">المدينة</label>
                        <input type="text" name="city" value="{{ old('city', $shop->city) }}"
                            style="width:100%;padding:10px 14px;background:#f8f9fc;border:1px solid var(--border);border-radius:8px;font-family:Tajawal,sans-serif;font-size:14px;">
                    </div>

                    <div>
                        <label style="display:block;margin-bottom:6px;font-size:14px;color:var(--text-muted);">الحالة</label>
                        <select name="status"
                            style="width:100%;padding:10px 14px;background:#f8f9fc;border:1px solid var(--border);border-radius:8px;font-family:Tajawal,sans-serif;font-size:14px;">
                            <option value="active"    {{ old('status', $shop->status) === 'active'    ? 'selected' : '' }}>نشط</option>
                            <option value="suspended" {{ old('status', $shop->status) === 'suspended' ? 'selected' : '' }}>موقوف</option>
                            <option value="pending"   {{ old('status', $shop->status) === 'pending'   ? 'selected' : '' }}>معلق</option>
                        </select>
                    </div>

                </div>
            </div>
        </div>

        {{-- Admin Account --}}
        <div class="card" style="margin-bottom:24px;">
            <div class="card-header">
                <h3><i class="fas fa-user-tie" style="color:var(--accent);margin-left:8px;"></i> حساب مدير المحل</h3>
            </div>
            <div class="card-body">
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">

                    <div>
                        <label style="display:block;margin-bottom:6px;font-size:14px;color:var(--text-muted);">اسم المدير <span style="color:#ef4444;">*</span></label>
                        <input type="text" name="admin_name" value="{{ old('admin_name', $shop->admin?->name) }}"
                            style="width:100%;padding:10px 14px;background:#f8f9fc;border:1px solid var(--border);border-radius:8px;font-family:Tajawal,sans-serif;font-size:14px;" required>
                    </div>

                    <div>
                        <label style="display:block;margin-bottom:6px;font-size:14px;color:var(--text-muted);">بريد المدير (للدخول) <span style="color:#ef4444;">*</span></label>
                        <input type="email" name="admin_email" value="{{ old('admin_email', $shop->admin?->email) }}"
                            style="width:100%;padding:10px 14px;background:#f8f9fc;border:1px solid var(--border);border-radius:8px;font-family:Tajawal,sans-serif;font-size:14px;" required>
                    </div>

                    <div style="grid-column:1/-1;">
                        <label style="display:block;margin-bottom:6px;font-size:14px;color:var(--text-muted);">كلمة مرور جديدة <span style="color:var(--text-muted);font-size:12px;">(اتركها فارغة إذا لا تريد التغيير)</span></label>
                        <input type="password" name="admin_password"
                            style="width:100%;padding:10px 14px;background:#f8f9fc;border:1px solid var(--border);border-radius:8px;font-family:Tajawal,sans-serif;font-size:14px;">
                    </div>

                </div>
            </div>
        </div>

        <div style="display:flex;gap:12px;">
            <button type="submit" class="btn btn-gold">
                <i class="fas fa-save" style="margin-left:6px;"></i> حفظ التعديلات
            </button>
            <a href="{{ route('superadmin.shops.show', $shop) }}" class="btn" style="background:var(--border);color:var(--text-dark);">
                <i class="fas fa-times"></i> إلغاء
            </a>
        </div>

    </form>
</div>
@endsection
