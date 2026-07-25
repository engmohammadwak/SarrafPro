@extends('layouts.admin')
@section('title', 'تفاصيل العملية')
@section('page-title', 'تفاصيل العملية')
@section('content')
<div style="max-width:600px">
    <div class="card">
        <div class="card-header">
            <h3><i class="fas fa-receipt" style="color:var(--accent);margin-left:8px"></i> عملية #{{ $transaction->reference }}</h3>
            <a href="{{ route('admin.transactions.index') }}" class="btn btn-sm" style="background:var(--border);color:var(--text-dark)"><i class="fas fa-arrow-right"></i> رجوع</a>
        </div>
        <div class="card-body">
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px">
                <div><p style="color:var(--text-muted);font-size:13px;margin-bottom:4px">النوع</p><p style="font-weight:700">{{ $transaction->type }}</p></div>
                <div><p style="color:var(--text-muted);font-size:13px;margin-bottom:4px">المبلغ</p><p style="font-weight:700;font-size:18px;color:var(--accent)">{{ number_format($transaction->amount,4) }} {{ $transaction->currency_from }}</p></div>
                @if($transaction->rate)
                <div><p style="color:var(--text-muted);font-size:13px;margin-bottom:4px">سعر الصرف</p><p style="font-weight:600">{{ $transaction->rate }}</p></div>
                <div><p style="color:var(--text-muted);font-size:13px;margin-bottom:4px">النتيجة</p><p style="font-weight:700">{{ number_format($transaction->amount_result,4) }} {{ $transaction->currency_to }}</p></div>
                @endif
                <div><p style="color:var(--text-muted);font-size:13px;margin-bottom:4px">العميل</p><p style="font-weight:600">{{ $transaction->customer->name ?? '-' }}</p></div>
                <div><p style="color:var(--text-muted);font-size:13px;margin-bottom:4px">المندوب</p><p style="font-weight:600">{{ $transaction->agent->name ?? '-' }}</p></div>
                <div><p style="color:var(--text-muted);font-size:13px;margin-bottom:4px">منفذ بواسطة</p><p style="font-weight:600">{{ $transaction->performer->name }}</p></div>
                <div><p style="color:var(--text-muted);font-size:13px;margin-bottom:4px">التاريخ</p><p style="font-weight:600">{{ $transaction->created_at->format('Y-m-d H:i') }}</p></div>
                @if($transaction->notes)
                <div style="grid-column:1/-1"><p style="color:var(--text-muted);font-size:13px;margin-bottom:4px">ملاحظات</p><p>{{ $transaction->notes }}</p></div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
