<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>@yield('title', 'Dashboard Mahasiswa') – LabSystem</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;1,9..40,400&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
<style>
*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

:root {
    --bg:          #F7F7F5;
    --surface:     #FFFFFF;
    --border:      #E8E8E3;
    --text:        #18181B;
    --muted:       #8C8C8A;
    --accent:      #1A1A1A;
    --blue:        #2563EB;
    --blue-soft:   #EFF4FF;
    --violet:      #7C3AED;
    --violet-soft: #F5F3FF;
    --warn:        #D97706;
    --warn-soft:   #FFFBEB;
    --green:       #16A34A;
    --green-soft:  #F0FDF4;
    --red:         #DC2626;
    --red-soft:    #FEF2F2;
    --sidebar-w:   228px;
    --radius:      10px;
}

body { font-family: 'DM Sans', sans-serif; background: var(--bg); color: var(--text); font-size: 14px; line-height: 1.5; }

/* ── SIDEBAR ── */
.sidebar {
    position: fixed; top: 0; left: 0; bottom: 0;
    width: var(--sidebar-w);
    background: var(--surface);
    border-right: 1px solid var(--border);
    display: flex; flex-direction: column;
    padding: 24px 16px;
    z-index: 1000;
    transition: transform .25s ease;
    overflow-y: auto;
}

