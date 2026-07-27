@extends('layouts.superadmin')
@section('title', 'المناديب')
@section('page-title', 'قائمة المناديب')
@section('content')
<div class="card">
    <div class="card-header">
        <h3><i class="fas fa-id-badge" style="color:var(--accent);margin-left:8px;"></i> المناديب</h3>
        <a href="{{ route('superadmin.agents.create') }}" class="btn btn-gold btn-sm">
            <i class="fas fa-user-plus"></i> إضافة مندوب
        </a>
    </div>
    <div class="card-body" style="padding:0">
        <div class="table-wrapper">
            <table>
                <thead><tr>
                    <th>#</th><th>الاسم</th><th>Username</th><th>البريد</th><th>الحالة</th><th>تاريخ التسجيل</th><th>الإجراءات</th>
                </tr></thead>
                <tbody>
                @forelse($agents as $agent)
                <tr>
                    <td style="color:var(--text-muted);font-size:13px">{{ $loop->iteration }}</td>
                    <td style="font-weight:600">{{ $agent->name }}</td>
                    <td>
                        @if($agent->username)
                            <span style="background:#f3f4f6;padding:3px 10px;border-radius:6px;font-size:13px;font-family:monospace;color:#374151">{{ $agent->username }}</span>
                        @else
                            <span style="color:#d1d5db">—</span>
                        @endif
                    </td>
                    <td style="color:var(--text-muted)">{{ $agent->email }}</td>
                    <td>
                        @if($agent->status === 'active')
                            <span class="badge badge-success"><i class="fas fa-circle" style="font-size:7px"></i> نشط</span>
                        @else
                            <span class="badge badge-danger"><i class="fas fa-circle" style="font-size:7px"></i> موقوف</span>
                        @endif
                    </td>
                    <td style="color:var(--text-muted);font-size:13px">{{ $agent->created_at->format('Y-m-d') }}</td>
                    <td>
                        <div style="display:flex;gap:6px;flex-wrap:wrap">
                            <a href="{{ route('superadmin.agents.show', $agent) }}" class="btn btn-sm btn-primary"><i class="fas fa-eye"></i></a>
                            <a href="{{ route('superadmin.agents.edit', $agent) }}" class="btn btn-sm btn-gold"><i class="fas fa-edit"></i></a>

                            @if($agent->status === 'active')
                            <form action="{{ route('superadmin.agents.suspend', $agent) }}" method="POST" style="display:inline">
                                @csrf @method('PATCH')
                                <button class="btn btn-sm" style="background:#fef3c7;color:#92400e;border:1px solid #fde68a" title="تعليق الحساب"
                                    onclick="return confirm('تعليق حساب {{ $agent->name }}؟')">
                                    <i class="fas fa-ban"></i>
                                </button>
                            </form>
                            @else
                            <form action="{{ route('superadmin.agents.activate', $agent) }}" method="POST" style="display:inline">
                                @csrf @method('PATCH')
                                <button class="btn btn-sm" style="background:#d1fae5;color:#065f46;border:1px solid #a7f3d0" title="تفعيل الحساب"
                                    onclick="return confirm('تفعيل حساب {{ $agent->name }}؟')">
                                    <i class="fas fa-check-circle"></i>
                                </button>
                            </form>
                            @endif

                            <form action="{{ route('superadmin.agents.destroy', $agent) }}" method="POST" style="display:inline">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-danger" onclick="return confirm('حذف؟')"><i class="fas fa-trash"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" style="text-align:center;padding:40px;color:var(--text-muted)">لا يوجد مناديب بعد</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($agents->hasPages())<div style="padding:16px">{{ $agents->links() }}</div>@endif
</div>
@endsection
