@extends('layouts.admin')
@section('title', 'العملاء')
@section('page-title', 'العملاء')
@section('content')
<div class="card">
    <div class="card-header">
        <h3><i class="fas fa-users" style="color:var(--accent);margin-left:8px"></i> العملاء</h3>
        <a href="{{ route('admin.customers.create') }}" class="btn btn-gold btn-sm"><i class="fas fa-plus"></i> إضافة عميل</a>
    </div>
    <div class="card-body" style="padding:0">
        <div class="table-wrapper">
        <table>
            <thead><tr><th>#</th><th>الاسم</th><th>الهاتف</th><th>رقم الهوية</th><th>الجنسية</th><th>إجراءات</th></tr></thead>
            <tbody>
            @forelse($customers as $c)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td style="font-weight:600">{{ $c->name }}</td>
                <td>{{ $c->phone ?? '-' }}</td>
                <td>{{ $c->id_number ?? '-' }}</td>
                <td>{{ $c->nationality ?? '-' }}</td>
                <td style="display:flex;gap:6px">
                    <a href="{{ route('admin.customers.edit',$c) }}" class="btn btn-sm btn-gold"><i class="fas fa-edit"></i></a>
                    <form action="{{ route('admin.customers.destroy',$c) }}" method="POST" style="display:inline">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-danger" onclick="return confirm('حذف?')"><i class="fas fa-trash"></i></button>
                    </form>
                </td>
            </tr>
            @empty
            <tr><td colspan="6" style="text-align:center;padding:40px;color:var(--text-muted)">لا يوجد عملاء</td></tr>
            @endforelse
            </tbody>
        </table>
        </div>
    </div>
    @if($customers->hasPages())<div style="padding:16px">{{ $customers->links() }}</div>@endif
</div>
@endsection
