<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>لوحة المندوب</title>
<link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<style>
*{margin:0;padding:0;box-sizing:border-box}
body{font-family:Tajawal,sans-serif;background:#0f172a;color:#f1f5f9;min-height:100vh}
.topbar{background:#1e293b;border-bottom:1px solid #334155;padding:14px 28px;display:flex;align-items:center;justify-content:space-between}
.topbar h2{font-size:18px;color:#f59e0b}<br>.topbar .user{display:flex;align-items:center;gap:10px;font-size:14px;color:#94a3b8}
.main{padding:28px}
.grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:16px;margin-bottom:28px}
.card{background:#1e293b;border:1px solid #334155;border-radius:14px;padding:22px}
.card-stat{display:flex;align-items:center;gap:16px}
.card-stat .icon{width:50px;height:50px;border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:22px}
.card-stat .info h4{font-size:22px;font-weight:700}
.card-stat .info p{font-size:13px;color:#94a3b8;margin-top:2px}
.badge{display:inline-block;padding:3px 10px;border-radius:20px;font-size:12px;font-weight:600}
.badge-pending{background:rgba(245,158,11,0.15);color:#f59e0b}
.badge-approved{background:rgba(34,197,94,0.15);color:#22c55e}
.badge-rejected{background:rgba(239,68,68,0.15);color:#ef4444}
.badge-none{background:rgba(148,163,184,0.15);color:#94a3b8}
table{width:100%;border-collapse:collapse}
th,td{padding:12px 14px;text-align:right;border-bottom:1px solid #334155;font-size:14px}
th{color:#94a3b8;font-weight:600;font-size:13px}
.btn-logout{background:rgba(239,68,68,0.1);color:#ef4444;border:1px solid rgba(239,68,68,0.3);padding:7px 16px;border-radius:8px;font-family:Tajawal,sans-serif;font-size:13px;cursor:pointer}
</style>
</head>
<body>
<div class="topbar">
    <h2><i class="fas fa-user-tie" style="margin-left:8px"></i>لوحة المندوب</h2>
    <div class="user">
        <i class="fas fa-user-circle" style="font-size:20px"></i>
        {{ auth()->user()->name }}
        <form action="{{ route('agent.logout') }}" method="POST" style="display:inline">
            @csrf
            <button type="submit" class="btn-logout"><i class="fas fa-sign-out-alt"></i> خروج</button>
        </form>
    </div>
</div>

<div class="main">
    <div class="grid">
        <div class="card">
            <div class="card-stat">
                <div class="icon" style="background:rgba(245,158,11,0.15);color:#f59e0b"><i class="fas fa-store"></i></div>
                <div class="info"><h4>{{ $agents->count() }}</h4><p>إجمالي الربط</p></div>
            </div>
        </div>
        <div class="card">
            <div class="card-stat">
                <div class="icon" style="background:rgba(34,197,94,0.15);color:#22c55e"><i class="fas fa-check-circle"></i></div>
                <div class="info"><h4>{{ $approvedCount }}</h4><p>ربط موافق عليه</p></div>
            </div>
        </div>
        <div class="card">
            <div class="card-stat">
                <div class="icon" style="background:rgba(245,158,11,0.15);color:#f59e0b"><i class="fas fa-clock"></i></div>
                <div class="info"><h4>{{ $pendingCount }}</h4><p>بانتظار موافقة</p></div>
            </div>
        </div>
    </div>

    <div class="card">
        <div style="padding:16px 20px;border-bottom:1px solid #334155;font-weight:700;font-size:15px">
            <i class="fas fa-link" style="color:#f59e0b;margin-left:8px"></i>المحلات المرتبطة
        </div>
        @if($agents->isEmpty())
        <div style="padding:40px;text-align:center;color:#94a3b8">لا يوجد ربط حتى الآن</div>
        @else
        <table>
            <thead><tr><th>المحل</th><th>الدولة</th><th>حالة الربط</th></tr></thead>
            <tbody>
            @foreach($agents as $a)
            <tr>
                <td style="font-weight:600">{{ $a->shop->name ?? '-' }}</td>
                <td style="color:#94a3b8">{{ $a->shop->city ?? '-' }}</td>
                <td>
                    @if($a->link_status === 'approved')
                        <span class="badge badge-approved"><i class="fas fa-check"></i> موافق عليه</span>
                    @elseif($a->link_status === 'pending')
                        <span class="badge badge-pending"><i class="fas fa-clock"></i> بانتظار موافقة</span>
                    @elseif($a->link_status === 'rejected')
                        <span class="badge badge-rejected"><i class="fas fa-times"></i> مرفوض</span>
                    @else
                        <span class="badge badge-none">بدون ربط</span>
                    @endif
                </td>
            </tr>
            @endforeach
            </tbody>
        </table>
        @endif
    </div>
</div>
</body>
</html>
