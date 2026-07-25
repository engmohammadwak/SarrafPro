@extends('layouts.admin')
@section('title', 'المناديب')
@section('page-title', 'المناديب')
@section('content')
<div class="card">
    <div class="card-header">
        <h3><i class="fas fa-handshake" style="color:var(--accent);margin-left:8px"></i> المناديب</h3>
        <a href="{{ route('admin.agents.create') }}" class="btn btn-gold btn-sm"><i class="fas fa-plus"></i> إضافة مندوب</a>
    </div>
    <div class="card-body" style="padding:0">
        <div class="table-wrapper">
        <table>
            <thead><tr><th>#</th><th>الاسم</th><th>الهاتف</th><th>الدولة</th><th>الشركة</th><th>الرصيد</th><th>إجراءات</th></tr></thead>
            <tbody>
            @forelse($agents as $a)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td style="font-weight:600">{{ $a->name }}</td>
                <td>{{ $a->phone ?? '-' }}</td>
                <td>{{ $a->country ?? '-' }}</td>
                <td>{{ $a->company ?? '-' }}</td>
                <td style="font-weight:700;color:var(--accent)">{{ number_format($a->balance,4) }}</td>
                <td style="display:flex;gap:6px">
                    <a href="{{ route('admin.agents.edit',$a) }}" class="btn btn-sm btn-gold"><i class="fas fa-edit"></i></a>
                    <form action="{{ route('admin.agents.destroy',$a) }}" method="POST" style="display:inline">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-danger" onclick="return confirm('حذف?')"><i class="fas fa-trash"></i></button>
                    </form>
                </td>
            </tr>
            @empty
            <tr><td colspan="7" style="text-align:center;padding:40px;color:var(--text-muted)">لا يوجد مناديب</td></tr>
            @endforelse
            </tbody>
        </table>
        </div>
    </div>
    @if($agents->hasPages())<div style="padding:16px">{{ $agents->links() }}</div>@endif
</div>
@endsection
