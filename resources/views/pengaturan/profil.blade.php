@extends('layouts.dashboard')
@section('title', 'Pengaturan Profil')
@section('subtitle', 'Kelola informasi akun dan keamanan Anda')

@push('styles')
<style>
  .profil-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 16px;
    align-items: start;
  }

  @media (max-width: 768px) {
    .profil-grid { grid-template-columns: 1fr; }
  }

  .profil-card {
    background: #fff;
    border: 0.5px solid #e2e8f0;
    border-radius: 12px;
    overflow: hidden;
  }

  .profil-card-header {
    padding: 14px 18px;
    border-bottom: 0.5px solid #f1f5f9;
    display: flex;
    align-items: center;
    gap: 8px;
  }

  .profil-card-header i {
    font-size: 16px;
    color: #3b82f6;
  }

  .profil-card-header span {
    font-size: 13px;
    font-weight: 600;
    color: #111827;
  }

  .profil-card-body {
    padding: 20px 18px;
  }

  /* Avatar */
  .profil-avatar-wrap {
    display: flex;
    flex-direction: column;
    align-items: center;
    margin-bottom: 24px;
  }

  .profil-avatar {
    width: 72px;
    height: 72px;
    border-radius: 50%;
    background: linear-gradient(135deg, #2563eb, #7c3aed);
    color: #fff;
    font-size: 24px;
    font-weight: 700;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 10px;
    letter-spacing: 1px;
  }

  .profil-name-label {
    font-size: 15px;
    font-weight: 600;
    color: #111827;
    margin-bottom: 3px;
    text-align: center;
  }

  /* Form */
  .form-group {
    margin-bottom: 14px;
  }

  .form-label {
    font-size: 12px;
    font-weight: 500;
    color: #374151;
    margin-bottom: 5px;
    display: block;
  }

  .form-control {
    width: 100%;
    padding: 8px 12px;
    font-size: 13px;
    border: 0.5px solid #d1d5db;
    border-radius: 8px;
    outline: none;
    transition: border-color 0.15s;
    font-family: inherit;
    color: #111827;
    background: #fff;
    box-sizing: border-box;
  }

  .form-control:focus {
    border-color: #2563eb;
    box-shadow: 0 0 0 3px rgba(37,99,235,.08);
  }

  .form-control:disabled {
    background: #f9fafb;
    color: #6b7280;
    cursor: not-allowed;
  }

  .btn-primary {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 8px 18px;
    font-size: 13px;
    font-weight: 500;
    color: #fff;
    background: #2563eb;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    transition: background 0.15s;
    font-family: inherit;
  }

  .btn-primary:hover { background: #1d4ed8; }
  .btn-primary:disabled { opacity: 0.6; cursor: not-allowed; }

  .role-pill {
    display: inline-flex;
    align-items: center;
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 500;
  }

  /* Toast */
  .toast-container {
    position: fixed;
    top: 20px;
    right: 20px;
    z-index: 9999;
    display: flex;
    flex-direction: column;
    gap: 8px;
  }

  .toast {
    padding: 12px 16px;
    border-radius: 10px;
    font-size: 13px;
    font-weight: 500;
    display: flex;
    align-items: center;
    gap: 8px;
    min-width: 260px;
    box-shadow: 0 4px 20px rgba(0,0,0,.12);
    animation: slideIn 0.25s ease;
  }

  .toast-success { background: #f0fdf4; border: 1px solid #bbf7d0; color: #166534; }
  .toast-error   { background: #fef2f2; border: 1px solid #fecaca; color: #991b1b; }

  @keyframes slideIn {
    from { opacity: 0; transform: translateX(30px); }
    to   { opacity: 1; transform: translateX(0); }
  }

  .form-hint {
    font-size: 11px;
    color: #9ca3af;
    margin-top: 4px;
  }
</style>
@endpush

@section('content')

{{-- Toast container --}}
<div class="toast-container" id="toast-container"></div>

<div class="profil-grid">

  {{-- ── CARD 1: Informasi Profil ── --}}
  <div class="profil-card">
    <div class="profil-card-header">
      <i class="ti ti-id-badge"></i>
      <span>Informasi Profil</span>
    </div>
    <div class="profil-card-body">

      {{-- Avatar --}}
      <div class="profil-avatar-wrap">
        <div class="profil-avatar" id="avatar-initials">
          {{ strtoupper(substr($user->name, 0, 1)) }}{{ strtoupper(substr(strstr($user->name, ' ') ?: ' ', 1, 1)) }}
        </div>
        <div class="profil-name-label" id="display-name">{{ $user->name }}</div>
        <span class="role-pill"
              style="background:{{ $user->role_badge_color['bg'] }};color:{{ $user->role_badge_color['text'] }}">
          {{ $user->role_label }}
        </span>
      </div>

      <form id="form-profil">
        @csrf

        <div class="form-group">
          <label class="form-label">Nama Lengkap</label>
          <input type="text" name="name" id="inp-name"
                 class="form-control" value="{{ $user->name }}" required>
        </div>

        <div class="form-group">
          <label class="form-label">Username</label>
          <input type="text" name="username" id="inp-username"
                 class="form-control" value="{{ $user->username }}" required>
          <div class="form-hint">Digunakan untuk login sistem.</div>
        </div>

        <div class="form-group">
          <label class="form-label">Email</label>
          <input type="email" name="email" id="inp-email"
                 class="form-control" value="{{ $user->email }}" required>
        </div>

        <div class="form-group">
          <label class="form-label">Role</label>
          <input type="text" class="form-control" value="{{ $user->role_label }}" disabled>
        </div>

        @if($user->fakultas)
        <div class="form-group">
          <label class="form-label">Fakultas</label>
          <input type="text" class="form-control"
                 value="{{ $user->fakultas->nama_fakultas }}" disabled>
        </div>
        @endif

        <button type="button" class="btn-primary" id="btn-simpan" onclick="simpanProfil()">
          <i class="ti ti-device-floppy"></i> Simpan Perubahan
        </button>
      </form>
    </div>
  </div>

  {{-- ── CARD 2: Ubah Password ── --}}
  <div class="profil-card">
    <div class="profil-card-header">
      <i class="ti ti-lock"></i>
      <span>Ubah Password</span>
    </div>
    <div class="profil-card-body">

      <div style="background:#fffbeb;border:0.5px solid #fde68a;border-radius:8px;padding:10px 14px;margin-bottom:18px;font-size:12px;color:#92400e;display:flex;align-items:flex-start;gap:8px;">
        <i class="ti ti-info-circle" style="flex-shrink:0;margin-top:1px;"></i>
        <span>
          Password default akun ini adalah
          <strong>{{ $user->username }}123</strong>.
          Segera ubah untuk keamanan akun Anda.
        </span>
      </div>

      <form id="form-password">
        @csrf

        <div class="form-group">
          <label class="form-label">Password Lama</label>
          <input type="password" name="password_lama" id="inp-old-pw"
                 class="form-control" placeholder="••••••••" required>
        </div>

        <div class="form-group">
          <label class="form-label">Password Baru</label>
          <input type="password" name="password_baru" id="inp-new-pw"
                 class="form-control" placeholder="Minimal 6 karakter" required>
        </div>

        <div class="form-group">
          <label class="form-label">Konfirmasi Password Baru</label>
          <input type="password" name="password_baru_confirmation" id="inp-conf-pw"
                 class="form-control" placeholder="Ulangi password baru" required>
        </div>

        <button type="button" class="btn-primary" id="btn-pw" onclick="ubahPassword()">
          <i class="ti ti-shield-lock"></i> Ubah Password
        </button>
      </form>
    </div>
  </div>

</div>
@endsection

@push('scripts')
<script>
  const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

  function showToast(msg, type = 'success') {
    const container = document.getElementById('toast-container');
    const icon = type === 'success' ? 'ti-circle-check' : 'ti-alert-circle';
    const el = document.createElement('div');
    el.className = `toast toast-${type}`;
    el.innerHTML = `<i class="ti ${icon}"></i> ${msg}`;
    container.appendChild(el);
    setTimeout(() => el.remove(), 4000);
  }

  function getInitials(name) {
    const parts = name.trim().split(' ');
    if (parts.length === 1) return parts[0].substring(0, 2).toUpperCase();
    return (parts[0][0] + parts[1][0]).toUpperCase();
  }

  function simpanProfil() {
    const btn = document.getElementById('btn-simpan');
    btn.disabled = true;
    btn.innerHTML = '<i class="ti ti-loader-2"></i> Menyimpan...';

    const body = new FormData(document.getElementById('form-profil'));

    fetch('{{ route("pengaturan.update") }}', {
      method: 'POST',
      headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' },
      body: body,
    })
    .then(r => r.json())
    .then(data => {
      if (data.success) {
        showToast(data.message, 'success');
        // Update tampilan nama di halaman
        const newName = document.getElementById('inp-name').value;
        document.getElementById('display-name').textContent = newName;
        document.getElementById('avatar-initials').textContent = getInitials(newName);
        // Update sidebar & topbar (elemen di layout)
        document.querySelectorAll('.js-user-name').forEach(el => el.textContent = newName);
      } else {
        showToast(data.message || 'Gagal menyimpan.', 'error');
      }
    })
    .catch(() => showToast('Terjadi kesalahan.', 'error'))
    .finally(() => {
      btn.disabled = false;
      btn.innerHTML = '<i class="ti ti-device-floppy"></i> Simpan Perubahan';
    });
  }

  function ubahPassword() {
    const newPw  = document.getElementById('inp-new-pw').value;
    const confPw = document.getElementById('inp-conf-pw').value;

    if (newPw !== confPw) {
      showToast('Konfirmasi password tidak cocok.', 'error');
      return;
    }

    const btn = document.getElementById('btn-pw');
    btn.disabled = true;
    btn.innerHTML = '<i class="ti ti-loader-2"></i> Memproses...';

    const body = new FormData(document.getElementById('form-password'));

    fetch('{{ route("pengaturan.password") }}', {
      method: 'POST',
      headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' },
      body: body,
    })
    .then(r => r.json())
    .then(data => {
      if (data.success) {
        showToast(data.message, 'success');
        document.getElementById('form-password').reset();
      } else {
        showToast(data.message || 'Gagal mengubah password.', 'error');
      }
    })
    .catch(() => showToast('Terjadi kesalahan.', 'error'))
    .finally(() => {
      btn.disabled = false;
      btn.innerHTML = '<i class="ti ti-shield-lock"></i> Ubah Password';
    });
  }
</script>
@endpush
