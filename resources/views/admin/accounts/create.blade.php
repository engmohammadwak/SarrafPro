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

    <form action="{{ route('admin.accounts.store') }}" method="POST" enctype="multipart/form-data" id="accountForm">
        @csrf

        {{-- اختيار النوع --}}
        <div class="card" style="margin-bottom:20px">
            <div class="card-header"><h3><i class="fas fa-layer-group" style="color:var(--accent);margin-left:8px"></i> نوع الحساب</h3></div>
            <div class="card-body">
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px">
                    @foreach([
                        'cash'     => ['نقدي',      'fa-money-bill-wave'],
                        'bank'     => ['بنك',         'fa-university'],
                        'exchange' => ['صراف',        'fa-coins'],
                        'crypto'   => ['عملة رقمية','fa-bitcoin-sign'],
                    ] as $val => [$label, $icon])
                    <label style="cursor:pointer">
                        <input type="radio" name="type" value="{{ $val }}"
                               {{ old('type') === $val ? 'checked' : '' }}
                               style="display:none" class="type-radio">
                        <div class="type-card" id="tc-{{ $val }}"
                             style="border:2px solid var(--border);border-radius:14px;padding:18px;text-align:center;transition:all 0.2s">
                            <i class="fas {{ $icon }}" style="font-size:28px;margin-bottom:8px;display:block"></i>
                            <span style="font-weight:700;font-size:15px">{{ $label }}</span>
                        </div>
                    </label>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- حقول عامة --}}
        <div class="card" id="commonFields" style="margin-bottom:20px;display:none">
            <div class="card-header"><h3 id="formTitle"><i class="fas fa-info-circle" style="color:var(--accent);margin-left:8px"></i> بيانات الحساب</h3></div>
            <div class="card-body">
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px">

                    {{-- اسم --}}
                    <div style="grid-column:1/-1">
                        <label id="nameLbl" style="display:block;margin-bottom:6px;font-size:14px;color:var(--text-muted)">الاسم *</label>
                        <input type="text" name="name" id="nameInput" value="{{ old('name') }}" required
                            style="width:100%;padding:10px 14px;background:#f8f9fc;border:1px solid var(--border);border-radius:8px;font-family:Tajawal,sans-serif;font-size:14px">
                    </div>

                    {{-- حقول غير كريبتو --}}
                    <div id="countryField">
                        <label style="display:block;margin-bottom:6px;font-size:14px;color:var(--text-muted)">الدولة</label>
                        <input type="text" name="country" value="{{ old('country') }}" placeholder="عُمان"
                            style="width:100%;padding:10px 14px;background:#f8f9fc;border:1px solid var(--border);border-radius:8px;font-family:Tajawal,sans-serif;font-size:14px">
                    </div>

                    <div id="currencyField">
                        <label style="display:block;margin-bottom:6px;font-size:14px;color:var(--text-muted)">العملة *</label>
                        <input type="text" name="currency" value="{{ old('currency','OMR') }}"
                            style="width:100%;padding:10px 14px;background:#f8f9fc;border:1px solid var(--border);border-radius:8px;font-family:Tajawal,sans-serif;font-size:14px">
                    </div>

                    <div id="accountNumberField" style="grid-column:1/-1">
                        <label style="display:block;margin-bottom:6px;font-size:14px;color:var(--text-muted)">رقم الحساب / IBAN</label>
                        <input type="text" name="account_number" value="{{ old('account_number') }}" placeholder="اختياري"
                            style="width:100%;padding:10px 14px;background:#f8f9fc;border:1px solid var(--border);border-radius:8px;font-family:Tajawal,sans-serif;font-size:14px">
                    </div>

                    {{-- حقول خاصة بالعملات الرقمية --}}
                    <div id="cryptoFields" style="display:none;grid-column:1/-1">
                        <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px">
                            <div style="grid-column:1/-1">
                                <label style="display:block;margin-bottom:6px;font-size:14px;color:var(--text-muted)">Address</label>
                                <input type="text" name="crypto_address" value="{{ old('crypto_address') }}" placeholder="0x..."
                                    style="width:100%;padding:10px 14px;background:#f8f9fc;border:1px solid var(--border);border-radius:8px;font-family:monospace;font-size:13px">
                            </div>
                            <div style="grid-column:1/-1">
                                <label style="display:block;margin-bottom:6px;font-size:14px;color:var(--text-muted)">Network</label>
                                <input type="text" name="crypto_network" value="{{ old('crypto_network') }}" placeholder="TRC20 / ERC20 / BEP20"
                                    style="width:100%;padding:10px 14px;background:#f8f9fc;border:1px solid var(--border);border-radius:8px;font-family:Tajawal,sans-serif;font-size:14px">
                            </div>
                        </div>
                    </div>

                    {{-- رصيد افتتاحي --}}
                    <div style="grid-column:1/-1">
                        <label style="display:block;margin-bottom:6px;font-size:14px;color:var(--text-muted)">رصيد افتتاحي</label>
                        <input type="number" step="0.0001" name="balance" value="{{ old('balance',0) }}"
                            style="width:100%;padding:10px 14px;background:#f8f9fc;border:1px solid var(--border);border-radius:8px;font-family:Tajawal,sans-serif;font-size:14px">
                    </div>

                    {{-- ملف --}}
                    <div style="grid-column:1/-1">
                        <label style="display:block;margin-bottom:6px;font-size:14px;color:var(--text-muted)">إرفاق ملف <span style="font-size:12px;color:var(--text-muted)">(PDF أو صورة، حد أقصى 5MB)</span></label>
                        <input type="file" name="attachment" accept=".pdf,.jpg,.jpeg,.png"
                            style="width:100%;padding:8px 14px;background:#f8f9fc;border:1px solid var(--border);border-radius:8px;font-family:Tajawal,sans-serif;font-size:13px">
                    </div>

                    {{-- ملاحظات --}}
                    <div style="grid-column:1/-1">
                        <label style="display:block;margin-bottom:6px;font-size:14px;color:var(--text-muted)">ملاحظات</label>
                        <textarea name="notes" rows="3" placeholder="اختياري"
                            style="width:100%;padding:10px 14px;background:#f8f9fc;border:1px solid var(--border);border-radius:8px;font-family:Tajawal,sans-serif;font-size:14px">{{ old('notes') }}</textarea>
                    </div>
                </div>
            </div>
        </div>

        <div id="submitRow" style="display:none;gap:12px">
            <button type="submit" class="btn btn-gold"><i class="fas fa-plus"></i> إضافة الحساب</button>
            <a href="{{ route('admin.accounts.index') }}" class="btn" style="background:var(--border);color:var(--text-dark)"><i class="fas fa-times"></i> إلغاء</a>
        </div>
    </form>
