@extends('layouts.superadmin')
@section('title', 'إدارة المحلات - صراف برو')
@section('page-title', 'إدارة المحلات')

@section('content')

@if(session('success'))
<div class="alert alert-success" style="background:rgba(34,197,94,0.1);border:1px solid rgba(34,197,94,0.3);color:#22c55e;padding:12px 16px;border-radius:8px;margin-bottom:20px;">
    <i class="fas fa-check-circle" style="margin-left:8px;"></i>{{ session('success') }}
</div>
@endif

<div class="card">
    <div class="card-header">
        <h3><i class="fas fa-store" style="color:var(--accent);margin-left:8px;"></i> قائمة المحلات</h3>
    </div>
    <div class="card-body" style="padding:0;">
        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>اسم المحل</th>
                        <th>المدير</th>
                        <th>المدينة</th>
                        <th>الهاتف</th>
                        <th>الحالة</th>
                        <th>الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($shops as $shop)
                    <tr>
                        <td style="color:var(--text-muted);font-size:13px;">{{ $loop->iteration }}</td>
                        <td>
                            <div style="font-weight:600;">{{ $shop->name }}</div>
                        </td>
                        <td>{{ $shop->admin->name ?? 'غير محدد' }}</td>
                        <td>{{ $shop->city ?? '-' }}</td>
                        <td>{{ $shop->phone ?? '-' }}</td>
                        <td>
                            @if($shop->status === 'active')
                                <span class="badge badge-success"><i class="fas fa-circle" style="font-size:8px;"></i> نشط</span>
                            @elseif($shop->status === 'suspended')
                                <span class="badge badge-danger"><i class="fas fa-circle" style="font-size:8px;"></i> موقوف</span>
                            @else
                                <span class="badge badge-warning"><i class="fas fa-circle" style="font-size:8px;"></i> معلق</span>
                            @endif
                        </td>
                        <td style="display:flex;gap:6px;">
                            <a href="{{ route('superadmin.shops.show', $shop) }}" class="btn btn-sm btn-primary" title="عرض">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('superadmin.shops.edit', $shop) }}" class="btn btn-sm btn-gold" title="تعديل">
                                <i class="fas fa-edit"></i>
                            </a>
                            @if($shop->status === 'active')
                            <form action="{{ route('superadmin.shops.suspend', $shop) }}" method="POST" style="display:inline;">
                                @csrf @method('PATCH')
                                <button type="submit" class="btn btn-sm btn-danger" title="تعليق" onclick="return confirm('هل تريد تعليق هذا المحل؟')">
                                    <i class="fas fa-ban"></i>
                                </button>
                            </form>
                            @else
                            <form action="{{ route('superadmin.shops.activate', $shop) }}" method="POST" style="display:inline;">
                                @csrf @method('PATCH')
                                <button type="submit" class="btn btn-sm btn-success" title="تفعيل">
                                    <i class="fas fa-check"></i>
                                </button>
                            </form>
                            @endif
                            <form action="{{ route('superadmin.shops.destroy', $shop) }}" method="POST" style="display:inline;">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" title="حذف" onclick="return confirm('هل أنت متأكد من حذف هذا المحل؟')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" style="text-align:center;padding:40px;color:var(--text-muted);">
                            <i class="fas fa-store" style="font-size:32px;opacity:0.3;display:block;margin-bottom:10px;"></i>
                            لا توجد محلات مسجلة بعد
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($shops->hasPages())
    <div class="card-footer" style="padding:16px;">
        {{ $shops->links() }}
    </div>
    @endif
</div>
@endsection
