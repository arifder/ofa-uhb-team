@extends('layouts.dashboard')
@section('title', 'Notifikasi')

@push('styles')
<style>
  /* ── Layout ───────────────────────────────────────────────── */
  .notif-wrapper {
    width: 100%;
  }

  /* ── Header ─────────────────────────────────────────────── */
  .notif-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 12px;
    margin-bottom: 20px;
  }

  .notif-header-left {
    display: flex;
    align-items: center;
    gap: 10px;
  }

  .notif-header-title {
    font-size: 15px;
    font-weight: 700;
    color: #111827;
    display: flex;
    align-items: center;
    gap: 8px;
  }

  .notif-header-title i {
    font-size: 20px;
    color: #3b82f6;
  }

  .notif-count-badge {
    background: #2563eb;
    color: #fff;
    font-size: 11px;
    padding: 2px 9px;
    border-radius: 20px;
    font-weight: 600;
  }

  .notif-header-right {
    display: flex;
    align-items: center;
    gap: 10px;
    flex-wrap: wrap;
  }

  /* ── Filter Tabs ─────────────────────────────────────────── */
  .filter-tabs {
    display: flex;
    gap: 4px;
    background: #f1f5f9;
    padding: 4px;
    border-radius: 10px;
  }

  .filter-tab {
    padding: 5px 13px;
    font-size: 12px;
    font-weight: 500;
    color: #6b7280;
    border-radius: 7px;
    cursor: pointer;
    text-decoration: none;
    transition: all 0.15s;
    white-space: nowrap;
    border: none;
    background: transparent;
  }

  .filter-tab:hover {
    background: #e2e8f0;
    color: #374151;
  }

  .filter-tab.active {
    background: #ffffff;
    color: #1d4ed8;
    font-weight: 600;
    box-shadow: 0 1px 3px rgba(0,0,0,.08);
  }

  /* ── Read All Button ─────────────────────────────────────── */
  .btn-read-all {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 6px 14px;
    font-size: 12px;
    font-weight: 500;
    color: #1d4ed8;
    background: #eff6ff;
    border: 0.5px solid #bfdbfe;
    border-radius: 8px;
    cursor: pointer;
    transition: background 0.15s;
    white-space: nowrap;
  }

  .btn-read-all:hover {
    background: #dbeafe;
  }

  /* ── Date Group Divider ──────────────────────────────────── */
  .date-group-header {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 10px 0 8px;
    font-size: 11px;
    font-weight: 600;
    color: #9ca3af;
    text-transform: uppercase;
    letter-spacing: 0.06em;
  }

  .date-group-header::after {
    content: '';
    flex: 1;
    height: 1px;
    background: #e5e7eb;
  }

  /* ── Notif List Card ─────────────────────────────────────── */
  .notif-list {
    background: #ffffff;
    border: 0.5px solid #e2e8f0;
    border-radius: 14px;
    overflow: hidden;
  }

  /* ── Notif Item ──────────────────────────────────────────── */
  .notif-item {
    display: flex;
    align-items: flex-start;
    gap: 16px;
    padding: 16px 20px;
    border-bottom: 0.5px solid #f1f5f9;
    cursor: pointer;
    transition: background 0.1s;
    text-decoration: none;
  }

  .notif-item:last-child {
    border-bottom: none;
  }

  .notif-item:hover {
    background: #f8fafc;
  }

  .notif-item.belum-dibaca {
    background: #f0f7ff;
  }

  .notif-item.belum-dibaca:hover {
    background: #dbeafe;
  }

  /* ── Icon Column ─────────────────────────────────────────── */
  .notif-icon-wrap {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 6px;
    flex-shrink: 0;
    padding-top: 2px;
  }

  .notif-icon {
    width: 36px;
    height: 36px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 17px;
    flex-shrink: 0;
  }

  .icon-notulensi { background: #ccfbf1; color: #0f766e; }
  .icon-kas       { background: #dbeafe; color: #1d4ed8; }
  .icon-sistem    { background: #f1f5f9; color: #6b7280; }

  .notif-unread-dot {
    width: 7px;
    height: 7px;
    border-radius: 50%;
    background: #3b82f6;
    flex-shrink: 0;
  }

  /* ── Body ────────────────────────────────────────────────── */
  .notif-body {
    flex: 1;
    min-width: 0;
  }

  .notif-meta-row {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 4px;
    flex-wrap: wrap;
  }

  .notif-judul {
    font-size: 13px;
    color: #111827;
    font-weight: 500;
    line-height: 1.4;
  }

  .notif-judul.bold {
    font-weight: 700;
  }

  .notif-badge-tipe {
    font-size: 10px;
    padding: 2px 8px;
    border-radius: 20px;
    font-weight: 600;
    flex-shrink: 0;
    text-transform: capitalize;
  }

  .tipe-notulensi { background: #ccfbf1; color: #0f766e; }
  .tipe-kas       { background: #dbeafe; color: #1d4ed8; }
  .tipe-sistem    { background: #f1f5f9; color: #6b7280; }

  .notif-pesan {
    font-size: 12px;
    color: #6b7280;
    line-height: 1.6;
    margin-bottom: 0;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
  }

  /* ── Time Column (sisi kanan) ────────────────────────────── */
  .notif-time-col {
    flex-shrink: 0;
    text-align: right;
    display: flex;
    flex-direction: column;
    align-items: flex-end;
    gap: 4px;
    min-width: 150px;
    padding-top: 2px;
  }

  .notif-time-absolute {
    font-size: 11px;
    font-weight: 500;
    color: #374151;
    white-space: nowrap;
  }

  .notif-time-relative {
    font-size: 11px;
    color: #9ca3af;
    white-space: nowrap;
  }

  .notif-time-sep {
    font-size: 10px;
    color: #d1d5db;
  }

  /* ── Empty ───────────────────────────────────────────────── */
  .notif-empty {
    padding: 60px 20px;
    text-align: center;
    color: #9ca3af;
    font-size: 13px;
  }

  .notif-empty i {
    font-size: 42px;
    display: block;
    margin-bottom: 12px;
    color: #d1d5db;
  }

  .notif-empty-title {
    font-size: 14px;
    font-weight: 600;
    color: #6b7280;
    margin-bottom: 4px;
  }

  /* ── Pagination ──────────────────────────────────────────── */
  .pagination-wrap {
    margin-top: 16px;
    display: flex;
    justify-content: center;
  }

  .pagination {
    display: flex;
    gap: 4px;
    list-style: none;
    padding: 0;
    margin: 0;
  }

  .pagination .page-item .page-link {
    display: flex;
    align-items: center;
    justify-content: center;
    min-width: 32px;
    height: 32px;
    padding: 0 10px;
    font-size: 12px;
    border: 0.5px solid #e2e8f0;
    border-radius: 8px;
    color: #4b5563;
    background: #fff;
    text-decoration: none;
    transition: background 0.1s;
  }

  .pagination .page-item.active .page-link {
    background: #2563eb;
    border-color: #2563eb;
    color: #fff;
  }

  .pagination .page-item.disabled .page-link {
    opacity: 0.4;
    pointer-events: none;
  }

  .pagination .page-item .page-link:hover {
    background: #f1f5f9;
  }

  /* ── Responsive ──────────────────────────────────────────── */
  @media (max-width: 768px) {
    .filter-tabs {
      overflow-x: auto;
      -webkit-overflow-scrolling: touch;
    }

    .notif-header {
      flex-direction: column;
      align-items: flex-start;
    }

    .notif-header-right {
      width: 100%;
    }

    .notif-time-col {
      display: none; /* sembunyikan kolom waktu di mobile, tampil di body */
    }

    .notif-time-mobile {
      display: flex !important;
    }

    .notif-item {
      align-items: flex-start;
    }
  }
</style>
@endpush

@section('content')
@php
  use Carbon\Carbon;

  $jumlahBelumDibaca = \App\Models\Notifikasi::where('user_id', auth()->id())->where('dibaca', false)->count();

  $filterOptions = [
    'semua'    => 'Semua',
    'hari_ini' => 'Hari Ini',
    'kemarin'  => 'Kemarin',
    '7_hari'   => '7 Hari',
    '30_hari'  => '30 Hari',
  ];

  // Helper: label grup tanggal — filter-aware
  $getGroupLabel = function($date, $activeFilter) {
    $today     = Carbon::today();
    $yesterday = Carbon::yesterday();

    // Hitung selisih hari (integer, positif = masa lalu)
    $diffDays = (int) $today->copy()->startOfDay()->diffInDays($date->copy()->startOfDay());

    switch ($activeFilter) {

      // ── Filter: Hari Ini ─────────────────────────
      case 'hari_ini':
        return 'Hari Ini';

      // ── Filter: Kemarin ──────────────────────────
      case 'kemarin':
        return 'Kemarin';

      // ── Filter: 7 Hari — grup per hari ──────────
      case '7_hari':
        if ($diffDays === 0) return 'Hari Ini';
        if ($diffDays === 1) return '1 Hari Lalu';
        return $diffDays . ' Hari Lalu';

      // ── Filter: 30 Hari — grup per minggu ────────
      case '30_hari':
        if ($diffDays < 7)  return 'Minggu Ini';
        $weeks = (int) ceil($diffDays / 7);
        if ($weeks === 1)   return '1 Minggu Lalu';
        return $weeks . ' Minggu Lalu';

      // ── Filter: Semua — grup kontekstual ─────────
      default:
        if ($date->isSameDay($today))     return 'Hari Ini';
        if ($date->isSameDay($yesterday)) return 'Kemarin';
        if ($diffDays <= 7)               return 'Minggu Ini';
        if ($diffDays <= 30)              return $date->translatedFormat('d F Y');
        return $date->translatedFormat('F Y');
    }
  };

  $prevGroupLabel = null;
@endphp

<div class="notif-wrapper">

  {{-- ── Header ── --}}
  <div class="notif-header">
    <div class="notif-header-left">
      <div class="notif-header-title">
        <i class="ti ti-bell"></i>
        Notifikasi
        @if($jumlahBelumDibaca > 0)
          <span class="notif-count-badge">{{ $jumlahBelumDibaca }} baru</span>
        @endif
      </div>
    </div>

    <div class="notif-header-right">
      {{-- Filter Tabs --}}
      <div class="filter-tabs" style="display:flex; align-items:center; gap:4px; flex-wrap:wrap;">
        @foreach($filterOptions as $key => $label)
          <a href="{{ route('notifikasi.index', ['filter' => $key]) }}"
             class="filter-tab {{ $filter === $key && !request('tanggal') ? 'active' : '' }}">
            {{ $label }}
          </a>
        @endforeach
        <form action="{{ route('notifikasi.index') }}" method="GET" style="display:flex; align-items:center; gap:4px; margin-left:4px;">
            <input type="date" name="tanggal" value="{{ request('tanggal') }}" class="filter-tab" style="border:1px solid #cbd5e1; background:#fff; padding:4px 8px; cursor:text; height:28px;" onchange="this.form.submit()">
            @if(request('tanggal'))
            <a href="{{ route('notifikasi.index') }}" title="Hapus Filter Tanggal" style="color:#ef4444; font-size:14px; margin-left:4px; text-decoration:none;"><i class="ti ti-circle-x-filled"></i></a>
            @endif
        </form>
      </div>

      {{-- Tandai Semua Dibaca --}}
      @if($jumlahBelumDibaca > 0)
        <form action="{{ route('notifikasi.readAll') }}" method="POST">
          @csrf
          <button type="submit" class="btn-read-all">
            <i class="ti ti-checks"></i> Tandai Dibaca
          </button>
        </form>
      @endif
    </div>
  </div>

  {{-- ── Notification List ── --}}
  @if($notifikasi->isEmpty())
    <div class="notif-list">
      <div class="notif-empty">
        <i class="ti ti-bell-off"></i>
        <div class="notif-empty-title">Tidak ada notifikasi</div>
        <div>{{ $filterOptions[$filter] === 'Semua' ? 'Belum ada notifikasi yang masuk.' : 'Tidak ada notifikasi pada periode "'.$filterOptions[$filter].'".' }}</div>
      </div>
    </div>
  @else
    @php $prevGroupLabel = null; @endphp

    @foreach($notifikasi as $item)
      @php
        $itemDate   = Carbon::parse($item->created_at)->setTimezone('Asia/Jakarta');
        $groupLabel = $getGroupLabel($itemDate, $filter);
      @endphp

      @if($groupLabel !== $prevGroupLabel)
        @if($prevGroupLabel !== null)
          </div>{{-- close .notif-list --}}
        @endif
        <div class="date-group-header">{{ $groupLabel }}</div>
        <div class="notif-list">
        @php $prevGroupLabel = $groupLabel; @endphp
      @endif

      @php
        $emojiRegex = '/[\x{1F300}-\x{1F5FF}\x{1F600}-\x{1F64F}\x{1F680}-\x{1F6FF}\x{2600}-\x{26FF}\x{2700}-\x{27BF}\x{1F900}-\x{1F9FF}\x{1FA00}-\x{1FAFF}\x{1F1E0}-\x{1F1FF}]/u';
        $cleanJudul = trim(preg_replace($emojiRegex, '', str_replace(['📋', '✏️', '🗑️', '💰', '📤', '🧾', '✅'], '', $item->judul)));
        $cleanPesan = trim(preg_replace($emojiRegex, '', str_replace(['📋', '✏️', '🗑️', '💰', '📤', '🧾', '✅'], '', $item->pesan)));
      @endphp

      {{-- Notif Item --}}
      <a href="{{ route('notifikasi.read', $item->id) }}"
         class="notif-item {{ !$item->dibaca ? 'belum-dibaca' : '' }}"
         onclick="event.preventDefault(); document.getElementById('read-form-{{ $item->id }}').submit();">

        {{-- Icon column --}}
        <div class="notif-icon-wrap">
          @php
            $iconClass = match($item->tipe) {
              'notulensi' => 'icon-notulensi',
              'kas'       => 'icon-kas',
              default     => 'icon-sistem',
            };
            $iconName = match($item->tipe) {
              'notulensi' => 'ti-notes',
              'kas'       => 'ti-cash',
              default     => 'ti-info-circle',
            };
          @endphp
          <div class="notif-icon {{ $iconClass }}">
            <i class="ti {{ $iconName }}"></i>
          </div>
          @if(!$item->dibaca)
            <div class="notif-unread-dot"></div>
          @endif
        </div>

        {{-- Body --}}
        <div class="notif-body">
          <div class="notif-meta-row">
            <span class="notif-judul {{ !$item->dibaca ? 'bold' : '' }}">{{ $cleanJudul }}</span>
            <span class="notif-badge-tipe tipe-{{ $item->tipe }}">{{ ucfirst($item->tipe) }}</span>
          </div>
          <div class="notif-pesan">{{ $cleanPesan }}</div>
          {{-- Waktu versi mobile (tersembunyi di desktop) --}}
          <div class="notif-time-mobile" style="display:none; margin-top:6px; align-items:center; gap:6px; flex-wrap:wrap;">
            <span class="notif-time-absolute">
              <i class="ti ti-clock" style="font-size:11px;vertical-align:-1px;"></i>
              {{ $itemDate->translatedFormat('d M Y') }} • {{ $itemDate->format('H:i') }} WIB
            </span>
            <span class="notif-time-sep">·</span>
            <span class="notif-time-relative">{{ $itemDate->diffForHumans() }}</span>
            @if(!$item->dibaca)
            <button type="button" onclick="event.preventDefault(); event.stopPropagation(); markAsRead({{ $item->id }}, this)" title="Tandai sudah dibaca" style="background:none; border:none; color:#10b981; font-size:16px; cursor:pointer; padding:0; margin-left:auto;">
                <i class="ti ti-circle-check"></i>
            </button>
            @endif
          </div>
        </div>

        {{-- Time column (sisi kanan, desktop only) --}}
        <div class="notif-time-col" style="flex-direction:row; align-items:center; gap:12px;">
          <div style="display:flex; flex-direction:column; align-items:flex-end; gap:4px;">
              <span class="notif-time-absolute">
                <i class="ti ti-clock" style="font-size:11px;vertical-align:-1px;"></i>
                {{ $itemDate->translatedFormat('d M Y') }} • {{ $itemDate->format('H:i') }} WIB
              </span>
              <span class="notif-time-relative">{{ $itemDate->diffForHumans() }}</span>
          </div>
          @if(!$item->dibaca)
          <button type="button" onclick="event.preventDefault(); event.stopPropagation(); markAsRead({{ $item->id }}, this)" title="Tandai sudah dibaca" style="background:none; border:none; color:#10b981; font-size:20px; cursor:pointer; padding:4px; border-radius:50%; transition:transform 0.1s;" onmouseover="this.style.transform='scale(1.1)'" onmouseout="this.style.transform='scale(1)'">
              <i class="ti ti-circle-check"></i>
          </button>
          @endif
        </div>

      </a>

      {{-- Hidden form for mark-read redirect --}}
      <form id="read-form-{{ $item->id }}"
            action="{{ route('notifikasi.read', $item->id) }}"
            method="POST"
            style="display:none;">
        @csrf
      </form>

    @endforeach

    {{-- Close last .notif-list --}}
    @if($prevGroupLabel !== null)
      </div>
    @endif
  @endif

  {{-- ── Pagination ── --}}
  @if($notifikasi->hasPages())
    <div class="pagination-wrap">
      {{ $notifikasi->links() }}
    </div>
  @endif

</div>
@endsection

@push('scripts')
<script>
function markAsRead(id, btn) {
    fetch(`/notifikasi/${id}/read`, {
        method: 'POST',
        headers: { 
            'X-CSRF-TOKEN': '{{ csrf_token() }}', 
            'Accept': 'application/json' 
        }
    }).then(r => r.json()).then(data => {
        if(data.success) {
            const item = btn.closest('.notif-item');
            if (item) {
                item.classList.remove('belum-dibaca');
                const dot = item.querySelector('.notif-unread-dot');
                if(dot) dot.remove();
                
                // remove the checkmark button in both mobile and desktop views
                const buttons = item.querySelectorAll('button[title="Tandai sudah dibaca"]');
                buttons.forEach(b => b.remove());
                
                // make the title unbold
                const title = item.querySelector('.notif-judul');
                if (title) title.classList.remove('bold');
            }
            
            // update header count
            const badge = document.querySelector('.notif-count-badge');
            if(badge) {
                let count = parseInt(badge.textContent);
                if(count > 1) {
                    badge.textContent = (count - 1) + ' baru';
                } else {
                    badge.remove();
                    // if count becomes 0, we can also remove the Tandai Dibaca button if we want
                    const readAllBtn = document.querySelector('.btn-read-all');
                    if (readAllBtn) readAllBtn.style.display = 'none';
                }
            }
        }
    });
}
</script>
@endpush
