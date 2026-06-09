@extends('layouts.dashboard')
@section('title', 'Arsip User')
@section('title_addon', 'Total: ' . $users->total())
@section('subtitle', 'Daftar akun pengguna yang dinonaktifkan')

@push('styles')
    <style>
        .master-card { background: #ffffff; border: 1px solid #e2e8f0; border-radius: 12px; font-family: 'Plus Jakarta Sans', sans-serif; overflow: hidden; margin-bottom: 24px; }
        .master-table { width: 100%; border-collapse: collapse; font-size: 13px; }
        .master-table th, .master-table td { padding: 12px 16px; text-align: left; border-bottom: 1px solid #e2e8f0; }
        .master-table th { background-color: #f8fafc; font-weight: 600; color: #475569; text-transform: uppercase; font-size: 11px; letter-spacing: 0.05em; }
        .master-badge { padding: 4px 8px; border-radius: 20px; font-size: 11px; font-weight: 500; display: inline-block; }
        .badge-aktif { background: #dcfce7; color: #166534; }
        .badge-nonaktif { background: #fee2e2; color: #991b1b; }
        .icon-btn { background: none; border: none; cursor: pointer; color: #64748b; display: inline-flex; align-items: center; justify-content: center; width: 28px; height: 28px; border-radius: 6px; }
        .icon-btn:hover { background: #f1f5f9; color: #2563eb; }
        .icon-btn.delete:hover { color: #ef4444; }
        .btn-primary { background-color: #2563eb; color: #fff; border: none; padding: 8px 16px; border-radius: 8px; cursor: pointer; font-size: 13px; font-weight: 500; display: inline-flex; align-items: center; gap: 6px; }
        .btn-primary:hover { background-color: #1d4ed8; }
        .btn-outline { background-color: #fff; color: #475569; border: 1px solid #e2e8f0; padding: 8px 16px; border-radius: 8px; font-size: 13px; font-weight: 500; cursor: pointer;}
        .btn-outline:hover { background-color: #f1f5f9; }
        .filter-control { border: 1px solid #e2e8f0; padding: 8px 12px; border-radius: 8px; font-size: 13px; outline: none; width: 100%; }
        .filter-control:focus { border-color: #2563eb; }
        
        #toast-container { position: fixed; top: 20px; right: 20px; z-index: 9999; display: flex; flex-direction: column; gap: 10px; }
        .custom-toast { min-width: 250px; background: #fff; border-left: 4px solid #10b981; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1); padding: 12px 16px; border-radius: 8px; font-size: 13px; font-weight: 500; display: flex; align-items: center; gap: 10px; animation: slideIn 0.3s ease forwards; font-family: 'Plus Jakarta Sans', sans-serif;}
        .custom-toast.error { border-left-color: #ef4444; }
        @keyframes slideIn { from { transform: translateX(100%); opacity: 0; } to { transform: translateX(0); opacity: 1; } }
    </style>
@endpush

@section('content')
    <div id="toast-container"></div>

    @livewire('users-arsip')

    <style>
        /* Pagination styles */
        .pagination-container nav > div:first-child { display: none; }
        .pagination-container nav > div:last-child { display: flex; justify-content: space-between; align-items: center; width: 100%; }
        .pagination-container nav p { margin: 0; font-size: 13px; color: #64748b; }
        .pagination-container nav p span { font-weight: 600; color: #1e293b; }
        .pagination-container nav span.shadow-sm, .pagination-container nav .relative.inline-flex { display: inline-flex; gap: 4px; box-shadow: none; }
        .pagination-container svg { width: 16px; height: 16px; display: inline; }
        .pagination-container nav .shadow-sm > span, .pagination-container nav .shadow-sm > a { display: inline-flex; align-items: center; justify-content: center; min-width: 32px; height: 32px; padding: 0 8px; border: 1px solid #e2e8f0; border-radius: 6px; font-size: 13px; color: #475569; background: #ffffff; text-decoration: none; transition: all 0.2s; }
        .pagination-container nav .shadow-sm > a:hover { background: #f1f5f9; color: #2563eb; }
        .pagination-container nav .shadow-sm > span[aria-current="page"] > span { background: #2563eb !important; color: #ffffff !important; border-color: #2563eb !important; border-radius: 6px; display: flex; align-items: center; justify-content: center; width: 100%; height: 100%; }
        .pagination-container nav .shadow-sm > span[aria-current="page"] { padding: 0; border: none; }
    </style>
@endsection

@push('scripts')
    <script>
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        function showToast(msg, type = 'success') {
            const t = document.createElement('div');
            t.className = `custom-toast ${type}`;
            t.innerHTML = `<i class="ti ti-${type === 'success' ? 'check' : 'alert-circle'}"></i> ${msg}`;
            document.getElementById('toast-container').appendChild(t);
            setTimeout(() => t.remove(), 3000);
        }

        async function restoreUser(id) {
            if (!confirm('Apakah Anda yakin ingin memulihkan user ini sehingga bisa aktif dan login kembali?')) return;
            try {
                const res = await fetch(`/master/users/${id}/toggle-status`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    }
                });

                const data = await res.json();
                if (res.ok && data.success) {
                    showToast(data.message, 'success');
                    setTimeout(() => location.reload(), 1000);
                } else {
                    showToast(data.message || 'Terjadi kesalahan', 'error');
                }
            } catch (err) {
                showToast('Koneksi gagal', 'error');
            }
        }

        async function deleteUser(id) {
            if (!confirm('Apakah Anda yakin ingin menghapus user ini secara permanen?')) return;
            try {
                const res = await fetch(`/master/users/${id}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ _method: 'DELETE' })
                });

                const data = await res.json();
                if (res.ok && data.success) {
                    showToast(data.message, 'success');
                    setTimeout(() => location.reload(), 1000);
                } else {
                    showToast(data.message || 'Terjadi kesalahan', 'error');
                }
            } catch (err) {
                showToast('Koneksi gagal', 'error');
            }
        }
    </script>
@endpush
