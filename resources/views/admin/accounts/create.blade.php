@extends('layouts.admin')
@section('title', 'إضافة حساب')
@section('page-title', 'إضافة حساب جديد')
@section('content')
<div style="max-width:640px">
    @if($errors->any())
    <div style="background:rgba(239,68,68,0.1);border:1px solid rgba(239,68,68,0.3);color:#ef4444;padding:14px;border-radius:12px;margin-bottom:20px">
        <ul style="margin:0;padding-right:18px">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
    </div>
    @endif

    <form action="{{ route('admin.accounts.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        {{-- النوع --}}
        <div class="card" style="margin-bottom:20px">
            <div class="card-header"><h3><i class="fas fa-layer-group" style="color:var(--accent);margin-left:8px"></i> نوع الحساب</h3></div>
            <div class="card-body">
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px" id="typeGrid">
                    @foreach(['cash'=>['نقدي','fa-money-bill-wave','gold'],'bank'=>['بنك','fa-university','blue'],'exchange'=>['صراف','fa-coins','green'],'crypto'=>['عملة رقمية','fa-bitcoin-sign','purple']] as $val=>[$label,$icon,$color])
                    <label style="cursor:pointer">
                        <input type="radio" name="type" value="{{ $val }}" {{ old('type')===$val?'checked':'' }} style="display:none" class="type-radio" onchange="onTypeChange('{{ $val }}','{{ $label }}')"> 
                        <div class="type-card" id="tc-{{ $val }}" style="border:2px solid var(--border);border-radius:14px;padding:18px;text-align:center;transition:all 0.2s">
                            <i class="fas {{ $icon }}" style="font-size:28px;margin-bottom:8px;display:block"></i>
                            <span style="font-weight:700;font-size:15px">{{ $label }}</span>
                        </div>
                    </label>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- بيانات الحساب --}}
        <div class="card" style="margin-bottom:20px">
            <div class="card-header"><h3><i class="fas fa-info-circle" style="color:var(--accent);margin-left:8px"></i> بيانات الحساب</h3></div>
            <div class="card-body">
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px">

                    <div style="grid-column:1/-1">
                        <label id="nameLbl" style="display:block;margin-bottom:6px;font-size:14px;color:var(--text-muted)">الاسم *</label>
                        <input type="text" name="name" id="nameInput" value="{{ old('name') }}" required
                            placeholder="اختر النوع أولاً"
                            style="width:100%;padding:10px 14px;background:#f8f9fc;border:1px solid var(--border);border-radius:8px;font-family:Tajawal,sans-serif;font-size:14px">
                    </div>

                    <div>
                        <label style="display:block;margin-bottom:6px;font-size:14px;color:var(--text-muted)">الدولة</label>
                        <input type="text" name="country" value="{{ old('country') }}" placeholder="عُمان"
                            style="width:100%;padding:10px 14px;background:#f8f9fc;border:1px solid var(--border);border-radius:8px;font-family:Tajawal,sans-serif;font-size:14px">
                    </div>

                    <div>
                        <label style="display:block;margin-bottom:6px;font-size:14px;color:var(--text-muted)">العملة *</label>
                        <input type="text" name="currency" value="{{ old('currency','OMR') }}"
                            style="width:100%;padding:10px 14px;background:#f8f9fc;border:1px solid var(--border);border-radius:8px;font-family:Tajawal,sans-serif;font-size:14px">
                    </div>

                    <div style="grid-column:1/-1">
                        <label style="display:block;margin-bottom:6px;font-size:14px;color:var(--text-muted)">رقم الحساب / IBAN</label>
                        <input type="text" name="account_number" value="{{ old('account_number') }}" placeholder="اختياري"
                            style="width:100%;padding:10px 14px;background:#f8f9fc;border:1px solid var(--border);border-radius:8px;font-family:Tajawal,sans-serif;font-size:14px">
                    </div>

                    <div>
                        <label style="display:block;margin-bottom:6px;font-size:14px;color:var(--text-muted)">رصيد افتتاحي</label>
                        <input type="number" step="0.0001" name="balance" value="{{ old('balance',0) }}"
                            style="width:100%;padding:10px 14px;background:#f8f9fc;border:1px solid var(--border);border-radius:8px;font-family:Tajawal,sans-serif;font-size:14px">
                    </div>

                    <div>
                        <label style="display:block;margin-bottom:6px;font-size:14px;color:var(--text-muted)">إرفاق ملف <span style="font-size:12px;color:var(--text-muted)">(PDF أو صورة، حد أقصى 5MB)</span></label>
                        <input type="file" name="attachment" accept=".pdf,.jpg,.jpeg,.png"
                            style="width:100%;padding:8px 14px;background:#f8f9fc;border:1px solid var(--border);border-radius:8px;font-family:Tajawal,sans-serif;font-size:13px">
                    </div>

                    <div style="grid-column:1/-1">
                        <label style="display:block;margin-bottom:6px;font-size:14px;color:var(--text-muted)">ملاحظات</label>
                        <textarea name="notes" rows="3" placeholder="اختياري"
                            style="width:100%;padding:10px 14px;background:#f8f9fc;border:1px solid var(--border);border-radius:8px;font-family:Tajawal,sans-serif;font-size:14px">{{ old('notes') }}</textarea>
                    </div>
                </div>
            </div>
        </div>

        <div style="display:flex;gap:12px">
            <button type="submit" class="btn btn-gold"><i class="fas fa-plus"></i> إضافة الحساب</button>
            <a href="{{ route('admin.accounts.index') }}" class="btn" style="background:var(--border);color:var(--text-dark)"><i class="fas fa-times"></i> إلغاء</a>
        </div>
    </form>
</div>

@push('scripts')
<script>
const labels = {
    cash:     'اسم الصندوق / الخزنة',
    bank:     'اسم البنك',
    exchange: 'اسم الصراف',
    crypto:   'اسم العملة الرقمية',
};
const colors = {
    cash: 'var(--accent)', bank: 'var(--info)', exchange: 'var(--success)', crypto: '#8b5cf6'
};

function onTypeChange(val, label) {
    // update label
    document.getElementById('nameLbl').textContent = labels[val] + ' *';
    document.getElementById('nameInput').placeholder = 'اكتب ' + label;
    document.getElementById('nameInput').focus();
    // highlight selected card
    document.querySelectorAll('.type-card').forEach(c => {
        c.style.borderColor = 'var(--border)';
        c.style.background  = '';
        c.style.color       = '';
    });
    const card = document.getElementById('tc-' + val);
    card.style.borderColor = colors[val];
    card.style.background  = colors[val] + '18';
    card.style.color       = colors[val];
}

// restore on page reload with old()
window.addEventListener('DOMContentLoaded', () => {
    const checked = document.querySelector('.type-radio:checked');
    if (checked) onTypeChange(checked.value, labels[checked.value]?.replace(' *',''));
});
</script>
@endpush
@endsection
