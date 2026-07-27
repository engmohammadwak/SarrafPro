@extends('layouts.agent')
@section('title', 'لوحة التحكم')
@section('page-title', 'لوحة التحكم')
@section('content')

{{-- ===== POPUP طلبات الربط المعلقة ===== --}}
@if($pendingAgents->count() > 0)
<div id="pendingOverlay" style="
    position:fixed;inset:0;background:rgba(0,0,0,0.55);z-index:9000;
    display:flex;align-items:center;justify-content:center;padding:20px;
    backdrop-filter:blur(3px);
">
    <div style="
        background:#fff;border-radius:20px;width:100%;max-width:480px;
        box-shadow:0 20px 60px rgba(0,0,0,0.25);overflow:hidden;
        animation:popIn .3s cubic-bezier(.34,1.56,.64,1);
    ">
        {{-- رأس --}}
        <div style="background:linear-gradient(135deg,#1a1f3c,#2d3561);padding:24px 28px;display:flex;align-items:center;gap:14px;">
            <div style="width:48px;height:48px;background:rgba(201,168,76,0.2);border-radius:14px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                <i class="fas fa-link" style="color:#c9a84c;font-size:20px;"></i>
            </div>
            <div>
                <h3 style="color:#fff;font-size:16px;font-weight:800;margin:0;">طلب انضمام جديد</h3>
                <p style="color:rgba(255,255,255,0.6);font-size:13px;margin:4px 0 0;">لديك {{ $pendingAgents->count() }} طلب{{ $pendingAgents->count() > 1 ? 'ات' : '' }} بانتظار ردك</p>
            </div>
        </div>

        {{-- قائمة الطلبات --}}
        <div style="max-height:320px;overflow-y:auto;">
            @foreach($pendingAgents as $pa)
            <div style="padding:20px 28px;border-bottom:1px solid #f3f4f6;">
                <div style="display:flex;align-items:center;gap:14px;margin-bottom:16px;">
                    <div style="width:44px;height:44px;background:linear-gradient(135deg,#c9a84c,#f0d080);border-radius:12px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                        <i class="fas fa-store" style="color:#1a1f3c;font-size:18px;"></i>
                    </div>
                    <div>
                        <p style="font-weight:700;font-size:15px;margin:0;">{{ $pa->shop->name ?? 'محل غير معروف' }}</p>
                        <p style="color:#6b7280;font-size:12px;margin:3px 0 0;">
                            @if($pa->shop?->city || $pa->shop?->country)
                                <i class="fas fa-map-marker-alt" style="margin-left:4px;"></i>
                                {{ $pa->shop->city ?? '' }}{{ $pa->shop->city && $pa->shop->country ? '، ' : '' }}{{ $pa->shop->country ?? '' }}
                            @else
                                <i class="fas fa-clock" style="margin-left:4px;"></i>
                                {{ $pa->created_at->diffForHumans() }}
                            @endif
                        </p>
                    </div>
                </div>

                <div style="display:flex;gap:10px;">
                    {{-- زر القبول --}}
                    <form method="POST" action="{{ route('agent.agents.approve', $pa->id) }}" style="flex:1;">
                        @csrf @method('PATCH')
                        <button type="submit" style="
                            width:100%;padding:11px;border:none;border-radius:10px;cursor:pointer;
                            background:linear-gradient(135deg,#10b981,#059669);color:#fff;
                            font-family:Tajawal,sans-serif;font-size:14px;font-weight:700;
                            display:flex;align-items:center;justify-content:center;gap:8px;
                            transition:opacity .2s;
                        " onmouseover="this.style.opacity='.85'" onmouseout="this.style.opacity='1'">
                            <i class="fas fa-check"></i> قبول
                        </button>
                    </form>

                    {{-- زر الرفض --}}
                    <form method="POST" action="{{ route('agent.agents.reject', $pa->id) }}" style="flex:1;">
                        @csrf @method('PATCH')
                        <button type="submit" style="
                            width:100%;padding:11px;border:none;border-radius:10px;cursor:pointer;
                            background:linear-gradient(135deg,#ef4444,#dc2626);color:#fff;
                            font-family:Tajawal,sans-serif;font-size:14px;font-weight:700;
                            display:flex;align-items:center;justify-content:center;gap:8px;
                            transition:opacity .2s;
                        " onmouseover="this.style.opacity='.85'" onmouseout="this.style.opacity='1'">
                            <i class="fas fa-times"></i> رفض
                        </button>
                    </form>
                </div>
            </div>
            @endforeach
        </div>

        {{-- زر تأجيل --}}
        <div style="padding:16px 28px;text-align:center;border-top:1px solid #f3f4f6;">
            <button onclick="document.getElementById('pendingOverlay').style.display='none'" style="
                background:none;border:1px solid #e5e7eb;padding:9px 24px;border-radius:10px;
                color:#6b7280;font-family:Tajawal,sans-serif;font-size:13px;cursor:pointer;
                transition:all .2s;
            " onmouseover="this.style.background='#f9fafb'" onmouseout="this.style.background='none'">
                <i class="fas fa-clock" style="margin-left:6px;"></i> تأجيل القرار
            </button>
        </div>
    </div>
</div>
@endif

<style>
@keyframes popIn {
    from { opacity:0; transform:scale(.85) translateY(20px); }
    to   { opacity:1; transform:scale(1)  translateY(0); }
}
</style>

<div class="stats-grid">
    <div class="stat-card gold">
        <div class="stat-icon"><i class="fas fa-store"></i></div>
        <div class="stat-info"><h4>{{ $agents->count() }}</h4><p>إجمالي الربط</p></div>
    </div>
    <div class="stat-card green">
        <div class="stat-icon"><i class="fas fa-check-circle"></i></div>
        <div class="stat-info"><h4>{{ $approvedCount }}</h4><p>ربط موافق عليه</p></div>
    </div>
    <div class="stat-card blue">
        <div class="stat-icon"><i class="fas fa-hourglass-half"></i></div>
        <div class="stat-info"><h4>{{ $pendingCount }}</h4><p>بانتظار موافقة</p></div>
    </div>
    <div class="stat-card red">
        <div class="stat-icon"><i class="fas fa-times-circle"></i></div>
        <div class="stat-info"><h4>{{ $rejectedCount }}</h4><p>مرفوض</p></div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h3><i class="fas fa-link" style="color:var(--accent);margin-left:8px"></i> المحلات المرتبطة</h3>
    </div>
    @if($agents->isEmpty())
    <div style="padding:48px;text-align:center;color:var(--text-muted)">
        <i class="fas fa-store" style="font-size:40px;opacity:.3;display:block;margin-bottom:12px"></i>
        لا يوجد ربط حتى الآن
    </div>
    @else
    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>المحل</th>
                    <th>المدينة</th>
                    <th>الدولة</th>
                    <th>حالة الربط</th>
                </tr>
            </thead>
            <tbody>
            @foreach($agents as $i => $a)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td style="font-weight:600">{{ $a->shop->name ?? '-' }}</td>
                <td style="color:var(--text-muted)">{{ $a->shop->city ?? '-' }}</td>
                <td style="color:var(--text-muted)">{{ $a->shop->country ?? '-' }}</td>
                <td>
                    @if($a->link_status === 'approved')
                        <span class="badge badge-success"><i class="fas fa-check"></i> موافق عليه</span>
                    @elseif($a->link_status === 'pending')
                        <span class="badge badge-warning"><i class="fas fa-clock"></i> بانتظار موافقة</span>
                    @elseif($a->link_status === 'rejected')
                        <span class="badge badge-danger"><i class="fas fa-times"></i> مرفوض</span>
                    @else
                        <span class="badge badge-info">بدون ربط</span>
                    @endif
                </td>
            </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    @endif
</div>
@endsection
