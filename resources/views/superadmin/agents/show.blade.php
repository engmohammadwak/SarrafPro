@extends('layouts.superadmin')
@section('title', $agent->name)
@section('page-title', 'تفاصيل المندوب')
@section('content')
@php
    $colorMap = [
        'bank'     => ['bg'=>'#eff6ff','border'=>'#bfdbfe','text'=>'#1e40af','icon'=>'fa-university'],
        'exchange' => ['bg'=>'#fefce8','border'=>'#fde68a','text'=>'#92400e','icon'=>'fa-coins'],
        'crypto'   => ['bg'=>'#f5f3ff','border'=>'#ddd6fe','text'=>'#5b21b6','icon'=>'fa-bitcoin-sign'],
        'cash'     => ['bg'=>'#f0fdf4','border'=>'#bbf7d0','text'=>'#065f46','icon'=>'fa-money-bill-wave'],
    ];
@endphp

<div style="max-width:860px;display:flex;flex-direction:column;gap:20px">

{{-- بطاقة بيانات الحساب --}}
<div class="card">
    <div class="card-header">
        <h3><i class="fas fa-id-badge" style="color:var(--accent);margin-left:8px"></i> {{ $agent->name }}</h3>
        <div style="display:flex;gap:8px;flex-wrap:wrap">
            @if($agent->status === 'active')
            <form action="{{ route('superadmin.agents.suspend', $agent) }}" method="POST">
                @csrf @method('PATCH')
                <button type="submit" class="btn btn-sm" style="background:#fef3c7;color:#92400e;border:1px solid #fde68a"
                    onclick="return confirm('تعليق حساب {{ $agent->name }}؟')">
                    <i class="fas fa-ban"></i> تعليق
                </button>
            </form>
            @else
            <form action="{{ route('superadmin.agents.activate', $agent) }}" method="POST">
                @csrf @method('PATCH')
                <button type="submit" class="btn btn-sm" style="background:#d1fae5;color:#065f46;border:1px solid #a7f3d0"
                    onclick="return confirm('تفعيل حساب {{ $agent->name }}؟')">
                    <i class="fas fa-check-circle"></i> تفعيل
                </button>
            </form>
            @endif
            <a href="{{ route('superadmin.agents.edit', $agent) }}" class="btn btn-gold btn-sm"><i class="fas fa-edit"></i> تعديل</a>
            <a href="{{ route('superadmin.agents.index') }}" class="btn btn-sm" style="background:#e5e7eb;color:#374151"><i class="fas fa-arrow-right"></i> رجوع</a>
        </div>
    </div>
    <div class="card-body">
        <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(190px,1fr));gap:20px">
            <div>
                <p style="color:var(--text-muted);font-size:12px;margin-bottom:4px">الاسم الكامل</p>
                <p style="font-weight:700;font-size:15px">{{ $agent->name }}</p>
            </div>
            <div>
                <p style="color:var(--text-muted);font-size:12px;margin-bottom:4px">Username</p>
                @if($agent->username)
                    <span style="background:#f3f4f6;padding:4px 12px;border-radius:7px;font-size:14px;font-family:monospace;color:#1a1f3c;font-weight:700">{{ $agent->username }}</span>
                @else
                    <span style="color:#d1d5db">غير محدد</span>
                @endif
            </div>
            <div>
                <p style="color:var(--text-muted);font-size:12px;margin-bottom:4px">البريد</p>
                <p style="font-weight:600;font-size:14px">{{ $agent->email }}</p>
            </div>
            <div>
                <p style="color:var(--text-muted);font-size:12px;margin-bottom:4px">الحالة</p>
                @if($agent->status === 'active')
                    <span class="badge badge-success"><i class="fas fa-circle" style="font-size:7px"></i> نشط</span>
                @else
                    <span class="badge badge-danger"><i class="fas fa-circle" style="font-size:7px"></i> موقوف</span>
                @endif
            </div>
            <div>
                <p style="color:var(--text-muted);font-size:12px;margin-bottom:4px">أضيفه</p>
                @if($agent->creator)
                    <div style="display:flex;align-items:center;gap:7px">
                        <div style="width:28px;height:28px;background:linear-gradient(135deg,var(--accent),var(--accent-light));border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:12px;font-weight:700;color:var(--primary)">{{ mb_substr($agent->creator->name,0,1) }}</div>
                        <div><p style="font-weight:600;font-size:13px">{{ $agent->creator->name }}</p></div>
                    </div>
                @else <span style="color:#d1d5db">—</span> @endif
            </div>
            <div>
                <p style="color:var(--text-muted);font-size:12px;margin-bottom:4px">تاريخ الإضافة</p>
                <p style="font-weight:600;font-size:13px">{{ $agent->created_at->format('Y-m-d H:i') }}</p>
            </div>
            <div>
                <p style="color:var(--text-muted);font-size:12px;margin-bottom:4px">آخر تحديث</p>
                @if($agent->updated_by)
                    <p style="font-weight:600;font-size:13px">{{ $agent->updated_at->format('Y-m-d H:i') }}</p>
                @else <span style="color:#d1d5db">—</span> @endif
            </div>
            <div>
                <p style="color:var(--text-muted);font-size:12px;margin-bottom:4px">عدّله</p>
                @if($agent->updater)
                    <div style="display:flex;align-items:center;gap:7px">
                        <div style="width:28px;height:28px;background:linear-gradient(135deg,#6366f1,#8b5cf6);border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:12px;font-weight:700;color:#fff">{{ mb_substr($agent->updater->name,0,1) }}</div>
                        <div><p style="font-weight:600;font-size:13px">{{ $agent->updater->name }}</p></div>
                    </div>
                @else <span style="color:#d1d5db">—</span> @endif
            </div>
            @if($agent->notes)
            <div style="grid-column:1/-1">
                <p style="color:var(--text-muted);font-size:12px;margin-bottom:4px">ملاحظة</p>
                <p style="background:#f8f9fc;border:1px solid var(--border);border-radius:8px;padding:10px 14px;font-size:13px;white-space:pre-wrap">{{ $agent->notes }}</p>
            </div>
            @endif
            @if($agent->attachment)
            @php $ext=strtolower(pathinfo($agent->attachment,PATHINFO_EXTENSION));$isImage=in_array($ext,['jpg','jpeg','png','gif','webp']);$isPdf=$ext==='pdf'; @endphp
            <div style="grid-column:1/-1">
                <p style="color:var(--text-muted);font-size:12px;margin-bottom:8px">ملف مرفق</p>
                @if($isImage)
                <div style="border:1px solid var(--border);border-radius:10px;overflow:hidden;display:inline-block;max-width:100%;margin-bottom:10px">
                    <img src="{{ Storage::url($agent->attachment) }}" alt="ملف" style="max-width:100%;max-height:340px;display:block;cursor:pointer" onclick="window.open(this.src,'_blank')">
                </div>
                @elseif($isPdf)
                <iframe src="{{ Storage::url($agent->attachment) }}" style="width:100%;height:420px;border:1px solid var(--border);border-radius:10px;display:block;margin-bottom:10px"></iframe>
                @endif
                <div style="display:flex;gap:8px;flex-wrap:wrap">
                    <a href="{{ Storage::url($agent->attachment) }}" target="_blank" class="btn btn-sm" style="background:#f3f4f6;color:#374151"><i class="fas fa-external-link-alt"></i> فتح</a>
                    <form method="POST" action="{{ route('superadmin.agents.attachment.destroy', $agent) }}" onsubmit="return confirm('حذف الملف؟')">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-sm" style="background:#fee2e2;color:#dc2626;border:1px solid #fecaca"><i class="fas fa-trash"></i> حذف</button>
                    </form>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

