@extends('layouts.admin')
@section('title', 'المناديب والشركاء')
@section('page-title', 'المناديب والشركاء')
@section('content')

{{-- بطاقات الطلبات المعلّقة --}}
@php $pendingAgents = $agents->filter(fn($a) => $a->link_status === 'pending'); @endphp
@if($pendingAgents->count())
<div style="margin-bottom:20px">
    <div style="display:flex;align-items:center;gap:10px;margin-bottom:12px">
        <span style="background:rgba(251,191,36,0.18);color:#b45309;font-size:13px;font-weight:700;padding:5px 14px;border-radius:20px">
            <i class="fas fa-hourglass-half" style="margin-left:5px"></i>
            طلبات ربط بانتظار الموافقة ({{ $pendingAgents->count() }})
        </span>
    </div>
    <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(320px,1fr));gap:14px">
    @foreach($pendingAgents as $a)
    @php $isPartner = ($a->type ?? 'agent') === 'partner'; @endphp
    <div style="background:var(--card-bg,#fff);border:1.5px solid {{ $isPartner ? 'rgba(99,102,241,0.35)' : 'rgba(251,191,36,0.35)' }};border-radius:14px;padding:18px 20px;box-shadow:0 2px 10px rgba(0,0,0,0.06)">
        <div style="display:flex;align-items:center;gap:12px;margin-bottom:10px">
            <div style="width:42px;height:42px;border-radius:50%;background:{{ $isPartner ? 'linear-gradient(135deg,#6366f1,#8b5cf6)' : 'linear-gradient(135deg,#fbbf24,#f59e0b)' }};display:flex;align-items:center;justify-content:center;color:#fff;font-size:18px;flex-shrink:0">
                <i class="fas {{ $isPartner ? 'fa-handshake' : 'fa-user-tie' }}"></i>
            </div>
            <div>
                <div style="font-weight:700;font-size:15px">{{ $a->name }}</div>
                <div style="font-size:11px;font-weight:600;padding:2px 8px;border-radius:20px;display:inline-block;margin-top:2px;
                    background:{{ $isPartner ? 'rgba(99,102,241,0.12)' : 'rgba(251,191,36,0.15)' }};
                    color:{{ $isPartner ? '#4f46e5' : '#b45309' }}">
                    {{ $isPartner ? 'شراكة' : 'مندوب' }}
                </div>
            </div>
        </div>
        @if($a->user)
        <div style="font-size:12px;color:var(--text-muted);margin-bottom:4px">
            <i class="fas fa-envelope" style="margin-left:4px"></i>{{ $a->user->email }}
        </div>
        @endif
        <div style="font-size:12px;color:var(--text-muted);margin-bottom:14px">
            {{ $isPartner ? 'طلب المحل ربطه كشريك. بانتظار موافقتك.' : 'طلب المحل ربطه كمندوب. بانتظار موافقتك.' }}
        </div>
        <div style="display:flex;gap:8px">
            <form action="{{ route('admin.agents.approve-link', $a) }}" method="POST" style="flex:1">
                @csrf @method('PATCH')
                <button type="submit" class="btn btn-sm" style="width:100%;background:rgba(34,197,94,0.13);color:#15803d;border:1px solid rgba(34,197,94,0.3);border-radius:8px;padding:7px;font-weight:600;font-size:13px">
                    <i class="fas fa-check" style="margin-left:4px"></i> قبول
                </button>
            </form>
            <form action="{{ route('admin.agents.reject-link', $a) }}" method="POST" style="flex:1">
                @csrf @method('PATCH')
                <button type="submit" class="btn btn-sm" style="width:100%;background:rgba(239,68,68,0.1);color:#dc2626;border:1px solid rgba(239,68,68,0.3);border-radius:8px;padding:7px;font-weight:600;font-size:13px"
                    onclick="return confirm('رفض طلب الربط؟')">
                    <i class="fas fa-times" style="margin-left:4px"></i> رفض
                </button>
            </form>
        </div>
    </div>
    @endforeach
    </div>
</div>
@endif

