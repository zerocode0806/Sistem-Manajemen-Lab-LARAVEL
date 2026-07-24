<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>LabSystem – Reservasi Laboratorium</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@500;600;700&family=DM+Sans:wght@300;400;500;600&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
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
    --red:         #DC2626;
    --red-soft:    #FEF2F2;
    --green:       #16A34A;
    --radius:      10px;
}

html { scroll-behavior: smooth; }

body {
    font-family: 'DM Sans', sans-serif;
    background: var(--bg);
    color: var(--text);
    font-size: 14px;
    line-height: 1.5;
    min-height: 100vh;
    overflow-x: hidden;
}

/* ── PAGE SHELL ── */
.shell {
    min-height: 100vh;
    display: grid;
    grid-template-columns: 1.05fr 0.95fr;
}

/* ── LEFT CONTENT ── */
.content-col {
    display: flex;
    flex-direction: column;
    justify-content: center;
    padding: 56px 64px;
    position: relative;
}

.brand {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 56px;
}

.brand-icon {
    width: 34px; height: 34px;
    background: var(--accent);
    border-radius: 9px;
    display: grid;
    place-items: center;
    flex-shrink: 0;
}

.brand-icon i { color: #fff; font-size: 16px; }
.brand-text strong { display: block; font-size: 14px; font-weight: 600; color: var(--text); }
.brand-text span { font-size: 10.5px; color: var(--muted); letter-spacing: .04em; }

.content-inner { max-width: 480px; }

.eyebrow {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    font-family: 'DM Mono', monospace;
    font-size: 11px;
    font-weight: 500;
    letter-spacing: .08em;
    color: var(--muted);
    background: var(--surface);
    border: 1px solid var(--border);
    padding: 5px 11px;
    border-radius: 100px;
    margin-bottom: 22px;
}

.eyebrow .dot { width: 6px; height: 6px; border-radius: 50%; background: var(--green); flex-shrink: 0; }

.intro h1 {
    font-family: 'Space Grotesk', sans-serif;
    font-size: 38px;
    font-weight: 600;
    letter-spacing: -.02em;
    line-height: 1.12;
    margin-bottom: 16px;
}

.intro h1 em { font-style: normal; color: var(--blue); }

.intro p {
    font-size: 14.5px;
    color: var(--muted);
    line-height: 1.65;
    margin-bottom: 36px;
    max-width: 420px;
}

/* ── ROLE CARDS ── */
.role-cards {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 12px;
    margin-bottom: 36px;
}

.role-card {
    display: block;
    text-decoration: none;
    padding: 18px 20px;
    border-radius: var(--radius);
    border: 1.5px solid var(--border);
    background: var(--surface);
    transition: border-color .2s, transform .15s, box-shadow .2s;
    cursor: pointer;
    position: relative;
    overflow: hidden;
}

.role-card:hover {
    border-color: var(--accent);
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(0,0,0,.06);
}

.role-card.blue-card:hover { border-color: var(--blue); }

.role-icon {
    width: 36px; height: 36px;
    border-radius: 9px;
    display: grid;
    place-items: center;
    font-size: 17px;
    margin-bottom: 12px;
}

.role-icon.dark { background: var(--accent); color: #fff; }
.role-icon.blue { background: var(--blue-soft); color: var(--blue); }

.role-card h3 { font-size: 13.5px; font-weight: 600; margin-bottom: 4px; color: var(--text); }
.role-card p  { font-size: 12px; color: var(--muted); line-height: 1.4; }

.role-card-arrow {
    position: absolute;
    top: 16px; right: 16px;
    font-size: 14px;
    color: var(--muted);
    transition: transform .2s, color .2s;
}

.role-card:hover .role-card-arrow { transform: translateX(3px); color: var(--text); }

.divider-row {
    display: flex;
    align-items: center;
    gap: 12px;
    font-size: 11.5px;
    color: var(--muted);
    margin-bottom: 16px;
}

.divider-row::before,
.divider-row::after { content: ''; flex: 1; height: 1px; background: var(--border); }

.feature-list {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 10px;
}

.feature-item {
    display: flex;
    align-items: flex-start;
    gap: 8px;
    font-size: 12.5px;
    color: var(--muted);
}

.feature-item i { color: var(--green); font-size: 13px; margin-top: 1px; flex-shrink: 0; }

/* ── RIGHT VISUAL ── */
.visual-col {
    background: var(--accent);
    position: relative;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 48px 36px;
    overflow: hidden;
}

.panel-glow {
    position: absolute;
    top: -80px; right: -80px;
    width: 300px; height: 300px;
    border-radius: 50%;
    background: radial-gradient(circle, rgba(37,99,235,.35) 0%, transparent 70%);
    pointer-events: none;
}

.room-card {
    background: rgba(255,255,255,.06);
    border: 1px solid rgba(255,255,255,.12);
    border-radius: 14px;
    padding: 24px;
    width: 100%;
    max-width: 360px;
    backdrop-filter: blur(8px);
}

.room-card-label {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 18px;
    font-size: 11px;
    letter-spacing: .05em;
    text-transform: uppercase;
    color: rgba(255,255,255,.5);
    font-family: 'DM Mono', monospace;
}

.live-dot {
    display: flex;
    align-items: center;
    gap: 5px;
    font-size: 10px;
    color: #4ade80;
}

.live-dot::before {
    content: '';
    width: 6px; height: 6px;
    border-radius: 50%;
    background: #4ade80;
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0%, 100% { opacity: 1; }
    50% { opacity: .4; }
}

.admin-desk-mini {
    background: rgba(255,255,255,.09);
    border-radius: 8px;
    padding: 10px;
    text-align: center;
    font-size: 11px;
    color: rgba(255,255,255,.5);
    margin-bottom: 14px;
    font-family: 'DM Mono', monospace;
    letter-spacing: .04em;
}

.mini-seat-rows {
    display: grid;
    grid-template-columns: repeat(6, 1fr);
    gap: 5px;
    margin-bottom: 18px;
}

.mini-seat {
    aspect-ratio: 1;
    border-radius: 5px;
    background: rgba(255,255,255,.12);
    animation: fadeIn .4s ease calc(var(--i) * 0.06s) both;
}

.mini-seat:nth-child(3n+1) { background: rgba(37,99,235,.45); }
.mini-seat:nth-child(7n+2) { background: rgba(220,38,38,.4); }

@keyframes fadeIn {
    from { opacity: 0; transform: scale(.8); }
    to   { opacity: 1; transform: scale(1); }
}

.room-card-footer {
    display: flex;
    justify-content: space-between;
    align-items: flex-end;
}

.rc-lab { font-size: 12px; font-weight: 500; color: rgba(255,255,255,.8); line-height: 1.3; }
.rc-lab span { display: block; font-size: 10.5px; color: rgba(255,255,255,.4); margin-top: 2px; font-family: 'DM Mono', monospace; }

.rc-badge { font-size: 10.5px; background: rgba(74,222,128,.15); color: #4ade80; border: 1px solid rgba(74,222,128,.3); padding: 3px 9px; border-radius: 100px; font-family: 'DM Mono', monospace; }

.visual-caption {
    margin-top: 24px;
    font-size: 12px;
    color: rgba(255,255,255,.4);
    text-align: center;
    max-width: 300px;
    line-height: 1.5;
}

/* ── RESPONSIVE ── */
@media (max-width: 900px) {
    .shell { grid-template-columns: 1fr; }
    .visual-col { display: none; }
    .content-col { padding: 40px 28px; }
}

@media (max-width: 540px) {
    .role-cards { grid-template-columns: 1fr; }
    .feature-list { grid-template-columns: 1fr; }
    .intro h1 { font-size: 30px; }
}
</style>
</head>
<body>

<div class="shell">
    <div class="content-col">
        <div class="brand">
            <div class="brand-icon"><i class="bi bi-boxes"></i></div>
            <div class="brand-text">
                <strong>LabSystem</strong>
                <span>SISTEM MANAJEMEN LAB</span>
            </div>
        </div>

        <div class="content-inner">
            <span class="eyebrow">
                <span class="dot"></span>
                Sistem aktif & siap digunakan
            </span>

            <div class="intro">
                <h1>Kelola <em>Laboratorium</em><br>lebih efisien.</h1>
                <p>Peminjaman ruang lab dan peralatan, inventaris, serta manajemen mahasiswa — semua dalam satu platform terpadu.</p>
            </div>

            <div class="role-cards">
                <a href="{{ route('admin.login') }}" class="role-card">
                    <div class="role-icon dark"><i class="bi bi-person-badge-fill"></i></div>
                    <h3>Login Admin</h3>
                    <p>Kelola laboratorium, peminjaman, dan pengguna sistem.</p>
                    <i class="bi bi-arrow-right role-card-arrow"></i>
                </a>
                <a href="{{ route('mahasiswa.login') }}" class="role-card blue-card">
                    <div class="role-icon blue"><i class="bi bi-person-fill"></i></div>
                    <h3>Login Mahasiswa</h3>
                    <p>Ajukan peminjaman lab dan pantau status permintaan.</p>
                    <i class="bi bi-arrow-right role-card-arrow"></i>
                </a>
            </div>

            <div class="divider-row">Fitur Unggulan</div>

            <div class="feature-list">
                <div class="feature-item"><i class="bi bi-check-circle-fill"></i> Peminjaman Lab & Barang</div>
                <div class="feature-item"><i class="bi bi-check-circle-fill"></i> Inventaris per Lab</div>
                <div class="feature-item"><i class="bi bi-check-circle-fill"></i> Manajemen Mahasiswa</div>
                <div class="feature-item"><i class="bi bi-check-circle-fill"></i> Riwayat & Arsip</div>
                <div class="feature-item"><i class="bi bi-check-circle-fill"></i> Export Data Excel</div>
                <div class="feature-item"><i class="bi bi-check-circle-fill"></i> Dashboard Real-time</div>
            </div>
        </div>
    </div>

    <div class="visual-col">
        <div class="panel-glow"></div>
        <div class="room-card">
            <div class="room-card-label">
                <span>Lab.Sys / Live Preview</span>
                <span class="live-dot">Live</span>
            </div>
            <div class="admin-desk-mini">Meja Admin</div>
            <div class="mini-seat-rows" id="miniSeatRows"></div>
            <div class="room-card-footer">
                <div class="rc-lab">
                    Algoritma &amp; Pemrograman
                    <span>Gedung Saintek · Lt. 2</span>
                </div>
                <span class="rc-badge">Tersedia</span>
            </div>
        </div>
        <div class="visual-caption">
            <p>Setiap kursi punya statusnya sendiri — dipilih langsung oleh mahasiswa saat mengajukan peminjaman.</p>
        </div>
    </div>
</div>

<script>
const seatWrap = document.getElementById('miniSeatRows');
for (let i = 1; i <= 24; i++) {
    const seat = document.createElement('div');
    seat.className = 'mini-seat';
    seat.style.setProperty('--i', Math.floor(Math.random() * 10));
    seatWrap.appendChild(seat);
}
</script>
</body>
</html>
