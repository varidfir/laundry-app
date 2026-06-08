<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laundry App</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <style>

        body{
            background:#f4f7fc;
        }

        .sidebar{
            width:250px;
            height:100vh;
            background:#1e293b;
            position:fixed;
            left:0;
            top:0;
            color:white;
            display:flex;
            flex-direction:column;
        }

        .sidebar-brand {
            padding:20px;
            font-size:1.2rem;
            font-weight:700;
            border-bottom:1px solid rgba(255,255,255,0.1);
            margin-bottom:8px;
        }

        .sidebar-nav {
            flex:1;
        }

        .sidebar a,
        .sidebar button{
            display:flex;
            align-items:center;
            gap:10px;
            width:100%;
            text-align:left;
            color:rgba(255,255,255,0.8);
            text-decoration:none;
            padding:12px 20px;
            background:none;
            border:none;
            font-size:14px;
            transition:background 0.2s, color 0.2s;
        }

        .sidebar a:hover,
        .sidebar button:hover{
            background:#334155;
            color:white;
        }

        .sidebar a.active {
            background:#3b82f6;
            color:white;
        }

        .sidebar-footer {
            border-top:1px solid rgba(255,255,255,0.1);
            padding:15px 20px;
        }

        .sidebar-user-info {
            display:flex;
            align-items:center;
            gap:10px;
            margin-bottom:10px;
        }

        .sidebar-avatar {
            width:36px;
            height:36px;
            border-radius:50%;
            background:#3b82f6;
            display:flex;
            align-items:center;
            justify-content:center;
            font-weight:700;
            font-size:14px;
            flex-shrink:0;
        }

        .sidebar-user-name {
            font-size:13px;
            font-weight:600;
            color:white;
            white-space:nowrap;
            overflow:hidden;
            text-overflow:ellipsis;
        }

        .role-badge {
            font-size:10px;
            padding:2px 8px;
            border-radius:20px;
            font-weight:600;
            text-transform:uppercase;
            letter-spacing:0.5px;
        }

        .role-badge.admin {
            background:rgba(239,68,68,0.2);
            color:#fca5a5;
            border:1px solid rgba(239,68,68,0.3);
        }

        .role-badge.kasir {
            background:rgba(34,197,94,0.2);
            color:#86efac;
            border:1px solid rgba(34,197,94,0.3);
        }

        .content{
            margin-left:250px;
            padding:30px;
        }

        .card-dashboard{
            border:none;
            border-radius:15px;
            box-shadow:0 5px 15px rgba(0,0,0,.08);
        }

    </style>

</head>
<body>

<div class="sidebar">

    <div class="sidebar-brand">
        <i class="bi bi-water me-2"></i>Laundry App
    </div>

    <div class="sidebar-nav">

        <a href="/dashboard" class="{{ request()->is('dashboard') ? 'active' : '' }}">
            <i class="bi bi-speedometer2"></i> Dashboard
        </a>

        <a href="/pesanan" class="{{ request()->is('pesanan*') ? 'active' : '' }}">
            <i class="bi bi-basket2"></i> Kelola Pesanan
        </a>

        <a href="{{ route('transaksi.index') }}" class="{{ request()->is('transaksi') ? 'active' : '' }}">
            <i class="bi bi-clock-history"></i> Riwayat Transaksi
        </a>

        @if(auth()->check() && auth()->user()->role === 'admin')
            <a href="{{ route('users.index') }}" class="{{ request()->is('users*') ? 'active' : '' }}">
                <i class="bi bi-people"></i> Kelola User
            </a>
        @endif

    </div>

    <div class="sidebar-footer">
        @auth
        <div class="sidebar-user-info">
            <div class="sidebar-avatar">
                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
            </div>
            <div style="overflow:hidden;">
                <div class="sidebar-user-name">{{ auth()->user()->name }}</div>
                <span class="role-badge {{ auth()->user()->role }}">
                    {{ auth()->user()->role }}
                </span>
            </div>
        </div>
        @endauth

        {{-- LOGOUT --}}
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" style="padding:8px 12px; font-size:13px; color:rgba(255,255,255,0.6);">
                <i class="bi bi-box-arrow-right"></i> Logout
            </button>
        </form>
    </div>

</div>

<div class="content">

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @yield('content')

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>