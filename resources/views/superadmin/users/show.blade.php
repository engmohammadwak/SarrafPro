@extends('layouts.superadmin')
@section('title', $user->name . ' - صراف برو')
@section('page-title', 'تفاصيل المستخدم')

@section('content')
@php
    $roleColors = ['super_admin'=>'gold','shop_admin'=>'success','agent'=>'info','staff'=>'warning'];
    $roleLabels = ['super_admin'=>'سوبر ادمن','shop_admin'=>'مدير محل','agent'=>'مندوب','staff'=>'موظف'];
@endphp
<div class="card" style="max-width:640px;">
    <div class="card-header">
        <h3><i class="fas fa-user" style="color:var(--accent);margin-left:8px;"></i> {{ $user->name }}</h3>
        <div style="display:flex;gap:8px;">
            <a href="{{ route('superadmin.users.edit', $user) }}" class="btn btn-gold btn-sm">
                <i class="fas fa-edit"></i> تعديل
            </a>
            <a href="{{ route('superadmin.users.index') }}" class="btn btn-sm" style="background:var(--border);color:#374151;">
                <i class="fas fa-arrow-right"></i> رجوع
            </a>
        </div>
    </div>
    <div class="card-body">
        <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:24px;">
            <div>
                <p style="color:var(--text-muted);font-size:12px;margin-bottom:5px;الاسم الكامل">الاسم الكامل</p>
                <p style="font-weight:700;font-size:16px;">{{ $user->name }}</p>
            </div>
            <div>
                <p style="color:var(--text-muted);font-size:12px;margin-bottom:5px;">Username</p>
                @if($user->username)
                    <span style="background:#f3f4f6;padding:5px 14px;border-radius:8px;font-size:15px;font-family:monospace;color:#1a1f3c;font-weight:700;">@{{ $user->username }}</span>
                @else
                    <span style="color:#d1d5db;font-size:14px;">غير محدد</span>
                @endif
            </div>
            <div>
                <p style="color:var(--text-muted);font-size:12px;margin-bottom:5px;">البريد الإلكتروني</p>
                <p style="font-weight:600;">{{ $user->email }}</p>
            </div>
            <div>
                <p style="color:var(--text-muted);font-size:12px;margin-bottom:5px;">الدور</p>
                <span class="badge badge-{{ $roleColors[$user->role] ?? 'warning' }}">
                    {{ $roleLabels[$user->role] ?? $user->role }}
                </span>
            </div>
            <div>
                <p style="color:var(--text-muted);font-size:12px;margin-bottom:5px;">تاريخ التسجيل</p>
                <p style="font-weight:600;">{{ $user->created_at->format('Y-m-d') }}</p>
            </div>
        </div>
    </div>
</div>
@endsection
