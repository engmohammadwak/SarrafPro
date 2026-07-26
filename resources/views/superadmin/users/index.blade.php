@extends('layouts.superadmin')
@section('title', 'مستخدمو السوبر ادمن')
@section('page-title', 'مستخدمو السوبر ادمن')
@section('content')
@if(session('success'))
<div style="background:rgba(34,197,94,0.1);border:1px solid rgba(34,197,94,0.3);color:#16a34a;padding:12px 16px;border-radius:8px;margin-bottom:20px;">
    <i class="fas fa-check-circle" style="margin-left:8px;"></i>{{ session('success') }}
</div>
@endif
<div class="card">
    <div class="card-header">
        <h3><i class="fas fa-user-shield" style="color:var(--accent);margin-left:8px;"></i> مستخدمو السوبر ادمن</h3>
        <a href="{{ route('superadmin.users.create') }}" class="btn btn-gold btn-sm">
            <i class="fas fa-user-plus"></i> إضافة
        </a>
    </div>
    <div class="card-body" style="padding:0">
        <div class="table-wrapper">
            <table>
                <thead><tr>
                    <th>#</th><th>الاسم</th><th>Username</th><th>البريد</th><th>تاريخ التسجيل</th><th>الإجراءات</th>
                </tr></thead>
                <tbody>
                @forelse($users as $user)
                <tr>
                    <td style="color:var(--text-muted);font-size:13px">{{ $loop->iteration }}</td>
                    <td style="font-weight:600">{{ $user->name }}</td>
                    <td>
                        @if($user->username)
                            <span style="background:#f3f4f6;padding:3px 10px;border-radius:6px;font-size:13px;font-family:monospace;color:#374151">&#64;{{ $user->username }}</span>
                        @else
                            <span style="color:#d1d5db">—</span>
                        @endif
                    </td>
                    <td style="color:var(--text-muted)">{{ $user->email }}</td>
                    <td style="color:var(--text-muted);font-size:13px">{{ $user->created_at->format('Y-m-d') }}</td>
                    <td style="display:flex;gap:6px">
                        <a href="{{ route('superadmin.users.show', $user) }}" class="btn btn-sm btn-primary"><i class="fas fa-eye"></i></a>
                        <a href="{{ route('superadmin.users.edit', $user) }}" class="btn btn-sm btn-gold"><i class="fas fa-edit"></i></a>
                        <form action="{{ route('superadmin.users.destroy', $user) }}" method="POST" style="display:inline">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-danger" onclick="return confirm('حذف؟')"><i class="fas fa-trash"></i></button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" style="text-align:center;padding:40px;color:var(--text-muted)">لا يوجد مستخدمون</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($users->hasPages())<div style="padding:16px">{{ $users->links() }}</div>@endif
</div>
@endsection
