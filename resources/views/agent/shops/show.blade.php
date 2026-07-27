@extends('layouts.agent')
@section('title', 'تفاصيل المحل')
@section('page-title', 'تفاصيل المحل')
@section('content')

@php $shop = $agent->shop; @endphp

<div style="margin-bottom:20px">
    <a href="{{ route('agent.shops.index') }}" style="display:inline-flex;align-items:center;gap:8px;color:var(--text-muted);text-decoration:none;font-size:14px;font-weight:600;">
        <i class="fas fa-arrow-right"></i> العودة للمحلات
    </a>
</div>

<div style="display:grid;grid-template-columns:1fr 1fr;gap:20px">

    {{-- بطاقة معلومات المحل --}}
    <div class="card" style="grid-column:1/-1">
        <div class="card-header">
            <h3><i class="fas fa-store" style="color:var(--accent);margin-left:8px"></i> معلومات المحل</h3>
            <div style="display:flex;gap:8px">
                @if($agent->link_status === 'approved')
                    @if($agent->is_active)
                        <span class="badge badge-success"><i class="fas fa-circle" style="font-size:8px"></i> نشط</span>
                    @else
                        <span class="badge badge-danger"><i class="fas fa-ban"></i> موقوف</span>
                    @endif
                @endif
                @if($agent->link_status === 'approved')
                    <span class="badge badge-success"><i class="fas fa-link"></i> مرتبط</span>
                @elseif($agent->link_status === 'pending')
                    <span class="badge badge-warning"><i class="fas fa-clock"></i> بانتظار</span>
                @endif
            </div>
        </div>
        <div class="card-body">
            <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:24px">
                <div>
                    <p style="font-size:11px;color:var(--text-muted);text-transform:uppercase;font-weight:700;margin-bottom:6px">اسم المحل</p>
                    <p style="font-size:16px;font-weight:700">{{ $shop->name ?? '-' }}</p>
                </div>
                <div>
                    <p style="font-size:11px;color:var(--text-muted);text-transform:uppercase;font-weight:700;margin-bottom:6px">المدينة</p>
                    <p style="font-size:15px">{{ $shop->city ?? '-' }}</p>
                </div>
                <div>
                    <p style="font-size:11px;color:var(--text-muted);text-transform:uppercase;font-weight:700;margin-bottom:6px">الدولة</p>
                    <p style="font-size:15px">{{ $shop->country ?? '-' }}</p>
                </div>
                <div>
                    <p style="font-size:11px;color:var(--text-muted);text-transform:uppercase;font-weight:700;margin-bottom:6px">البريد الإلكتروني</p>
                    <p style="font-size:15px">{{ $shop->email ?? '-' }}</p>
                </div>
                <div>
                    <p style="font-size:11px;color:var(--text-muted);text-transform:uppercase;font-weight:700;margin-bottom:6px">الهاتف</p>
                    <p style="font-size:15px">{{ $shop->phone ?? '-' }}</p>
                </div>
                <div>
                    <p style="font-size:11px;color:var(--text-muted);text-transform:uppercase;font-weight:700;margin-bottom:6px">تاريخ الانضمام</p>
                    <p style="font-size:15px">{{ $agent->created_at->format('Y/m/d') }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- بطاقة رصيدي --}}
    <div class="card">
        <div class="card-header">
            <h3><i class="fas fa-wallet" style="color:var(--accent);margin-left:8px"></i> رصيدي في هذا المحل</h3>
        </div>
        <div class="card-body" style="text-align:center;padding:40px">
            <div style="font-size:48px;font-weight:800;color:var(--accent);line-height:1">
                {{ number_format($agent->balance ?? 0, 2) }}
            </div>
            <p style="color:var(--text-muted);margin-top:10px;font-size:14px">الرصيد الحالي</p>
        </div>
    </div>

    {{-- بطاقة الإجراءات --}}
    <div class="card">
        <div class="card-header">
            <h3><i class="fas fa-cog" style="color:var(--accent);margin-left:8px"></i> الإجراءات</h3>
        </div>
        <div class="card-body" style="display:flex;flex-direction:column;gap:12px">

            @if($agent->link_status === 'approved')
                {{-- توقيف / تفعيل --}}
                @if($agent->is_active)
                <form method="POST" action="{{ route('agent.shops.block', $agent->id) }}" onsubmit="return confirm('هل تريد توقيف هذا المحل؟')">
                    @csrf @method('PATCH')
                    <button type="submit" style="width:100%;padding:13px;background:#fff3cd;border:1px solid #ffc10744;border-radius:10px;font-size:14px;font-weight:700;color:#92610a;cursor:pointer;font-family:Tajawal,sans-serif;display:flex;align-items:center;justify-content:center;gap:10px;transition:all .2s"
                        onmouseover="this.style.background='#ffe69c'" onmouseout="this.style.background='#fff3cd'">
                        <i class="fas fa-ban"></i> توقيف المحل
                    </button>
                </form>
                @else
                <form method="POST" action="{{ route('agent.shops.unblock', $agent->id) }}">
                    @csrf @method('PATCH')
                    <button type="submit" style="width:100%;padding:13px;background:#d1fae5;border:1px solid #10b98144;border-radius:10px;font-size:14px;font-weight:700;color:#065f46;cursor:pointer;font-family:Tajawal,sans-serif;display:flex;align-items:center;justify-content:center;gap:10px;transition:all .2s"
                        onmouseover="this.style.background='#a7f3d0'" onmouseout="this.style.background='#d1fae5'">
                        <i class="fas fa-check-circle"></i> تفعيل المحل
                    </button>
                </form>
                @endif

                {{-- فك الربط --}}
                <form method="POST" action="{{ route('agent.shops.unlink', $agent->id) }}" onsubmit="return confirm('هل أنت متأكد من طلب فك الربط؟')">
                    @csrf @method('PATCH')
                    <button type="submit" style="width:100%;padding:13px;background:#fee2e2;border:1px solid #ef444444;border-radius:10px;font-size:14px;font-weight:700;color:#991b1b;cursor:pointer;font-family:Tajawal,sans-serif;display:flex;align-items:center;justify-content:center;gap:10px;transition:all .2s"
                        onmouseover="this.style.background='#fecaca'" onmouseout="this.style.background='#fee2e2'">
                        <i class="fas fa-unlink"></i> طلب فك الربط
                    </button>
                </form>

            @elseif($agent->link_status === 'pending')
                <form method="POST" action="{{ route('agent.agents.approve', $agent->id) }}">
                    @csrf @method('PATCH')
                    <button type="submit" style="width:100%;padding:13px;background:#d1fae5;border:1px solid #10b98144;border-radius:10px;font-size:14px;font-weight:700;color:#065f46;cursor:pointer;font-family:Tajawal,sans-serif;display:flex;align-items:center;justify-content:center;gap:10px">
                        <i class="fas fa-check"></i> قبول طلب الانضمام
                    </button>
                </form>
                <form method="POST" action="{{ route('agent.agents.reject', $agent->id) }}">
                    @csrf @method('PATCH')
                    <button type="submit" style="width:100%;padding:13px;background:#fee2e2;border:1px solid #ef444444;border-radius:10px;font-size:14px;font-weight:700;color:#991b1b;cursor:pointer;font-family:Tajawal,sans-serif;display:flex;align-items:center;justify-content:center;gap:10px">
                        <i class="fas fa-times"></i> رفض الطلب
                    </button>
                </form>
            @else
                <p style="color:var(--text-muted);text-align:center;padding:20px;font-size:14px">لا توجد إجراءات متاحة</p>
            @endif
        </div>
    </div>

</div>
@endsection
