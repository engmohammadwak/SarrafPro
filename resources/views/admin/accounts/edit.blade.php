@extends('layouts.admin')
@section('title', 'تعديل حساب')
@section('page-title', 'تعديل الحساب')
@section('content')
<div style="max-width:500px">
    <form action="{{ route('admin.accounts.update',$account) }}" method="POST">
        @csrf @method('PUT')
        <div class="card">
            <div class="card-header"><h3><i class="fas fa-edit" style="color:var(--accent);margin-left:8px"></i> {{ $account->name }}</h3></div>
            <div class="card-body">
                <div style="display:flex;flex-direction:column;gap:16px">
                    <div><label style="display:block;margin-bottom:6px;font-size:14px;color:var(--text-muted)">اسم الحساب</label>
                    <input type="text" name="name" value="{{ old('name',$account->name) }}" required style="width:100%;padding:10px 14px;background:#f8f9fc;border:1px solid var(--border);border-radius:8px;font-family:Tajawal,sans-serif;font-size:14px"></div>
                    <div><label style="display:block;margin-bottom:6px;font-size:14px;color:var(--text-muted)">النوع</label>
                    <select name="type" style="width:100%;padding:10px 14px;background:#f8f9fc;border:1px solid var(--border);border-radius:8px;font-family:Tajawal,sans-serif;font-size:14px">
                        <option value="cash" {{ old('type',$account->type)==='cash'?'selected':'' }}>نقدي</option>
                        <option value="bank" {{ old('type',$account->type)==='bank'?'selected':'' }}>بنك</option>
                        <option value="safe" {{ old('type',$account->type)==='safe'?'selected':'' }}>خزنة</option>
                    </select></div>
                    <div><label style="display:block;margin-bottom:6px;font-size:14px;color:var(--text-muted)">العملة</label>
                    <input type="text" name="currency" value="{{ old('currency',$account->currency) }}" style="width:100%;padding:10px 14px;background:#f8f9fc;border:1px solid var(--border);border-radius:8px;font-family:Tajawal,sans-serif;font-size:14px"></div>
                </div>
            </div>
        </div>
        <div style="margin-top:16px;display:flex;gap:12px">
            <button type="submit" class="btn btn-gold"><i class="fas fa-save"></i> حفظ</button>
            <a href="{{ route('admin.accounts.index') }}" class="btn" style="background:var(--border);color:var(--text-dark)"><i class="fas fa-times"></i> إلغاء</a>
        </div>
    </form>
</div>
@endsection