</div>

@push('scripts')
<script>
const cfg = {
    cash:     { name: 'اسم الصندوق / الخزنة', color: 'var(--accent)',  crypto: false },
    bank:     { name: 'اسم البنك',            color: 'var(--info)',    crypto: false },
    exchange: { name: 'اسم الصراف',           color: 'var(--success)', crypto: false },
    crypto:   { name: 'اسم العملة الرقمية',  color: '#8b5cf6',        crypto: true  },
};

function onTypeChange(val) {
    const c = cfg[val];

    // highlight card
    Object.keys(cfg).forEach(k => {
        const card = document.getElementById('tc-' + k);
        card.style.borderColor = k === val ? cfg[k].color : 'var(--border)';
        card.style.background  = k === val ? cfg[k].color + '18' : '';
        card.style.color       = k === val ? cfg[k].color : '';
    });

    // update name label
    document.getElementById('nameLbl').textContent = c.name + ' *';
    document.getElementById('nameInput').placeholder = 'اكتب ' + c.name;

    // toggle crypto fields
    const isCrypto = c.crypto;
    document.getElementById('cryptoFields').style.display      = isCrypto ? 'block' : 'none';
    document.getElementById('countryField').style.display      = isCrypto ? 'none'  : 'block';
    document.getElementById('currencyField').style.display     = isCrypto ? 'none'  : 'block';
    document.getElementById('accountNumberField').style.display= isCrypto ? 'none'  : 'block';

    // show form
    document.getElementById('commonFields').style.display = 'block';
    document.getElementById('submitRow').style.display    = 'flex';
    document.getElementById('nameInput').focus();
}

// attach events
document.querySelectorAll('.type-radio').forEach(r => {
    r.addEventListener('change', () => onTypeChange(r.value));
});

// restore old() selection
window.addEventListener('DOMContentLoaded', () => {
    const checked = document.querySelector('.type-radio:checked');
    if (checked) onTypeChange(checked.value);
});
</script>
@endpush
@endsection
