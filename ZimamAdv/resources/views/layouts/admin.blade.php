<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Admin - Zimam Advertising')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #f8fafc; }
        .glass-sidebar { background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(12px); -webkit-backdrop-filter: blur(12px); border-right: 1px solid rgba(0,0,0,0.05); box-shadow: 4px 0 24px rgba(0,0,0,0.02); }
        .glass-header { background: rgba(255, 255, 255, 0.85); backdrop-filter: blur(12px); -webkit-backdrop-filter: blur(12px); border-bottom: 1px solid rgba(0,0,0,0.05); }
    </style>
    @stack('scripts')
</head>

<body class="min-h-screen bg-slate-50 text-slate-800 selection:bg-emerald-100 selection:text-emerald-900">
    <div class="flex min-h-screen">
        <aside id="admin-sidebar"
            class="glass-sidebar fixed inset-y-0 left-0 z-40 w-64 text-slate-800 transform transition-transform duration-200 ease-out -translate-x-full md:translate-x-0 md:static md:flex md:flex-col">
            <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between">
                <a href="{{ route('admin.dashboard.index') }}" class="flex items-center gap-3 group w-full">
                    <div class="h-10 w-10 rounded-xl bg-gradient-to-br from-emerald-500 to-blue-600 flex items-center justify-center text-white font-bold text-xl shadow-lg shadow-emerald-500/30 group-hover:shadow-blue-500/50 transition-all duration-300 group-hover:scale-105">
                        Z
                    </div>
                    <div class="flex flex-col leading-tight">
                        <span class="font-bold text-slate-900 text-lg tracking-tight">Admin</span>
                        <span class="text-[10px] uppercase font-semibold tracking-wider text-emerald-600">Zimam Adv</span>
                    </div>
                </a>
                <button type="button" class="md:hidden text-slate-400 hover:text-emerald-600 transition-colors"
                    onclick="document.getElementById('admin-sidebar').classList.add('-translate-x-full')">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <nav class="flex-1 px-4 py-6 space-y-2 text-sm overflow-y-auto">
                @php
                    $navItemBase = 'group flex items-center gap-3 rounded-xl px-4 py-2.5 transition-all duration-200 border font-medium';
                    $navItemInactive = 'bg-transparent text-slate-600 border-transparent hover:bg-slate-50 hover:text-emerald-700';
                    $navItemActive = 'bg-gradient-to-r from-emerald-50 to-blue-50 text-emerald-700 border-emerald-200 shadow-sm';
                @endphp

                @php $isActive = request()->routeIs('admin.dashboard.*'); @endphp
                <a href="{{ route('admin.dashboard.index') }}"
                    class="{{ $navItemBase }} {{ $isActive ? $navItemActive : $navItemInactive }}">
                    <svg class="w-5 h-5 {{ $isActive ? 'text-emerald-600' : 'text-slate-400 group-hover:text-emerald-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                    <span>Dashboard</span>
                </a>

                @php $isActive = request()->routeIs('admin.users.*'); @endphp
                <a href="{{ route('admin.users.index') }}"
                    class="{{ $navItemBase }} {{ $isActive ? $navItemActive : $navItemInactive }}">
                    <svg class="w-5 h-5 {{ $isActive ? 'text-emerald-600' : 'text-slate-400 group-hover:text-emerald-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    <span>Pengguna</span>
                </a>

                @php $isActive = request()->routeIs('admin.products.*'); @endphp
                <a href="{{ route('admin.products.index') }}"
                    class="{{ $navItemBase }} {{ $isActive ? $navItemActive : $navItemInactive }}">
                    <svg class="w-5 h-5 {{ $isActive ? 'text-emerald-600' : 'text-slate-400 group-hover:text-emerald-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path></svg>
                    <span>Produk</span>
                </a>

                @php $isActive = request()->routeIs('admin.orders.*'); @endphp
                <a href="{{ route('admin.orders.index') }}"
                    class="{{ $navItemBase }} {{ $isActive ? $navItemActive : $navItemInactive }}">
                    <svg class="w-5 h-5 {{ $isActive ? 'text-emerald-600' : 'text-slate-400 group-hover:text-emerald-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                    <span>Pesanan</span>
                </a>

                @php $isActive = request()->routeIs('admin.payments.*'); @endphp
                <a href="{{ route('admin.payments.index') }}"
                    class="{{ $navItemBase }} {{ $isActive ? $navItemActive : $navItemInactive }}">
                    <svg class="w-5 h-5 {{ $isActive ? 'text-emerald-600' : 'text-slate-400 group-hover:text-emerald-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                    <span>Verifikasi Pembayaran</span>
                </a>

                @php $isActive = request()->routeIs('admin.installments.*'); @endphp
                <a href="{{ route('admin.installments.index') }}"
                    class="{{ $navItemBase }} {{ $isActive ? $navItemActive : $navItemInactive }}">
                    <svg class="w-5 h-5 {{ $isActive ? 'text-emerald-600' : 'text-slate-400 group-hover:text-emerald-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                    <span>Cicilan</span>
                </a>

                @if(auth()->user()->role && auth()->user()->role->name === 'admin')
                @php $isActive = request()->routeIs('admin.bank-accounts.*'); @endphp
                <a href="{{ route('admin.bank-accounts.index') }}"
                    class="{{ $navItemBase }} {{ $isActive ? $navItemActive : $navItemInactive }}">
                    <svg class="w-5 h-5 {{ $isActive ? 'text-emerald-600' : 'text-slate-400 group-hover:text-emerald-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z"></path></svg>
                    <span>Metode Pembayaran</span>
                </a>

                @php $isActive = request()->routeIs('admin.chats.*'); @endphp
                <a href="{{ route('admin.chats.index') }}"
                    class="{{ $navItemBase }} {{ $isActive ? $navItemActive : $navItemInactive }}">
                    <svg class="w-5 h-5 {{ $isActive ? 'text-emerald-600' : 'text-slate-400 group-hover:text-emerald-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z"></path></svg>
                    <span>Customer Service</span>
                </a>
                @endif

                @php $isActive = request()->routeIs('admin.lihat-website'); @endphp
                <a href="{{ route('admin.lihat-website') }}" target="_blank"
                    class="{{ $navItemBase }} {{ $isActive ? $navItemActive : $navItemInactive }}">
                    <svg class="w-5 h-5 {{ $isActive ? 'text-emerald-600' : 'text-slate-400 group-hover:text-emerald-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                    <span>Lihat Website</span>
                </a>
            </nav>

            <form action="{{ route('admin.logout') }}" method="POST" class="p-4 mt-auto border-t border-slate-100">
                @csrf
                <button class="w-full flex items-center justify-center gap-2 rounded-xl px-4 py-2.5 bg-red-50 text-red-600 font-medium hover:bg-red-100 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                    <span>Keluar</span>
                </button>
            </form>
        </aside>

        <div class="flex-1 flex flex-col">
            <header class="glass-header sticky top-0 z-20 px-6 py-4 flex items-center justify-between gap-4">
                <div class="flex items-center gap-4">
                    <button type="button" class="md:hidden text-slate-400 hover:text-emerald-600 transition-colors"
                        onclick="document.getElementById('admin-sidebar').classList.remove('-translate-x-full')">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                    <div>
                        <div class="text-xs uppercase tracking-wider text-slate-500 font-semibold mb-0.5">Admin Dashboard</div>
                        <h1 class="text-xl font-bold text-slate-800">@yield('page_title')</h1>
                    </div>
                </div>
                <div class="hidden sm:flex items-center gap-4">
                    <div class="relative">
                        <input type="text" placeholder="Cari data..." class="pl-10 pr-4 py-2 bg-slate-100 border-transparent rounded-full text-sm focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 transition-all w-64">
                        <svg class="w-4 h-4 text-slate-400 absolute left-4 top-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                    @auth
                        <div class="flex items-center gap-3 pl-4 border-l border-slate-200">
                            <div class="text-right">
                                <div class="text-sm font-bold text-slate-800">{{ auth()->user()->name }}</div>
                                <div class="text-[10px] uppercase tracking-wider font-semibold text-emerald-600">{{ auth()->user()->role->name ?? 'Admin' }}</div>
                            </div>
                            <div class="h-10 w-10 rounded-full bg-gradient-to-r from-emerald-400 to-blue-500 flex items-center justify-center text-white font-bold shadow-md">
                                {{ substr(auth()->user()->name, 0, 1) }}
                            </div>
                        </div>
                    @endauth
                </div>
            </header>

            <main class="flex-1 p-6 md:p-8">
                @yield('content')
            </main>
        </div>
    </div>
</body>

</html>