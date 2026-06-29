<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Zimam Advertising - Percetakan & Advertising')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8fafc;
        }

        .glass {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.3);
        }

        .btn-primary {
            background: linear-gradient(135deg, #10b981, #2563eb);
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 0.5rem;
            font-weight: 500;
            transition: all 0.3s ease;
            box-shadow: 0 4px 10px rgba(16, 185, 129, 0.3);
        }

        .btn-primary:hover {
            box-shadow: 0 10px 20px rgba(16, 185, 129, 0.4);
            transform: translateY(-1px);
            background: linear-gradient(135deg, #059669, #1d4ed8);
        }

        .btn-secondary {
            background: white;
            color: #334155;
            border: 1px solid #cbd5e1;
            padding: 0.5rem 1rem;
            border-radius: 0.5rem;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn-secondary:hover {
            background: #f1f5f9;
            border-color: #10b981;
            color: #059669;
        }

        .card-premium {
            background: white;
            border-radius: 1rem;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05);
            border: 1px solid rgba(0, 0, 0, 0.05);
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .card-premium:hover {
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(16, 185, 129, 0.05);
            transform: translateY(-3px);
            border-color: rgba(16, 185, 129, 0.2);
        }
    </style>
    @stack('scripts')
</head>

<body class="min-h-screen flex flex-col text-slate-800 selection:bg-emerald-100 selection:text-emerald-900">
    <header class="glass sticky top-0 z-50 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3 flex items-center justify-between gap-4 lg:gap-8">
            <!-- Logo -->
            <div class="flex-shrink-0 flex items-center gap-2">
                <a href="{{ route('home') }}" class="flex items-center gap-3 group">
                    <div
                        class="h-10 w-10 rounded-xl bg-gradient-to-br from-emerald-500 to-blue-600 flex items-center justify-center text-white font-bold text-xl shadow-lg shadow-emerald-500/30 group-hover:shadow-blue-500/50 transition-all duration-300 group-hover:scale-105">
                        Z
                    </div>
                    <div class="hidden sm:flex flex-col leading-tight">
                        <span class="font-bold text-slate-900 text-lg tracking-tight">Zimam</span>
                        <span
                            class="text-[10px] uppercase font-semibold tracking-wider text-emerald-600">Advertising</span>
                    </div>
                </a>
            </div>

            <!-- Search Bar -->
            <div class="flex-1 max-w-2xl hidden md:block">
                <form action="{{ route('products.index') }}" method="GET" class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-slate-400 group-focus-within:text-emerald-500 transition-colors"
                            xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                    <input type="text" name="q" value="{{ request('q') }}"
                        placeholder="Cari layanan cetak, banner, brosur..."
                        class="block w-full pl-10 pr-3 py-2 border border-slate-200 rounded-full leading-5 bg-slate-50 placeholder-slate-400 focus:outline-none focus:bg-white focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all duration-300 sm:text-sm">
                </form>
            </div>

            <!-- Mobile Menu Button -->
            <button type="button"
                class="md:hidden inline-flex items-center justify-center rounded-lg p-2 text-slate-600 hover:bg-slate-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-blue-500"
                onclick="document.getElementById('mobile-menu').classList.toggle('hidden')">
                <span class="sr-only">Buka menu</span>
                <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>

            <!-- Desktop Nav -->
            <nav class="hidden md:flex items-center gap-1">
                <a href="{{ route('home') }}"
                    class="px-3 py-2 rounded-lg text-sm font-medium text-slate-600 hover:text-blue-600 hover:bg-blue-50 transition-colors">Beranda</a>
                <a href="{{ route('products.index') }}"
                    class="px-3 py-2 rounded-lg text-sm font-medium text-slate-600 hover:text-blue-600 hover:bg-blue-50 transition-colors">Produk</a>
                @auth
                <a href="{{ route('chat.index') }}"
                    class="px-3 py-2 rounded-lg text-sm font-medium text-slate-600 hover:text-blue-600 hover:bg-blue-50 transition-colors">Chat</a>
                @endauth
                <div class="w-px h-6 bg-slate-200 mx-2"></div>
                <a href="{{ route('cart.index') }}"
                    class="p-2 rounded-full text-slate-600 hover:text-blue-600 hover:bg-blue-50 transition-colors relative"
                    title="Keranjang">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                            d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    @if(session('cart') && count(session('cart')) > 0)
                        <span
                            class="absolute top-1 right-1 inline-flex items-center justify-center px-1.5 py-0.5 text-[10px] font-bold leading-none text-white transform translate-x-1/4 -translate-y-1/4 bg-red-500 rounded-full ring-2 ring-white">
                            {{ count(session('cart')) }}
                        </span>
                    @endif
                </a>

                @auth
                    <div class="relative ml-2" x-data="{ open: false }">
                        <button type="button"
                            class="flex items-center gap-2 p-1.5 pr-3 rounded-full hover:bg-slate-100 transition-colors"
                            onclick="document.getElementById('user-dropdown').classList.toggle('hidden')">
                            <div
                                class="h-8 w-8 rounded-full bg-gradient-to-r from-emerald-500 to-blue-600 flex items-center justify-center text-white text-sm font-bold shadow-sm">
                                {{ substr(auth()->user()->name, 0, 1) }}
                            </div>
                            <span
                                class="text-sm font-medium text-slate-700 hidden lg:block">{{ auth()->user()->name }}</span>
                            <svg class="h-4 w-4 text-slate-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                    clip-rule="evenodd" />
                            </svg>
                        </button>
                        <!-- Dropdown -->
                        <div id="user-dropdown"
                            class="hidden absolute right-0 mt-2 w-48 rounded-xl shadow-lg bg-white ring-1 ring-black ring-opacity-5 divide-y divide-slate-100 transform opacity-100 scale-100 transition-all origin-top-right">
                            <div class="py-1">
                                <a href="{{ route('orders.index') }}"
                                    class="block px-4 py-2 text-sm text-slate-700 hover:bg-slate-50 hover:text-blue-600">Pesanan
                                    Saya</a>
                                <a href="{{ route('orders.track-form') }}"
                                    class="block px-4 py-2 text-sm text-slate-700 hover:bg-slate-50 hover:text-blue-600">Lacak
                                    Pesanan</a>
                                <a href="{{ route('chat.index') }}"
                                    class="block px-4 py-2 text-sm text-slate-700 hover:bg-slate-50 hover:text-blue-600">Chat
                                    & Bantuan</a>
                            </div>
                            @if(auth()->user()->role && auth()->user()->role->name === 'admin')
                                <div class="py-1">
                                    <a href="{{ route('admin.dashboard.index') }}"
                                        class="block px-4 py-2 text-sm text-blue-600 hover:bg-blue-50 font-medium">Panel
                                        Admin</a>
                                </div>
                            @endif
                            <div class="py-1">
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit"
                                        class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50">Keluar</button>
                                </form>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="flex items-center gap-2 ml-4">
                        <a href="{{ route('login') }}" class="btn-secondary text-sm px-4 py-2">Masuk</a>
                        <a href="{{ route('register') }}" class="btn-primary text-sm px-4 py-2">Daftar</a>
                    </div>
                @endauth
            </nav>
        </div>

        <!-- Mobile Menu -->
        <div id="mobile-menu" class="hidden md:hidden border-t border-slate-200 bg-white">
            <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3">
                <a href="{{ route('home') }}"
                    class="block px-3 py-2 rounded-md text-base font-medium text-slate-900 hover:bg-slate-50">Beranda</a>
                <a href="{{ route('products.index') }}"
                    class="block px-3 py-2 rounded-md text-base font-medium text-slate-900 hover:bg-slate-50">Produk</a>
                @auth
                <a href="{{ route('chat.index') }}"
                    class="block px-3 py-2 rounded-md text-base font-medium text-slate-900 hover:bg-slate-50">Chat Admin</a>
                @endauth
                <a href="{{ route('cart.index') }}"
                    class="block px-3 py-2 rounded-md text-base font-medium text-slate-900 hover:bg-slate-50">Keranjang</a>
                <a href="{{ route('orders.track-form') }}"
                    class="block px-3 py-2 rounded-md text-base font-medium text-slate-900 hover:bg-slate-50">Lacak
                    Pesanan</a>
            </div>
            @auth
                <div class="pt-4 pb-3 border-t border-slate-200">
                    <div class="flex items-center px-5">
                        <div class="flex-shrink-0">
                            <div
                                class="h-10 w-10 rounded-full bg-blue-500 flex items-center justify-center text-white font-bold">
                                {{ substr(auth()->user()->name, 0, 1) }}
                            </div>
                        </div>
                        <div class="ml-3">
                            <div class="text-base font-medium text-slate-800">{{ auth()->user()->name }}</div>
                            <div class="text-sm font-medium text-slate-500">{{ auth()->user()->email }}</div>
                        </div>
                    </div>
                    <div class="mt-3 px-2 space-y-1">
                        <a href="{{ route('orders.index') }}"
                            class="block px-3 py-2 rounded-md text-base font-medium text-slate-900 hover:bg-slate-50">Pesanan
                            Saya</a>
                        <a href="{{ route('chat.index') }}"
                            class="block px-3 py-2 rounded-md text-base font-medium text-slate-900 hover:bg-slate-50">Chat</a>
                        @if(auth()->user()->role && auth()->user()->role->name === 'admin')
                            <a href="{{ route('admin.dashboard.index') }}"
                                class="block px-3 py-2 rounded-md text-base font-medium text-blue-600 hover:bg-blue-50">Panel
                                Admin</a>
                        @endif
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit"
                                class="block w-full text-left px-3 py-2 rounded-md text-base font-medium text-red-600 hover:bg-red-50">Keluar</button>
                        </form>
                    </div>
                </div>
            @else
                <div class="pt-4 pb-3 border-t border-slate-200 px-5 flex gap-2">
                    <a href="{{ route('login') }}" class="flex-1 btn-secondary text-center justify-center py-2">Masuk</a>
                    <a href="{{ route('register') }}" class="flex-1 btn-primary text-center justify-center py-2">Daftar</a>
                </div>
            @endauth
        </div>
    </header>

    <main class="flex-1 w-full py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @if(session('status'))
                <div class="mb-6 p-4 rounded-xl bg-green-50 border border-green-200 flex items-start gap-3 shadow-sm">
                    <svg class="h-5 w-5 text-green-500 mt-0.5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                        fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                            clip-rule="evenodd" />
                    </svg>
                    <div class="text-sm text-green-800 font-medium">{{ session('status') }}</div>
                </div>
            @endif
            @if(session('error'))
                <div class="mb-6 p-4 rounded-xl bg-red-50 border border-red-200 flex items-start gap-3 shadow-sm">
                    <svg class="h-5 w-5 text-red-500 mt-0.5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                        fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                            clip-rule="evenodd" />
                    </svg>
                    <div class="text-sm text-red-800 font-medium">{{ session('error') }}</div>
                </div>
            @endif

            @yield('content')
        </div>
    </main>

    @if(!request()->routeIs('login', 'register'))
    <footer class="bg-white border-t border-slate-200 text-slate-600 mt-auto">
        <!-- Main Footer Content -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-8 mb-12">
                <!-- Brand Section -->
                <div class="md:col-span-2 lg:col-span-1">
                    <div class="flex items-center gap-3 mb-4">
                        <div
                            class="h-10 w-10 rounded-xl bg-gradient-to-br from-emerald-500 to-blue-600 flex items-center justify-center text-white font-bold text-lg shadow-lg shadow-emerald-500/30">
                            Z
                        </div>
                        <div>
                            <div class="font-bold text-slate-900 text-lg leading-tight">Zimam</div>
                            <div class="text-[10px] uppercase font-semibold tracking-wider text-emerald-600">
                                Advertising</div>
                        </div>
                    </div>
                    <p class="text-sm text-slate-600 leading-relaxed mb-4 max-w-xs">
                        Solusi cetak dan advertising terpercaya dengan kualitas premium. Mendukung bisnis Anda untuk
                        tampil lebih profesional dan berdampak.
                    </p>
                    <!-- Social Links -->
                    <div class="flex gap-3 mt-6">
                        <a href="#"
                            class="h-9 w-9 rounded-lg bg-slate-100 hover:bg-emerald-500 hover:text-white flex items-center justify-center transition-colors group"
                            title="Instagram">
                            <svg class="w-5 h-5 text-slate-600 group-hover:text-white" fill="currentColor"
                                viewBox="0 0 20 20">
                                <path
                                    d="M10 1.5c2.478 0 2.78.01 3.764.055 2.329.107 3.654 1.431 3.76 3.76.047.984.057 1.286.057 3.764 0 2.478-.01 2.78-.055 3.764-.107 2.33-1.431 3.654-3.76 3.76-.984.047-1.286.057-3.764.057-2.478 0-2.78-.01-3.764-.055-2.33-.107-3.654-1.431-3.76-3.76C1.51 12.78 1.5 12.478 1.5 10c0-2.478.01-2.78.055-3.764.107-2.329 1.431-3.654 3.76-3.76.984-.047 1.286-.057 3.764-.057m0-1.5c-2.516 0-2.832.011-3.822.056-3.018.138-4.568 1.688-4.706 4.706-.044.99-.056 1.306-.056 3.822s.011 2.832.056 3.822c.138 3.018 1.688 4.568 4.706 4.706.99.044 1.306.056 3.822.056 2.516 0 2.832-.011 3.822-.056 3.018-.138 4.568-1.688 4.706-4.706.044-.99.056-1.306.056-3.822s-.011-2.832-.056-3.822c-.138-3.018-1.688-4.568-4.706-4.706C12.832.011 12.516 0 10 0z" />
                                <path
                                    d="M10 4.865a5.135 5.135 0 100 10.27 5.135 5.135 0 000-10.27zm0 8.47a3.335 3.335 0 110-6.67 3.335 3.335 0 010 6.67z" />
                                <circle cx="15.396" cy="4.864" r="1.2" />
                            </svg>
                        </a>
                    </div>
                </div>

                <!-- Layanan Section -->
                <div>
                    <h3 class="font-semibold text-slate-900 text-sm uppercase tracking-widest mb-4 flex items-center gap-2">
                        <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                        Layanan
                    </h3>
                    <ul class="space-y-3">
                        <li><a href="{{ route('products.index') }}"
                                class="text-sm text-slate-600 hover:text-emerald-600 transition-colors flex items-center gap-2 group">
                                <span class="text-slate-400 group-hover:translate-x-1 group-hover:text-emerald-500 transition-all">→</span> Cetak Banner
                            </a></li>
                        <li><a href="{{ route('products.index') }}"
                                class="text-sm text-slate-600 hover:text-emerald-600 transition-colors flex items-center gap-2 group">
                                <span class="text-slate-400 group-hover:translate-x-1 group-hover:text-emerald-500 transition-all">→</span> Brosur & Flyer
                            </a></li>
                        <li><a href="{{ route('products.index') }}"
                                class="text-sm text-slate-600 hover:text-emerald-600 transition-colors flex items-center gap-2 group">
                                <span class="text-slate-400 group-hover:translate-x-1 group-hover:text-emerald-500 transition-all">→</span> Kartu Nama
                            </a></li>
                        <li><a href="{{ route('products.index') }}"
                                class="text-sm text-slate-600 hover:text-emerald-600 transition-colors flex items-center gap-2 group">
                                <span class="text-slate-400 group-hover:translate-x-1 group-hover:text-emerald-500 transition-all">→</span> Merchandise
                            </a></li>
                        <li><a href="{{ route('products.index') }}"
                                class="text-sm text-slate-600 hover:text-emerald-600 transition-colors flex items-center gap-2 group">
                                <span class="text-slate-400 group-hover:translate-x-1 group-hover:text-emerald-500 transition-all">→</span> Jasa Desain
                            </a></li>
                    </ul>
                </div>

                <!-- Toko Section -->
                <div>
                    <h3 class="font-semibold text-slate-900 text-sm uppercase tracking-widest mb-4 flex items-center gap-2">
                        <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                        Toko
                    </h3>
                    <ul class="space-y-3">
                        <li><a href="{{ route('home') }}"
                                class="text-sm text-slate-600 hover:text-emerald-600 transition-colors flex items-center gap-2 group">
                                <span class="text-slate-400 group-hover:translate-x-1 group-hover:text-emerald-500 transition-all">→</span> Beranda
                            </a></li>
                        <li><a href="{{ route('products.index') }}"
                                class="text-sm text-slate-600 hover:text-emerald-600 transition-colors flex items-center gap-2 group">
                                <span class="text-slate-400 group-hover:translate-x-1 group-hover:text-emerald-500 transition-all">→</span> Katalog Produk
                            </a></li>
                        <li><a href="{{ route('cart.index') }}"
                                class="text-sm text-slate-600 hover:text-emerald-600 transition-colors flex items-center gap-2 group">
                                <span class="text-slate-400 group-hover:translate-x-1 group-hover:text-emerald-500 transition-all">→</span> Keranjang
                            </a></li>
                        <li><a href="{{ route('orders.track-form') }}"
                                class="text-sm text-slate-600 hover:text-emerald-600 transition-colors flex items-center gap-2 group">
                                <span class="text-slate-400 group-hover:translate-x-1 group-hover:text-emerald-500 transition-all">→</span> Lacak Pesanan
                            </a></li>
                    </ul>
                </div>

                <!-- Bantuan Section -->
                <div>
                    <h3 class="font-semibold text-slate-900 text-sm uppercase tracking-widest mb-4 flex items-center gap-2">
                        <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                        Bantuan
                    </h3>
                    <ul class="space-y-3">
                        <li><a href="#"
                                class="text-sm text-slate-600 hover:text-emerald-600 transition-colors flex items-center gap-2 group">
                                <span class="text-slate-400 group-hover:translate-x-1 group-hover:text-emerald-500 transition-all">→</span> Cara Pemesanan
                            </a></li>
                        <li><a href="#"
                                class="text-sm text-slate-600 hover:text-emerald-600 transition-colors flex items-center gap-2 group">
                                <span class="text-slate-400 group-hover:translate-x-1 group-hover:text-emerald-500 transition-all">→</span> FAQ
                            </a></li>
                        <li><a href="#"
                                class="text-sm text-slate-600 hover:text-emerald-600 transition-colors flex items-center gap-2 group">
                                <span class="text-slate-400 group-hover:translate-x-1 group-hover:text-emerald-500 transition-all">→</span> Syarat & Ketentuan
                            </a></li>
                        <li><a href="{{ route('chat.index') }}"
                                class="text-sm text-slate-600 hover:text-emerald-600 transition-colors flex items-center gap-2 group">
                                <span class="text-slate-400 group-hover:translate-x-1 group-hover:text-emerald-500 transition-all">→</span> Chat & Bantuan
                            </a></li>
                    </ul>
                </div>
            </div>

            <!-- Contact Section -->
            <div class="bg-slate-50 rounded-2xl p-6 mb-8 border border-slate-100">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="flex items-start gap-4">
                        <div
                            class="h-10 w-10 rounded-lg bg-emerald-500/10 flex items-center justify-center flex-shrink-0 mt-1">
                            <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs text-slate-500 uppercase tracking-wide font-semibold mb-1">Email</p>
                            <a href="mailto:zimamadvertising@gmail.com"
                                class="text-sm text-slate-800 hover:text-emerald-600 transition-colors font-medium">
                                zimamadvertising@gmail.com
                            </a>
                        </div>
                    </div>
                    <div class="flex items-start gap-4">
                        <div
                            class="h-10 w-10 rounded-lg bg-emerald-500/10 flex items-center justify-center flex-shrink-0 mt-1">
                            <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs text-slate-500 uppercase tracking-wide font-semibold mb-1">Telepon</p>
                            <a href="tel:+6281275514355"
                                class="text-sm text-slate-800 hover:text-emerald-600 transition-colors font-medium">
                                +62 812 7551 4355
                            </a>
                        </div>
                    </div>
                    <div class="flex items-start gap-4">
                        <div
                            class="h-10 w-10 rounded-lg bg-emerald-500/10 flex items-center justify-center flex-shrink-0 mt-1">
                            <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs text-slate-500 uppercase tracking-wide font-semibold mb-1">Lokasi</p>
                            <p class="text-sm text-slate-700">
                                Jl. Pemda, Pangkalan Kerinci Kota, Kec. Pangkalan Kerinci, Kabupaten Pelalawan, Riau.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Divider -->
            <div class="border-t border-slate-200 my-8"></div>

            <!-- Bottom Footer -->
            <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                <div class="text-center md:text-left">
                    <p class="text-sm text-slate-500">
                        &copy; {{ date('Y') }} <span class="font-semibold text-slate-800">Zimam Advertising</span>. Semua
                        hak cipta dilindungi.
                    </p>
                    <p class="text-xs text-slate-400 mt-1">Solusi Terpercaya untuk Kebutuhan Cetak & Advertising Anda
                    </p>
                </div>
            </div>
        </div>


    </footer>
    @endif
</body>

</html>