{{-- الأرصدة الإجمالية عبر جميع المحلات --}}
<div class="card">
    <div class="card-header">
        <h3><i class="fas fa-wallet" style="color:var(--accent);margin-left:8px"></i> إجمالي الأرصدة</h3>
    </div>
    <div class="card-body">
        @if($allBalances->count() > 0)
        <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(140px,1fr));gap:10px">
            @foreach($allBalances as $bal)
            @php $c = $colorMap[$bal->type] ?? $colorMap['cash']; @endphp
            <div style="background:{{ $c['bg'] }};border:1px solid {{ $c['border'] }};border-radius:12px;padding:14px 16px">
                <div style="display:flex;align-items:center;gap:7px;margin-bottom:8px">
                    <i class="fas {{ $c['icon'] }}" style="color:{{ $c['text'] }};font-size:13px"></i>
                    <span style="font-size:11px;color:{{ $c['text'] }};font-weight:700;text-transform:uppercase;letter-spacing:0.5px">{{ $bal->currency }}</span>
                </div>
                <p style="font-size:20px;font-weight:800;color:#111;line-height:1">{{ number_format($bal->total, 2) }}</p>
            </div>
            @endforeach
        </div>
        @else
        <p style="color:var(--text-muted);font-size:13px;padding:8px 0">لا يوجد حسابات مفعلة</p>
        @endif
    </div>
