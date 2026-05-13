<x-guest-layout>
<style>
@import url('https://fonts.googleapis.com/css2?family=Syne:wght@700;800&family=Plus+Jakarta+Sans:wght@400;500;600&display=swap');
.font-syne{font-family:'Syne',sans-serif;}
.font-jakarta{font-family:'Plus Jakarta Sans',sans-serif;}
@keyframes float{0%,100%{transform:translateY(0)}50%{transform:translateY(-8px)}}
@keyframes floatA{0%,100%{transform:translateY(0)}50%{transform:translateY(-6px)}}
@keyframes floatB{0%,100%{transform:translateY(0)}50%{transform:translateY(-5px)}}
.anim-float{animation:float 6s ease-in-out infinite;}
.anim-float-a{animation:floatA 5s ease-in-out infinite;animation-delay:1s;}
.anim-float-b{animation:floatB 7s ease-in-out infinite;animation-delay:2s;}
@keyframes pulse-dot{0%,100%{opacity:1}50%{opacity:.4}}
.dot-pulse{animation:pulse-dot 2s ease-in-out infinite;}
</style>

<div class="min-h-screen flex font-jakarta">

    {{-- LEFT PANEL --}}
    <div class="hidden lg:flex lg:w-[55%] flex-col justify-between p-12 relative overflow-hidden" style="background:linear-gradient(135deg,#0a0f1e 0%,#0d1a3a 30%,#0f2860 65%,#0a4a94 100%);">

        {{-- Orbs --}}
        <div class="absolute -top-20 right-10 w-96 h-96 rounded-full pointer-events-none" style="background:radial-gradient(circle,#22d3ee,transparent);filter:blur(60px);opacity:.20;"></div>
        <div class="absolute bottom-20 -left-20 w-80 h-80 rounded-full pointer-events-none" style="background:radial-gradient(circle,#2d7de0,transparent);filter:blur(50px);opacity:.15;"></div>
        <div class="absolute top-1/2 left-1/2 w-48 h-48 rounded-full pointer-events-none" style="background:radial-gradient(circle,#4d9ef7,transparent);filter:blur(40px);opacity:.10;transform:translate(-50%,-50%);"></div>

        {{-- Grid overlay --}}
        <div class="absolute inset-0 pointer-events-none" style="background-image:linear-gradient(rgba(255,255,255,.07) 1px,transparent 1px),linear-gradient(90deg,rgba(255,255,255,.07) 1px,transparent 1px);background-size:40px 40px;opacity:.05;"></div>

        {{-- Logo --}}
        <div class="relative z-10 flex items-center gap-3">
            <div class="w-9 h-9 rounded-xl flex items-center justify-center" style="background:rgba(255,255,255,.12);border:1px solid rgba(255,255,255,.2);">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="#22d3ee" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 21h18M3 10h18M3 7l9-4 9 4M4 10h1v11H4zm15 0h1v11h-1zM9 10h1v11H9zm5 0h1v11h-1z"/></svg>
            </div>
            <span class="font-syne text-white font-bold text-base tracking-wide">UHB System</span>
            <span class="text-white/20 text-xs">|</span>
            <span class="text-cyan-300 text-xs font-medium">Universitas Harapan Bangsa</span>
        </div>

        {{-- Middle Content --}}
        <div class="relative z-10 flex-1 flex flex-col justify-center py-8">

            {{-- Badge --}}
            <div class="inline-flex items-center gap-2 w-fit px-3 py-1.5 rounded-full mb-7 text-xs font-semibold text-cyan-300" style="background:rgba(34,211,238,.1);border:1px solid rgba(34,211,238,.3);">
                <span class="w-1.5 h-1.5 rounded-full bg-cyan-400 dot-pulse"></span> Enterprise ERP v2.0
            </div>

            {{-- Heading --}}
            <h1 class="font-syne font-extrabold text-white leading-tight mb-2" style="font-size:clamp(1.8rem,3vw,2.6rem);letter-spacing:-0.02em;">Website Office &<br><span style="background:linear-gradient(90deg,#ffffff,#a5f3fc);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;">Financial Administration</span></h1>
            <p class="font-syne text-2xl font-bold text-cyan-400 mb-1 tracking-wider">OFA-UHB</p>
            <div class="w-24 h-0.5 mb-4 rounded-full" style="background:linear-gradient(90deg,#22d3ee,transparent);"></div>
            <p class="text-blue-200/85 text-sm max-w-sm leading-relaxed">Sistem pengelolaan kas dan notulensi rapat untuk mendukung administrasi fakultas dan dosen secara terintegrasi.</p>

            {{-- Floating Dashboard Container --}}
            <div class="relative mt-8 max-w-sm">

                {{-- Floating Card Top-Right --}}
                <div class="absolute -top-4 -right-6 z-20 w-44 rounded-2xl p-4 anim-float-a" style="background:rgba(13,26,62,.85);border:1px solid rgba(255,255,255,.15);backdrop-filter:blur(20px);box-shadow:0 20px 60px rgba(0,0,0,.4);">
                    <div class="flex items-center gap-2 mb-1">
                        <svg class="w-4 h-4 text-cyan-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        <span class="text-white/60 text-xs">Notulensi</span>
                    </div>
                    <p class="font-syne text-cyan-400 font-bold text-2xl">36</p>
                    <p class="text-white/40 text-xs mt-0.5">Rapat bulan ini</p>
                </div>

                {{-- Main Dashboard Card --}}
                <div class="rounded-2xl p-5 anim-float" style="background:rgba(255,255,255,.06);border:1px solid rgba(255,255,255,.12);backdrop-filter:blur(20px);">
                    <div class="flex items-center justify-between mb-4">
                        <span class="text-white/80 text-sm font-semibold">Dashboard Keuangan</span>
                        <span class="flex items-center gap-1.5 text-xs text-cyan-300 px-2 py-0.5 rounded-full" style="background:rgba(34,211,238,.1);border:1px solid rgba(34,211,238,.2);"><span class="w-1.5 h-1.5 rounded-full bg-cyan-400 dot-pulse"></span>Live</span>
                    </div>
                    {{-- Stat Cards --}}
                    <div class="grid grid-cols-3 gap-2 mb-4">
                        <div class="rounded-xl p-3" style="background:rgba(255,255,255,.05);">
                            <p class="text-white/50 text-xs mb-1">Total Kas</p>
                            <p class="text-white font-bold text-sm">48,5M</p>
                            <p class="text-emerald-400 text-xs">+12.4%</p>
                        </div>
                        <div class="rounded-xl p-3" style="background:rgba(255,255,255,.05);">
                            <p class="text-white/50 text-xs mb-1">Transaksi</p>
                            <p class="text-white font-bold text-sm">1.284</p>
                            <p class="text-emerald-400 text-xs">+8.1%</p>
                        </div>
                        <div class="rounded-xl p-3" style="background:rgba(255,255,255,.05);">
                            <p class="text-white/50 text-xs mb-1">Notulensi</p>
                            <p class="text-white font-bold text-sm">36</p>
                            <p class="text-blue-300 text-xs">Stabil</p>
                        </div>
                    </div>
                    {{-- Bar Chart --}}
                    <div class="flex items-end gap-1 h-12">
                        @foreach([45,62,38,78,55,88,70,92,65,84,73,95] as $h)
                        <div class="flex-1 rounded-sm" style="height:{{ $h }}%;background:linear-gradient(180deg,#22d3ee,#2d7de0);opacity:{{ 0.4 + ($loop->index * 0.05) }};"></div>
                        @endforeach
                    </div>
                    <div class="flex justify-between mt-1.5">
                        <span class="text-white/30 text-xs">Jan</span>
                        <span class="text-white/30 text-xs">Des</span>
                    </div>
                </div>

                {{-- Floating Card Bottom-Left --}}
                <div class="absolute -bottom-5 -left-6 z-20 w-48 rounded-2xl p-4 anim-float-b" style="background:rgba(13,26,62,.85);border:1px solid rgba(255,255,255,.15);backdrop-filter:blur(20px);box-shadow:0 20px 60px rgba(0,0,0,.4);">
                    <div class="flex items-center gap-2 mb-1">
                        <svg class="w-4 h-4 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span class="text-white/60 text-xs">Disetujui</span>
                    </div>
                    <div class="flex items-end gap-2">
                        <p class="font-syne text-emerald-400 font-bold text-2xl">98.2%</p>
                        <span class="text-xs text-emerald-400 mb-1 px-1.5 py-0.5 rounded-full" style="background:rgba(52,211,153,.1);">+2.1%</span>
                    </div>
                    <p class="text-white/40 text-xs mt-0.5">Tingkat keberhasilan</p>
                </div>
            </div>

            {{-- Feature Cards --}}
            <div class="grid grid-cols-3 gap-3 max-w-sm mt-14">
                <div class="rounded-xl p-3.5 hover:scale-105 transition-transform duration-300 cursor-default" style="background:rgba(255,255,255,.07);border:1px solid rgba(255,255,255,.1);">
                    <div class="w-7 h-7 rounded-lg flex items-center justify-center mb-2" style="background:rgba(34,211,238,.15);">
                        <svg class="w-4 h-4 text-cyan-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75"/></svg>
                    </div>
                    <p class="text-white text-xs font-semibold">Manajemen Kas</p>
                    <p class="text-white/40 text-xs mt-0.5">Kelola arus keuangan</p>
                </div>
                <div class="rounded-xl p-3.5 hover:scale-105 transition-transform duration-300 cursor-default" style="background:rgba(255,255,255,.07);border:1px solid rgba(255,255,255,.1);">
                    <div class="w-7 h-7 rounded-lg flex items-center justify-center mb-2" style="background:rgba(45,125,224,.2);">
                        <svg class="w-4 h-4 text-blue-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/></svg>
                    </div>
                    <p class="text-white text-xs font-semibold">Rekap Notulensi</p>
                    <p class="text-white/40 text-xs mt-0.5">Arsip rapat digital</p>
                </div>
                <div class="rounded-xl p-3.5 hover:scale-105 transition-transform duration-300 cursor-default" style="background:rgba(255,255,255,.07);border:1px solid rgba(255,255,255,.1);">
                    <div class="w-7 h-7 rounded-lg flex items-center justify-center mb-2" style="background:rgba(77,158,247,.15);">
                        <svg class="w-4 h-4 text-blue-200" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 3v11.25A2.25 2.25 0 006 16.5h2.25M3.75 3h-1.5m1.5 0h16.5m0 0h1.5m-1.5 0v11.25A2.25 2.25 0 0118 16.5h-2.25m-7.5 0h7.5m-7.5 0l-1 3m8.5-3l1 3m0 0l.5 1.5m-.5-1.5h-9.5m0 0l-.5 1.5"/></svg>
                    </div>
                    <p class="text-white text-xs font-semibold">Monitoring</p>
                    <p class="text-white/40 text-xs mt-0.5">Pantau administrasi</p>
                </div>
            </div>
        </div>

        {{-- Footer --}}
        <div class="relative z-10 flex items-center justify-between pt-6" style="border-top:1px solid rgba(255,255,255,.08);">
            <p class="text-white/30 text-xs">© {{ date('Y') }} Universitas Harapan Bangsa</p>
            <div class="flex items-center gap-2">
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 dot-pulse"></span>
                <span class="text-emerald-400 text-xs font-medium">Sistem Aktif</span>
            </div>
        </div>
    </div>

    {{-- RIGHT PANEL --}}
    <div class="flex-1 lg:w-[45%] flex items-center justify-center bg-white relative min-h-screen lg:min-h-0" style="background:radial-gradient(ellipse at top left,rgba(29,96,200,.04) 0%,transparent 50%),radial-gradient(ellipse at bottom right,rgba(34,211,238,.04) 0%,transparent 50%),#ffffff;">

        {{-- Mobile Logo --}}
        <div class="absolute top-6 left-6 flex items-center gap-2 lg:hidden">
            <div class="w-8 h-8 rounded-xl flex items-center justify-center" style="background:linear-gradient(135deg,#0f2860,#1d60c8);">
                <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 21h18M3 10h18M3 7l9-4 9 4M4 10h1v11H4zm15 0h1v11h-1zM9 10h1v11H9zm5 0h1v11h-1z"/></svg>
            </div>
            <div><p class="font-syne font-bold text-slate-800 text-sm leading-none">OFA-UHB</p><p class="text-slate-400 text-xs">Office & Financial Administration</p></div>
        </div>

        <div class="w-full max-w-md px-8 py-12">

            {{-- Heading --}}
            <div class="mb-7">
                <h2 class="font-syne font-bold text-slate-900 text-2xl xl:text-3xl mb-1.5">Selamat datang 👋</h2>
                <p class="text-slate-500 text-sm">Masuk ke sistem OFA-UHB untuk melanjutkan.</p>
            </div>

            {{-- Role Badges --}}
            <div class="mb-6">
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-widest mb-2.5">Akses Tersedia</p>
                <div class="flex flex-wrap gap-2">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-semibold" style="background:rgba(10,15,30,.06);border:1px solid rgba(10,15,30,.12);color:#0f2860;"><span class="w-1.5 h-1.5 rounded-full" style="background:#0f2860;"></span>Super Admin</span>
                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-semibold" style="background:rgba(29,96,200,.08);border:1px solid rgba(29,96,200,.2);color:#1a3a6b;"><span class="w-1.5 h-1.5 rounded-full bg-blue-600"></span>Admin Fakultas</span>
                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-semibold" style="background:rgba(34,211,238,.08);border:1px solid rgba(34,211,238,.25);color:#0e7490;"><span class="w-1.5 h-1.5 rounded-full bg-cyan-500"></span>Kepala Unit</span>
                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-semibold" style="background:rgba(77,158,247,.08);border:1px solid rgba(77,158,247,.2);color:#1d60c8;"><span class="w-1.5 h-1.5 rounded-full" style="background:#4d9ef7;"></span>Dosen</span>
                </div>
            </div>

            {{-- Divider --}}
            <div class="flex items-center gap-3 mb-6">
                <div class="flex-1 h-px bg-slate-100"></div>
                <span class="text-slate-300 text-xs whitespace-nowrap">masuk dengan akun Anda</span>
                <div class="flex-1 h-px bg-slate-100"></div>
            </div>

            {{-- Errors --}}
            @if($errors->any())
            <div class="mb-5 rounded-xl p-4 flex gap-3" style="background:rgba(239,68,68,.06);border:1px solid rgba(239,68,68,.2);">
                <svg class="w-5 h-5 text-red-500 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"/></svg>
                <ul class="space-y-1">@foreach($errors->all() as $e)<li class="text-sm text-red-600">{{ $e }}</li>@endforeach</ul>
            </div>
            @endif

            {{-- Session Status --}}
            @session('status')
            <div class="mb-5 rounded-xl p-4 flex gap-3" style="background:rgba(16,185,129,.06);border:1px solid rgba(16,185,129,.2);">
                <svg class="w-5 h-5 text-emerald-500 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <p class="text-sm text-emerald-700">{{ $value }}</p>
            </div>
            @endsession

            {{-- Form --}}
            <form id="loginForm" method="POST" action="{{ route('login') }}" novalidate>
                @csrf

                {{-- Email --}}
                <div class="mb-5">
                    <label for="email" class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-2">Alamat Email</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                            <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75"/></svg>
                        </div>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" placeholder="nama@uhb.ac.id"
                            class="w-full pl-10 pr-4 py-3.5 rounded-xl border text-sm text-slate-800 placeholder-slate-400 transition-all duration-200 outline-none hover:border-slate-300 focus:bg-white focus:ring-4 {{ $errors->has('email') ? 'border-red-400 bg-red-50' : 'border-slate-200 bg-slate-50 focus:border-blue-500 focus:ring-blue-500/10' }}">
                    </div>
                    @error('email')<p class="mt-1.5 text-xs text-red-500 flex items-center gap-1"><svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>{{ $message }}</p>@enderror
                </div>

                {{-- Password --}}
                <div class="mb-5">
                    <div class="flex items-center justify-between mb-2">
                        <label for="password" class="block text-xs font-semibold text-slate-500 uppercase tracking-wide">Kata Sandi</label>
                        @if(Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="text-xs font-medium text-blue-600 hover:underline underline-offset-2 transition-colors">Lupa kata sandi?</a>
                        @endif
                    </div>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                            <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"/></svg>
                        </div>
                        <input id="password" type="password" name="password" required autocomplete="current-password" placeholder="Masukkan kata sandi"
                            class="w-full pl-10 pr-11 py-3.5 rounded-xl border text-sm text-slate-800 placeholder-slate-400 transition-all duration-200 outline-none hover:border-slate-300 focus:bg-white focus:ring-4 {{ $errors->has('password') ? 'border-red-400 bg-red-50' : 'border-slate-200 bg-slate-50 focus:border-blue-500 focus:ring-blue-500/10' }}">
                        <button type="button" onclick="togglePassword()" class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-slate-600 transition-colors" tabindex="-1">
                            <svg id="eye-show" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.964-7.178z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            <svg id="eye-hide" class="w-4 h-4 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88"/></svg>
                        </button>
                    </div>
                    @error('password')<p class="mt-1.5 text-xs text-red-500 flex items-center gap-1"><svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>{{ $message }}</p>@enderror
                </div>

                {{-- Remember Me --}}
                <div class="flex items-center justify-between mb-6">
                    <label class="flex items-center gap-2.5 cursor-pointer select-none group">
                        <input type="checkbox" id="remember_me" name="remember" class="sr-only" onchange="toggleCheckbox(this)">
                        <div id="checkbox-ui" class="w-4.5 h-4.5 rounded-md border-2 border-slate-300 flex items-center justify-center transition-all duration-200 flex-shrink-0" style="width:18px;height:18px;">
                            <svg id="check-icon" class="w-3 h-3 text-white hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                        </div>
                        <span class="text-sm text-slate-600">Ingat saya</span>
                    </label>
                </div>

                {{-- Submit Button --}}
                <button id="submitBtn" type="submit" class="relative w-full py-3.5 rounded-xl text-sm font-semibold text-white overflow-hidden transition-all duration-200 hover:-translate-y-0.5 active:translate-y-0 disabled:opacity-75 disabled:cursor-not-allowed disabled:transform-none focus:outline-none focus:ring-4 focus:ring-blue-500/20" style="background:linear-gradient(135deg,#0f2860,#1d60c8,#0c3a7a);box-shadow:0 4px 20px rgba(29,96,200,.4);">
                    <span class="shine-layer"></span>
                    <span id="btnNormal" class="flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3 0l3-3m0 0l-3-3m3 3H9"/></svg>
                        Masuk ke Sistem
                    </span>
                    <span id="btnLoading" class="hidden items-center justify-center gap-2">
                        <svg class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
                        Memproses...
                    </span>
                </button>
            </form>

            {{-- Footer --}}
            <div class="mt-8 pt-6 border-t border-slate-100">
                <div class="flex items-center justify-center gap-3 mb-3">
                    <span class="flex items-center gap-1.5 text-xs text-slate-400"><svg class="w-3.5 h-3.5 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"/></svg>SSL Secured</span>
                    <span class="text-slate-200">|</span>
                    <span class="flex items-center gap-1.5 text-xs text-slate-400"><svg class="w-3.5 h-3.5 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z"/></svg>Data Terenkripsi</span>
                    <span class="text-slate-200">|</span>
                    <span class="text-xs text-slate-400">v2.0.0</span>
                </div>
                <p class="text-center text-xs text-slate-400">© {{ date('Y') }} Universitas Harapan Bangsa. All rights reserved.</p>
            </div>
        </div>
    </div>

</div>

<style>
.shine-layer{position:absolute;top:0;left:-100%;width:60%;height:100%;background:linear-gradient(90deg,transparent,rgba(255,255,255,.12),transparent);transform:skewX(-12deg);transition:left .7s ease;pointer-events:none;}
#submitBtn:hover .shine-layer{left:150%;}
#submitBtn:hover{box-shadow:0 8px 30px rgba(29,96,200,.55);}
</style>

<script>
function togglePassword(){
    var inp=document.getElementById('password');
    var show=document.getElementById('eye-show');
    var hide=document.getElementById('eye-hide');
    if(inp.type==='password'){inp.type='text';show.classList.add('hidden');hide.classList.remove('hidden');}
    else{inp.type='password';show.classList.remove('hidden');hide.classList.add('hidden');}
}
function toggleCheckbox(el){
    var ui=document.getElementById('checkbox-ui');
    var icon=document.getElementById('check-icon');
    if(el.checked){ui.style.background='#1d60c8';ui.style.borderColor='#1d60c8';icon.classList.remove('hidden');}
    else{ui.style.background='';ui.style.borderColor='';icon.classList.add('hidden');}
}
document.getElementById('loginForm').addEventListener('submit',function(){
    var btn=document.getElementById('submitBtn');
    var normal=document.getElementById('btnNormal');
    var loading=document.getElementById('btnLoading');
    btn.disabled=true;
    normal.classList.add('hidden');
    loading.classList.remove('hidden');
    loading.style.display='flex';
});
</script>
</x-guest-layout>
