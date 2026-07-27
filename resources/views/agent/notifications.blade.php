@extends('layouts.agent')
@section('title', 'الإشعارات')
@section('page-title', 'الإشعارات')
@section('content')

<div class="card">
    <div class="card-header">
        <h3><i class="fas fa-bell" style="color:var(--accent);margin-left:8px"></i> الإشعارات</h3>
    </div>
    <div style="padding:64px;text-align:center;color:var(--text-muted)">
        <i class="fas fa-bell" style="font-size:48px;opacity:.2;display:block;margin-bottom:16px"></i>
        <div style="font-size:16px;font-weight:600;margin-bottom:8px">لا توجد إشعارات</div>
        <div style="font-size:14px">ستظهر إشعاراتك هنا</div>
    </div>
</div>
@endsection
