@extends('layouts.agent')
@section('title', 'معاملات - ' . ($agent->shop->name ?? ''))
@section('page-title', 'معاملات محل ' . ($agent->shop->name ?? ''))
@section('content')

{{-- بطاقة معلومات المحل --}}
<div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px;margin-bottom:20px">
    <div style="display:flex;align-items:center;gap:14px">
        <div style="width:48px;height:48px;background:linear-gradient(135deg,#c9a84c22,#c9a84c55);border-radius:14px;display:flex;align-items:center;justify-content:center">
            <i class="fas fa-store" style="color:var(--accent);font-size:20px"></i>
        </div>
        <div>
            <h2 style="margin:0;font-size:18px;font-weight:700">{{ $agent->shop->name ?? '-' }}</h2>
            <span style="font-size:13px;color:var(--text-muted)">{{ $agent->shop->city ?? '' }}</span>
        </div>
    </div>
    <a href="{{ route('agent.shops.index') }}"
       style="display:inline-flex;align-items:center;gap:6px;padding:8px 16px;background:#f4f6fb;border:1px solid #e5e7eb;border-radius:10px;font-size:13px;font-weight:600;color:var(--text-dark);text-decoration:none">
        <i class="fas fa-arrow-right"></i> رجوع
    </a>
</div>

{{-- بطاقات إحصائية --}}
<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(160px,1fr));gap:14px;margin-bottom:24px">
    <div style="background:#fff;border:1px solid #e5e7eb;border-radius:14px;padding:16px 20px">
        <p style="font-size:12px;color:var(--text-muted);margin:0 0 6px"><i class="fas fa-list" style="margin-left:5px"></i>إجمالي المعاملات</p>
        <p style="font-size:22px;font-weight:800;margin:0;color:var(--text-dark)">{{ $transactions->total() }}</p>
    </div>
    <div style="background:#fff;border:1px solid #e5e7eb;border-radius:14px;padding:16px 20px">
        <p style="font-size:12px;color:var(--text-muted);margin:0 0 6px"><i class="fas fa-coins" style="margin-left:5px;color:var(--accent)"></i>رصيدي</p>
        <p style="font-size:22px;font-weight:800;margin:0;color:{{ ($agent->balance ?? 0) != 0 ? '#ef4444' : '#10b981' }}">{{ number_format($agent->balance ?? 0, 2) }}</p>
    </div>
    <div style="background:#fff;border:1px solid #e5e7eb;border-radius:14px;padding:16px 20px">
        <p style="font-size:12px;color:var(--text-muted);margin:0 0 6px"><i class="fas fa-check-circle" style="margin-left:5px;color:#10b981"></i>مكتملة</p>
        <p style="font-size:22px;font-weight:800;margin:0;color:#10b981">{{ $transactions->where('status','completed')->count() }}</p>
    </div>
    <div style="background:#fff;border:1px solid #e5e7eb;border-radius:14px;padding:16px 20px">
        <p style="font-size:12px;color:var(--text-muted);margin:0 0 6px"><i class="fas fa-clock" style="margin-left:5px;color:#f59e0b"></i>معلقة</p>
        <p style="font-size:22px;font-weight:800;margin:0;color:#f59e0b">{{ $transactions->where('status','pending')->count() }}</p>
    </div>
</div>

