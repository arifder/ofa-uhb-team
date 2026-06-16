<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>@yield('title', 'Dashboard') - OFA-UHB</title>
  <link rel="icon" href="https://uhb.ac.id/wp-content/uploads/2024/03/logo_UHB_r-1.png">
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <!-- Tabler Icons -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/dist/tabler-icons.min.css">

  <!-- Alpine.js (Bawaan Livewire 3) -->
  <style>[x-cloak] { display: none !important; }</style>
  @livewireStyles

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

    /* Table responsiveness & formatting */
    .table-responsive {
      width: 100%;
      overflow-x: auto;
      -webkit-overflow-scrolling: touch;
    }
    .table-responsive table {
      width: 100%;
    }
    .table-responsive table th,
    .table-responsive table td {
      white-space: nowrap;
      text-align: center !important;
    }
    .table-responsive .master-table {
      min-width: 1000px;
    }
    .master-table th,
    .master-table td {
      text-align: center !important;
    }

    /* Fix table action icon buttons during Livewire wire:navigate */
    .master-table .icon-btn,
    .master-table button.icon-btn,
    .master-table a.icon-btn {
      background: none !important;
      border: none !important;
      cursor: pointer !important;
      color: #64748b !important;
      display: inline-flex !important;
      align-items: center !important;
      justify-content: center !important;
      width: 28px !important;
      height: 28px !important;
      border-radius: 6px !important;
      box-shadow: none !important;
    }
    .master-table .icon-btn:hover,
    .master-table button.icon-btn:hover,
    .master-table a.icon-btn:hover {
      background: #f1f5f9 !important;
      color: #2563eb !important;
    }
    .master-table .icon-btn.delete:hover,
    .master-table button.icon-btn.delete:hover,
    .master-table a.icon-btn.delete:hover {
      color: #ef4444 !important;
    }

    /* Fix modal close buttons during Livewire wire:navigate */
    .custom-modal-header .icon-btn,
    .detail-modal-content .icon-btn,
    .custom-modal-header button.icon-btn,
    .detail-modal-content button.icon-btn {
      background: none !important;
      border: none !important;
      cursor: pointer !important;
      color: #64748b !important;
      display: inline-flex !important;
      align-items: center !important;
      justify-content: center !important;
      width: 28px !important;
      height: 28px !important;
      border-radius: 6px !important;
      box-shadow: none !important;
    }
    .custom-modal-header .icon-btn:hover,
    .detail-modal-content .icon-btn:hover,
    .custom-modal-header button.icon-btn:hover,
    .detail-modal-content button.icon-btn:hover {
      background: #f1f5f9 !important;
      color: #2563eb !important;
    }
  </style>
  @stack('styles')
</head>

