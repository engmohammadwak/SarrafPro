<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- ❌ منع المتصفح من تخزين الصفحة (bfcache & cache) -->
    <meta http-equiv="Cache-Control" content="no-store, no-cache, must-revalidate, max-age=0">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <title>@yield('title', 'صراف برو')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        *{margin:0;padding:0;box-sizing:border-box}
        :root{--primary:#1a1f3c;--accent:#c9a84c;--accent-light:#f0d080;--sidebar-bg:#12172b;--sidebar-width:260px;--card-bg:#fff;--body-bg:#f4f6fb;--text-dark:#1a1f3c;--text-muted:#6b7280;--success:#10b981;--danger:#ef4444;--warning:#f59e0b;--info:#3b82f6;--border:#e5e7eb;--shadow:0 2px 15px rgba(0,0,0,0.08);--shadow-lg:0 8px 30px rgba(0,0,0,0.12)}
        body{font-family:Tajawal,sans-serif;background:var(--body-bg);color:var(--text-dark);min-height:100vh;display:flex}
        .sidebar{width:var(--sidebar-width);background:var(--sidebar-bg);min-height:100vh;position:fixed;right:0;top:0;z-index:1000;display:flex;flex-direction:column;transition:transform .3s;box-shadow:-4px 0 20px rgba(0,0,0,.3)}
        .sidebar-logo{padding:28px 24px 20px;border-bottom:1px solid rgba(255,255,255,.07);display:flex;align-items:center;gap:12px}
        .sidebar-logo .logo-icon{width:44px;height:44px;background:linear-gradient(135deg,var(--accent),var(--accent-light));border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:20px;color:var(--primary);font-weight:800;flex-shrink:0}
        .sidebar-logo .logo-text h2{color:#fff;font-size:18px;font-weight:800;line-height:1.2}
        .sidebar-logo .logo-text span{color:var(--accent);font-size:11px}
        .sidebar-menu{flex:1;padding:16px 0;overflow-y:auto}
        .menu-section-title{padding:12px 24px 6px;font-size:10px;font-weight:700;color:rgba(255,255,255,.3);text-transform:uppercase;letter-spacing:1px}
        .sidebar-menu a{display:flex;align-items:center;gap:12px;padding:12px 24px;color:rgba(255,255,255,.65);text-decoration:none;font-size:14.5px;font-weight:500;transition:all .2s;border-right:3px solid transparent;margin:1px 0}
        .sidebar-menu a:hover,.sidebar-menu a.active{background:rgba(201,168,76,.1);color:var(--accent);border-right-color:var(--accent)}
        .sidebar-menu a i{width:20px;text-align:center;font-size:16px}
        .sidebar-footer{padding:16px 24px;border-top:1px solid rgba(255,255,255,.07)}
        .sidebar-footer a{display:flex;align-items:center;gap:10px;color:rgba(255,255,255,.5);text-decoration:none;font-size:13.5px;padding:10px 12px;border-radius:10px;transition:all .2s}
        .sidebar-footer a:hover{background:rgba(239,68,68,.15);color:#ef4444}
        .main-content{margin-right:var(--sidebar-width);flex:1;display:flex;flex-direction:column;min-height:100vh}
        .topbar{background:#fff;padding:0 28px;height:68px;display:flex;align-items:center;justify-content:space-between;box-shadow:0 1px 10px rgba(0,0,0,.06);position:sticky;top:0;z-index:100}
        .topbar-left{display:flex;align-items:center;gap:16px}
        .topbar-left h1{font-size:20px;font-weight:700}
        .topbar-right{display:flex;align-items:center;gap:16px}
        .topbar-badge{background:linear-gradient(135deg,var(--primary),#2d3561);color:#fff;padding:8px 18px;border-radius:50px;font-size:13px;font-weight:600;display:flex;align-items:center;gap:8px}
        .topbar-badge .dot{width:8px;height:8px;background:var(--accent);border-radius:50%;animation:pulse 2s infinite}
        @keyframes pulse{0%,100%{opacity:1}50%{opacity:.4}}
        .btn-toggle-sidebar{display:none;background:none;border:none;font-size:22px;cursor:pointer;color:var(--text-dark)}
        .page-content{padding:28px;flex:1}
        .card{background:var(--card-bg);border-radius:16px;box-shadow:var(--shadow);overflow:hidden}
        .card-header{padding:18px 24px;border-bottom:1px solid var(--border);display:flex;align-items:center;justify-content:space-between}
        .card-header h3{font-size:16px;font-weight:700}
        .card-body{padding:24px}
        .stats-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:20px;margin-bottom:28px}
        .stat-card{background:var(--card-bg);border-radius:16px;padding:24px;box-shadow:var(--shadow);display:flex;align-items:center;gap:18px;transition:transform .2s,box-shadow .2s;border-right:4px solid transparent}
        .stat-card:hover{transform:translateY(-3px);box-shadow:var(--shadow-lg)}
        .stat-card.gold{border-right-color:var(--accent)}.stat-card.green{border-right-color:var(--success)}
        .stat-card.blue{border-right-color:var(--info)}.stat-card.red{border-right-color:var(--danger)}
        .stat-icon{width:56px;height:56px;border-radius:14px;display:flex;align-items:center;justify-content:center;font-size:22px;flex-shrink:0}
        .stat-card.gold .stat-icon{background:rgba(201,168,76,.12);color:var(--accent)}
        .stat-card.green .stat-icon{background:rgba(16,185,129,.12);color:var(--success)}
        .stat-card.blue .stat-icon{background:rgba(59,130,246,.12);color:var(--info)}
        .stat-card.red .stat-icon{background:rgba(239,68,68,.12);color:var(--danger)}
        .stat-info h4{font-size:26px;font-weight:800}.stat-info p{font-size:13px;color:var(--text-muted);margin-top:3px}
        .table-wrapper{overflow-x:auto}
        table{width:100%;border-collapse:collapse}
        table th{background:#f8f9fc;padding:13px 16px;text-align:right;font-size:12px;font-weight:700;color:var(--text-muted);text-transform:uppercase;letter-spacing:.5px;border-bottom:1px solid var(--border)}
        table td{padding:14px 16px;border-bottom:1px solid var(--border);font-size:14px;vertical-align:middle}
        table tr:last-child td{border-bottom:none}table tr:hover td{background:#fafbff}
        .badge{display:inline-flex;align-items:center;gap:4px;padding:4px 12px;border-radius:50px;font-size:12px;font-weight:600}
        .badge-success{background:rgba(16,185,129,.12);color:var(--success)}.badge-danger{background:rgba(239,68,68,.12);color:var(--danger)}
        .badge-warning{background:rgba(245,158,11,.12);color:var(--warning)}.badge-info{background:rgba(59,130,246,.12);color:var(--info)}
        .badge-gold{background:rgba(201,168,76,.12);color:var(--accent)}
        .btn{display:inline-flex;align-items:center;gap:8px;padding:10px 20px;border-radius:10px;font-size:14px;font-weight:600;font-family:Tajawal,sans-serif;cursor:pointer;border:none;text-decoration:none;transition:all .2s}
        .btn-primary{background:var(--primary);color:#fff}.btn-primary:hover{background:#2d3561}
        .btn-gold{background:linear-gradient(135deg,var(--accent),var(--accent-light));color:var(--primary)}.btn-gold:hover{opacity:.9}
        .btn-sm{padding:6px 14px;font-size:12.5px}
        .btn-success{background:rgba(16,185,129,.1);color:var(--success)}.btn-success:hover{background:var(--success);color:#fff}
        .btn-danger{background:rgba(239,68,68,.1);color:var(--danger)}.btn-danger:hover{background:var(--danger);color:#fff}
        .sidebar-overlay{display:none;position:fixed;inset:0;background:rgba(0,0,0,.5);z-index:999}
        @media(max-width:768px){.sidebar{transform:translateX(100%)}.sidebar.open{transform:translateX(0)}.sidebar-overlay.show{display:block}.main-content{margin-right:0}.page-content{padding:16px}.btn-toggle-sidebar{display:block}.topbar{padding:0 16px}}
    </style>
    @stack('styles')
</head>
<body>
<div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleSidebar()"></div>
<aside class="sidebar" id="sidebar">
    <div class="sidebar-logo">
        <div class="logo-icon">ص</div>
        <div class="logo-text"><h2>صراف برو</h2><span>Super Admin</span></div>
    </div>
    <nav class="sidebar-menu">
        <div class="menu-section-title">الرئيسية</div>
        <a href="{{ route('superadmin.dashboard') }}" class="{{ request()->routeIs('superadmin.dashboard') ? 'active' : '' }}">
            <i class="fas fa-th-large"></i> لوحة التحكم
        </a>
        <div class="menu-section-title">الإدارة</div>
        <a href="{{ route('superadmin.shops.index') }}" class="{{ request()->routeIs('superadmin.shops.*') ? 'active' : '' }}">
            <i class="fas fa-store"></i> المحلات
        </a>
        <a href="{{ route('superadmin.agents.index') }}" class="{{ request()->routeIs('superadmin.agents.*') ? 'active' : '' }}">
            <i class="fas fa-id-badge"></i> المناديب
        </a>
        <a href="{{ route('superadmin.users.index') }}" class="{{ request()->routeIs('superadmin.users.*') ? 'active' : '' }}">
            <i class="fas fa-user-shield"></i> مستخدمو النظام
        </a>
        <div class="menu-section-title">التقارير</div>
        <a href="#"><i class="fas fa-chart-bar"></i> التقارير</a>
        <a href="#"><i class="fas fa-history"></i> السجلات</a>
    </nav>
    <div class="sidebar-footer">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" style="background:none;border:none;width:100%;cursor:pointer">
                <a href="#" onclick="this.closest('form').submit();return false">
                    <i class="fas fa-sign-out-alt"></i> تسجيل الخروج
                </a>
            </button>
        </form>
    </div>
</aside>
<div class="main-content">
    <header class="topbar">
        <div class="topbar-left">
            <button class="btn-toggle-sidebar" onclick="toggleSidebar()"><i class="fas fa-bars"></i></button>
            <h1>@yield('page-title', 'لوحة التحكم')</h1>
        </div>
        <div class="topbar-right">
            <div class="topbar-badge"><div class="dot"></div><i class="fas fa-user-shield"></i> {{ auth()->user()->name }}</div>
        </div>
    </header>
    <main class="page-content">
        @if(session('success'))
        <div style="background:rgba(16,185,129,.1);border:1px solid rgba(16,185,129,.3);color:#065f46;padding:14px 20px;border-radius:12px;margin-bottom:20px;display:flex;align-items:center;gap:10px">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
        </div>
        @endif
        @if(session('error'))
        <div style="background:rgba(239,68,68,.1);border:1px solid rgba(239,68,68,.3);color:#991b1b;padding:14px 20px;border-radius:12px;margin-bottom:20px;display:flex;align-items:center;gap:10px">
            <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
        </div>
        @endif
        @yield('content')
    </main>
</div>
<script>
function toggleSidebar(){
    document.getElementById('sidebar').classList.toggle('open');
    document.getElementById('sidebarOverlay').classList.toggle('show');
}

// ✅ منع bfcache: أعد تحميل الصفحة عند الرجوع إليها بزر Back
window.addEventListener('pageshow', function(e) {
    if (e.persisted) {
        window.location.reload();
    }
});
</script>
@stack('scripts')
</body>
</html>