{{-- جدول المعاملات --}}
<div class="card">
    <div class="card-header">
        <h3><i class="fas fa-exchange-alt" style="color:var(--accent);margin-left:8px"></i> سجل المعاملات</h3>
        <span style="font-size:13px;color:var(--text-muted)">{{ $transactions->total() }} معاملة</span>
    </div>

    @if($transactions->isEmpty())
    <div style="padding:60px;text-align:center;color:var(--text-muted)">
        <i class="fas fa-inbox" style="font-size:48px;opacity:.2;display:block;margin-bottom:16px"></i>
        <p style="font-size:15px">لا توجد معاملات بعد</p>
    </div>
    @else
    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>المرجع</th>
                    <th>النوع</th>
                    <th>من / إلى</th>
                    <th>المبلغ</th>
                    <th>سعر الصرف</th>
                    <th>النتيجة</th>
                    <th>العمولة</th>
                    <th>العميل</th>
                    <th>الحالة</th>
                    <th>التاريخ</th>
                </tr>
            </thead>
            <tbody>
            @foreach($transactions as $i => $t)
            <tr>
                <td style="color:var(--text-muted);font-size:12px">{{ $transactions->firstItem() + $i }}</td>

                {{-- المرجع --}}
                <td>
                    <span style="font-family:monospace;font-size:12px;background:#f4f6fb;padding:3px 8px;border-radius:6px;border:1px solid #e5e7eb">
                        {{ $t->reference ?? '—' }}
                    </span>
                </td>

                {{-- النوع --}}
                <td>
                    @php
                        $typeMap = [
                            'exchange'  => ['صرف عملة','#3b82f6','fa-exchange-alt'],
                            'send'      => ['حوالة صادرة','#f59e0b','fa-paper-plane'],
                            'receive'   => ['حوالة واردة','#10b981','fa-inbox'],
                            'deposit'   => ['إيداع','#8b5cf6','fa-arrow-down'],
                            'withdraw'  => ['سحب','#ef4444','fa-arrow-up'],
                        ];
                        $tm = $typeMap[$t->type] ?? [$t->type,'#6b7280','fa-circle'];
                    @endphp
                    <span style="display:inline-flex;align-items:center;gap:5px;font-size:12px;font-weight:700;color:{{ $tm[1] }}">
                        <i class="fas {{ $tm[2] }}" style="font-size:11px"></i> {{ $tm[0] }}
                    </span>
                </td>

                {{-- من / إلى --}}
                <td style="font-size:13px;font-weight:700">
                    <span style="color:#64748b">{{ $t->currency_from }}</span>
                    <i class="fas fa-arrow-left" style="font-size:10px;color:#94a3b8;margin:0 4px"></i>
                    <span style="color:var(--accent)">{{ $t->currency_to }}</span>
                </td>

                {{-- المبلغ --}}
                <td style="font-weight:700;font-size:14px">{{ number_format($t->amount, 2) }}</td>

                {{-- سعر الصرف --}}
                <td style="font-size:12px;color:var(--text-muted);font-family:monospace">{{ $t->rate }}</td>

                {{-- النتيجة --}}
                <td style="font-weight:800;font-size:14px;color:var(--accent)">{{ number_format($t->amount_result, 2) }}</td>

                {{-- العمولة --}}
                <td style="font-size:12px;color:{{ $t->fee > 0 ? '#f59e0b' : 'var(--text-muted)' }};font-weight:{{ $t->fee > 0 ? '700':'400' }}">
                    {{ $t->fee > 0 ? number_format($t->fee,2) : '—' }}
                </td>

                {{-- العميل --}}
                <td style="font-size:12px;color:var(--text-muted)">
                    {{ $t->customer->name ?? '—' }}
                </td>

                {{-- الحالة --}}
                <td>
                    @if($t->status === 'completed')
                        <span class="badge badge-success" style="font-size:11px"><i class="fas fa-check"></i> مكتملة</span>
                    @elseif($t->status === 'pending')
                        <span class="badge badge-warning" style="font-size:11px"><i class="fas fa-clock"></i> معلقة</span>
                    @elseif($t->status === 'cancelled')
                        <span class="badge badge-danger" style="font-size:11px"><i class="fas fa-times"></i> ملغاة</span>
                    @else
                        <span class="badge badge-info" style="font-size:11px">{{ $t->status }}</span>
                    @endif
                </td>

                {{-- التاريخ --}}
                <td style="font-size:11px;color:var(--text-muted);white-space:nowrap">
                    {{ $t->created_at->format('Y/m/d') }}<br>
                    <span style="font-size:10px">{{ $t->created_at->format('H:i') }}</span>
                </td>
            </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    @if($transactions->hasPages())
    <div style="padding:16px">{{ $transactions->links() }}</div>
    @endif
    @endif
</div>
@endsection
