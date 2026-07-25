@extends('layouts.admin')
@section('title', 'العمليات')
@section('page-title', 'سجل العمليات')
@section('content')
<div class="card">
    <div class="card-header">
        <h3><i class="fas fa-exchange-alt" style="color:var(--accent);margin-left:8px"></i> العمليات</h3>
        <a href="{{ route('admin.transactions.create') }}" class="btn btn-gold btn-sm"><i class="fas fa-plus"></i> عملية جديدة</a>
    </div>
    <div class="card-body" style="padding:0">
        <div class="table-wrapper">
        <table>
            <thead><tr><th>مرجع</th><th>النوع</th><th>العميل</th><th>المبلغ</th><th>العملة</th><th>منفذ بواسطة</th><th>التاريخ</th><th></th></tr></thead>
            <tbody>
            @forelse($transactions as $t)
            <tr>
                <td style="font-family:monospace;font-size:12px">{{ $t->reference }}</td>
                <td>
                    @php $colors=['buy'=>'success','sell'=>'danger','transfer'=>'info','deposit'=>'warning','withdraw'=>'gold']; @endphp
                    <span class="badge badge-{{ $colors[$t->type]??'info' }}">
                        @php $labels=['buy'=>'شراء','sell'=>'بيع','transfer'=>'تحويل','deposit'=>'إيداع','withdraw'=>'سحب']; @endphp
                        {{ $labels[$t->type]??$t->type }}
                    </span>
                </td>
                <td>{{ $t->customer->name ?? '-' }}</td>
                <td style="font-weight:700">{{ number_format($t->amount,4) }}</td>
                <td>{{ $t->currency_from }} @if($t->currency_to) → {{ $t->currency_to }} @endif</td>
                <td style="font-size:13px">{{ $t->performer->name }}</td>
                <td style="font-size:13px;color:var(--text-muted)">{{ $t->created_at->format('Y-m-d H:i') }}</td>
                <td><a href="{{ route('admin.transactions.show',$t) }}" class="btn btn-sm btn-primary"><i class="fas fa-eye"></i></a></td>
            </tr>
            @empty
            <tr><td colspan="8" style="text-align:center;padding:40px;color:var(--text-muted)">لا توجد عمليات</td></tr>
            @endforelse
            </tbody>
        </table>
        </div>
    </div>
    @if($transactions->hasPages())<div style="padding:16px">{{ $transactions->links() }}</div>@endif
</div>
@endsection
