@extends('layouts.superadmin')
@section('title', $user->name)
@section('page-title', 'تفاصيل المستخدم')
@section('content')
<div class="card" style="max-width:640px">
    <div class="card-header">
        <h3><i class="fas fa-user-shield" style="color:var(--accent);margin-left:8px"></i> {{ $user->name }}</h3>
        <div style="display:flex;gap:8px">
            <a href="{{ route('superadmin.users.edit', $user) }}" class="btn btn-gold btn-sm"><i class="fas fa-edit"></i> تعديل</a>
            <a href="{{ route('superadmin.users.index') }}" class="btn btn-sm" style="background:#e5e7eb;color:#374151"><i class="fas fa-arrow-right"></i> رجوع</a>
        </div>
    </div>
    <div class="card-body">
        <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:24px">
            <div>
                <p style="color:var(--text-muted);font-size:12px;margin-bottom:5px">الاسم الكامل</p>
                <p style="font-weight:700;font-size:16px">{{ $user->name }}</p>
            </div>
            <div>
                <p style="color:var(--text-muted);font-size:12px;margin-bottom:5px">Username</p>
                @if($user->username)
                    <span style="background:#f3f4f6;padding:5px 14px;border-radius:8px;font-size:15px;font-family:monospace;color:#1a1f3c;font-weight:700">&#64;{{ $user->username }}</span>
                @else
                    <span style="color:#d1d5db">غير محدد</span>
                @endif
            </div>
            <div>
                <p style="color:var(--text-muted);font-size:12px;margin-bottom:5px">أضيفه</p>
                @if($user->creator)
                    <div style="display:flex;align-items:center;gap:8px">
                        <div style="width:30px;height:30px;background:linear-gradient(135deg,var(--accent),var(--accent-light));border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:13px;font-weight:700;color:var(--primary)">{{ mb_substr($user->creator->name,0,1) }}</div>
                        <div>
                            <p style="font-weight:600;font-size:14px">{{ $user->creator->name }}</p>
                            @if($user->creator->username)<p style="font-size:12px;color:var(--text-muted)">&#64;{{ $user->creator->username }}</p>@endif
                        </div>
                    </div>
                @else
                    <span style="color:#d1d5db">غير محدد</span>
                @endif
            </div>
            <div>
                <p style="color:var(--text-muted);font-size:12px;margin-bottom:5px">تاريخ الإضافة</p>
                <p style="font-weight:600">{{ $user->created_at->format('Y-m-d H:i') }}</p>
            </div>
        </div>
    </div>
</div>
@endsection
