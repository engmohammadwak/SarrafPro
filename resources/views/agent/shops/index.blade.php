@extends('layouts.agent')
@section('title', 'محلاتي')
@section('page-title', 'محلاتي')
@section('content')

<div class="card">
    <div class="card-header">
        <h3><i class="fas fa-store" style="color:var(--accent);margin-left:8px"></i> قائمة المحلات</h3>
        <span style="font-size:13px;color:var(--text-muted);">{{ $agents->count() }} محل</span>
    </div>

    @if($agents->isEmpty())
    <div style="padding:60px;text-align:center;color:var(--text-muted)">
        <i class="fas fa-store" style="font-size:48px;opacity:.2;display:block;margin-bottom:16px"></i>
        <p style="font-size:15px"> لا يوجد محل مرتبط حتى الآن</p>
    </div>
    @else
    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>المحل</th>
                    <th>المدينة / الدولة</th>
                    <th>حالة الربط</th>
                    <th>حالة المحل</th>
                    <th>رصيدي</th>
                    <th>الإجراءات</th>
                </tr>
            </thead>
            <tbody>
            @foreach($agents as $i => $a)
            <tr>
                <td style="color:var(--text-muted);font-size:13px">{{ $i + 1 }}</td>

                {{-- اسم المحل --}}
                <td>
                    <div style="display:flex;align-items:center;gap:10px">
                        <div style="width:36px;height:36px;background:linear-gradient(135deg,#c9a84c22,#c9a84c44);border-radius:10px;display:flex;align-items:center;justify-content:center;flex-shrink:0">
                            <i class="fas fa-store" style="color:var(--accent);font-size:14px"></i>
                        </div>
                        <div>
                            <p style="font-weight:700;font-size:14px;margin:0">{{ $a->shop->name ?? '-' }}</p>
                            <span style="font-size:11px;color:var(--text-muted)">ID: {{ $a->shop_id }}</span>
                        </div>
                    </div>
                </td>

                {{-- المدينة --}}
                <td style="color:var(--text-muted);font-size:13px">
                    {{ $a->shop->city ?? '-' }}
                    @if($a->shop?->country)
                        <br><span style="font-size:11px">{{ $a->shop->country }}</span>
                    @endif
                </td>

                {{-- حالة الربط --}}
                <td>
                    @if($a->link_status === 'approved')
                        <span class="badge badge-success"><i class="fas fa-check"></i> مرتبط</span>
                    @elseif($a->link_status === 'pending')
                        <span class="badge badge-warning"><i class="fas fa-clock"></i> بانتظار الموافقة</span>
                    @elseif($a->link_status === 'unlink_pending')
                        <span class="badge" style="background:rgba(245,158,11,0.15);color:#b45309;">
                            <i class="fas fa-hourglass-half"></i> بانتظار تسوية الرصيد
                        </span>
                    @elseif($a->link_status === 'rejected')
                        <span class="badge badge-danger"><i class="fas fa-times"></i> مرفوض</span>
                    @else
                        <span class="badge badge-info">بدون ربط</span>
                    @endif
                </td>

                {{-- حالة التوقيف --}}
                <td>
                    @if(in_array($a->link_status, ['approved', 'unlink_pending']))
                        @if($a->is_active)
                            <span class="badge badge-success"><i class="fas fa-circle" style="font-size:8px"></i> نشط</span>
                        @else
                            <span class="badge badge-danger"><i class="fas fa-ban"></i> موقوف</span>
                        @endif
                    @else
                        <span style="color:var(--text-muted);font-size:12px">—</span>
                    @endif
                </td>

                {{-- رصيدي --}}
                <td>
                    <span style="font-weight:700;font-size:15px;color:{{ ($a->balance ?? 0) != 0 ? '#ef4444' : 'var(--accent)' }}">
                        {{ number_format($a->balance ?? 0, 2) }}
                    </span>
                    @if(($a->balance ?? 0) != 0)
                        <br><span style="font-size:10px;color:#ef4444"><i class="fas fa-exclamation-circle"></i> رصيد معلق</span>
                    @endif
                </td>

                {{-- الإجراءات --}}
                <td>
                    <div style="display:flex;gap:6px;flex-wrap:wrap">

                        {{-- عرض التفاصيل دائماً --}}
                        <a href="{{ route('agent.shops.show', $a->id) }}"
                           style="display:inline-flex;align-items:center;gap:5px;padding:6px 12px;background:#f4f6fb;border:1px solid #e5e7eb;border-radius:8px;font-size:12px;font-weight:600;color:var(--text-dark);text-decoration:none;transition:all .2s"
                           onmouseover="this.style.background='#eef0f7'" onmouseout="this.style.background='#f4f6fb'">
                            <i class="fas fa-eye"></i> تفاصيل
                        </a>

                        @if($a->link_status === 'approved')

                            {{-- توقيف / تفعيل --}}
                            @if($a->is_active)
                            <form method="POST" action="{{ route('agent.shops.block', $a->id) }}" style="display:inline" onsubmit="return confirm('هل تريد توقيف هذا المحل؟')">
                                @csrf @method('PATCH')
                                <button type="submit" style="display:inline-flex;align-items:center;gap:5px;padding:6px 12px;background:#fff3cd;border:1px solid #ffc10744;border-radius:8px;font-size:12px;font-weight:600;color:#92610a;cursor:pointer;transition:all .2s;font-family:Tajawal,sans-serif"
                                    onmouseover="this.style.background='#ffe69c'" onmouseout="this.style.background='#fff3cd'">
                                    <i class="fas fa-ban"></i> توقيف
                                </button>
                            </form>
                            @else
                            <form method="POST" action="{{ route('agent.shops.unblock', $a->id) }}" style="display:inline">
                                @csrf @method('PATCH')
                                <button type="submit" style="display:inline-flex;align-items:center;gap:5px;padding:6px 12px;background:#d1fae5;border:1px solid #10b98144;border-radius:8px;font-size:12px;font-weight:600;color:#065f46;cursor:pointer;transition:all .2s;font-family:Tajawal,sans-serif"
                                    onmouseover="this.style.background='#a7f3d0'" onmouseout="this.style.background='#d1fae5'">
                                    <i class="fas fa-check-circle"></i> تفعيل
                                </button>
                            </form>
                            @endif

                            {{-- فك الربط --}}
                            <form method="POST" action="{{ route('agent.shops.unlink', $a->id) }}" style="display:inline"
                                  onsubmit="return confirmUnlink(event, {{ ($a->balance ?? 0) != 0 ? 'true' : 'false' }}, '{{ number_format($a->balance ?? 0, 2) }}')">
                                @csrf @method('PATCH')
                                <button type="submit" style="display:inline-flex;align-items:center;gap:5px;padding:6px 12px;background:#fee2e2;border:1px solid #ef444444;border-radius:8px;font-size:12px;font-weight:600;color:#991b1b;cursor:pointer;transition:all .2s;font-family:Tajawal,sans-serif"
                                    onmouseover="this.style.background='#fecaca'" onmouseout="this.style.background='#fee2e2'">
                                    <i class="fas fa-unlink"></i> فك الربط
                                </button>
                            </form>

                        @elseif($a->link_status === 'unlink_pending')
                            {{-- في انتظار تسوية الرصيد --}}
                            <span style="font-size:11px;color:#b45309;background:#fef3c7;padding:5px 10px;border-radius:8px;border:1px solid #fcd34d44;display:inline-flex;align-items:center;gap:5px">
                                <i class="fas fa-hourglass-half"></i> بانتظار تسوية الرصيد
                            </span>

                        @elseif($a->link_status === 'pending')
                            <form method="POST" action="{{ route('agent.agents.approve', $a->id) }}" style="display:inline">
                                @csrf @method('PATCH')
                                <button type="submit" style="display:inline-flex;align-items:center;gap:5px;padding:6px 12px;background:#d1fae5;border:1px solid #10b98144;border-radius:8px;font-size:12px;font-weight:600;color:#065f46;cursor:pointer;transition:all .2s;font-family:Tajawal,sans-serif">
                                    <i class="fas fa-check"></i> قبول
                                </button>
                            </form>
                            <form method="POST" action="{{ route('agent.agents.reject', $a->id) }}" style="display:inline">
                                @csrf @method('PATCH')
                                <button type="submit" style="display:inline-flex;align-items:center;gap:5px;padding:6px 12px;background:#fee2e2;border:1px solid #ef444444;border-radius:8px;font-size:12px;font-weight:600;color:#991b1b;cursor:pointer;transition:all .2s;font-family:Tajawal,sans-serif">
                                    <i class="fas fa-times"></i> رفض
                                </button>
                            </form>
                        @endif

                    </div>
                </td>
            </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    @endif
</div>

@push('scripts')
<script>
function confirmUnlink(e, hasBalance, balance) {
    if (hasBalance) {
        e.preventDefault();
        const ok = confirm(
            'تنبيه: لديك رصيد معلق بقيمة ' + balance + '\n\n' +
            'لن يتم فك الارتباط نهائياً حتى يقوم الأدمن بتسوية الرصيد.\n' +
            'هل تريد تقديم طلب فك الارتباط؟'
        );
        if (ok) e.target.submit();
        return false;
    }
    return confirm('هل أنت متأكد من طلب فك الارتباط؟');
}
</script>
@endpush
@endsection
