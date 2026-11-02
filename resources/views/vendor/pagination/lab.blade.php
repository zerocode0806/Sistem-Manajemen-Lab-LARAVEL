{{-- resources/views/vendor/pagination/lab.blade.php
     Pagination dengan tombol angka untuk setiap halaman.
     CSS sudah termasuk di file ini (blok style di bawah). --}}

@once
<style>
/* ── BAR PAGINATION (kartu sendiri di bawah tabel) ── */
.pagination-wrapper {
    margin-top: 14px;
    padding: 14px 20px;
    background: var(--surface, #FFFFFF);
    border: 1px solid var(--border, #E8E8E3);
    border-radius: var(--radius, 10px);
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 14px;
    flex-wrap: wrap;
    max-width: 100%;
    box-sizing: border-box;
}
.pagination-info { font-size: 12.5px; color: var(--muted, #8C8C8A); }
.pagination-info strong { color: var(--text, #18181B); font-weight: 600; }

/* ── TOMBOL ── */
.pager { display: flex; justify-content: flex-end; }
.pager-full { display: flex; align-items: center; flex-wrap: wrap; gap: 6px; justify-content: flex-end; }
.pager-compact { display: none; }

.pager-btn {
    min-width: 34px;
    height: 34px;
    padding: 0 10px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 5px;
    border: 1px solid var(--border, #E8E8E3);
    border-radius: 7px;
    background: var(--surface, #FFFFFF);
    color: var(--text, #18181B);
    font-family: 'DM Mono', monospace;
    font-size: 12.5px;
    text-decoration: none;
    box-sizing: border-box;
    transition: background .15s, border-color .15s;
}
a.pager-btn:hover { background: var(--bg, #F7F7F5); border-color: var(--accent, #1A1A1A); }
.pager-btn.active { background: var(--accent, #1A1A1A); border-color: var(--accent, #1A1A1A); color: #fff; font-weight: 600; }
.pager-btn.disabled { color: var(--muted, #8C8C8A); background: var(--bg, #F7F7F5); pointer-events: none; opacity: .6; }
.pager-btn.dots {
    min-width: 20px;
    padding: 0;
    border-color: transparent;
    background: transparent;
    color: var(--muted, #8C8C8A);
    pointer-events: none;
}

/* ── MOBILE: satu baris  ‹ 1 … 4 5 6 … 91 › ── */
@media (max-width: 640px) {
    .pagination-wrapper {
        flex-direction: column;
        align-items: stretch;
        padding: 14px 10px;
        gap: 12px;
    }
    .pagination-info { text-align: center; }

    .pager { display: block; width: 100%; }
    .pager-full { display: none; }

    .pager-compact {
        display: flex;
        flex-wrap: nowrap;
        align-items: center;
        justify-content: center;
        gap: 4px;
        width: 100%;
    }
    /* semua tombol berbagi lebar secara rata, jadi tetap muat di layar sempit */
    .pager-compact .pager-btn {
        flex: 1 1 0;
        min-width: 0;
        max-width: 44px;
        height: 40px;
        padding: 0;
        font-size: 13px;
    }
    .pager-compact .pager-btn.dots { flex: 0 0 16px; max-width: 16px; }
}
</style>
@endonce

@if ($paginator->hasPages())
@php
    $current = $paginator->currentPage();
    $last    = $paginator->lastPage();

    // Susun daftar halaman yang ditampilkan: halaman 1, halaman terakhir,
    // dan $side halaman di kiri/kanan halaman aktif. Celah 1 halaman diisi
    // angka (bukan "…"), celah lebih besar diganti "…".
    $buildPages = function (int $current, int $last, int $side): array {
        $set = [1, $last];
        for ($i = $current - $side; $i <= $current + $side; $i++) {
            if ($i >= 1 && $i <= $last) {
                $set[] = $i;
            }
        }
        $set = array_values(array_unique($set));
        sort($set);

        $out  = [];
        $prev = null;
        foreach ($set as $p) {
            if ($prev !== null) {
                if ($p - $prev === 2) {
                    $out[] = $prev + 1;
                } elseif ($p - $prev > 2) {
                    $out[] = '...';
                }
            }
            $out[] = $p;
            $prev  = $p;
        }
        return $out;
    };

    $mobilePages  = $buildPages($current, $last, 1);  // maksimal 7 item
    $desktopPages = $buildPages($current, $last, 2);
@endphp

<nav class="pager" role="navigation" aria-label="Navigasi halaman">

    {{-- ===================== MOBILE (1 baris) ===================== --}}
    <div class="pager-compact">

        @if ($paginator->onFirstPage())
            <span class="pager-btn disabled"><i class="bi bi-chevron-left"></i></span>
        @else
            <a class="pager-btn" href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="Halaman sebelumnya">
                <i class="bi bi-chevron-left"></i>
            </a>
        @endif

        @foreach ($mobilePages as $p)
            @if ($p === '...')
                <span class="pager-btn dots">…</span>
            @elseif ($p == $current)
                <span class="pager-btn active" aria-current="page">{{ $p }}</span>
            @else
                <a class="pager-btn" href="{{ $paginator->url($p) }}" aria-label="Halaman {{ $p }}">{{ $p }}</a>
            @endif
        @endforeach

        @if ($paginator->hasMorePages())
            <a class="pager-btn" href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="Halaman berikutnya">
                <i class="bi bi-chevron-right"></i>
            </a>
        @else
            <span class="pager-btn disabled"><i class="bi bi-chevron-right"></i></span>
        @endif
    </div>

    {{-- ===================== DESKTOP ===================== --}}
    <div class="pager-full">
        @if ($paginator->onFirstPage())
            <span class="pager-btn disabled"><i class="bi bi-chevron-left"></i></span>
        @else
            <a class="pager-btn" href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="Halaman sebelumnya">
                <i class="bi bi-chevron-left"></i>
            </a>
        @endif

        @foreach ($desktopPages as $p)
            @if ($p === '...')
                <span class="pager-btn dots">…</span>
            @elseif ($p == $current)
                <span class="pager-btn active" aria-current="page">{{ $p }}</span>
            @else
                <a class="pager-btn" href="{{ $paginator->url($p) }}" aria-label="Halaman {{ $p }}">{{ $p }}</a>
            @endif
        @endforeach

        @if ($paginator->hasMorePages())
            <a class="pager-btn" href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="Halaman berikutnya">
                <i class="bi bi-chevron-right"></i>
            </a>
        @else
            <span class="pager-btn disabled"><i class="bi bi-chevron-right"></i></span>
        @endif
    </div>
</nav>
@endif