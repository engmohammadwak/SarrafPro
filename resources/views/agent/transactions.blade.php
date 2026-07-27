@extends('layouts.agent')
@section('title', 'المعاملات')
@section('page-title', 'المعاملات')
@section('content')

<div class="card">
    <div class="card-header">
        <h3><i class="fas fa-exchange-alt" style="color:var(--accent);margin-left:8px"></i> سجل المعاملات</h3>
    </div>
    <div style="padding:64px;text-align:center;color:var(--text-muted)">
        <i class="fas fa-exchange-alt" style="font-size:48px;opacity:.2;display:block;margin-bottom:16px"></i>
        <div style="font-size:16px;font-weight:600;margin-bottom:8px">لا توجد معاملات بعد</div>
        <div style="font-size:14px">ستظهر معاملاتك هنا عند إضافتها من قِبل المحل</div>
    </div>
</div>
@endsection
