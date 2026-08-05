{{--
  Seat Picker Component — Layout 3-2-3 (8 kursi per baris)
  =========================================================
  @include('components.seat-picker', [
      'seatCheckUrl' => route('admin.peminjaman.checkSeats'),
  ])

  Layout ruang (kiri→kanan di layar):
    [Grup Kiri 3 col] | lorong | [Grup Tengah 2 col] | lorong | [Grup Kanan 3 col ← seat 1,2,3]

  Penomoran (kanan→kiri, atas→bawah):
    Baris 1: [8][7][6]  |  [5][4]  |  [3][2][1]
    Baris 2: [16][15][14] | [13][12] | [11][10][9]
    dst...
--}}

<div id="seatPickerSection" style="display:none;margin-top:20px">
<style>
/* ── SEAT PICKER ─────────────────────────────────────────────────── */
.sp-wrap {
    background: var(--surface,#fff);
    border: 1px solid var(--border,#E8E8E3);
    border-radius: 10px;
    padding: 24px;
    user-select: none;
    /* allow horizontal scroll on narrow screens */
    overflow: hidden;
}

.sp-room-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-end;
    margin-bottom: 22px;
    padding-bottom: 18px;
    border-bottom: 1px solid var(--border,#E8E8E3);
    min-width: 0;
}

.sp-papan-tulis {
    display: flex;
    flex-direction: column;
    align-items: flex-start;
    gap: 6px;
    font-size: 11.5px;
    color: var(--muted,#8C8C8A);
    letter-spacing: .04em;
    text-transform: uppercase;
    font-family: 'DM Mono', monospace;
    flex-shrink: 0;
}

.sp-papan-tulis-bar {
    width: 120px;
    height: 10px;
    background: var(--accent,#1A1A1A);
    border-radius: 3px;
}

.sp-meja-admin {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 6px;
    font-size: 11.5px;
    color: var(--muted,#8C8C8A);
    letter-spacing: .04em;
    text-transform: uppercase;
    font-family: 'DM Mono', monospace;
    flex-shrink: 0;
}

.sp-meja-admin-box {
    width: 64px;
    height: 34px;
    background: var(--bg,#F7F7F5);
    border: 2px solid var(--accent,#1A1A1A);
    border-radius: 5px;
}

/* ── SCROLL WRAPPER — keeps grid from collapsing on mobile ── */
.sp-scroll-wrap {
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
    padding-bottom: 4px;
}

/* ── SEAT GRID ── */
.sp-grid {
    display: inline-flex;   /* shrink-wraps to content width */
    flex-direction: column;
    gap: 8px;
    /* fixed min-width so it never wraps/collapses on mobile */
    min-width: max-content;
}

.sp-row {
    display: flex;
    align-items: center;
    gap: 0;
    flex-shrink: 0;
}

.sp-row-label {
    font-size: 10.5px;
    color: var(--muted,#8C8C8A);
    font-family: 'DM Mono', monospace;
    width: 28px;
    text-align: right;
    padding-right: 8px;
    flex-shrink: 0;
}

.sp-group {
    display: flex;
    gap: 5px;
    flex-shrink: 0;
}

.sp-aisle {
    width: 22px;
    flex-shrink: 0;
}

/* ── SEAT CELL ── */
.sp-seat {
    width: 38px;
    height: 38px;
    border-radius: 6px;
    border: 1.5px solid var(--border,#E8E8E3);
    background: var(--surface,#fff);
    cursor: pointer;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    font-size: 10px;
    font-weight: 600;
    color: var(--text,#18181B);
    font-family: 'DM Mono', monospace;
    transition: background .12s, border-color .12s, transform .08s;
    position: relative;
    flex-shrink: 0;
}

.sp-seat:hover:not(.sp-seat--taken):not(.sp-seat--empty) {
    border-color: var(--blue,#2563EB);
    background: #EFF4FF;
    transform: scale(1.08);
}

.sp-seat--selected {
    background: var(--blue,#2563EB) !important;
    border-color: var(--blue,#2563EB) !important;
    color: #fff !important;
    transform: scale(1.08);
    box-shadow: 0 2px 10px rgba(37,99,235,.3);
}

.sp-seat--taken {
    background: #F3F4F6;
    border-color: #E5E7EB;
    color: #9CA3AF;
    cursor: not-allowed;
}

.sp-seat--taken::after {
    content: '';
    position: absolute;
    width: 60%;
    height: 1.5px;
    background: #D1D5DB;
    transform: rotate(-45deg);
}

.sp-seat--empty {
    background: transparent;
    border: 1.5px dashed #E5E7EB;
    cursor: default;
    opacity: .35;
}

/* ── LEGEND ── */
.sp-legend {
    display: flex;
    gap: 18px;
    flex-wrap: wrap;
    margin-top: 20px;
    padding-top: 16px;
    border-top: 1px solid var(--border,#E8E8E3);
}

.sp-legend-item {
    display: flex;
    align-items: center;
    gap: 7px;
    font-size: 12px;
    color: var(--muted,#8C8C8A);
}

.sp-legend-dot {
    width: 22px;
    height: 22px;
    border-radius: 5px;
    border: 1.5px solid var(--border,#E8E8E3);
    flex-shrink: 0;
}

.sp-legend-dot--available { background: var(--surface,#fff); }
.sp-legend-dot--taken     { background: #F3F4F6; border-color: #E5E7EB; }
.sp-legend-dot--selected  { background: var(--blue,#2563EB); border-color: var(--blue,#2563EB); }

/* ── SELECTED INFO ── */
.sp-selected-info {
    margin-top: 14px;
    display: flex;
    align-items: center;
    gap: 10px;
    font-size: 13px;
    color: var(--text,#18181B);
    min-height: 32px;
    flex-wrap: wrap;
}

.sp-selected-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: #EFF4FF;
    color: var(--blue,#2563EB);
    border: 1px solid #BFDBFE;
    padding: 4px 12px;
    border-radius: 100px;
    font-size: 12.5px;
    font-weight: 600;
    font-family: 'DM Mono', monospace;
}

.sp-loading {
    display: flex;
    align-items: center;
    gap: 10px;
    font-size: 13px;
    color: var(--muted,#8C8C8A);
    padding: 32px 0;
}

.sp-spinner {
    width: 18px; height: 18px;
    border: 2px solid var(--border,#E8E8E3);
    border-top-color: var(--blue,#2563EB);
    border-radius: 50%;
    animation: sp-spin .7s linear infinite;
    flex-shrink: 0;
}

@keyframes sp-spin { to { transform: rotate(360deg); } }

/* ── mobile hint ── */
.sp-scroll-hint {
    display: none;
    font-size: 11px;
    color: var(--muted,#8C8C8A);
    margin-bottom: 8px;
    font-family: 'DM Mono', monospace;
}

@media (max-width: 600px) {
    .sp-scroll-hint { display: block; }
    .sp-wrap { padding: 16px 12px; }
}
</style>

<div class="sp-wrap">
    {{-- Room orientation --}}
    <div class="sp-room-header">
        <div class="sp-papan-tulis">
            <span>Papan Tulis</span>
            <div class="sp-papan-tulis-bar"></div>
        </div>
        <div class="sp-meja-admin">
            <span>Meja Admin</span>
            <div class="sp-meja-admin-box"></div>
        </div>
    </div>

    {{-- Mobile scroll hint --}}
    <div class="sp-scroll-hint">← geser untuk melihat semua kursi →</div>

    {{-- Scrollable seat grid --}}
    <div class="sp-scroll-wrap">
        <div id="spGridContainer">
            <div class="sp-loading">
                <div class="sp-spinner"></div>
                <span>Pilih lab, tanggal, dan jam terlebih dahulu…</span>
            </div>
        </div>
    </div>

    {{-- Legend --}}
    <div class="sp-legend">
        <div class="sp-legend-item">
            <div class="sp-legend-dot sp-legend-dot--available"></div>
            <span>Tersedia</span>
        </div>
        <div class="sp-legend-item">
            <div class="sp-legend-dot sp-legend-dot--taken"></div>
            <span>Sudah dipesan</span>
        </div>
        <div class="sp-legend-item">
            <div class="sp-legend-dot sp-legend-dot--selected"></div>
            <span>Pilihan Anda</span>
        </div>
    </div>

    {{-- Selected seat info --}}
    <div class="sp-selected-info" id="spSelectedInfo" style="display:none">
        <span>Kursi dipilih:</span>
        <span class="sp-selected-badge" id="spSelectedBadge">–</span>
        <a href="#" id="spClearBtn" style="font-size:12px;color:#DC2626;text-decoration:none;margin-left:4px">Batalkan pilihan</a>
    </div>
</div>

{{-- Hidden input that stores selected seat number --}}
<input type="hidden" name="kursi" id="spKursiInput" value="">

<script>
(function () {
    /*
     * Layout 3-2-3 (kiri→kanan di layar):
     *   Grup 0 (kiri,  3 col)  |  Grup 1 (tengah, 2 col)  |  Grup 2 (kanan, 3 col)
     *
     * Penomoran kursi mulai dari kanan (dekat admin) → kiri:
     *   Grup 2 (kanan)  → seatInRow 3,2,1
     *   Grup 1 (tengah) → seatInRow 5,4
     *   Grup 0 (kiri)   → seatInRow 8,7,6
     *
     * Baris 1: [8][7][6] | [5][4] | [3][2][1]
     * Baris 2: [16][15][14] | [13][12] | [11][10][9]
     */

    // Group sizes left→right on screen: [kiri, tengah, kanan]
    var GROUP_SIZES   = [3, 2, 3];
    var SEATS_PER_ROW = 8; // 3+2+3

    // Offset for each group: how many seats are to the RIGHT of it
    // Grup 2 (kanan): offset 0  → seats 1,2,3
    // Grup 1 (tengah): offset 3 → seats 4,5
    // Grup 0 (kiri):  offset 5  → seats 6,7,8
    var GROUP_OFFSET  = [5, 3, 0]; // indexed same as GROUP_SIZES

    var SEAT_CHECK_URL = '{{ $seatCheckUrl ?? "" }}';

    var currentTotal    = 0;
    var currentTaken    = [];
    var selectedSeat    = null;
    var lastFetchParams = {};

    function seatNumber(row, group, col) {
        // Nomor besar di kiri, kecil di kanan (dari kanan ke kiri)
        // col 0 = kolom paling kiri group → dapat nomor tertinggi di group itu
        var seatInRow = GROUP_OFFSET[group] + (GROUP_SIZES[group] - col);
        return row * SEATS_PER_ROW + seatInRow;
    }

    function renderGrid(totalSeats, takenSeats) {
        currentTotal = totalSeats;
        currentTaken = takenSeats;

        var rows      = Math.ceil(totalSeats / SEATS_PER_ROW);
        var taken     = {};
        for (var i = 0; i < takenSeats.length; i++) { taken[+takenSeats[i]] = true; }

        var container = document.getElementById('spGridContainer');

        if (rows === 0) {
            container.innerHTML = '<p style="color:var(--muted,#8C8C8A);font-size:13px;padding:24px 0">Tidak ada data kursi untuk lab ini.</p>';
            return;
        }

        var grid = document.createElement('div');
        grid.className = 'sp-grid';

        for (var r = 0; r < rows; r++) {
            var rowEl = document.createElement('div');
            rowEl.className = 'sp-row';

            // Row label
            var label = document.createElement('div');
            label.className = 'sp-row-label';
            label.textContent = 'B' + (r + 1);
            rowEl.appendChild(label);

            for (var g = 0; g < GROUP_SIZES.length; g++) {
                // Aisle spacer between groups
                if (g > 0) {
                    var aisle = document.createElement('div');
                    aisle.className = 'sp-aisle';
                    rowEl.appendChild(aisle);
                }

                var groupEl = document.createElement('div');
                groupEl.className = 'sp-group';

                var colsInGroup = GROUP_SIZES[g];
                for (var c = 0; c < colsInGroup; c++) {
                    var num  = seatNumber(r, g, c);
                    var seat = document.createElement('div');
                    seat.className = 'sp-seat';
                    seat.dataset.seat = num;

                    if (num > totalSeats) {
                        // Ghost / empty placeholder
                        seat.classList.add('sp-seat--empty');
                    } else if (taken[num]) {
                        seat.classList.add('sp-seat--taken');
                        seat.textContent = num;
                        seat.title = 'Meja ' + num + ' — sudah dipesan';
                    } else {
                        seat.textContent = num;
                        seat.title = 'Meja ' + num + ' — tersedia';

                        if (selectedSeat === num) {
                            seat.classList.add('sp-seat--selected');
                        }

                        (function (n) {
                            seat.addEventListener('click', function () { selectSeat(n); });
                        })(num);
                    }

                    groupEl.appendChild(seat);
                }

                rowEl.appendChild(groupEl);
            }

            grid.appendChild(rowEl);
        }

        container.innerHTML = '';
        container.appendChild(grid);
        refreshSelectedInfo();
    }

    function selectSeat(num) {
        selectedSeat = (selectedSeat === num) ? null : num;
        document.getElementById('spKursiInput').value = selectedSeat || '';
        renderGrid(currentTotal, currentTaken);
    }

    function refreshSelectedInfo() {
        var infoEl  = document.getElementById('spSelectedInfo');
        var badgeEl = document.getElementById('spSelectedBadge');
        if (selectedSeat) {
            infoEl.style.display  = 'flex';
            badgeEl.textContent   = 'Meja ' + selectedSeat;
        } else {
            infoEl.style.display = 'none';
        }
    }

    document.getElementById('spClearBtn').addEventListener('click', function (e) {
        e.preventDefault();
        selectedSeat = null;
        document.getElementById('spKursiInput').value = '';
        renderGrid(currentTotal, currentTaken);
    });

    /* ── FETCH SEAT AVAILABILITY ─────────────────────────────────── */
    function fetchSeats(labName, tanggal, jamMulai, jamSelesai) {
        if (!labName || !tanggal || !jamMulai || !jamSelesai) return;

        var key = labName + '|' + tanggal + '|' + jamMulai + '|' + jamSelesai;
        if (lastFetchParams.key === key) return;
        lastFetchParams.key = key;

        var container = document.getElementById('spGridContainer');
        container.innerHTML = '<div class="sp-loading"><div class="sp-spinner"></div><span>Memeriksa ketersediaan kursi…</span></div>';
        document.getElementById('seatPickerSection').style.display = '';

        // Reset selection
        selectedSeat = null;
        document.getElementById('spKursiInput').value = '';
        document.getElementById('spSelectedInfo').style.display = 'none';

        var url = new URL(SEAT_CHECK_URL, window.location.origin);
        url.searchParams.set('nama_lab',    labName);
        url.searchParams.set('tanggal',     tanggal);
        url.searchParams.set('jam_mulai',   jamMulai);
        url.searchParams.set('jam_selesai', jamSelesai);

        fetch(url.toString())
            .then(function (r) { return r.json(); })
            .then(function (data) {
                renderGrid(data.total_kursi || 0, data.taken || []);
            })
            .catch(function () {
                container.innerHTML = '<p style="color:#DC2626;font-size:13px;padding:16px 0">Gagal memuat data kursi. Periksa koneksi dan coba lagi.</p>';
            });
    }

    /* ── WATCHERS ─────────────────────────────────────────────────── */
    function getVal(id) {
        var el = document.getElementById(id);
        return el ? el.value : '';
    }

    function checkAndFetch() {
        if (getVal('jenisLab') !== 'lab') return;
        var labEl    = document.getElementById('namaLabSelect');
        var labName  = labEl && labEl.selectedIndex >= 0 ? labEl.options[labEl.selectedIndex].value : '';
        var tanggal  = getVal('tanggalInput');
        var jamMulai = getVal('jamMulaiInput');
        var jamSelesai = getVal('jamSelesaiInput');

        if (labName && tanggal && jamMulai && jamSelesai) {
            fetchSeats(labName, tanggal, jamMulai, jamSelesai);
        }
    }

    window.SeatPicker = { checkAndFetch: checkAndFetch };

    document.addEventListener('DOMContentLoaded', function () {
        ['namaLabSelect', 'tanggalInput', 'jamMulaiInput', 'jamSelesaiInput'].forEach(function (id) {
            var el = document.getElementById(id);
            if (el) {
                el.addEventListener('change', checkAndFetch);
                el.addEventListener('input',  checkAndFetch);
            }
        });
    });
})();
</script>
</div>
