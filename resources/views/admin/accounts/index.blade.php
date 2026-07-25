@extends('layouts.admin')
@section('title', 'الحسابات')
@section('page-title', 'الحسابات')
@section('content')
<div class="card">
    <div class="card-header">
        <h3><i class="fas fa-wallet" style="color:var(--accent);margin-left:8px"></i> الحسابات</h3>
        <a href="{{ route('admin.accounts.create') }}" class="btn btn-gold btn-sm"><i class="fas fa-plus"></i> إضافة حساب</a>
    </div>
    <div class="card-body" style="padding:0">
        <div class="table-wrapper">
        <table>
            <thead><tr><th>#</th><th>الاسم</th><th>النوع</th><th>الدولة</th><th>العملة</th><th>رقم الحساب</th><th>الرصيد</th><th>إجراءات</th></tr></thead>
            <tbody>
            @forelse($accounts as $a)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td style="font-weight:600">
                    <i class="fas {{ \App\Models\Account::typeIcon($a->type) }}" style="margin-left:6px;color:var(--accent)"></i>
                    {{ $a->name }}
                </td>
                <td><span class="badge badge-info">{{ \App\Models\Account::typeLabel($a->type) }}</span></td>
                <td>{{ $a->country ?? '-' }}</td>
                <td>{{ $a->currency }}</td>
                <td style="font-size:13px;font-family:monospace">{{ $a->account_number ?? '-' }}</td>
                <td style="font-weight:700;color:var(--accent)">{{ number_format($a->balance,4) }}</td>
                <td style="display:flex;gap:6px">
                    <a href="{{ route('admin.accounts.edit',$a) }}" class="btn btn-sm btn-gold"><i class="fas fa-edit"></i></a>
                    @if($a->attachment)
                    <a href="{{ Storage::url($a->attachment) }}" target="_blank" class="btn btn-sm btn-primary"><i class="fas fa-paperclip"></i></a>
                    @endif
                    <form action="{{ route('admin.accounts.destroy',$a) }}" method="POST" style="display:inline">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-danger" onclick="return confirm('حذف؟')"><i class="fas fa-trash"></i></button>
                    </form>
                </td>
            </tr>
            @empty
            <tr><td colspan="8" style="text-align:center;padding:40px;color:var(--text-muted)">لا يوجد حسابات</td></tr>
            @endforelse
            </tbody>
        </table>
        </div>
    </div>
</div>
@endsection
