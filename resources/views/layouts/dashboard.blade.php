<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>@yield('title', 'Dashboard') - OFA-UHB</title>
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <!-- Tabler Icons -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/dist/tabler-icons.min.css">

  <!-- Alpine.js -->
  <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
  <style>[x-cloak] { display: none !important; }</style>

  <!-- Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap"
    rel="stylesheet">

  <style>
    :root {
      --font-sans: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
    }

    body {
      margin: 0;
      padding: 0;
      font-family: var(--font-sans);
      background-color: #f0f4f8;
      height: 100vh;
      width: 100vw;
      overflow: hidden;
    }

    /* Remove default link styling */
    a {
      text-decoration: none;
    }
  </style>

  <style>
    /* [ORIGINAL CSS - Diubah ukuran menjadi full screen] */
    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0
    }

    .wrap {
      display: flex;
      height: 100vh;
      width: 100vw;
      background: #f0f4f8;
      overflow: hidden;
      border: none;
      border-radius: 0
    }

    .sidebar {
      width: 210px;
      background: #ffffff;
      border-right: 0.5px solid #e2e8f0;
      display: flex;
      flex-direction: column;
      flex-shrink: 0
    }

    .sidebar-logo {
      padding: 16px;
      border-bottom: 0.5px solid #e2e8f0;
      display: flex;
      align-items: center;
      gap: 10px
    }

    .logo-icon {
      width: 32px;
      height: 32px;
      background: #2563eb;
      border-radius: 8px;
      display: flex;
      align-items: center;
      justify-content: center;
      color: #fff;
      font-size: 15px
    }

    .logo-text {
      font-size: 13px;
      font-weight: 500;
      color: #111827;
      line-height: 1.3
    }

    .logo-text span {
      font-size: 11px;
      color: #6b7280;
      font-weight: 400
    }

    .nav-section {
      padding: 12px 8px 4px;
      font-size: 10px;
      color: #9ca3af;
      text-transform: uppercase;
      letter-spacing: 0.06em;
      font-weight: 500
    }

    .nav-item {
      display: flex;
      align-items: center;
      gap: 10px;
      padding: 8px 12px;
      margin: 1px 8px;
      border-radius: 8px;
      cursor: pointer;
      font-size: 13px;
      color: #4b5563;
      transition: background 0.1s
    }

    .nav-item:hover {
      background: #f1f5f9
    }

    .nav-item.active {
      background: #EFF6FF;
      color: #1D4ED8
    }

    .nav-item i {
      font-size: 16px;
      flex-shrink: 0
    }

    .nav-item .badge {
      margin-left: auto;
      background: #EFF6FF;
      color: #1D4ED8;
      font-size: 10px;
      padding: 1px 6px;
      border-radius: 10px;
      font-weight: 500
    }

    .nav-dropdown {
      display: flex;
      flex-direction: column;
      gap: 2px;
      margin: 0 8px;
    }

    .nav-dropdown-btn {
      display: flex;
      align-items: center;
      gap: 10px;
      padding: 8px 12px;
      border-radius: 8px;
      cursor: pointer;
      font-size: 13px;
      color: #4b5563;
      transition: background 0.1s;
      width: 100%;
      border: none;
      background: none;
      text-align: left;
    }

    .nav-dropdown-btn:hover {
      background: #f1f5f9
    }

    .nav-dropdown-btn.active {
      background: #EFF6FF;
      color: #1D4ED8
    }

    .nav-dropdown-btn i {
      font-size: 16px;
      flex-shrink: 0
    }

    .nav-dropdown-btn .chevron {
      margin-left: auto;
      font-size: 14px;
      transition: transform 0.2s ease;
    }

    .nav-dropdown-btn[aria-expanded="true"] .chevron {
      transform: rotate(180deg);
    }

    .nav-dropdown-menu {
      display: flex;
      flex-direction: column;
      gap: 2px;
      padding-left: 28px;
      overflow: hidden;
      transition: all 0.2s ease-in-out;
    }

    .nav-dropdown-item {
      display: flex;
      align-items: center;
      gap: 8px;
      padding: 7px 12px;
      border-radius: 6px;
      cursor: pointer;
      font-size: 12px;
      color: #6b7280;
      transition: all 0.1s;
      text-decoration: none;
    }

    .nav-dropdown-item:hover {
      background: #f1f5f9;
      color: #111827;
    }

    .nav-dropdown-item.active {
      color: #1D4ED8;
      font-weight: 500;
      background: #EFF6FF;
    }

    .sidebar-footer {
      margin-top: auto;
      padding: 12px;
      border-top: 0.5px solid #e2e8f0
    }

    .user-row {
      display: flex;
      align-items: center;
      gap: 8px
    }

    .avatar {
      width: 30px;
      height: 30px;
      border-radius: 50%;
      background: #DBEAFE;
      color: #1D4ED8;
      font-size: 11px;
      font-weight: 500;
      display: flex;
      align-items: center;
      justify-content: center;
      flex-shrink: 0
    }

    .user-info {
      font-size: 12px;
      color: #111827;
      font-weight: 500;
      line-height: 1.2
    }

    .user-info span {
      font-size: 11px;
      color: #6b7280;
      font-weight: 400;
      display: block
    }

    .main {
      flex: 1;
      overflow-y: auto;
      display: flex;
      flex-direction: column
    }

    .topbar {
      padding: 14px 20px;
      background: #ffffff;
      border-bottom: 0.5px solid #e2e8f0;
      display: flex;
      align-items: center;
      justify-content: space-between
    }

    .topbar-title {
      font-size: 15px;
      font-weight: 500;
      color: #111827
    }

    .topbar-actions {
      display: flex;
      align-items: center;
      gap: 8px
    }

    .icon-btn {
      width: 32px;
      height: 32px;
      border: 0.5px solid #d1d5db;
      border-radius: 8px;
      display: flex;
      align-items: center;
      justify-content: center;
      cursor: pointer;
      color: #6b7280;
      font-size: 16px;
      background: #ffffff
    }

    .role-badge {
      font-size: 11px;
      padding: 3px 10px;
      border-radius: 10px;
      font-weight: 500
    }

    .role-sa {
      background: #DBEAFE;
      color: #1D4ED8
    }

    .content {
      padding: 18px 20px;
      flex: 1;
      background: #f8fafc
    }

    .section-title {
      font-size: 12px;
      font-weight: 500;
      color: #6b7280;
      margin-bottom: 12px;
      display: flex;
      align-items: center;
      gap: 6px
    }

    .modules-grid {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 12px;
      margin-bottom: 20px
    }

    .mod-card {
      background: #ffffff;
      border: 0.5px solid #e2e8f0;
      border-radius: 12px;
      padding: 16px;
      cursor: pointer;
      transition: border-color 0.15s
    }

    .mod-card:hover {
      border-color: #93c5fd
    }

    .mod-icon {
      width: 38px;
      height: 38px;
      border-radius: 10px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 20px;
      margin-bottom: 10px
    }

    .mod-blue {
      background: #DBEAFE;
      color: #1D4ED8
    }

    .mod-teal {
      background: #CCFBF1;
      color: #0F766E
    }

    .mod-title {
      font-size: 13px;
      font-weight: 500;
      color: #111827;
      margin-bottom: 3px
    }

    .mod-desc {
      font-size: 11px;
      color: #6b7280;
      line-height: 1.5
    }

    .mod-footer {
      margin-top: 10px;
      padding-top: 10px;
      border-top: 0.5px solid #e2e8f0;
      display: flex;
      align-items: center;
      gap: 6px;
      font-size: 11px;
      color: #6b7280
    }

    .stats-grid {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 10px;
      margin-bottom: 20px
    }

    .stat-card {
      background: #ffffff;
      border: 0.5px solid #e2e8f0;
      border-radius: 8px;
      padding: 12px 14px
    }

    .stat-label {
      font-size: 11px;
      color: #6b7280;
      margin-bottom: 4px
    }

    .stat-val {
      font-size: 20px;
      font-weight: 500;
      color: #111827
    }

    .stat-sub {
      font-size: 11px;
      color: #9ca3af;
      margin-top: 2px
    }

    .stat-up {
      color: #059669
    }

    .activity-list {
      background: #ffffff;
      border: 0.5px solid #e2e8f0;
      border-radius: 12px;
      overflow: hidden
    }

    .activity-header {
      padding: 12px 14px;
      border-bottom: 0.5px solid #e2e8f0;
      font-size: 12px;
      font-weight: 500;
      color: #6b7280;
      display: flex;
      justify-content: space-between;
      align-items: center
    }

    .activity-item {
      padding: 10px 14px;
      border-bottom: 0.5px solid #f1f5f9;
      display: flex;
      align-items: center;
      gap: 10px;
      font-size: 12px
    }

    .activity-item:last-child {
      border-bottom: none
    }

    .act-dot {
      width: 7px;
      height: 7px;
      border-radius: 50%;
      flex-shrink: 0
    }

    .act-blue {
      background: #3B82F6
    }

    .act-teal {
      background: #14B8A6
    }

    .act-amber {
      background: #F59E0B
    }

    .act-text {
      flex: 1;
      color: #374151
    }

    .act-time {
      color: #9ca3af;
      font-size: 11px
    }

    .view-all {
      font-size: 11px;
      color: #1D4ED8;
      cursor: pointer
    }

    .role-tabs {
      display: flex;
      gap: 6px;
      margin-bottom: 16px
    }

    .rtab {
      font-size: 11px;
      padding: 5px 12px;
      border-radius: 20px;
      cursor: pointer;
      border: 0.5px solid #d1d5db;
      color: #4b5563;
      background: #ffffff
    }

    .rtab.active {
      background: #2563eb;
      color: #fff;
      border-color: #2563eb
    }
  </style>
  @stack('styles')
