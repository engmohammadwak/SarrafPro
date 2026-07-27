@extends('layouts.superadmin')
@section('title', $shop->name . ' - صراف برو')
@section('page-title', 'تفاصيل المحل')

@section('content')
<div style="display:flex;flex-direction:column;gap:20px;max-width:800px;">

    {{-- Shop Info --}}
    <div class="card">
        <div class="card-header">
            <h3><i class="fas fa-store" style="color:var(--accent);margin-left:8px;"></i> {{ $shop->name }}</h3>
            <div style="display:flex;gap:8px;">
                <a href="{{ route('superadmin.shops.edit', $shop) }}" class="btn btn-gold btn-sm">
                    <i class="fas fa-edit"></i> تعديل
                </a>
                <a href="{{ route('superadmin.shops.index') }}" class="btn btn-sm" style="background:#e5e7eb;color:#374151;">
                    <i class="fas fa-arrow-right"></i> رجوع
                </a>
            </div>
        </div>
        <div class="card-body">
            <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:20px;">
                <div>
                    <p style="color:var(--text-muted);font-size:13px;margin-bottom:4px;">اسم المحل (عربي)</p>
                    <p style="font-weight:600;">{{ $shop->name }}</p>
                </div>
                <div>
                    <p style="color:var(--text-muted);font-size:13px;margin-bottom:4px;">اسم المحل (إنجليزي)</p>
                    <p style="font-weight:600;">{{ $shop->name_en ?? '-' }}</p>
                </div>
                <div>
                    <p style="color:var(--text-muted);font-size:13px;margin-bottom:4px;">رقم الترخيص</p>
                    <p style="font-weight:600;">{{ $shop->license_number ?? '-' }}</p>
                </div>
                <div>
                    <p style="color:var(--text-muted);font-size:13px;margin-bottom:4px;">الهاتف</p>
                    <p style="font-weight:600;">{{ $shop->phone ?? '-' }}</p>
                </div>
                <div>
                    <p style="color:var(--text-muted);font-size:13px;margin-bottom:4px;">المدينة</p>
                    <p style="font-weight:600;">{{ $shop->city ?? '-' }}</p>
                </div>
                <div>
                    <p style="color:var(--text-muted);font-size:13px;margin-bottom:4px;">الحالة</p>
                    @if($shop->status === 'active')
                        <span class="badge badge-success"><i class="fas fa-circle" style="font-size:8px;"></i> نشط</span>
                    @elseif($shop->status === 'suspended')
                        <span class="badge badge-danger"><i class="fas fa-circle" style="font-size:8px;"></i> موقوف</span>
                    @else
                        <span class="badge badge-warning"><i class="fas fa-circle" style="font-size:8px;"></i> معلق</span>
                    @endif
                </div>
                <div>
                    <p style="color:var(--text-muted);font-size:13px;margin-bottom:4px;">الرصيد</p>
                    <p style="font-weight:700;color:var(--accent);font-size:18px;">{{ number_format($shop->balance ?? 0, 4) }} <span style="font-size:13px;color:var(--text-muted);">OMR</span></p>
                </div>

                {{-- أضافه --}}
                <div>
                    <p style="color:var(--text-muted);font-size:13px;margin-bottom:4px;">أضافه</p>
                    @if($shop->creator)
                        <div style="display:flex;align-items:center;gap:8px;">
                            <div style="width:30px;height:30px;background:linear-gradient(135deg,var(--accent),var(--accent-light));border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:13px;font-weight:700;color:var(--primary);">{{ mb_substr($shop->creator->name,0,1) }}</div>
                            <div>
                                <p style="font-weight:600;font-size:14px;">{{ $shop->creator->name }}</p>
                                @if($shop->creator->username)<p style="font-size:12px;color:var(--text-muted);">{{ $shop->creator->username }}</p>@endif
                            </div>
                        </div>
                    @else
                        <span style="color:#d1d5db;">غير محدد</span>
                    @endif
                </div>

                <div>
                    <p style="color:var(--text-muted);font-size:13px;margin-bottom:4px;">تاريخ الإضافة</p>
                    <p style="font-weight:600;">{{ $shop->created_at->format('Y-m-d H:i') }}</p>
                </div>

                {{-- آخر تحديث --}}
                <div>
                    <p style="color:var(--text-muted);font-size:13px;margin-bottom:4px;">آخر تحديث</p>
                    @if($shop->updated_by)
                        <p style="font-weight:600;font-size:14px;">{{ $shop->updated_at->format('Y-m-d H:i') }}</p>
                    @else
                        <span style="color:#d1d5db;">—</span>
                    @endif
                </div>

                <div>
                    <p style="color:var(--text-muted);font-size:13px;margin-bottom:4px;">عدّله</p>
                    @if($shop->updater)
                        <div style="display:flex;align-items:center;gap:8px;">
                            <div style="width:30px;height:30px;background:linear-gradient(135deg,#6366f1,#8b5cf6);border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:13px;font-weight:700;color:#fff;">{{ mb_substr($shop->updater->name,0,1) }}</div>
                            <div>
                                <p style="font-weight:600;font-size:14px;">{{ $shop->updater->name }}</p>
                                @if($shop->updater->username)<p style="font-size:12px;color:var(--text-muted);">{{ $shop->updater->username }}</p>@endif
                            </div>
                        </div>
                    @else
                        <span style="color:#d1d5db;">—</span>
                    @endif
                </div>

                @if($shop->notes)
                <div style="grid-column:1/-1">
                    <p style="color:var(--text-muted);font-size:13px;margin-bottom:4px;">ملاحظة</p>
                    <p style="background:#f8f9fc;border:1px solid var(--border);border-radius:8px;padding:10px 14px;font-size:14px;white-space:pre-wrap">{{ $shop->notes }}</p>
                </div>
                @endif

                @if($shop->attachment)
                @php
                    $ext = strtolower(pathinfo($shop->attachment, PATHINFO_EXTENSION));
                    $isImage = in_array($ext, ['jpg','jpeg','png','gif','webp']);
                @endphp
                <div style="grid-column:1/-1">
                    <p style="color:var(--text-muted);font-size:13px;margin-bottom:10px;">ملف مرفق</p>
                    @if($isImage)
                        <div style="border:1px solid var(--border);border-radius:12px;overflow:hidden;display:inline-block;max-width:100%;">
                            <img src="{{ Storage::url($shop->attachment) }}"
                                 alt="ملف مرفق"
                                 style="max-width:100%;max-height:400px;display:block;cursor:pointer;"
                                 onclick="window.open(this.src,'_blank')">
                        </div>
                        <div style="margin-top:8px;">
                            <a href="{{ Storage::url($shop->attachment) }}" target="_blank" class="btn btn-sm" style="background:#f3f4f6;color:#374151;">
                                <i class="fas fa-expand-alt"></i> فتح بالحجم الكامل
                            </a>
                        </div>
                    @else
                        <a href="{{ Storage::url($shop->attachment) }}" target="_blank" class="btn btn-sm btn-primary">
                            <i class="fas fa-file-download"></i> تحميل الملف
                        </a>
                    @endif
                </div>
                @endif

            </div>
        </div>
    </div>

    {{-- Admin Account Info --}}
    <div class="card">
        <div class="card-header">
            <h3><i class="fas fa-user-tie" style="color:var(--accent);margin-left:8px;"></i> حساب مدير المحل</h3>
        </div>
        <div class="card-body">
            @if($shop->admin)
            <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:20px;">
                <div>
                    <p style="color:var(--text-muted);font-size:13px;margin-bottom:4px;">اسم المدير</p>
                    <p style="font-weight:600;">{{ $shop->admin->name }}</p>
                </div>
                <div>
                    <p style="color:var(--text-muted);font-size:13px;margin-bottom:4px;">البريد الإلكتروني</p>
                    <p style="font-weight:600;">{{ $shop->admin->email }}</p>
                </div>
                <div>
                    <p style="color:var(--text-muted);font-size:13px;margin-bottom:4px;">اسم المستخدم (Username)</p>
                    @if($shop->admin->username)
                        <span style="background:#f3f4f6;padding:3px 12px;border-radius:8px;font-family:monospace;font-size:14px;font-weight:700;">{{ $shop->admin->username }}</span>
                    @else
                        <span style="color:#d1d5db;">—</span>
                    @endif
                </div>
                <div>
                    <p style="color:var(--text-muted);font-size:13px;margin-bottom:4px;">رابط الدخول</p>
                    <a href="{{ url('/admin/login') }}" target="_blank" style="color:var(--accent);font-weight:600;font-size:13px;">
                        <i class="fas fa-external-link-alt" style="margin-left:4px;"></i> /admin/login
                    </a>
                </div>
            </div>
            @else
            <p style="color:var(--text-muted);"><i class="fas fa-exclamation-triangle" style="margin-left:6px;color:var(--warning);"></i> لا يوجد مدير مرتبط بهذا المحل</p>
            @endif
        </div>
    </div>

</div>
@endsection
