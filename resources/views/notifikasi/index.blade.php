@extends('layouts.dashboard')
@section('title', 'Notifikasi')

@push('styles')
<style>
  .notif-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 16px;
  }

  .notif-header-title {
    font-size: 14px;
    font-weight: 600;
    color: #111827;
    display: flex;
    align-items: center;
    gap: 8px;
  }

  .notif-header-title i {
    font-size: 18px;
    color: #3b82f6;
  }

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
  }

  .btn-read-all:hover {
    background: #dbeafe;
  }

  .notif-list {
    background: #ffffff;
    border: 0.5px solid #e2e8f0;
    border-radius: 12px;
    overflow: hidden;
  }

  .notif-item {
    display: flex;
    align-items: flex-start;
    gap: 12px;
    padding: 14px 16px;
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
    background: #eff6ff;
  }

  .notif-item.belum-dibaca:hover {
    background: #dbeafe;
  }

  .notif-dot {
    width: 9px;
    height: 9px;
    border-radius: 50%;
    flex-shrink: 0;
    margin-top: 5px;
  }

  .dot-notulensi { background: #14b8a6; }
  .dot-sistem    { background: #9ca3af; }

  .notif-body {
    flex: 1;
    min-width: 0;
  }

  .notif-judul {
    font-size: 13px;
    color: #111827;
    margin-bottom: 3px;
    display: flex;
    align-items: center;
    gap: 8px;
  }

  .notif-judul.bold {
    font-weight: 600;
  }

  .notif-badge-tipe {
    font-size: 10px;
    padding: 1px 7px;
    border-radius: 10px;
    font-weight: 500;
    flex-shrink: 0;
  }

  .tipe-notulensi { background: #ccfbf1; color: #0f766e; }
  .tipe-sistem    { background: #f1f5f9; color: #6b7280; }

  .notif-pesan {
    font-size: 12px;
    color: #6b7280;
    line-height: 1.5;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
  }

  .notif-time {
    font-size: 11px;
    color: #9ca3af;
    flex-shrink: 0;
    margin-top: 2px;
  }

  .notif-empty {
    padding: 40px 20px;
    text-align: center;
    color: #9ca3af;
    font-size: 13px;
  }

  .notif-empty i {
    font-size: 36px;
    display: block;
    margin-bottom: 10px;
    color: #d1d5db;
  }

  .pagination-wrap {
    margin-top: 16px;
    display: flex;
    justify-content: center;
  }

  /* Override Laravel pagination */
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
</style>
@endpush

@section('content')

<div class="notif-header">
  <div class="notif-header-title">
    <i class="ti ti-bell"></i>
    Notifikasi
    @php
      $jumlahBelumDibaca = \App\Models\Notifikasi::where('user_id', auth()->id())->belumDibaca()->count();
    @endphp
    @if($jumlahBelumDibaca > 0)
      <span style="background:#2563eb;color:#fff;font-size:11px;padding:1px 8px;border-radius:10px;font-weight:500;">
        {{ $jumlahBelumDibaca }} baru
      </span>
    @endif
  </div>

  @if($jumlahBelumDibaca > 0)
  <form action="{{ route('notifikasi.readAll') }}" method="POST">
    @csrf
    <button type="submit" class="btn-read-all">
      <i class="ti ti-checks"></i> Tandai Semua Dibaca
    </button>
  </form>
  @endif
</div>

<div class="notif-list">
  @forelse($notifikasi as $item)
    <a
      href="{{ route('notifikasi.read', $item->id) }}"
      class="notif-item {{ !$item->dibaca ? 'belum-dibaca' : '' }}"
      onclick="event.preventDefault(); document.getElementById('read-form-{{ $item->id }}').submit();"
    >
      <div class="notif-dot dot-{{ $item->tipe === 'notulensi' ? 'notulensi' : 'sistem' }}"></div>

      <div class="notif-body">
        <div class="notif-judul {{ !$item->dibaca ? 'bold' : '' }}">
          {{ $item->judul }}
          <span class="notif-badge-tipe tipe-{{ $item->tipe }}">{{ ucfirst($item->tipe) }}</span>
          @if(!$item->dibaca)
            <span style="width:7px;height:7px;border-radius:50%;background:#3b82f6;display:inline-block;flex-shrink:0;"></span>
          @endif
        </div>
        <div class="notif-pesan">{{ $item->pesan }}</div>
      </div>

      <div class="notif-time">{{ $item->created_at->diffForHumans() }}</div>
    </a>

    {{-- Hidden form for mark-read redirect --}}
    <form id="read-form-{{ $item->id }}"
          action="{{ route('notifikasi.read', $item->id) }}"
          method="POST"
          style="display:none;">
      @csrf
    </form>

  @empty
    <div class="notif-empty">
      <i class="ti ti-bell-off"></i>
      Tidak ada notifikasi saat ini.
    </div>
  @endforelse
</div>

@if($notifikasi->hasPages())
  <div class="pagination-wrap">
    {{ $notifikasi->links() }}
  </div>
@endif

@endsection
