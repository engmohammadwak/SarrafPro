@extends('layouts.admin')
@section('title', 'عملية جديدة')
@section('page-title', 'تسجيل عملية جديدة')
@section('content')
<div style="max-width:700px">
    @if($errors->any())
    <div style="background:rgba(239,68,68,0.1);border:1px solid rgba(239,68,68,0.3);color:#ef4444;padding:14px;border-radius:12px;margin-bottom:20px">
        <ul style="margin:0;padding-right:18px">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
    </div>
    @endif
    <form action="{{ route('admin.transactions.store') }}" method="POST">
        @csrf
        <div class="card" style="margin-bottom:20px">
            <div class="card-header"><h3><i class="fas fa-exchange-alt" style="color:var(--accent);margin-left:8px"></i> بيانات العملية</h3></div>
            <div class="card-body">
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px">

                    <div style="grid-column:1/-1"><label style="display:block;margin-bottom:6px;font-size:14px;color:var(--text-muted)">نوع العملية *</label>
                    <select name="type" required style="width:100%;padding:10px 14px;background:#f8f9fc;border:1px solid var(--border);border-radius:8px;font-family:Tajawal,sans-serif;font-size:14px">
                        <option value="buy"      {{ old('type')==='buy'?'selected':'' }}>شراء عملة</option>
                        <option value="sell"     {{ old('type')==='sell'?'selected':'' }}>بيع عملة</option>
                        <option value="transfer" {{ old('type')==='transfer'?'selected':'' }}>تحويل</option>
                        <option value="deposit"  {{ old('type')==='deposit'?'selected':'' }}>إيداع</option>
                        <option value="withdraw" {{ old('type')==='withdraw'?'selected':'' }}>سحب</option>
                    </select></div>

                    <div><label style="display:block;margin-bottom:6px;font-size:14px;color:var(--text-muted)">من عملة</label>
                    <input type="text" name="currency_from" value="{{ old('currency_from') }}" placeholder="USD" style="width:100%;padding:10px 14px;background:#f8f9fc;border:1px solid var(--border);border-radius:8px;font-family:Tajawal,sans-serif;font-size:14px"></div>

                    <div><label style="display:block;margin-bottom:6px;font-size:14px;color:var(--text-muted)">إلى عملة</label>
                    <input type="text" name="currency_to" value="{{ old('currency_to') }}" placeholder="OMR" style="width:100%;padding:10px 14px;background:#f8f9fc;border:1px solid var(--border);border-radius:8px;font-family:Tajawal,sans-serif;font-size:14px"></div>

                    <div><label style="display:block;margin-bottom:6px;font-size:14px;color:var(--text-muted)">المبلغ *</label>
                    <input type="number" step="0.0001" name="amount" value="{{ old('amount') }}" required style="width:100%;padding:10px 14px;background:#f8f9fc;border:1px solid var(--border);border-radius:8px;font-family:Tajawal,sans-serif;font-size:14px"></div>

                    <div><label style="display:block;margin-bottom:6px;font-size:14px;color:var(--text-muted)">سعر الصرف</label>
                    <input type="number" step="0.000001" name="rate" value="{{ old('rate') }}" style="width:100%;padding:10px 14px;background:#f8f9fc;border:1px solid var(--border);border-radius:8px;font-family:Tajawal,sans-serif;font-size:14px"></div>

                    <div><label style="display:block;margin-bottom:6px;font-size:14px;color:var(--text-muted)">العميل</label>
                    <select name="customer_id" style="width:100%;padding:10px 14px;background:#f8f9fc;border:1px solid var(--border);border-radius:8px;font-family:Tajawal,sans-serif;font-size:14px">
                        <option value="">— بدون عميل —</option>
                        @foreach($customers as $c)<option value="{{ $c->id }}" {{ old('customer_id')==$c->id?'selected':'' }}>{{ $c->name }}</option>@endforeach
                    </select></div>

                    <div><label style="display:block;margin-bottom:6px;font-size:14px;color:var(--text-muted)">المندوب</label>
                    <select name="agent_id" style="width:100%;padding:10px 14px;background:#f8f9fc;border:1px solid var(--border);border-radius:8px;font-family:Tajawal,sans-serif;font-size:14px">
                        <option value="">— بدون مندوب —</option>
                        @foreach($agents as $a)<option value="{{ $a->id }}" {{ old('agent_id')==$a->id?'selected':'' }}>{{ $a->name }}</option>@endforeach
                    </select></div>

                    <div><label style="display:block;margin-bottom:6px;font-size:14px;color:var(--text-muted)">الحساب</label>
                    <select name="account_id" style="width:100%;padding:10px 14px;background:#f8f9fc;border:1px solid var(--border);border-radius:8px;font-family:Tajawal,sans-serif;font-size:14px">
                        <option value="">— بدون حساب —</option>
                        @foreach($accounts as $ac)<option value="{{ $ac->id }}" {{ old('account_id')==$ac->id?'selected':'' }}>{{ $ac->name }} ({{ $ac->currency }})</option>@endforeach
                    </select></div>

                    <div><label style="display:block;margin-bottom:6px;font-size:14px;color:var(--text-muted)">عمولة</label>
                    <input type="number" step="0.0001" name="fee" value="{{ old('fee',0) }}" style="width:100%;padding:10px 14px;background:#f8f9fc;border:1px solid var(--border);border-radius:8px;font-family:Tajawal,sans-serif;font-size:14px"></div>

                    <div style="grid-column:1/-1"><label style="display:block;margin-bottom:6px;font-size:14px;color:var(--text-muted)">ملاحظات</label>
                    <textarea name="notes" rows="2" style="width:100%;padding:10px 14px;background:#f8f9fc;border:1px solid var(--border);border-radius:8px;font-family:Tajawal,sans-serif;font-size:14px">{{ old('notes') }}</textarea></div>
                </div>
            </div>
        </div>
        <div style="display:flex;gap:12px">
            <button type="submit" class="btn btn-gold"><i class="fas fa-save"></i> تسجيل العملية</button>
            <a href="{{ route('admin.transactions.index') }}" class="btn" style="background:var(--border);color:var(--text-dark)"><i class="fas fa-times"></i> إلغاء</a>
        </div>
    </form>
</div>
@endsection