</div>

{{-- جدول المحلات المربوطة --}}
<div class="card">
    <div class="card-header">
        <h3><i class="fas fa-store" style="color:var(--accent);margin-left:8px"></i> المحلات المربوطة ({{ $agentRecords->count() }})</h3>
    </div>
    <div class="card-body" style="padding:0">
        @if($agentRecords->count() > 0)
        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>المحل</th>
                        <th>حالة الربط</th>
                        <th>الحسابات والأرصدة</th>
                        <th>تاريخ الربط</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($agentRecords as $rec)
                @php
                    $recAccounts = $accountsByAgent->get($rec->id, collect());
                    $linkColors = [
                        'approved' => ['bg'=>'#d1fae5','text'=>'#065f46','label'=>'معتمد'],
                        'pending'  => ['bg'=>'#fef3c7','text'=>'#92400e','label'=>'معلق'],
                        'rejected' => ['bg'=>'#fee2e2','text'=>'#dc2626','label'=>'مرفوض'],
                    ];
                    $lc = $linkColors[$rec->link_status] ?? ['bg'=>'#f3f4f6','text'=>'#374151','label'=>$rec->link_status];
                @endphp
                <tr>
                    <td>
                        @if($rec->shop)
                        <div style="display:flex;align-items:center;gap:8px">
                            <div style="width:32px;height:32px;background:linear-gradient(135deg,var(--accent),var(--accent-light));border-radius:8px;display:flex;align-items:center;justify-content:center;font-size:14px;color:var(--primary);font-weight:700;flex-shrink:0">
                                {{ mb_substr($rec->shop->name,0,1) }}
                            </div>
                            <div>
                                <p style="font-weight:600;font-size:14px">{{ $rec->shop->name }}</p>
                                @if($rec->shop->username)<p style="font-size:12px;color:var(--text-muted)">{{ $rec->shop->username }}</p>@endif
                            </div>
                        </div>
                        @else
                        <span style="color:#d1d5db">—</span>
                        @endif
                    </td>
                    <td>
                        <span style="background:{{ $lc['bg'] }};color:{{ $lc['text'] }};padding:3px 10px;border-radius:6px;font-size:12px;font-weight:600">
                            {{ $lc['label'] }}
                        </span>
                    </td>
                    <td>
                        @if($recAccounts->count() > 0)
                        <div style="display:flex;flex-wrap:wrap;gap:6px">
                            @foreach($recAccounts as $acc)
                            @php $c = $colorMap[$acc->type] ?? $colorMap['cash']; @endphp
                            <span style="background:{{ $c['bg'] }};border:1px solid {{ $c['border'] }};color:{{ $c['text'] }};padding:3px 10px;border-radius:6px;font-size:12px;font-weight:700;white-space:nowrap">
                                <i class="fas {{ $c['icon'] }}" style="font-size:10px;margin-left:3px"></i>
                                {{ number_format($acc->balance,2) }} {{ $acc->currency }}
                            </span>
                            @endforeach
                        </div>
                        @else
                        <span style="color:#d1d5db;font-size:13px">—</span>
                        @endif
                    </td>
                    <td style="color:var(--text-muted);font-size:13px">{{ $rec->created_at->format('Y-m-d') }}</td>
                </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div style="text-align:center;padding:40px;color:var(--text-muted)">
            <i class="fas fa-store" style="font-size:28px;opacity:0.25;display:block;margin-bottom:10px"></i>
            <p style="font-size:13px">هذا المندوب غير مربوط بأي محل بعد</p>
        </div>
        @endif
    </div>
</div>

</div>
@endsection
