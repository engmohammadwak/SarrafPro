@extends('layouts.superadmin')
@section('title', 'تعديل ' . $user->name . ' - صراف برو')
@section('page-title', 'تعديل المستخدم')

@section('content')
<div class="card" style="max-width:600px;">
    <div class="card-header">
        <h3><i class="fas fa-user-edit" style="color:var(--accent);margin-left:8px;"></i> تعديل: {{ $user->name }}</h3>
        <a href="{{ route('superadmin.users.index') }}" class="btn btn-sm" style="background:var(--border);color:var(--text-primary);">
            <i class="fas fa-arrow-right"></i> رجوع
        </a>
    </div>
    <div class="card-body">
        @if($errors->any())
        <div style="background:rgba(239,68,68,0.1);border:1px solid rgba(239,68,68,0.3);color:#ef4444;padding:12px 16px;border-radius:8px;margin-bottom:20px;">
            <ul style="margin:0;padding-right:16px;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form action="{{ route('superadmin.users.update', $user) }}" method="POST">
            @csrf
            @method('PUT')

            <div style="margin-bottom:16px;">
                <label style="display:block;margin-bottom:6px;font-size:14px;color:var(--text-secondary);">الاسم</label>
                <input type="text" name="name" value="{{ old('name', $user->name) }}"
                    style="width:100%;padding:10px 14px;background:var(--input-bg,var(--card-bg));border:1px solid var(--border);border-radius:8px;color:var(--text-primary);font-size:14px;"
                    required>
            </div>

            <div style="margin-bottom:16px;">
                <label style="display:block;margin-bottom:6px;font-size:14px;color:var(--text-secondary);">البريد الإلكتروني</label>
                <input type="email" name="email" value="{{ old('email', $user->email) }}"
                    style="width:100%;padding:10px 14px;background:var(--input-bg,var(--card-bg));border:1px solid var(--border);border-radius:8px;color:var(--text-primary);font-size:14px;"
                    required>
            </div>

            <div style="margin-bottom:16px;">
                <label style="display:block;margin-bottom:6px;font-size:14px;color:var(--text-secondary);">كلمة مرور جديدة <span style="color:var(--text-muted);font-size:12px;">(اتركها فارغة إذا لا تريد التغيير)</span></label>
                <input type="password" name="password"
                    style="width:100%;padding:10px 14px;background:var(--input-bg,var(--card-bg));border:1px solid var(--border);border-radius:8px;color:var(--text-primary);font-size:14px;">
            </div>

            <div style="margin-bottom:24px;">
                <label style="display:block;margin-bottom:6px;font-size:14px;color:var(--text-secondary);">تأكيد كلمة المرور</label>
                <input type="password" name="password_confirmation"
                    style="width:100%;padding:10px 14px;background:var(--input-bg,var(--card-bg));border:1px solid var(--border);border-radius:8px;color:var(--text-primary);font-size:14px;">
            </div>

            <button type="submit" class="btn btn-gold">
                <i class="fas fa-save" style="margin-left:6px;"></i> حفظ التعديلات
            </button>
        </form>
    </div>
</div>
@endsection
