@extends('layouts.agent')
@section('title', 'التقارير')
@section('page-title', 'التقارير')
@section('content')

<div class="card">
    <div class="card-header">
        <h3><i class="fas fa-chart-bar" style="color:var(--accent);margin-left:8px"></i> التقارير</h3>
    </div>
    <div style="padding:64px;text-align:center;color:var(--text-muted)">
        <i class="fas fa-chart-pie" style="font-size:48px;opacity:.2;display:block;margin-bottom:16px"></i>
        <div style="font-size:16px;font-weight:600;margin-bottom:8px">لا توجد تقارير بعد</div>
        <div style="font-size:14px">ستظهر تقاريرك هنا بعد تسجيل المعاملات</div>
    </div>
</div>
@endsection