<div class="card">
    <div class="card-header">
        <h3><i class="fas fa-handshake" style="color:var(--accent);margin-left:8px"></i> المناديب والشركاء</h3>
        <a href="{{ route('admin.agents.create') }}" class="btn btn-gold btn-sm"><i class="fas fa-plus"></i> إضافة</a>
    </div>
    <div class="card-body" style="padding:0">
        <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>الاسم</th>
                    <th>النوع</th>
                    <th>الهاتف</th>
                    <th>الدولة</th>
                    <th>الشركة</th>
                    <th>الرصيد</th>
                    <th>الحالة</th>
                    <th>إجراءات</th>
                </tr>
            </thead>
            <tbody>
            @forelse($agents as $a)
            @php
                $userSuspended = $a->user && $a->user->status === 'suspended';
                $isPartner     = ($a->type ?? 'agent') === 'partner';
            @endphp
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td style="font-weight:600">{{ $a->name }}</td>

                {{-- عمود النوع --}}
                <td>
                    @if($isPartner)
                        <span style="display:inline-flex;align-items:center;gap:5px;font-size:11px;font-weight:700;padding:3px 10px;border-radius:20px;background:rgba(99,102,241,0.12);color:#4f46e5;white-space:nowrap">
                            <i class="fas fa-handshake" style="font-size:9px"></i> شراكة
                        </span>
                    @else
                        <span style="display:inline-flex;align-items:center;gap:5px;font-size:11px;font-weight:700;padding:3px 10px;border-radius:20px;background:rgba(251,191,36,0.15);color:#b45309;white-space:nowrap">
                            <i class="fas fa-user-tie" style="font-size:9px"></i> مندوب
                        </span>
                    @endif
                </td>

                <td>{{ $a->phone ?? '-' }}</td>
                <td>{{ $a->country ?? '-' }}</td>
                <td>{{ $a->company ?? '-' }}</td>
                <td style="font-weight:700;color:var(--accent)">{{ number_format($a->balance,4) }}</td>

                <td>
                    <div style="display:flex;flex-direction:column;gap:5px">
                        @if($userSuspended)
                            <span style="display:inline-flex;align-items:center;gap:5px;font-size:11px;font-weight:600;padding:3px 9px;border-radius:20px;background:rgba(239,68,68,0.12);color:#dc2626;width:fit-content;white-space:nowrap">
                                <i class="fas fa-ban" style="font-size:9px"></i> موقوف
                            </span>
                        @elseif($a->is_active)
                            <span style="display:inline-flex;align-items:center;gap:5px;font-size:11px;font-weight:600;padding:3px 9px;border-radius:20px;background:rgba(34,197,94,0.13);color:#15803d;width:fit-content;white-space:nowrap">
                                <i class="fas fa-circle" style="font-size:6px"></i> شغال
                            </span>
                        @else
                            <span style="display:inline-flex;align-items:center;gap:5px;font-size:11px;font-weight:600;padding:3px 9px;border-radius:20px;background:rgba(107,114,128,0.13);color:#6b7280;width:fit-content;white-space:nowrap">
                                <i class="fas fa-circle" style="font-size:6px"></i> معطل
                            </span>
                        @endif

                        @if(! $a->user_id)
                            <span style="display:inline-flex;align-items:center;gap:5px;font-size:11px;font-weight:600;padding:3px 9px;border-radius:20px;background:rgba(251,146,60,0.14);color:#c2410c;width:fit-content;white-space:nowrap">
                                <i class="fas fa-user-slash" style="font-size:9px"></i> بدون حساب
                            </span>
                        @elseif($a->link_status === 'pending')
                            <span style="display:inline-flex;align-items:center;gap:5px;font-size:11px;font-weight:600;padding:3px 9px;border-radius:20px;background:rgba(251,191,36,0.16);color:#b45309;width:fit-content;white-space:nowrap">
                                <i class="fas fa-hourglass-half" style="font-size:9px"></i> بانتظار الموافقة
                            </span>
                        @elseif($a->link_status === 'approved')
                            <span style="display:inline-flex;align-items:center;gap:5px;font-size:11px;font-weight:600;padding:3px 9px;border-radius:20px;background:rgba(59,130,246,0.13);color:#1d4ed8;width:fit-content;white-space:nowrap">
                                <i class="fas fa-link" style="font-size:9px"></i> مربوط
                            </span>
                        @elseif($a->link_status === 'rejected')
                            <span style="display:inline-flex;align-items:center;gap:5px;font-size:11px;font-weight:600;padding:3px 9px;border-radius:20px;background:rgba(239,68,68,0.12);color:#dc2626;width:fit-content;white-space:nowrap">
                                <i class="fas fa-unlink" style="font-size:9px"></i> مرفوض
                            </span>
                        @elseif($a->link_status === 'unlink_pending')
                            <span style="display:inline-flex;align-items:center;gap:5px;font-size:11px;font-weight:600;padding:3px 9px;border-radius:20px;background:rgba(168,85,247,0.13);color:#7e22ce;width:fit-content;white-space:nowrap">
                                <i class="fas fa-unlink" style="font-size:9px"></i> طلب فك ارتباط
                            </span>
                        @endif
                    </div>
                </td>

                <td>
                    <div style="display:flex;gap:6px;flex-wrap:wrap">
                        <a href="{{ route('admin.agents.edit',$a) }}" class="btn btn-sm btn-gold" title="تعديل"><i class="fas fa-edit"></i></a>
                        <form action="{{ route('admin.agents.destroy',$a) }}" method="POST" style="display:inline">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-danger" onclick="return confirm('حذف هذا السجل؟')" title="حذف"><i class="fas fa-trash"></i></button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="9" style="text-align:center;padding:40px;color:var(--text-muted)">لا يوجد سجلات</td></tr>
            @endforelse
            </tbody>
        </table>
        </div>
    </div>
    @if($agents->hasPages())<div style="padding:16px">{{ $agents->links() }}</div>@endif
</div>
@endsection
