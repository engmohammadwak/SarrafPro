@extends('layouts.admin')
@section('title', 'إضافة حساب')
@section('page-title', 'إضافة حساب جديد')
@section('content')
<div style="max-width:500px">
    @if($errors->any())
    <div style="background:rgba(239,68,68,0.1);border:1px solid rgba(239,68,68,0.3);color:#ef4444;padding:14px;border-radius:12px;margin-bottom:20px">
        <ul style="margin:0;padding-right:18px">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
    </div>
    @endif
    <form action="{{ route('admin.accounts.store') }}" method="POST">
        @csrf
        <div class="card">
            <div class="card-header"><h3><i class="fas fa-plus-circle" style="color:var(--accent);margin-left:8px"></i> حساب جديد</h3></div>
            <div class="card-body">
                <div style="display:flex;flex-direction:column;gap:16px">
                    <div><label style="display:block;margin-bottom:6px;font-size:14px;color:var(--text-muted)">اسم الحساب *</label>
                    <input type="text" name="name" value="{{ old('name') }}" required style="width:100%;padding:10px 14px;background:#f8f9fc;border:1px solid var(--border);border-radius:8px;font-family:Tajawal,sans-serif;font-size:14px"></div>

                    <div><label style="display:block;margin-bottom:6px;font-size:14px;color:var(--text-muted)">النوع</label>
                    <select name="type" style="width:100%;padding:10px 14px;background:#f8f9fc;border:1px solid var(--border);border-radius:8px;font-family:Tajawal,sans-serif;font-size:14px">
                        <option value="cash" {{ old('type')==='cash'?'selected':'' }}>نقدي (Cash)</option>
                        <option value="bank" {{ old('type')==='bank'?'selected':'' }}>بنك (Bank)</option>
                        <option value="safe" {{ old('type')==='safe'?'selected':'' }}>خزنة (Safe)</option>
                    </select></div>

                    <div><label style="display:block;margin-bottom:6px;font-size:14px;color:var(--text-muted)">العملة</label>
                    <input type="text" name="currency" value="{{ old('currency','OMR') }}" style="width:100%;padding:10px 14px;background:#f8f9fc;border:1px solid var(--border);border-radius:8px;font-family:Tajawal,sans-serif;font-size:14px"></div>

                    <div><label style="display:block;margin-bottom:6px;font-size:14px;color:var(--text-muted)">رصيد افتتاحي</label>
                    <input type="number" step="0.0001" name="balance" value="{{ old('balance',0) }}" style="width:100%;padding:10px 14px;background:#f8f9fc;border:1px solid var(--border);border-radius:8px;font-family:Tajawal,sans-serif;font-size:14px"></div>
                </div>
            </div>
        </div>
        <div style="margin-top:16px;display:flex;gap:12px">
            <button type="submit" class="btn btn-gold"><i class="fas fa-plus"></i> إضافة</button>
            <a href="{{ route('admin.accounts.index') }}" class="btn" style="background:var(--border);color:var(--text-dark)"><i class="fas fa-times"></i> إلغاء</a>
        </div>
    </form>
</div>
@endsection
