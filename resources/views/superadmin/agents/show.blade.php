@extends('layouts.superadmin')
@section('title', $agent->name)
@section('page-title', 'تفاصيل المندوب')
@section('content')
<div class="card" style="max-width:640px">
    <div class="card-header">
        <h3><i class="fas fa-id-badge" style="color:var(--accent);margin-left:8px;"></i> {{ $agent->name }}</h3>
        <div style="display:flex;gap:8px">
            <a href="{{ route('superadmin.agents.edit', $agent) }}" class="btn btn-gold btn-sm"><i class="fas fa-edit"></i> تعديل</a>
            <a href="{{ route('superadmin.agents.index') }}" class="btn btn-sm" style="background:#e5e7eb;color:#374151"><i class="fas fa-arrow-right"></i> رجوع</a>
        </div>
    </div>
    <div class="card-body">
        <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:24px">
            <div>
                <p style="color:var(--text-muted);font-size:12px;margin-bottom:5px">الاسم الكامل</p>
                <p style="font-weight:700;font-size:16px">{{ $agent->name }}</p>
            </div>
            <div>
                <p style="color:var(--text-muted);font-size:12px;margin-bottom:5px">Username</p>
                @if($agent->username)
                    <span style="background:#f3f4f6;padding:5px 14px;border-radius:8px;font-size:15px;font-family:monospace;color:#1a1f3c;font-weight:700">&#64;{{ $agent->username }}</span>
                @else
                    <span style="color:#d1d5db">غير محدد</span>
                @endif
            </div>
            <div>
                <p style="color:var(--text-muted);font-size:12px;margin-bottom:5px">البريد الإلكتروني</p>
                <p style="font-weight:600">{{ $agent->email }}</p>
            </div>
            <div>
                <p style="color:var(--text-muted);font-size:12px;margin-bottom:5px">تاريخ التسجيل</p>
                <p style="font-weight:600">{{ $agent->created_at->format('Y-m-d') }}</p>
            </div>
        </div>
    </div>
</div>
@endsection
