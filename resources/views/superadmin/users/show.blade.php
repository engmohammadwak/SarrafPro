@extends('layouts.superadmin')
@section('title', $user->name . ' - صراف برو')
@section('page-title', 'تفاصيل المستخدم')

@section('content')
<div class="card" style="max-width:600px;">
    <div class="card-header">
        <h3><i class="fas fa-user" style="color:var(--accent);margin-left:8px;"></i> {{ $user->name }}</h3>
        <div style="display:flex;gap:8px;">
            <a href="{{ route('superadmin.users.edit', $user) }}" class="btn btn-gold btn-sm">
                <i class="fas fa-edit"></i> تعديل
            </a>
            <a href="{{ route('superadmin.users.index') }}" class="btn btn-sm" style="background:var(--border);color:var(--text-primary);">
                <i class="fas fa-arrow-right"></i> رجوع
            </a>
        </div>
    </div>
    <div class="card-body">
        <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:20px;">
            <div>
                <p style="color:var(--text-muted);font-size:13px;margin-bottom:4px;">الاسم</p>
                <p style="font-weight:600;">{{ $user->name }}</p>
            </div>
            <div>
                <p style="color:var(--text-muted);font-size:13px;margin-bottom:4px;">البريد الإلكتروني</p>
                <p style="font-weight:600;">{{ $user->email }}</p>
            </div>
            <div>
                <p style="color:var(--text-muted);font-size:13px;margin-bottom:4px;">الدور</p>
                <span class="badge badge-{{ $user->role === 'admin' ? 'success' : 'warning' }}">
                    {{ $user->role ?? 'user' }}
                </span>
            </div>
            <div>
                <p style="color:var(--text-muted);font-size:13px;margin-bottom:4px;">تاريخ التسجيل</p>
                <p style="font-weight:600;">{{ $user->created_at->format('Y-m-d') }}</p>
            </div>
        </div>
    </div>
</div>
@endsection
