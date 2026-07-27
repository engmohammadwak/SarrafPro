@extends('layouts.superadmin')
@section('title', 'مستخدمو السوبر ادمن')
@section('page-title', 'مستخدمو السوبر ادمن')
@section('content')
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
                    <th>#</th><th>الاسم</th><th>Username</th><th>تاريخ التسجيل</th><th>الإجراءات</th>
                </tr></thead>
                <tbody>
                @forelse($users as $user)
                <tr>
                    <td style="color:var(--text-muted);font-size:13px">{{ $loop->iteration }}</td>
                    <td style="font-weight:600">
                        {{ $user->name }}
                        @if($user->id === auth()->id())
                            <span style="background:rgba(212,175,55,0.15);color:var(--accent);font-size:11px;padding:2px 8px;border-radius:20px;margin-right:6px;font-weight:600">أنت</span>
                        @endif
                    </td>
                    <td>
                        @if($user->username)
                            <span style="background:#f3f4f6;padding:3px 10px;border-radius:6px;font-size:13px;font-family:monospace;color:#374151">{{ $user->username }}</span>
                        @else
                            <span style="color:#d1d5db">—</span>
                        @endif
                    </td>
                    <td style="color:var(--text-muted);font-size:13px">{{ $user->created_at->format('Y-m-d') }}</td>
                    <td style="display:flex;gap:6px">
                        <a href="{{ route('superadmin.users.show', $user) }}" class="btn btn-sm btn-primary"><i class="fas fa-eye"></i></a>
                        <a href="{{ route('superadmin.users.edit', $user) }}" class="btn btn-sm btn-gold"><i class="fas fa-edit"></i></a>
                        @if($user->id !== auth()->id())
                        <form action="{{ route('superadmin.users.destroy', $user) }}" method="POST" style="display:inline">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-danger" onclick="return confirm('حذف؟')"><i class="fas fa-trash"></i></button>
                        </form>
                        @endif
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" style="text-align:center;padding:40px;color:var(--text-muted)">لا يوجد مستخدمون</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($users->hasPages())<div style="padding:16px">{{ $users->links() }}</div>@endif
</div>
@endsection
