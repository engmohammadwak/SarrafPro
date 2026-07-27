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
            <thead>
                <tr>
                    <th>#</th>
                    <th>الاسم</th>
                    <th>الهاتف</th>
                    <th>الدولة</th>
                    <th>الشركة</th>
                    <th>الرصيد</th>
                    <th>الحالة</th>
                    <th>إجراءات</th>
                </tr>
            </thead>
            <tbody>
            @forelse($agents as $a)
            @php
                $userSuspended = $a->user && $a->user->status === 'suspended';
            @endphp
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td style="font-weight:600">{{ $a->name }}</td>
                <td>{{ $a->phone ?? '-' }}</td>
                <td>{{ $a->country ?? '-' }}</td>
                <td>{{ $a->company ?? '-' }}</td>
                <td style="font-weight:700;color:var(--accent)">{{ number_format($a->balance,4) }}</td>

                {{-- عمود الحالة --}}
                <td>
                    <div style="display:flex;flex-direction:column;gap:5px">

                        {{-- شارة التفعيل: شغال / موقوف (سوبر ادمن) / معطل --}}
                        @if($userSuspended)
                            <span style="display:inline-flex;align-items:center;gap:5px;font-size:11px;font-weight:600;padding:3px 9px;border-radius:20px;background:rgba(239,68,68,0.12);color:#dc2626;width:fit-content;white-space:nowrap">
                                <i class="fas fa-ban" style="font-size:9px"></i> موقوف
                            </span>
                        @elseif($a->is_active)
                            <span style="display:inline-flex;align-items:center;gap:5px;font-size:11px;font-weight:600;padding:3px 9px;border-radius:20px;background:rgba(34,197,94,0.13);color:#15803d;width:fit-content;white-space:nowrap">
                                <i class="fas fa-circle" style="font-size:6px"></i> شغال
                            </span>
                        @else
                            <span style="display:inline-flex;align-items:center;gap:5px;font-size:11px;font-weight:600;padding:3px 9px;border-radius:20px;background:rgba(107,114,128,0.13);color:#6b7280;width:fit-content;white-space:nowrap">
                                <i class="fas fa-circle" style="font-size:6px"></i> معطل
                            </span>
                        @endif

                        {{-- شارة حالة الحساب --}}
                        @if(! $a->user_id)
                            <span style="display:inline-flex;align-items:center;gap:5px;font-size:11px;font-weight:600;padding:3px 9px;border-radius:20px;background:rgba(251,146,60,0.14);color:#c2410c;width:fit-content;white-space:nowrap">
                                <i class="fas fa-user-slash" style="font-size:9px"></i> بدون حساب
                            </span>
                        @elseif($a->link_status === 'pending')
                            <span style="display:inline-flex;align-items:center;gap:5px;font-size:11px;font-weight:600;padding:3px 9px;border-radius:20px;background:rgba(251,191,36,0.16);color:#b45309;width:fit-content;white-space:nowrap">
                                <i class="fas fa-hourglass-half" style="font-size:9px"></i> لم يوافق بعد
                            </span>
                        @elseif($a->link_status === 'approved')
                            <span style="display:inline-flex;align-items:center;gap:5px;font-size:11px;font-weight:600;padding:3px 9px;border-radius:20px;background:rgba(59,130,246,0.13);color:#1d4ed8;width:fit-content;white-space:nowrap">
                                <i class="fas fa-link" style="font-size:9px"></i> مربوط
                            </span>
                        @elseif($a->link_status === 'rejected')
                            <span style="display:inline-flex;align-items:center;gap:5px;font-size:11px;font-weight:600;padding:3px 9px;border-radius:20px;background:rgba(239,68,68,0.12);color:#dc2626;width:fit-content;white-space:nowrap">
                                <i class="fas fa-unlink" style="font-size:9px"></i> مرفوض
                            </span>
                        @endif

                    </div>
                </td>

                <td>
                    <div style="display:flex;gap:6px;flex-wrap:wrap">
                        <a href="{{ route('admin.agents.edit',$a) }}" class="btn btn-sm btn-gold" title="تعديل"><i class="fas fa-edit"></i></a>
                        <form action="{{ route('admin.agents.destroy',$a) }}" method="POST" style="display:inline">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-danger" onclick="return confirm('حذف هذا المندوب؟')" title="حذف"><i class="fas fa-trash"></i></button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="8" style="text-align:center;padding:40px;color:var(--text-muted)">لا يوجد مناديب</td></tr>
            @endforelse
            </tbody>
        </table>
        </div>
    </div>
    @if($agents->hasPages())<div style="padding:16px">{{ $agents->links() }}</div>@endif
</div>
@endsection
