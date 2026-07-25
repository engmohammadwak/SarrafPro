@extends('layouts.superadmin')
@section('title', 'لوحة التحكم - صراف برو')
@section('page-title', 'لوحة التحكم')

@section('content')
<!-- Stats -->
<div class="stats-grid">
    <div class="stat-card gold">
        <div class="stat-icon"><i class="fas fa-store"></i></div>
        <div class="stat-info">
            <h4>{{ $totalShops }}</h4>
            <p>إجمالي المحلات</p>
        </div>
    </div>
    <div class="stat-card green">
        <div class="stat-icon"><i class="fas fa-check-circle"></i></div>
        <div class="stat-info">
            <h4>{{ $activeShops }}</h4>
            <p>محلات نشطة</p>
        </div>
    </div>
    <div class="stat-card blue">
        <div class="stat-icon"><i class="fas fa-users"></i></div>
        <div class="stat-info">
            <h4>{{ $totalUsers }}</h4>
            <p>إجمالي المستخدمين</p>
        </div>
    </div>
    <div class="stat-card red">
        <div class="stat-icon"><i class="fas fa-ban"></i></div>
        <div class="stat-info">
            <h4>{{ $inactiveShops }}</h4>
            <p>محلات موقوفة</p>
        </div>
    </div>
</div>

<!-- Latest Shops -->
<div class="card">
    <div class="card-header">
        <h3><i class="fas fa-store" style="color:var(--accent);margin-left:8px;"></i> أحدث المحلات المسجلة</h3>
        <a href="{{ route('superadmin.shops.create') }}" class="btn btn-gold btn-sm">
            <i class="fas fa-plus"></i> إضافة محل
        </a>
    </div>
    <div class="card-body" style="padding:0;">
        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>اسم المحل</th>
                        <th>المدير</th>
                        <th>المدينة</th>
                        <th>الحالة</th>
                        <th>الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($latestShops as $shop)
                    <tr>
                        <td style="color:var(--text-muted);font-size:13px;">{{ $loop->iteration }}</td>
                        <td>
                            <div style="font-weight:600;">{{ $shop->name }}</div>
                            <div style="font-size:12px;color:var(--text-muted);">{{ $shop->phone ?? '-' }}</div>
                        </td>
                        <td>{{ $shop->admin->name ?? 'غير محدد' }}</td>
                        <td>{{ $shop->city ?? '-' }}</td>
                        <td>
                            @if($shop->is_active)
                                <span class="badge badge-success"><i class="fas fa-circle" style="font-size:8px;"></i> نشط</span>
                            @else
                                <span class="badge badge-danger"><i class="fas fa-circle" style="font-size:8px;"></i> موقوف</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('superadmin.shops.show', $shop) }}" class="btn btn-sm btn-primary">
                                <i class="fas fa-eye"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" style="text-align:center;padding:40px;color:var(--text-muted);">
                            <i class="fas fa-store" style="font-size:32px;opacity:0.3;display:block;margin-bottom:10px;"></i>
                            لا توجد محلات مسجلة بعد
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
