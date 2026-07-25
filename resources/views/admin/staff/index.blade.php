@extends('layouts.admin')
@section('title', 'الموظفون')
@section('page-title', 'موظفو المحل')
@section('content')
<div class="card">
    <div class="card-header">
        <h3><i class="fas fa-users" style="color:var(--accent);margin-left:8px;"></i> الموظفون</h3>
        <a href="{{ route('admin.staff.create') }}" class="btn btn-gold btn-sm"><i class="fas fa-plus"></i> إضافة موظف</a>
    </div>
    <div class="card-body" style="padding:0">
        <div class="table-wrapper">
        <table>
            <thead><tr><th>#</th><th>الاسم</th><th>الإيميل</th><th>الدور</th><th>الحالة</th><th>إجراءات</th></tr></thead>
            <tbody>
            @forelse($staff as $s)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td style="font-weight:600">{{ $s->user->name }}</td>
                <td>{{ $s->user->email }}</td>
                <td><span class="badge badge-info">{{ $s->role }}</span></td>
                <td>@if($s->is_active)<span class="badge badge-success">نشط</span>@else<span class="badge badge-danger">معطل</span>@endif</td>
                <td style="display:flex;gap:6px">
                    <a href="{{ route('admin.staff.edit',$s) }}" class="btn btn-sm btn-gold"><i class="fas fa-edit"></i></a>
                    <form action="{{ route('admin.staff.destroy',$s) }}" method="POST" style="display:inline">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-danger" onclick="return confirm('حذف?')"><i class="fas fa-trash"></i></button>
                    </form>
                </td>
            </tr>
            @empty
            <tr><td colspan="6" style="text-align:center;padding:40px;color:var(--text-muted)">لا يوجد موظفون</td></tr>
            @endforelse
            </tbody>
        </table>
        </div>
    </div>
    @if($staff->hasPages())<div style="padding:16px">{{ $staff->links() }}</div>@endif
</div>
@endsection