</head>

<body>

  <div class="wrap">
    <div class="sidebar">
      <div class="sidebar-logo">
        <div class="logo-icon"><i class="ti ti-building-bank" aria-hidden="true"></i></div>
        <div class="logo-text">OFA-UHB</div>
      </div>
      <div class="nav-section">Platform</div>
      <a href="{{ route('dashboard') }}" class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}" style="text-decoration:none;">
          <i class="ti ti-layout-dashboard" aria-hidden="true"></i>Dashboard
      </a>


      @php $sidebarUser = auth()->user(); @endphp

      {{-- MENU KAS: sembunyikan untuk admin_notulensi dan dosen --}}
      @if(!in_array($sidebarUser->role, ['admin_notulensi_fst', 'admin_notulensi_fis', 'dosen']))
      <div class="nav-section">Manajemen Kas</div>
      @php
          $kasActive = request()->routeIs('kas.*');
      @endphp
      <div class="nav-dropdown" x-data="{ open: {{ $kasActive ? 'true' : 'false' }} }">
          <button type="button" class="nav-dropdown-btn {{ $kasActive ? 'active' : '' }}" @click="open = !open" :aria-expanded="open">
              <i class="ti ti-cash" aria-hidden="true"></i>Manajemen Kas
              <i class="ti ti-chevron-down chevron" aria-hidden="true"></i>
          </button>
          <div class="nav-dropdown-menu" x-show="open" x-collapse x-cloak>
              <a href="{{ route('kas.masuk') }}" class="nav-dropdown-item {{ request()->routeIs('kas.masuk') ? 'active' : '' }}">Data Kas Masuk</a>
              <a href="{{ route('kas.keluar') }}" class="nav-dropdown-item {{ request()->routeIs('kas.keluar') ? 'active' : '' }}">Data Kas Keluar</a>
              <a href="{{ route('kas.tagihan') }}" class="nav-dropdown-item {{ request()->routeIs('kas.tagihan') ? 'active' : '' }}">Tagihan Dosen</a>
              <a href="{{ route('kas.laporan') }}" class="nav-dropdown-item {{ request()->routeIs('kas.laporan') ? 'active' : '' }}">Laporan Kas</a>
          </div>
      </div>
      @endif

      {{-- MENU NOTULENSI: sembunyikan untuk admin_kas --}}
      @if(!in_array($sidebarUser->role, ['admin_kas_fst', 'admin_kas_fis', 'dosen']))
      <div class="nav-section">Manajemen Notulensi</div>
      @php
          $notulensiActive = request()->routeIs('notulensi.*');
      @endphp
      <div class="nav-dropdown" x-data="{ open: {{ $notulensiActive ? 'true' : 'false' }} }">
          <button type="button" class="nav-dropdown-btn {{ $notulensiActive ? 'active' : '' }}" @click="open = !open" :aria-expanded="open">
              <i class="ti ti-notes" aria-hidden="true"></i>Notulensi Rapat
              <i class="ti ti-chevron-down chevron" aria-hidden="true"></i>
          </button>
          <div class="nav-dropdown-menu" x-show="open" x-collapse x-cloak>
              <a href="{{ route('notulensi.index') }}" class="nav-dropdown-item {{ request()->routeIs('notulensi.index') && !request()->has('action') ? 'active' : '' }}">
                  Data Notulensi
                  <span class="badge" style="margin-left: auto; background: #EFF6FF; color: #1D4ED8; font-size: 9px; padding: 1px 5px; border-radius: 10px; font-weight: 600;">{{ \App\Models\Notulensi::count() }}</span>
              </a>
              <a href="{{ route('notulensi.index', ['action' => 'export_bap']) }}" class="nav-dropdown-item {{ request()->get('action') == 'export_bap' ? 'active' : '' }}">Export BAP</a>
              <a href="{{ route('notulensi.index', ['action' => 'export_pdf']) }}" class="nav-dropdown-item {{ request()->get('action') == 'export_pdf' ? 'active' : '' }}">Export Notulensi (PDF)</a>
          </div>
      </div>
      @endif

      {{-- MENU MASTER DATA: hanya super_admin --}}
      @if($sidebarUser->role === 'super_admin')
      <div class="nav-section">Master Data</div>
      <a href="{{ route('master.users.index') }}" class="nav-item {{ request()->routeIs('master.users.*') ? 'active' : '' }}">
        <i class="ti ti-user-cog" aria-hidden="true"></i>Manajemen User
        <span class="badge">{{ \App\Models\User::count() }}</span>
      </a>
      <a href="{{ route('master.fakultas.index') }}" class="nav-item {{ request()->routeIs('master.fakultas.*') ? 'active' : '' }}">
        <i class="ti ti-school" aria-hidden="true"></i>Fakultas & Prodi
      </a>
      <a href="{{ route('master.dosen.index') }}" class="nav-item {{ request()->routeIs('master.dosen.*') ? 'active' : '' }}">
        <i class="ti ti-chalkboard-teacher" aria-hidden="true"></i>Data Dosen
      </a>
      @endif

      <div class="sidebar-footer">
        <div class="user-row">
          <div class="avatar">{{ substr(Auth::user()->name ?? 'SA', 0, 2) }}</div>
          <div class="user-info">{{ Auth::user()->name ?? 'User' }}<span>{{ Auth::user()->role_label ?? 'Administrator' }}</span></div>

          <!-- Logout Form & Icon -->
          <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            @csrf
          </form>
          <i class="ti ti-logout" style="margin-left:auto;font-size:16px;color:#9ca3af;cursor:pointer"
            aria-hidden="true" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
            title="Logout"></i>
        </div>
      </div>
    </div>

    <div class="main">
      <div class="topbar">
        <div>
          <div class="topbar-title">@yield('title', 'Dashboard')</div>
          <div style="font-size:11px;color:#6b7280">{{ now()->translatedFormat('l, d F Y') }}</div>
        </div>
        <div class="topbar-actions">


          {{-- Notifikasi Dropdown --}}
          <div style="position:relative" x-data="{ open: false }">
            <div class="icon-btn" style="position:relative" @click="open = !open; if(open) fetchNotif()">
              <i class="ti ti-bell" aria-hidden="true"></i>
              <span id="notif-badge"
                    style="position:absolute;top:-5px;right:-5px;background:#ef4444;color:#fff;
                           font-size:9px;font-weight:700;min-width:16px;height:16px;
                           border-radius:99px;padding:0 4px;display:none;
                           align-items:center;justify-content:center;">
              </span>
            </div>

            <div x-show="open"
                 x-cloak
                 @click.outside="open = false"
                 style="position:absolute;right:0;top:44px;width:320px;background:#fff;
                        border:1px solid #e2e8f0;border-radius:12px;
                        box-shadow:0 8px 32px rgba(0,0,0,.12);z-index:999;">

              <div style="padding:12px 16px;border-bottom:1px solid #f1f5f9;
                          display:flex;justify-content:space-between;align-items:center;">
                <span style="font-weight:600;font-size:13px;color:#111827;">Notifikasi</span>
                <span onclick="markAllRead()"
                      style="font-size:11px;color:#1d4ed8;cursor:pointer;font-weight:500;">
                  Tandai semua dibaca
                </span>
              </div>

              <div id="notif-list" style="max-height:300px;overflow-y:auto;"></div>

              <div style="padding:10px 16px;border-top:1px solid #f1f5f9;text-align:center;">
                <a href="{{ route('notifikasi.index') }}"
                   style="font-size:12px;color:#1d4ed8;font-weight:500;">
                  Lihat semua notifikasi →
                </a>
              </div>
            </div>
          </div>

          {{-- Settings --}}
          <a href="{{ route('pengaturan.profil') }}" class="icon-btn" title="Pengaturan Profil">
            <i class="ti ti-settings" aria-hidden="true"></i>
          </a>
        </div>
      </div>

      <div class="content">
          @yield('content')
      </div>
    </div>
  </div>
  @stack('scripts')

  <script>
    const _csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    function fetchNotif() {
      fetch('{{ route("notifikasi.unread") }}')
        .then(r => r.json())
        .then(data => {
          // Badge update
          const badge = document.getElementById('notif-badge');
          if (badge) {
            if (data.count > 0) {
              badge.style.display = 'flex';
              badge.textContent = data.count > 99 ? '99+' : data.count;
            } else {
              badge.style.display = 'none';
            }
          }
          // Sidebar badge update
          const sidebarBadge = document.getElementById('sidebar-notif-badge');
          if (sidebarBadge) {
            sidebarBadge.textContent = data.count;
            sidebarBadge.style.display = data.count > 0 ? 'inline' : 'none';
          }

          const list = document.getElementById('notif-list');
          if (!list) return;

          if (data.data.length === 0) {
            list.innerHTML = `
              <div style="padding:24px;text-align:center;color:#9ca3af;font-size:12px;">
                <i class="ti ti-bell-off" style="font-size:24px;display:block;margin-bottom:6px;"></i>
                Tidak ada notifikasi baru
              </div>`;
            return;
          }

          list.innerHTML = data.data.map(n => `
            <div onclick="readNotif(${n.id}, '${n.url || ''}')"
                 style="padding:12px 16px;border-bottom:1px solid #f8fafc;cursor:pointer;
                        background:${!n.dibaca ? '#eff6ff' : '#fff'};"
                 onmouseover="this.style.background='#f1f5f9'"
                 onmouseout="this.style.background='${!n.dibaca ? '#eff6ff' : '#fff'}'">
              <div style="display:flex;gap:10px;align-items:flex-start;">
                <div style="width:8px;height:8px;border-radius:50%;margin-top:5px;flex-shrink:0;
                            background:${n.tipe === 'notulensi' ? '#14b8a6' : '#9ca3af'};"></div>
                <div style="flex:1;min-width:0;">
                  <p style="font-size:12px;font-weight:${!n.dibaca ? '600' : '500'};
                             color:#111827;margin:0;white-space:nowrap;
                             overflow:hidden;text-overflow:ellipsis;">${n.judul}</p>
                  <p style="font-size:11px;color:#6b7280;margin:2px 0 0;
                             white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">${n.pesan}</p>
                </div>
              </div>
            </div>
          `).join('');
        });
    }

    function readNotif(id, url) {
      fetch(`/notifikasi/${id}/read`, {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': _csrfToken }
      }).then(() => {
        if (url && url !== 'null' && url.trim() !== '') {
          window.location.href = url;
        } else {
          fetchNotif();
        }
      });
    }

    function markAllRead() {
      fetch('/notifikasi/read-all', {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': _csrfToken }
      }).then(() => fetchNotif());
    }

    document.addEventListener('DOMContentLoaded', () => {
      fetchNotif();
      setInterval(fetchNotif, 30000); // auto-refresh tiap 30 detik
    });
  </script>
</body>

</html>