<body>

  @php
    $routeModule = request()->routeIs('notulensi.*') ? 'notulensi' : (request()->routeIs('kas.*') ? 'kas' : '');
  @endphp

  <div class="wrap"
       x-data="{
          activeModule: '{{ $routeModule }}' || localStorage.getItem('activeModule') || 'kas',
          syncActiveModule() {
            const path = window.location.pathname;
            if (path.startsWith('/notulensi')) {
              this.activeModule = 'notulensi';
            } else if (path.startsWith('/kas')) {
              this.activeModule = 'kas';
            }
            localStorage.setItem('activeModule', this.activeModule);
          }
       }"
       x-init="syncActiveModule(); document.addEventListener('livewire:navigated', () => syncActiveModule())">
    <div class="sidebar">
      <div class="sidebar-logo">
        <div style="width:32px; height:32px; border-radius:8px; display:flex; align-items:center; justify-content:center; overflow:hidden; background:#fff; border:1px solid #e2e8f0; box-shadow:0 1px 2px rgba(0,0,0,0.05); flex-shrink:0;">
            <img src="{{ asset('images/logo-uhb.png') }}" alt="Logo UHB" style="width:100%; height:100%; object-fit:contain; padding:4px;">
        </div>
        <div class="logo-text" style="font-family:'Syne', sans-serif; font-weight:700; letter-spacing:0.02em; margin-left:4px;">OFA-UHB</div>
      </div>
      <div class="nav-section">Platform</div>
      <a wire:navigate href="{{ route('dashboard') }}" class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}" style="text-decoration:none;">
          <i class="ti ti-layout-dashboard" aria-hidden="true"></i>Dashboard
      </a>


      @php $sidebarUser = auth()->user(); @endphp

      {{-- MANAJEMEN FAKULTAS (KAS & NOTULENSI) --}}
      @if(in_array($sidebarUser->role, ['super_admin', 'admin_fst', 'admin_fis', 'kepala_unit']))
      <div x-show="activeModule === 'kas'" x-cloak>
        <div class="nav-section">Manajemen Kas</div>
        <a wire:navigate href="{{ route('kas.masuk') }}" class="nav-item {{ request()->routeIs('kas.masuk') ? 'active' : '' }}"><i class="ti ti-cash" aria-hidden="true"></i>Kas Masuk</a>
        <a wire:navigate href="{{ route('kas.keluar') }}" class="nav-item {{ request()->routeIs('kas.keluar') ? 'active' : '' }}"><i class="ti ti-cash-off" aria-hidden="true"></i>Kas Keluar</a>
        <a wire:navigate href="{{ route('kas.tagihan') }}" class="nav-item {{ request()->routeIs('kas.tagihan') ? 'active' : '' }}"><i class="ti ti-file-invoice" aria-hidden="true"></i>Tagihan Dosen</a>
        <a wire:navigate href="{{ route('kas.laporan') }}" class="nav-item {{ request()->routeIs('kas.laporan') ? 'active' : '' }}"><i class="ti ti-report-analytics" aria-hidden="true"></i>Laporan Kas</a>
      </div>

      <div x-show="activeModule === 'notulensi'" x-cloak>
        <div class="nav-section">Notulensi Rapat</div>
        <a wire:navigate href="{{ route('notulensi.index') }}" class="nav-item {{ request()->routeIs('notulensi.*') ? 'active' : '' }}">
          <i class="ti ti-notes" aria-hidden="true"></i>Data Notulensi
        </a>
      </div>
      @endif

      {{-- MENU DOSEN --}}
      @if($sidebarUser->role === 'dosen')
      <div class="nav-section">Modul Dosen</div>
      <a wire:navigate href="{{ route('kas.masuk') }}" class="nav-item {{ request()->routeIs('kas.*') ? 'active' : '' }}"><i class="ti ti-history" aria-hidden="true"></i>Riwayat Kas Saya</a>
      <a wire:navigate href="{{ route('notulensi.index') }}" class="nav-item {{ request()->routeIs('notulensi.*') ? 'active' : '' }}">
        <i class="ti ti-notes" aria-hidden="true"></i>Manajemen Rapat
      </a>
      @endif

      {{-- MENU MASTER DATA: hanya super_admin --}}
      @if($sidebarUser->role === 'super_admin')
      <div class="nav-section">Master Data</div>
      <a wire:navigate href="{{ route('master.users.index') }}" class="nav-item {{ request()->routeIs('master.users.index') ? 'active' : '' }}">
        <i class="ti ti-user-cog" aria-hidden="true"></i>Manajemen User
      </a>
      <a wire:navigate href="{{ route('master.users.arsip') }}" class="nav-item {{ request()->routeIs('master.users.arsip') ? 'active' : '' }}">
        <i class="ti ti-archive" aria-hidden="true"></i>Arsip User
      </a>
      <a wire:navigate href="{{ route('master.fakultas.index') }}" class="nav-item {{ request()->routeIs('master.fakultas.*') ? 'active' : '' }}">
        <i class="ti ti-school" aria-hidden="true"></i>Fakultas & Prodi
      </a>
      <a wire:navigate href="{{ route('master.dosen.index') }}" class="nav-item {{ request()->routeIs('master.dosen.*') ? 'active' : '' }}">
        <i class="ti ti-chalkboard-teacher" aria-hidden="true"></i>Data Dosen
      </a>
      @endif

      <div class="sidebar-footer" x-data="{ openProfileMenu: false }" style="position:relative; padding:12px;">
        
        <!-- Popover Menu -->
        <div x-show="openProfileMenu" @click.outside="openProfileMenu = false" x-cloak x-transition.opacity.duration.200ms
             style="position:absolute; bottom:calc(100% + 5px); left:12px; width:calc(100% - 24px); 
                    background:#2e364f; color:#f8fafc; border-radius:12px; padding:8px; 
                    box-shadow:0 10px 25px rgba(0,0,0,0.25); z-index:50; border: 1px solid #475569;">
           
           @if(in_array($sidebarUser->role, ['super_admin', 'admin_fst', 'admin_fis', 'kepala_unit']))
           <div style="font-size:10px; color:#94a3b8; text-transform:uppercase; letter-spacing:0.05em; padding:4px 8px; margin-bottom:4px;">Ganti Modul</div>
           <a wire:navigate href="{{ route('kas.masuk') }}" @click="activeModule = 'kas'; localStorage.setItem('activeModule', 'kas'); openProfileMenu = false"
              style="display:flex; align-items:center; gap:8px; padding:8px; border-radius:8px; color:#f8fafc; text-decoration:none; font-size:13px;"
              onmouseover="this.style.background='#3f4a6b'" onmouseout="this.style.background='transparent'">
              <i class="ti ti-cash-register"></i> Manajemen Kas
              <i class="ti ti-check" style="margin-left:auto; font-size:14px;" x-show="activeModule === 'kas'"></i>
           </a>
           <a wire:navigate href="{{ route('notulensi.index') }}" @click="activeModule = 'notulensi'; localStorage.setItem('activeModule', 'notulensi'); openProfileMenu = false"
              style="display:flex; align-items:center; gap:8px; padding:8px; border-radius:8px; color:#f8fafc; text-decoration:none; font-size:13px; margin-bottom:8px;"
              onmouseover="this.style.background='#3f4a6b'" onmouseout="this.style.background='transparent'">
              <i class="ti ti-clipboard-list"></i> Notulensi Rapat
              <i class="ti ti-check" style="margin-left:auto; font-size:14px;" x-show="activeModule === 'notulensi'"></i>
           </a>
           <div style="height:1px; background:#475569; margin:4px 0 8px 0;"></div>
           @endif

           <a wire:navigate href="{{ route('pengaturan.profil') }}" style="display:flex; align-items:center; gap:8px; padding:8px; border-radius:8px; color:#f8fafc; text-decoration:none; font-size:13px;" onmouseover="this.style.background='#3f4a6b'" onmouseout="this.style.background='transparent'">
             <i class="ti ti-user-circle"></i> Profile
           </a>
           <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" style="display:flex; align-items:center; gap:8px; padding:8px; border-radius:8px; color:#fca5a5; text-decoration:none; font-size:13px;" onmouseover="this.style.background='#3f4a6b'" onmouseout="this.style.background='transparent'">
             <i class="ti ti-logout"></i> Log Out
           </a>
        </div>

        <div class="user-row" @click="openProfileMenu = !openProfileMenu" style="cursor:pointer; padding: 10px; border-radius: 10px; transition: background 0.2s; background: #2e364f; display:flex; align-items:center; gap:8px;" onmouseover="this.style.background='#3f4a6b'" onmouseout="this.style.background='#2e364f'">
          <div class="avatar" style="background:#3b82f6; color:#fff; border: 1px solid #60a5fa; flex-shrink:0; display:flex; align-items:center; justify-content:center;">{{ substr(Auth::user()->name ?? 'SA', 0, 2) }}</div>
          <div class="user-info" style="color:#f8fafc; flex:1; min-width:0;">
             <div style="font-size:12px; font-weight:600; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">{{ Auth::user()->name ?? 'User' }}</div>
             <div style="font-size:10px; color:#94a3b8; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; margin-top:1px;">{{ Auth::user()->email ?? 'Administrator' }}</div>
          </div>
          <i class="ti ti-chevron-right" style="flex-shrink:0; margin-left:auto;font-size:14px;color:#64748b;" :style="openProfileMenu ? 'transform: rotate(-90deg); transition: transform 0.2s;' : 'transition: transform 0.2s;'"></i>

          <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            @csrf
          </form>
        </div>
      </div>
    </div>

    <div class="main">
      <div class="topbar">
        <div></div>
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
                 style="position:absolute;right:0;top:44px;width:380px;background:#fff;
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

              <div id="notif-list" style="max-height:360px;overflow-y:auto;"></div>

              <div style="padding:10px 16px;border-top:1px solid #f1f5f9;text-align:center;">
                <a wire:navigate href="{{ route('notifikasi.index') }}"
                   style="font-size:12px;color:#1d4ed8;font-weight:500;">
                  Lihat semua notifikasi →
                </a>
              </div>
            </div>
          </div>

          {{-- Settings --}}
          <a wire:navigate href="{{ route('pengaturan.profil') }}" class="icon-btn" title="Pengaturan Profil">
            <i class="ti ti-settings" aria-hidden="true"></i>
          </a>
        </div>
      </div>

      <div class="content">
          <!-- Page Header -->
          @if(View::hasSection('title'))
          <div class="page-header" style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 24px; padding-bottom: 8px;">
            <div>
              <div style="display: flex; align-items: center; gap: 10px;">
                <h1 style="font-size: 20px; font-weight: 700; color: #0f172a; margin: 0; font-family: var(--font-sans);">@yield('title')</h1>
                @if (trim($__env->yieldContent('title_addon')))
                  <span style="font-size: 12px; font-weight: 600; color: #1d4ed8; background: #eff6ff; padding: 2px 8px; border-radius: 20px; border: 0.5px solid #bfdbfe;">@yield('title_addon')</span>
                @endif
              </div>
              <div style="font-size: 12px; color: #64748b; margin-top: 4px; display: flex; align-items: center; gap: 4px;">
                <i class="ti ti-calendar" style="font-size: 14px;"></i>
                <span>@yield('subtitle', now()->translatedFormat('l, d F Y'))</span>
              </div>
            </div>
            @if (trim($__env->yieldContent('topbar_actions')))
            <div style="display: flex; align-items: center; gap: 8px;">
              @yield('topbar_actions')
            </div>
            @endif
          </div>
          @endif

          @yield('content')
      </div>
    </div>
  </div>
  @livewireScripts
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

          list.innerHTML = data.data.map(n => {
            const emojiRegex = /[\u{1F300}-\u{1F5FF}\u{1F600}-\u{1F64F}\u{1F680}-\u{1F6FF}\u{2600}-\u{26FF}\u{2700}-\u{27BF}\u{1F900}-\u{1F9FF}\u{1FA00}-\u{1FAFF}\u{1F1E0}-\u{1F1FF}]/gu;
            let cleanJudul = n.judul.replace(/📋|✏️|🗑️|💰|📤|🧾|✅/g, '').replace(emojiRegex, '').trim();
            let cleanPesan = n.pesan.replace(/📋|✏️|🗑️|💰|📤|🧾|✅/g, '').replace(emojiRegex, '').trim();

            let iconName = 'ti-info-circle', iconBg = '#f1f5f9', iconColor = '#6b7280';
            let badgeBg = '#f1f5f9', badgeColor = '#6b7280';
            
            if (n.tipe === 'notulensi') {
                iconName = 'ti-notes';
                iconBg = '#ccfbf1'; iconColor = '#0f766e';
                badgeBg = '#ccfbf1'; badgeColor = '#0f766e';
            } else if (n.tipe === 'kas') {
                iconName = 'ti-cash';
                iconBg = '#dbeafe'; iconColor = '#1d4ed8';
                badgeBg = '#dbeafe'; badgeColor = '#1d4ed8';
            }
            
            const badgeText = n.tipe ? (n.tipe.charAt(0).toUpperCase() + n.tipe.slice(1)) : 'Sistem';

            return `
            <div onclick="readNotif(${n.id}, '${n.url || ''}')"
                 style="padding:16px 20px;border-bottom:1px solid #f1f5f9;cursor:pointer;
                        background:${!n.dibaca ? '#f0f7ff' : '#fff'}; display:flex; gap:16px; align-items:flex-start;"
                 onmouseover="this.style.background='#f8fafc'"
                 onmouseout="this.style.background='${!n.dibaca ? '#f0f7ff' : '#fff'}'">
              
              <div style="display:flex; flex-direction:column; align-items:center; gap:6px; flex-shrink:0; padding-top:2px;">
                  <div style="width:36px; height:36px; border-radius:10px; display:flex; align-items:center; justify-content:center; font-size:17px; background:${iconBg}; color:${iconColor};">
                      <i class="ti ${iconName}"></i>
                  </div>
                  ${!n.dibaca ? `<div style="width:7px; height:7px; border-radius:50%; background:#3b82f6; flex-shrink:0;"></div>` : ''}
              </div>

              <div style="flex:1; min-width:0;">
                <div style="display:flex; align-items:center; gap:8px; margin-bottom:4px; flex-wrap:wrap;">
                  <span style="font-size:13px; font-weight:${!n.dibaca ? '700' : '500'}; color:#111827; line-height:1.4;">${cleanJudul}</span>
                  <span style="font-size:10px; padding:2px 8px; border-radius:20px; font-weight:600; flex-shrink:0; background:${badgeBg}; color:${badgeColor};">${badgeText}</span>
                </div>
                <p style="font-size:12px; color:#6b7280; line-height:1.6; margin:0; display:-webkit-box; -webkit-line-clamp:2; -webkit-box-orient:vertical; overflow:hidden;">${cleanPesan}</p>
              </div>
            </div>
            `;
          }).join('');
        });
    }

    function readNotif(id, url) {
      fetch(`/notifikasi/${id}/read`, {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': _csrfToken }
      }).then(() => {
        if (url && url !== 'null' && url.trim() !== '') {
          if (window.Livewire && typeof window.Livewire.navigate === 'function') {
            window.Livewire.navigate(url);
          } else {
            window.location.href = url;
          }
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

    let notifIntervalId = null;

    function initNotifPolling() {
      fetchNotif();
      if (!notifIntervalId) {
        notifIntervalId = setInterval(fetchNotif, 30000);
      }
    }

    document.addEventListener('DOMContentLoaded', initNotifPolling);
    document.addEventListener('livewire:navigated', initNotifPolling);
  </script>
</body>

</html>
