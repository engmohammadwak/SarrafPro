@extends('layouts.superadmin')
@section('title', 'إدارة المستخدمين - صراف برو')
@section('page-title', 'إدارة المستخدمين')

@section('content')

@if(session('success'))
<div style="background:rgba(34,197,94,0.1);border:1px solid rgba(34,197,94,0.3);color:#22c55e;padding:12px 16px;border-radius:8px;margin-bottom:20px;">
    <i class="fas fa-check-circle" style="margin-left:8px;"></i>{{ session('success') }}
</div>
@endif

<div class="card">
    <div class="card-header">
        <h3><i class="fas fa-users" style="color:var(--accent);margin-left:8px;"></i> قائمة المستخدمين</h3>
        <a href="{{ route('superadmin.users.create') }}" class="btn btn-gold btn-sm">
            <i class="fas fa-user-plus"></i> إضافة مستخدم
        </a>
    </div>
    <div class="card-body" style="padding:0;">
        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>الاسم</th>
                        <th>Username</th>
                        <th>البريد الإلكتروني</th>
                        <th>الدور</th>
                        <th>تاريخ التسجيل</th>
                        <th>الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                    <tr>
                        <td style="color:var(--text-muted);font-size:13px;">{{ $loop->iteration }}</td>
                        <td style="font-weight:600;">{{ $user->name }}</td>
                        <td>
                            @if($user->username)
                                <span style="background:#f3f4f6;padding:3px 10px;border-radius:6px;font-size:13px;font-family:monospace;color:#374151;">&#64;{{ $user->username }}</span>
                            @else
                                <span style="color:#d1d5db;font-size:13px;">—</span>
                            @endif
                        </td>
                        <td style="color:var(--text-muted);">{{ $user->email }}</td>
                        <td>
                            @php
                                $roleColors = ['super_admin'=>'gold','shop_admin'=>'success','agent'=>'info','staff'=>'warning'];
                                $roleLabels = ['super_admin'=>'سوبر ادمن','shop_admin'=>'مدير محل','agent'=>'مندوب','staff'=>'موظف'];
                            @endphp
                            <span class="badge badge-{{ $roleColors[$user->role] ?? 'warning' }}">
                                {{ $roleLabels[$user->role] ?? $user->role }}
                            </span>
                        </td>
                        <td style="color:var(--text-muted);font-size:13px;">{{ $user->created_at->format('Y-m-d') }}</td>
                        <td style="display:flex;gap:6px;">
                            <a href="{{ route('superadmin.users.show', $user) }}" class="btn btn-sm btn-primary" title="عرض">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('superadmin.users.edit', $user) }}" class="btn btn-sm btn-gold" title="تعديل">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('superadmin.users.destroy', $user) }}" method="POST" style="display:inline;">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" title="حذف"
                                    onclick="return confirm('هل أنت متأكد من حذف هذا المستخدم؟')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" style="text-align:center;padding:40px;color:var(--text-muted);">
                            <i class="fas fa-users" style="font-size:32px;opacity:0.3;display:block;margin-bottom:10px;"></i>
                            لا يوجد مستخدمون مسجلون بعد
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($users->hasPages())
    <div style="padding:16px;">{{ $users->links() }}</div>
    @endif
</div>
@endsection
