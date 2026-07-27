@extends('layouts.superadmin')
@section('title', $agent->name)
@section('page-title', 'تفاصيل المندوب')
@section('content')
<div class="card" style="max-width:760px">
    <div class="card-header">
        <h3><i class="fas fa-id-badge" style="color:var(--accent);margin-left:8px"></i> {{ $agent->name }}</h3>
        <div style="display:flex;gap:8px;flex-wrap:wrap">
            @if($agent->status === 'active')
            <form action="{{ route('superadmin.agents.suspend', $agent) }}" method="POST">
                @csrf @method('PATCH')
                <button type="submit" class="btn btn-sm"
                    style="background:#fef3c7;color:#92400e;border:1px solid #fde68a"
                    onclick="return confirm('تعليق حساب {{ $agent->name }}؟')">
                    <i class="fas fa-ban"></i> تعليق الحساب
                </button>
            </form>
            @else
            <form action="{{ route('superadmin.agents.activate', $agent) }}" method="POST">
                @csrf @method('PATCH')
                <button type="submit" class="btn btn-sm"
                    style="background:#d1fae5;color:#065f46;border:1px solid #a7f3d0"
                    onclick="return confirm('تفعيل حساب {{ $agent->name }}؟')">
                    <i class="fas fa-check-circle"></i> تفعيل الحساب
                </button>
            </form>
            @endif
            <a href="{{ route('superadmin.agents.edit', $agent) }}" class="btn btn-gold btn-sm"><i class="fas fa-edit"></i> تعديل</a>
            <a href="{{ route('superadmin.agents.index') }}" class="btn btn-sm" style="background:#e5e7eb;color:#374151"><i class="fas fa-arrow-right"></i> رجوع</a>
        </div>
    </div>
    <div class="card-body">

        {{-- بلوك الأرصدة حسب العملة --}}
        @if($balances->count() > 0)
        <div style="margin-bottom:28px">
            <p style="font-size:12px;color:var(--text-muted);margin-bottom:12px;font-weight:600;لترفونت">&#x1f4b0; الأرصدة المفعلة</p>
            <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(160px,1fr));gap:12px">
                @foreach($balances as $acc)
                @php
                    $colors = [
                        'bank'     => ['bg'=>'#eff6ff','border'=>'#bfdbfe','text'=>'#1e40af','icon'=>'fa-university'],
                        'exchange' => ['bg'=>'#fefce8','border'=>'#fde68a','text'=>'#92400e','icon'=>'fa-coins'],
                        'crypto'   => ['bg'=>'#f5f3ff','border'=>'#ddd6fe','text'=>'#5b21b6','icon'=>'fa-bitcoin-sign'],
                        'cash'     => ['bg'=>'#f0fdf4','border'=>'#bbf7d0','text'=>'#065f46','icon'=>'fa-money-bill-wave'],
                    ];
                    $c = $colors[$acc->type] ?? $colors['cash'];
                @endphp
                <div style="background:{{ $c['bg'] }};border:1px solid {{ $c['border'] }};border-radius:12px;padding:14px 16px">
                    <div style="display:flex;align-items:center;gap:8px;margin-bottom:8px">
                        <i class="fas {{ $c['icon'] }}" style="color:{{ $c['text'] }};font-size:14px"></i>
                        <span style="font-size:11px;color:{{ $c['text'] }};font-weight:600;text-transform:uppercase;letter-spacing:0.5px">{{ $acc->currency }}</span>
                    </div>
                    <p style="font-size:22px;font-weight:800;color:#111;line-height:1;margin-bottom:4px">{{ number_format($acc->balance, 2) }}</p>
                    <p style="font-size:11px;color:var(--text-muted);white-space:nowrap;overflow:hidden;text-overflow:ellipsis">{{ $acc->name }}</p>
                </div>
                @endforeach
            </div>
        </div>
        @elseif($agentRecord)
        <div style="background:#f8f9fc;border:1px dashed var(--border);border-radius:12px;padding:16px 20px;margin-bottom:28px;display:flex;align-items:center;gap:12px;color:var(--text-muted)">
            <i class="fas fa-wallet" style="font-size:18px;opacity:0.4"></i>
            <p style="font-size:13px">لا يوجد حسابات مفعلة لهذا المندوب</p>
        </div>
        @else
        <div style="background:#fff7ed;border:1px dashed #fed7aa;border-radius:12px;padding:16px 20px;margin-bottom:28px;display:flex;align-items:center;gap:12px;color:#92400e">
            <i class="fas fa-link-slash" style="font-size:18px;opacity:0.5"></i>
            <p style="font-size:13px">هذا المستخدم غير مربوط بسجل مندوب بعد</p>
        </div>
        @endif

        {{-- بيانات الحساب --}}
        <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:24px">

            <div>
                <p style="color:var(--text-muted);font-size:12px;margin-bottom:5px">الاسم الكامل</p>
                <p style="font-weight:700;font-size:16px">{{ $agent->name }}</p>
            </div>

            <div>
                <p style="color:var(--text-muted);font-size:12px;margin-bottom:5px">Username</p>
                @if($agent->username)
                    <span style="background:#f3f4f6;padding:5px 14px;border-radius:8px;font-size:15px;font-family:monospace;color:#1a1f3c;font-weight:700">{{ $agent->username }}</span>
                @else
                    <span style="color:#d1d5db">غير محدد</span>
                @endif
            </div>

            <div>
                <p style="color:var(--text-muted);font-size:12px;margin-bottom:5px">البريد الإلكتروني</p>
                <p style="font-weight:600">{{ $agent->email }}</p>
            </div>

            <div>
                <p style="color:var(--text-muted);font-size:12px;margin-bottom:5px">الحالة</p>
                @if($agent->status === 'active')
                    <span class="badge badge-success"><i class="fas fa-circle" style="font-size:7px"></i> نشط</span>
                @else
                    <span class="badge badge-danger"><i class="fas fa-circle" style="font-size:7px"></i> موقوف</span>
                @endif
            </div>

            <div>
                <p style="color:var(--text-muted);font-size:12px;margin-bottom:5px">أضيفه</p>
                @if($agent->creator)
                    <div style="display:flex;align-items:center;gap:8px">
                        <div style="width:30px;height:30px;background:linear-gradient(135deg,var(--accent),var(--accent-light));border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:13px;font-weight:700;color:var(--primary)">{{ mb_substr($agent->creator->name,0,1) }}</div>
                        <div>
                            <p style="font-weight:600;font-size:14px">{{ $agent->creator->name }}</p>
                            @if($agent->creator->username)<p style="font-size:12px;color:var(--text-muted)">{{ $agent->creator->username }}</p>@endif
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

            <div>
                <p style="color:var(--text-muted);font-size:12px;margin-bottom:5px">آخر تحديث</p>
                @if($agent->updated_by)
                    <p style="font-weight:600;font-size:14px">{{ $agent->updated_at->format('Y-m-d H:i') }}</p>
                @else
                    <span style="color:#d1d5db">—</span>
                @endif
            </div>

            <div>
                <p style="color:var(--text-muted);font-size:12px;margin-bottom:5px">عدّله</p>
                @if($agent->updater)
                    <div style="display:flex;align-items:center;gap:8px">
                        <div style="width:30px;height:30px;background:linear-gradient(135deg,#6366f1,#8b5cf6);border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:13px;font-weight:700;color:#fff">{{ mb_substr($agent->updater->name,0,1) }}</div>
                        <div>
                            <p style="font-weight:600;font-size:14px">{{ $agent->updater->name }}</p>
                            @if($agent->updater->username)<p style="font-size:12px;color:var(--text-muted)">{{ $agent->updater->username }}</p>@endif
                        </div>
                    </div>
                @else
                    <span style="color:#d1d5db">—</span>
                @endif
            </div>

            @if($agent->notes)
            <div style="grid-column:1/-1">
                <p style="color:var(--text-muted);font-size:12px;margin-bottom:5px">ملاحظة</p>
                <p style="background:#f8f9fc;border:1px solid var(--border);border-radius:8px;padding:10px 14px;font-size:14px;white-space:pre-wrap">{{ $agent->notes }}</p>
            </div>
            @endif

            @if($agent->attachment)
            @php
                $ext     = strtolower(pathinfo($agent->attachment, PATHINFO_EXTENSION));
                $isImage = in_array($ext, ['jpg','jpeg','png','gif','webp']);
                $isPdf   = $ext === 'pdf';
            @endphp
            <div style="grid-column:1/-1">
                <p style="color:var(--text-muted);font-size:12px;margin-bottom:10px">ملف مرفق</p>
                @if($isImage)
                <div style="border:1px solid var(--border);border-radius:12px;overflow:hidden;display:inline-block;max-width:100%;margin-bottom:12px">
                    <img src="{{ Storage::url($agent->attachment) }}" alt="ملف مرفق"
                         style="max-width:100%;max-height:400px;display:block;cursor:pointer"
                         onclick="window.open(this.src,'_blank')">
                </div>
                <div style="display:flex;gap:8px;flex-wrap:wrap">
                    <a href="{{ Storage::url($agent->attachment) }}" target="_blank" class="btn btn-sm" style="background:#f3f4f6;color:#374151">
                        <i class="fas fa-expand-alt"></i> فتح بالحجم الكامل
                    </a>
                    <form method="POST" action="{{ route('superadmin.agents.attachment.destroy', $agent) }}" onsubmit="return confirm('تأكيد حذف الصورة؟')">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-sm" style="background:#fee2e2;color:#dc2626;border:1px solid #fecaca">
                            <i class="fas fa-trash"></i> حذف الصورة
                        </button>
                    </form>
                </div>
                @elseif($isPdf)
                <div style="border:1px solid var(--border);border-radius:12px;overflow:hidden;margin-bottom:12px">
                    <iframe src="{{ Storage::url($agent->attachment) }}" style="width:100%;height:480px;border:none;display:block" title="PDF"></iframe>
                </div>
                <div style="display:flex;gap:8px;flex-wrap:wrap">
                    <a href="{{ Storage::url($agent->attachment) }}" target="_blank" class="btn btn-sm btn-primary"><i class="fas fa-file-pdf"></i> فتح الملف</a>
                    <form method="POST" action="{{ route('superadmin.agents.attachment.destroy', $agent) }}" onsubmit="return confirm('تأكيد حذف الملف؟')">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-sm" style="background:#fee2e2;color:#dc2626;border:1px solid #fecaca"><i class="fas fa-trash"></i> حذف الملف</button>
                    </form>
                </div>
                @else
                <div style="display:flex;gap:8px;flex-wrap:wrap">
                    <a href="{{ Storage::url($agent->attachment) }}" target="_blank" class="btn btn-sm btn-primary"><i class="fas fa-file-download"></i> تحميل الملف</a>
                    <form method="POST" action="{{ route('superadmin.agents.attachment.destroy', $agent) }}" onsubmit="return confirm('تأكيد حذف الملف؟')">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-sm" style="background:#fee2e2;color:#dc2626;border:1px solid #fecaca"><i class="fas fa-trash"></i> حذف الملف</button>
                    </form>
                </div>
                @endif
            </div>
            @endif

        </div>
    </div>
</div>
@endsection
