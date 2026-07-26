@extends('layouts.superadmin')
@section('title', $agent->name)
@section('page-title', 'تفاصيل المندوب')
@section('content')
<div class="card" style="max-width:680px">
    <div class="card-header">
        <h3><i class="fas fa-id-badge" style="color:var(--accent);margin-left:8px"></i> {{ $agent->name }}</h3>
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
                <p style="color:var(--text-muted);font-size:12px;margin-bottom:5px">أضيفه</p>
                @if($agent->creator)
                    <div style="display:flex;align-items:center;gap:8px">
                        <div style="width:30px;height:30px;background:linear-gradient(135deg,var(--accent),var(--accent-light));border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:13px;font-weight:700;color:var(--primary)">{{ mb_substr($agent->creator->name,0,1) }}</div>
                        <div>
                            <p style="font-weight:600;font-size:14px">{{ $agent->creator->name }}</p>
                            @if($agent->creator->username)<p style="font-size:12px;color:var(--text-muted)">&#64;{{ $agent->creator->username }}</p>@endif
                        </div>
                    </div>
                @else
                    <span style="color:#d1d5db">غير محدد</span>
                @endif
            </div>
            <div>
                <p style="color:var(--text-muted);font-size:12px;margin-bottom:5px">تاريخ الإضافة</p>
                <p style="font-weight:600">{{ $agent->created_at->format('Y-m-d H:i') }}</p>
            </div>
            @if($agent->notes)
            <div style="grid-column:1/-1">
                <p style="color:var(--text-muted);font-size:12px;margin-bottom:5px">ملاحظة</p>
                <p style="background:#f8f9fc;border:1px solid var(--border);border-radius:8px;padding:10px 14px;font-size:14px;white-space:pre-wrap">{{ $agent->notes }}</p>
            </div>
            @endif
            @if($agent->attachment)
            <div style="grid-column:1/-1">
                <p style="color:var(--text-muted);font-size:12px;margin-bottom:5px">ملف مرفق</p>
                <a href="{{ Storage::url($agent->attachment) }}" target="_blank" class="btn btn-sm btn-primary">
                    <i class="fas fa-file-download"></i> تحميل الملف
                </a>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
