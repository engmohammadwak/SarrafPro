<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'لوحة المندوب - صراف برو')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        :root {
            --primary: #1a1f3c; --accent: #c9a84c; --accent-light: #f0d080;
            --sidebar-bg: #12172b; --sidebar-width: 260px;
            --card-bg: #ffffff; --body-bg: #f4f6fb;
            --text-dark: #1a1f3c; --text-muted: #6b7280;
            --success: #10b981; --danger: #ef4444; --warning: #f59e0b; --info: #3b82f6;
            --border: #e5e7eb; --shadow: 0 2px 15px rgba(0,0,0,0.08); --shadow-lg: 0 8px 30px rgba(0,0,0,0.12);
        }
        body { font-family: 'Tajawal', sans-serif; background: var(--body-bg); color: var(--text-dark); min-height: 100vh; display: flex; }

        /* ===== SIDEBAR ===== */
        .sidebar { width: var(--sidebar-width); background: var(--sidebar-bg); min-height: 100vh; position: fixed; right: 0; top: 0; z-index: 1000; display: flex; flex-direction: column; transition: transform 0.3s ease; box-shadow: -4px 0 20px rgba(0,0,0,0.3); }
        .sidebar-logo { padding: 28px 24px 20px; border-bottom: 1px solid rgba(255,255,255,0.07); display: flex; align-items: center; gap: 12px; }
        .sidebar-logo .logo-icon { width: 44px; height: 44px; background: linear-gradient(135deg, var(--accent), var(--accent-light)); border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 20px; color: var(--primary); font-weight: 800; flex-shrink: 0; }
        .sidebar-logo .logo-text h2 { color: #fff; font-size: 15px; font-weight: 800; line-height: 1.2; }
        .sidebar-logo .logo-text span { color: var(--accent); font-size: 11px; }
        .sidebar-menu { flex: 1; padding: 16px 0; overflow-y: auto; }
        .menu-section-title { padding: 12px 24px 6px; font-size: 10px; font-weight: 700; color: rgba(255,255,255,0.3); text-transform: uppercase; letter-spacing: 1px; }
        .sidebar-menu a { display: flex; align-items: center; gap: 12px; padding: 12px 24px; color: rgba(255,255,255,0.65); text-decoration: none; font-size: 14.5px; font-weight: 500; transition: all 0.2s; border-right: 3px solid transparent; }
        .sidebar-menu a:hover, .sidebar-menu a.active { background: rgba(201,168,76,0.1); color: var(--accent); border-right-color: var(--accent); }
        .sidebar-menu a i { width: 20px; text-align: center; font-size: 16px; }
        .sidebar-menu a .badge-count { margin-right: auto; background: var(--danger); color: #fff; font-size: 11px; font-weight: 700; padding: 2px 7px; border-radius: 20px; }
        .sidebar-footer { padding: 16px 24px; border-top: 1px solid rgba(255,255,255,0.07); }

        /* ===== MAIN ===== */
        .main-content { margin-right: var(--sidebar-width); flex: 1; display: flex; flex-direction: column; min-height: 100vh; }
        .topbar { background: #fff; padding: 0 28px; height: 68px; display: flex; align-items: center; justify-content: space-between; box-shadow: 0 1px 10px rgba(0,0,0,0.06); position: sticky; top: 0; z-index: 100; }
        .topbar-left { display: flex; align-items: center; gap: 16px; }
        .topbar-left h1 { font-size: 20px; font-weight: 700; }
        .topbar-badge { background: linear-gradient(135deg, var(--primary), #2d3561); color: #fff; padding: 8px 18px; border-radius: 50px; font-size: 13px; font-weight: 600; display: flex; align-items: center; gap: 8px; }
        .topbar-badge .dot { width: 8px; height: 8px; background: var(--accent); border-radius: 50%; animation: pulse 2s infinite; }
        @keyframes pulse { 0%,100%{opacity:1} 50%{opacity:0.4} }
        .btn-toggle-sidebar { display: none; background: none; border: none; font-size: 22px; cursor: pointer; color: var(--text-dark); }
        .page-content { padding: 28px; flex: 1; }

        /* ===== CARDS ===== */
        .card { background: var(--card-bg); border-radius: 16px; box-shadow: var(--shadow); overflow: hidden; }
        .card-header { padding: 18px 24px; border-bottom: 1px solid var(--border); display: flex; align-items: center; justify-content: space-between; }
        .card-header h3 { font-size: 16px; font-weight: 700; }
        .card-body { padding: 24px; }
        .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px,1fr)); gap: 20px; margin-bottom: 28px; }
        .stat-card { background: var(--card-bg); border-radius: 16px; padding: 24px; box-shadow: var(--shadow); display: flex; align-items: center; gap: 18px; border-right: 4px solid transparent; transition: transform 0.2s, box-shadow 0.2s; }
        .stat-card:hover { transform: translateY(-3px); box-shadow: var(--shadow-lg); }
        .stat-card.gold  { border-right-color: var(--accent); }  .stat-card.green { border-right-color: var(--success); }
        .stat-card.blue  { border-right-color: var(--info); }    .stat-card.red   { border-right-color: var(--danger); }
        .stat-icon { width: 56px; height: 56px; border-radius: 14px; display: flex; align-items: center; justify-content: center; font-size: 22px; flex-shrink: 0; }
        .stat-card.gold  .stat-icon { background: rgba(201,168,76,0.12); color: var(--accent); }
        .stat-card.green .stat-icon { background: rgba(16,185,129,0.12); color: var(--success); }
        .stat-card.blue  .stat-icon { background: rgba(59,130,246,0.12); color: var(--info); }
        .stat-card.red   .stat-icon { background: rgba(239,68,68,0.12);  color: var(--danger); }
        .stat-info h4 { font-size: 26px; font-weight: 800; } .stat-info p { font-size: 13px; color: var(--text-muted); margin-top: 3px; }

        /* ===== TABLE ===== */
        .table-wrapper { overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; }
        table th { background: #f8f9fc; padding: 13px 16px; text-align: right; font-size: 12px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; border-bottom: 1px solid var(--border); }
        table td { padding: 14px 16px; border-bottom: 1px solid var(--border); font-size: 14px; vertical-align: middle; }
        table tr:last-child td { border-bottom: none; } table tr:hover td { background: #fafbff; }

        /* ===== BADGES / BTNS ===== */
        .badge { display: inline-flex; align-items: center; gap: 4px; padding: 4px 12px; border-radius: 50px; font-size: 12px; font-weight: 600; }
        .badge-success { background: rgba(16,185,129,0.12); color: var(--success); }
        .badge-danger  { background: rgba(239,68,68,0.12);  color: var(--danger); }
        .badge-warning { background: rgba(245,158,11,0.12); color: var(--warning); }
        .badge-info    { background: rgba(59,130,246,0.12); color: var(--info); }
        .badge-gold    { background: rgba(201,168,76,0.12); color: var(--accent); }
        .btn { display: inline-flex; align-items: center; gap: 8px; padding: 10px 20px; border-radius: 10px; font-size: 14px; font-weight: 600; font-family: 'Tajawal', sans-serif; cursor: pointer; border: none; text-decoration: none; transition: all 0.2s; }
        .btn-gold { background: linear-gradient(135deg, var(--accent), var(--accent-light)); color: var(--primary); } .btn-gold:hover { opacity: 0.9; }
        .btn-sm { padding: 6px 14px; font-size: 12.5px; }

        /* ===== RESPONSIVE ===== */
        .sidebar-overlay { display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 999; }
        @media (max-width: 768px) {
            .sidebar { transform: translateX(100%); } .sidebar.open { transform: translateX(0); }
            .sidebar-overlay.show { display: block; } .main-content { margin-right: 0; }
            .page-content { padding: 16px; } .btn-toggle-sidebar { display: block; }
        }
    </style>
    @stack('styles')
</head>
<body>
<div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleSidebar()"></div>
<aside class="sidebar" id="sidebar">
    <div class="sidebar-logo">
        <div class="logo-icon"><i class="fas fa-user-tie"></i></div>
        <div class="logo-text">
            <h2>{{ auth()->user()->name }}</h2>
            <span>لوحة المندوب</span>
        </div>
    </div>

    <nav class="sidebar-menu">
        <div class="menu-section-title">الرئيسية</div>
        <a href="{{ route('agent.dashboard') }}" class="{{ request()->routeIs('agent.dashboard') ? 'active' : '' }}">
            <i class="fas fa-th-large"></i> لوحة التحكم
        </a>

        <div class="menu-section-title">النشاط</div>
        <a href="{{ route('agent.transactions') }}" class="{{ request()->routeIs('agent.transactions') ? 'active' : '' }}">
            <i class="fas fa-exchange-alt"></i> المعاملات
        </a>
        <a href="{{ route('agent.notifications') }}" class="{{ request()->routeIs('agent.notifications') ? 'active' : '' }}">
            <i class="fas fa-bell"></i> الإشعارات
        </a>

        <div class="menu-section-title">التحليل</div>
        <a href="{{ route('agent.reports') }}" class="{{ request()->routeIs('agent.reports') ? 'active' : '' }}">
            <i class="fas fa-chart-bar"></i> التقارير
        </a>
    </nav>

    <div class="sidebar-footer">
        <form method="POST" action="{{ route('agent.logout') }}">
            @csrf
            <button type="submit" style="background:none;border:none;width:100%;cursor:pointer;font-family:Tajawal,sans-serif">
                <span style="display:flex;align-items:center;gap:10px;color:rgba(255,255,255,0.5);font-size:13.5px;padding:10px 12px;border-radius:10px;transition:all 0.2s;" onmouseover="this.style.background='rgba(239,68,68,0.15)';this.style.color='#ef4444'" onmouseout="this.style.background='none';this.style.color='rgba(255,255,255,0.5)'">
                    <i class="fas fa-sign-out-alt"></i> تسجيل الخروج
                </span>
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
        <div class="topbar-badge">
            <div class="dot"></div>
            <i class="fas fa-user-tie"></i>
            {{ auth()->user()->name }}
        </div>
    </header>

    <main class="page-content">
        @if(session('success'))
        <div style="background:rgba(16,185,129,0.1);border:1px solid rgba(16,185,129,0.3);color:#065f46;padding:14px 20px;border-radius:12px;margin-bottom:20px;display:flex;align-items:center;gap:10px;">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
        </div>
        @endif
        @if(session('error'))
        <div style="background:rgba(239,68,68,0.1);border:1px solid rgba(239,68,68,0.3);color:#991b1b;padding:14px 20px;border-radius:12px;margin-bottom:20px;display:flex;align-items:center;gap:10px;">
            <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
        </div>
        @endif
        @yield('content')
    </main>
</div>

<script>
function toggleSidebar() {
    document.getElementById('sidebar').classList.toggle('open');
    document.getElementById('sidebarOverlay').classList.toggle('show');
}
</script>
@stack('scripts')
</body>
</html>
