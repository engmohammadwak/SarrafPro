@extends('layouts.agent')
@section('title', 'لوحة التحكم')
@section('page-title', 'لوحة التحكم')
@section('content')

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
