<x-guest-layout>
<style>
@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap');
.font-syne{font-family:'Poppins',sans-serif;}
.font-jakarta{font-family:'Poppins',sans-serif;}
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
        <div class="relative z-10 flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl flex items-center justify-center overflow-hidden bg-white/10" style="border:1px solid rgba(255,255,255,.2);">
                <img src="{{ asset('images/logo-uhb.png') }}" alt="Logo UHB" class="w-full h-full object-contain p-1.5">
            </div>
            <div class="flex flex-col">
                <span class="font-syne text-white font-bold text-2xl tracking-wide leading-tight">OFA-UHB</span>
                <span class="text-cyan-300 text-sm font-medium tracking-wide">Universitas Harapan Bangsa</span>
            </div>
        </div>

        {{-- Middle Content --}}
        <div class="relative z-10 flex-1 flex flex-col justify-center py-8">

            {{-- Heading --}}
            <h1 class="font-syne font-extrabold text-white leading-tight mb-2" style="font-size:clamp(2.2rem,4vw,3.2rem);letter-spacing:-0.02em;">Website Office &<br><span style="background:linear-gradient(90deg,#ffffff,#a5f3fc);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;">Financial Administration</span></h1>
            <p class="font-syne text-3xl font-bold text-cyan-400 mb-1 tracking-wider">OFA-UHB</p>
            <div class="w-24 h-0.5 mb-4 rounded-full" style="background:linear-gradient(90deg,#22d3ee,transparent);"></div>
            <p class="text-blue-200/85 text-base max-w-sm leading-relaxed">Sistem pengelolaan kas dan notulensi rapat untuk mendukung administrasi fakultas dan dosen secara terintegrasi.</p>

        </div>

        {{-- Footer --}}
        <div class="relative z-10 flex items-center justify-between pt-6" style="border-top:1px solid rgba(255,255,255,.08);">
            <p class="text-white/30 text-sm">© {{ date('Y') }} Universitas Harapan Bangsa</p>
        </div>
    </div>

    {{-- RIGHT PANEL --}}
    <div class="flex-1 lg:w-[45%] flex items-center justify-center bg-white relative min-h-screen lg:min-h-0" style="background:radial-gradient(ellipse at top left,rgba(29,96,200,.04) 0%,transparent 50%),radial-gradient(ellipse at bottom right,rgba(34,211,238,.04) 0%,transparent 50%),#ffffff;">

        {{-- Mobile Logo --}}
        <div class="absolute top-6 left-6 flex items-center gap-3 lg:hidden">
            <div class="w-10 h-10 rounded-xl flex items-center justify-center overflow-hidden bg-white" style="border:1px solid #e2e8f0;box-shadow:0 4px 6px -1px rgba(0,0,0,0.1);">
                <img src="{{ asset('images/logo-uhb.png') }}" alt="Logo UHB" class="w-full h-full object-contain p-1.5">
            </div>
            <div><p class="font-syne font-bold text-slate-800 text-sm leading-none">OFA-UHB</p><p class="text-slate-400 text-xs">Universitas Harapan Bangsa</p></div>
        </div>

        <div class="w-full max-w-md px-8 py-12">

            {{-- Heading --}}
            <div class="mb-7">
                <h2 class="font-syne font-bold text-slate-900 text-3xl xl:text-4xl mb-1.5">Selamat datang 👋</h2>
                <p class="text-slate-500 text-base">Masuk ke sistem OFA-UHB untuk melanjutkan.</p>
            </div>



            {{-- Divider --}}
            <div class="flex items-center gap-3 mb-6">
                <div class="flex-1 h-px bg-slate-100"></div>
                <span class="text-slate-300 text-sm whitespace-nowrap">masuk dengan akun Anda</span>
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

                {{-- Username --}}
                <div class="mb-5">
                    <label for="username" class="block text-sm font-semibold text-slate-500 uppercase tracking-wide mb-2">Username</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                            <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/></svg>
                        </div>
                        <input id="username" type="text" name="username" value="{{ old('username') }}" required autofocus autocomplete="username" placeholder="Masukkan username"
                            class="w-full pl-10 pr-4 py-3.5 rounded-xl border text-base text-slate-800 placeholder-slate-400 transition-all duration-200 outline-none hover:border-slate-300 focus:bg-white focus:ring-4 {{ $errors->has('username') ? 'border-red-400 bg-red-50' : 'border-slate-200 bg-slate-50 focus:border-blue-500 focus:ring-blue-500/10' }}">
                    </div>
                    @error('username')<p class="mt-1.5 text-sm text-red-500 flex items-center gap-1"><svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>{{ $message }}</p>@enderror
                </div>

                {{-- Password --}}
                <div class="mb-5">
                    <div class="flex items-center justify-between mb-2">
                        <label for="password" class="block text-sm font-semibold text-slate-500 uppercase tracking-wide">Kata Sandi</label>
                        @if(Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="text-sm font-medium text-blue-600 hover:underline underline-offset-2 transition-colors">Lupa kata sandi?</a>
                        @endif
                    </div>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                            <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"/></svg>
                        </div>
                        <input id="password" type="password" name="password" required autocomplete="current-password" placeholder="Masukkan kata sandi"
                            class="w-full pl-10 pr-11 py-3.5 rounded-xl border text-base text-slate-800 placeholder-slate-400 transition-all duration-200 outline-none hover:border-slate-300 focus:bg-white focus:ring-4 {{ $errors->has('password') ? 'border-red-400 bg-red-50' : 'border-slate-200 bg-slate-50 focus:border-blue-500 focus:ring-blue-500/10' }}">
                        <button type="button" onclick="togglePassword()" class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-slate-600 transition-colors" tabindex="-1">
                            <svg id="eye-show" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.964-7.178z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            <svg id="eye-hide" class="w-4 h-4 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88"/></svg>
                        </button>
                    </div>
                    @error('Password')<p class="mt-1.5 text-sm text-red-500 flex items-center gap-1"><svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>{{ $message }}</p>@enderror
                </div>

                {{-- Remember Me --}}
                <div class="flex items-center justify-between mb-6">
                    <label class="flex items-center gap-2.5 cursor-pointer select-none group">
                        <input type="checkbox" id="remember_me" name="remember" class="sr-only" onchange="toggleCheckbox(this)">
                        <div id="checkbox-ui" class="w-4.5 h-4.5 rounded-md border-2 border-slate-300 flex items-center justify-center transition-all duration-200 flex-shrink-0" style="width:18px;height:18px;">
                            <svg id="check-icon" class="w-3 h-3 text-white hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                        </div>
                        <span class="text-base text-slate-600">Ingat Saya</span>
                    </label>
                </div>

                {{-- Submit Button --}}
                <button id="submitBtn" type="submit" class="relative w-full py-3.5 rounded-xl text-base font-semibold text-white overflow-hidden transition-all duration-200 hover:-translate-y-0.5 active:translate-y-0 disabled:opacity-75 disabled:cursor-not-allowed disabled:transform-none focus:outline-none focus:ring-4 focus:ring-blue-500/20" style="background:linear-gradient(135deg,#0f2860,#1d60c8,#0c3a7a);box-shadow:0 4px 20px rgba(29,96,200,.4);">
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