.sidebar-logo { display: flex; align-items: center; gap: 10px; padding: 0 4px; margin-bottom: 32px; flex-shrink: 0; }
.sidebar-logo .logo-icon { width: 32px; height: 32px; background: var(--accent); border-radius: 8px; display: grid; place-items: center; flex-shrink: 0; }
.sidebar-logo .logo-icon i { color: #fff; font-size: 15px; }
.sidebar-logo-text strong { display: block; font-size: 13px; font-weight: 600; color: var(--text); }
.sidebar-logo-text span   { font-size: 11px; color: var(--muted); }

.nav-section { font-size: 10px; font-weight: 600; letter-spacing: .08em; text-transform: uppercase; color: var(--muted); padding: 0 8px; margin-bottom: 6px; margin-top: 16px; }
.nav-section:first-of-type { margin-top: 0; }
.nav-item { list-style: none; }

.nav-link {
    display: flex; align-items: center; gap: 10px;
    padding: 8px 10px; border-radius: 7px;
    color: var(--muted); font-size: 13.5px; font-weight: 500;
    text-decoration: none; transition: background .15s, color .15s;
    margin-bottom: 1px; cursor: pointer;
    border: none; background: transparent; width: 100%; text-align: left;
}

.nav-link i { font-size: 15px; width: 18px; text-align: center; flex-shrink: 0; }
.nav-link:hover { background: var(--bg); color: var(--text); }
.nav-link.active { background: var(--accent); color: #fff; }
.nav-link.danger { color: var(--red); }
.nav-link.danger:hover { background: var(--red-soft); color: var(--red); }

.sidebar-spacer { flex: 1; }

.sidebar-user { margin-top: auto; padding: 12px 10px; background: var(--bg); border-radius: var(--radius); display: flex; align-items: center; gap: 10px; flex-shrink: 0; }
.sidebar-user .avatar { width: 32px; height: 32px; background: var(--blue); border-radius: 50%; display: grid; place-items: center; flex-shrink: 0; font-size: 13px; font-weight: 600; color: #fff; font-family: 'DM Mono', monospace; }
.sidebar-user-info strong { display: block; font-size: 12.5px; font-weight: 600; color: var(--text); }
.sidebar-user-info span   { display: block; font-size: 11px; color: var(--muted); }

/* ── TOPBAR ── */
.topbar { display: none; position: fixed; top: 0; left: 0; right: 0; height: 52px; background: var(--surface); border-bottom: 1px solid var(--border); align-items: center; justify-content: space-between; padding: 0 16px; z-index: 900; }
.topbar-title { font-size: 14px; font-weight: 600; }
.btn-icon { width: 36px; height: 36px; border: 1px solid var(--border); background: var(--surface); border-radius: 7px; display: grid; place-items: center; cursor: pointer; font-size: 16px; color: var(--text); }
.sidebar-overlay { display: none; position: fixed; inset: 0; background: rgba(0,0,0,.3); z-itabsndex: 999; }

/* ── MAIN ── */
.main { margin-left: var(--sidebar-w); padding: 32px 36px; min-height: 100vh; }

/* ── FLASH ── */
.flash { display: flex; align-items: center; gap: 10px; padding: 12px 16px; border-radius: var(--radius); font-size: 13.5px; font-weight: 500; margin-bottom: 20px; }
.flash-success { background: var(--green-soft); color: var(--green); border: 1px solid #bbf7d0; }
.flash-error   { background: var(--red-soft);   color: var(--red);   border: 1px solid #fecaca; }
.flash-warn    { background: var(--warn-soft);   color: var(--warn);  border: 1px solid #fde68a; }

/* ── PAGE HEADER ── */
.page-header { display: flex; align-items: flex-end; justify-content: space-between; margin-bottom: 24px; gap: 16px; }
.page-header h1 { font-size: 22px; font-weight: 600; letter-spacing: -.3px; }
.page-header p  { font-size: 13px; color: var(--muted); margin-top: 3px; }

/* ── TOOLBAR ── */
.toolbar { display: flex; align-items: center; gap: 10px; margin-bottom: 16px; flex-wrap: wrap; }
.search-wrap { display: flex; align-items: center; gap: 8px; background: var(--surface); border: 1px solid var(--border); border-radius: var(--radius); padding: 0 12px; height: 36px; flex: 1; max-width: 340px; }
.search-wrap i { color: var(--muted); font-size: 14px; }
.search-input { border: none; outline: none; font-family: 'DM Sans', sans-serif; font-size: 13.5px; color: var(--text); background: transparent; width: 100%; }
.search-input::placeholder { color: var(--muted); }
.row-count { font-size: 12.5px; color: var(--muted); font-family: 'DM Mono', monospace; white-space: nowrap; }

/* ── BUTTONS ── */
.btn-primary { display: inline-flex; align-items: center; gap: 7px; padding: 8px 16px; background: var(--accent); color: #fff; border: none; border-radius: var(--radius); font-family: 'DM Sans', sans-serif; font-size: 13.5px; font-weight: 500; cursor: pointer; text-decoration: none; transition: opacity .15s; white-space: nowrap; }
.btn-primary:hover { opacity: .85; color: #fff; }
.btn-secondary { display: inline-flex; align-items: center; gap: 7px; padding: 8px 14px; background: var(--surface); color: var(--text); border: 1px solid var(--border); border-radius: var(--radius); font-family: 'DM Sans', sans-serif; font-size: 13.5px; font-weight: 500; cursor: pointer; text-decoration: none; transition: background .15s; white-space: nowrap; }
.btn-secondary:hover { background: var(--bg); color: var(--text); }
.btn-blue { display: inline-flex; align-items: center; gap: 7px; padding: 8px 14px; background: var(--blue); color: #fff; border: none; border-radius: var(--radius); font-family: 'DM Sans', sans-serif; font-size: 13.5px; font-weight: 500; cursor: pointer; text-decoration: none; transition: opacity .15s; white-space: nowrap; }
.btn-blue:hover { opacity: .85; color: #fff; }

/* ── FILTER TABS ── */
.filter-tabs { display: flex; gap: 6px; flex-wrap: wrap; }

.filter-tab {
    padding: 6px 12px;
    border-radius: 100px;
    border: 1px solid var(--border);
    background: var(--surface);
    font-family: 'DM Sans', sans-serif;
    font-size: 12.5px;
    font-weight: 500;
    color: var(--muted);
    cursor: pointer;
    transition: background .15s, color .15s, border-color .15s;
}

.filter-tab:hover { background: var(--bg); color: var(--text); }
.filter-tab.active { background: var(--accent); color: #fff; border-color: var(--accent); }

/* ── TABLE ── */
.table-card { background: var(--surface); border: 1px solid var(--border); border-radius: var(--radius); overflow: auto; }
table { width: 100%; border-collapse: collapse; }
thead th { font-size: 11px; font-weight: 600; letter-spacing: .04em; text-transform: uppercase; color: var(--muted); padding: 11px 14px; border-bottom: 1px solid var(--border); text-align: left; white-space: nowrap; }
tbody td { padding: 13px 14px; border-bottom: 1px solid var(--border); font-size: 13.5px; vertical-align: middle; }
tbody tr:last-child td { border-bottom: none; }
tbody tr:hover { background: var(--bg); }

/* ── BADGE ── */
.badge { display: inline-flex; align-items: center; gap: 4px; padding: 3px 9px; border-radius: 100px; font-size: 11.5px; font-weight: 500; white-space: nowrap; }
.badge::before { content: ''; width: 5px; height: 5px; border-radius: 50%; flex-shrink: 0; }
.badge-menunggu  { background: var(--warn-soft);  color: var(--warn); }
.badge-menunggu::before  { background: var(--warn); }
.badge-disetujui { background: var(--green-soft); color: var(--green); }
.badge-disetujui::before { background: var(--green); }
.badge-ditolak   { background: var(--red-soft);   color: var(--red); }
.badge-ditolak::before   { background: var(--red); }
.badge-selesai   { background: var(--blue-soft);  color: var(--blue); }
.badge-selesai::before   { background: var(--blue); }
.badge-default   { background: var(--bg); color: var(--muted); border: 1px solid var(--border); }
.badge-default::before   { background: var(--muted); }
.badge-lab       { background: var(--blue-soft); color: var(--blue); }
.badge-lab::before { background: var(--blue); }
.badge-barang    { background: var(--violet-soft); color: var(--violet); }
.badge-barang::before { background: var(--violet); }

/* ── ACTION BUTTON ── */
.btn-detail { display: inline-flex; align-items: center; gap: 5px; padding: 6px 12px; border: 1px solid var(--border); background: var(--surface); color: var(--text); font-family: 'DM Sans', sans-serif; font-size: 12.5px; font-weight: 500; border-radius: 6px; text-decoration: none; transition: background .15s; white-space: nowrap; }
.btn-detail:hover { background: var(--bg); color: var(--text); }

/* ── EMPTY STATE ── */
.empty-state { text-align: center; padding: 52px 24px; color: var(--muted); }
.empty-state .empty-icon { width: 52px; height: 52px; border-radius: 50%; background: var(--bg); border: 1px solid var(--border); display: grid; place-items: center; margin: 0 auto 14px; font-size: 22px; }
.empty-state p { font-size: 13.5px; }

/* ── MISC ── */
.mono { font-family: 'DM Mono', monospace; font-size: 12.5px; }
.time-range { font-family: 'DM Mono', monospace; font-size: 12px; color: var(--muted); background: var(--bg); border: 1px solid var(--border); padding: 2px 7px; border-radius: 5px; display: inline-block; white-space: nowrap; }

/* ── STAT CARDS ── */
.stat-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 14px; margin-bottom: 28px; }
.stat-card { background: var(--surface); border: 1px solid var(--border); border-radius: var(--radius); padding: 18px 20px; }
.stat-card-label { font-size: 11.5px; font-weight: 600; letter-spacing: .03em; color: var(--muted); margin-bottom: 8px; }
.stat-card-value { font-size: 28px; font-weight: 600; letter-spacing: -.5px; color: var(--text); font-family: 'DM Mono', monospace; line-height: 1; }
.stat-card-sub { font-size: 11.5px; color: var(--muted); margin-top: 4px; }
.stat-card.blue  .stat-card-value { color: var(--blue); }
.stat-card.green .stat-card-value { color: var(--green); }
.stat-card.warn  .stat-card-value { color: var(--warn); }
.stat-card.red   .stat-card-value { color: var(--red); }

/* ── FORM ── */
.form-card { background: var(--surface); border: 1px solid var(--border); border-radius: var(--radius); padding: 28px 32px; max-width: 640px; }
.field-group { display: flex; flex-direction: column; gap: 6px; margin-bottom: 16px; }
.field-row   { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; }
.field-group label { font-size: 12.5px; font-weight: 600; color: var(--text); }
.field-group input, .field-group select, .field-group textarea { height: 38px; padding: 0 12px; border: 1px solid var(--border); border-radius: 8px; font-family: 'DM Sans', sans-serif; font-size: 13.5px; color: var(--text); background: var(--surface); outline: none; transition: border-color .15s; }
.field-group textarea { height: auto; padding: 10px 12px; min-height: 80px; resize: vertical; }
.field-group input:focus, .field-group select:focus, .field-group textarea:focus { border-color: var(--accent); }
.field-group .field-error { font-size: 12px; color: var(--red); }
.form-actions { display: flex; align-items: center; justify-content: flex-end; gap: 8px; margin-top: 24px; padding-top: 20px; border-top: 1px solid var(--border); }
.section-divider { border: none; border-top: 1px solid var(--border); margin: 20px 0; }

/* ── CARD ── */
.card { background: var(--surface); border: 1px solid var(--border); border-radius: var(--radius); padding: 20px 24px; }
.card-header { display: flex; align-items: center; gap: 10px; margin-bottom: 16px; padding-bottom: 14px; border-bottom: 1px solid var(--border); }
.card-header-icon { width: 30px; height: 30px; background: var(--bg); border: 1px solid var(--border); border-radius: 8px; display: grid; place-items: center; font-size: 15px; }
.card-header h2 { font-size: 15px; font-weight: 600; }

/* ── INFO ITEM ── */
.info-item { display: flex; justify-content: space-between; align-items: flex-start; padding: 10px 0; border-bottom: 1px solid var(--border); }
.info-item:last-child { border-bottom: none; }
.info-label { font-size: 12.5px; color: var(--muted); font-weight: 500; min-width: 120px; }
.info-value { font-size: 13.5px; color: var(--text); text-align: right; word-break: break-word; }

/* ── BREADCRUMB ── */
.breadcrumb { display: flex; align-items: center; gap: 6px; font-size: 12.5px; color: var(--muted); margin-bottom: 16px; }
.breadcrumb a { color: var(--muted); text-decoration: none; }
.breadcrumb a:hover { color: var(--text); }
.breadcrumb i { font-size: 10px; }
.breadcrumb .current { color: var(--text); font-weight: 500; }

/* ── RESPONSIVE ── */
@media (max-width: 900px) { .stat-grid { grid-template-columns: repeat(2, 1fr); } }
@media (max-width: 768px) {
    .sidebar { transform: translateX(-100%); }
    .sidebar.show { transform: translateX(0); }
    .sidebar-overlay.show { display: block; }
    .topbar { display: flex; }
    .main { margin-left: 0; padding: 68px 16px 24px; }
    .stat-grid { grid-template-columns: 1fr 1fr; gap: 10px; }
    .page-header { flex-direction: column; align-items: flex-start; }
    .field-row { grid-template-columns: 1fr; }
    .form-card { padding: 20px; }
}
@media (max-width: 480px) { .stat-grid { grid-template-columns: 1fr; } }
</style>
@stack('styles')
</head>
<body>

<div class="sidebar-overlay" id="overlay"></div>

<header class="topbar">
    <button class="btn-icon" id="toggleSidebar"><i class="bi bi-list"></i></button>
    <span class="topbar-title">@yield('title', 'Dashboard')</span>
    <div style="width:36px"></div>
</header>

<aside class="sidebar" id="sidebar">
    <div class="sidebar-logo">
        <div class="logo-icon"><i class="bi bi-boxes"></i></div>
        <div class="sidebar-logo-text">
            <strong>LabSystem</strong>
            <span>Mahasiswa</span>
        </div>
    </div>

    <p class="nav-section">Menu</p>
    <ul style="list-style:none;padding:0;margin:0">
        <li class="nav-item">
            <a class="nav-link @if(request()->routeIs('mahasiswa.dashboard')) active @endif" href="{{ route('mahasiswa.dashboard') }}">
                <i class="bi bi-grid-1x2"></i> Dashboard
            </a>
        </li>
    </ul>

    <p class="nav-section">Peminjaman</p>
    <ul style="list-style:none;padding:0;margin:0">
        <li class="nav-item">
            <a class="nav-link @if(request()->routeIs('mahasiswa.peminjaman.create')) active @endif" href="{{ route('mahasiswa.peminjaman.create') }}">
                <i class="bi bi-plus-circle-fill"></i> Ajukan Peminjaman
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link @if(request()->routeIs('mahasiswa.peminjaman.riwayat')) active @endif" href="{{ route('mahasiswa.peminjaman.riwayat') }}">
                <i class="bi bi-clock-history"></i> Ongoing
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link @if(request()->routeIs('mahasiswa.peminjaman.arsip')) active @endif" href="{{ route('mahasiswa.peminjaman.arsip') }}">
                <i class="bi bi-archive-fill"></i> Arsip
            </a>
        </li>
    </ul>

    <div class="sidebar-spacer"></div>

    <ul style="list-style:none;padding:0;margin:0 0 12px">
        <li class="nav-item">
            <form action="{{ route('mahasiswa.logout') }}" method="POST" style="margin:0">
                @csrf
                <button type="submit" class="nav-link danger" onclick="return confirm('Yakin ingin logout?')">
                    <i class="bi bi-box-arrow-right"></i> Logout
                </button>
            </form>
        </li>
    </ul>

    <div class="sidebar-user">
        <div class="avatar">{{ strtoupper(substr(Auth::guard('mahasiswa')->user()->nama, 0, 1)) }}</div>
        <div class="sidebar-user-info">
            <strong>{{ Auth::guard('mahasiswa')->user()->nama }}</strong>
            <span>{{ Auth::guard('mahasiswa')->user()->nim }}</span>
        </div>
    </div>
</aside>

<main class="main">
    @if(session('success'))
        <div class="flash flash-success"><i class="bi bi-check-circle-fill"></i> {{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="flash flash-error"><i class="bi bi-exclamation-circle-fill"></i> {{ session('error') }}</div>
    @endif
    @if($errors->any())
        <div class="flash flash-error">
            <i class="bi bi-exclamation-circle-fill"></i>
            <div>@foreach($errors->all() as $error)<div>{{ $error }}</div>@endforeach</div>
        </div>
    @endif

    @yield('content')
</main>

<script>
const sidebar = document.getElementById('sidebar');
const overlay = document.getElementById('overlay');
const toggleBtn = document.getElementById('toggleSidebar');
if (toggleBtn) {
    toggleBtn.addEventListener('click', () => { sidebar.classList.toggle('show'); overlay.classList.toggle('show'); });
}
overlay.addEventListener('click', () => { sidebar.classList.remove('show'); overlay.classList.remove('show'); });
</script>
@stack('scripts')
</body>
</html>
