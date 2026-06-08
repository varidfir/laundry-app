@extends('layouts.app')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="mb-1">Kelola User</h2>
        <p class="text-muted mb-0" style="font-size:14px;">Manajemen akun staf &amp; admin laundry</p>
    </div>

    <a href="{{ route('users.create') }}" class="btn btn-primary" id="btn-tambah-user">
        <i class="bi bi-person-plus-fill me-1"></i> Tambah User
    </a>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body">

        <table class="table table-hover align-middle">

            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Bergabung</th>
                    <th>Aksi</th>
                </tr>
            </thead>

            <tbody>

                @forelse($users as $user)

                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <div style="width:34px; height:34px; border-radius:50%; background:#3b82f6; display:flex; align-items:center; justify-content:center; color:white; font-weight:700; font-size:13px; flex-shrink:0;">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                            <span class="fw-semibold">{{ $user->name }}</span>
                            @if($user->id === auth()->id())
                                <span class="badge bg-secondary" style="font-size:10px;">Anda</span>
                            @endif
                        </div>
                    </td>
                    <td class="text-muted">{{ $user->email }}</td>
                    <td>
                        @if($user->role === 'admin')
                            <span class="badge" style="background:rgba(239,68,68,0.15); color:#dc2626; border:1px solid rgba(239,68,68,0.3); font-size:11px; text-transform:uppercase; letter-spacing:0.5px;">
                                <i class="bi bi-shield-fill me-1"></i>Admin
                            </span>
                        @else
                            <span class="badge" style="background:rgba(34,197,94,0.15); color:#16a34a; border:1px solid rgba(34,197,94,0.3); font-size:11px; text-transform:uppercase; letter-spacing:0.5px;">
                                <i class="bi bi-person-fill me-1"></i>Kasir
                            </span>
                        @endif
                    </td>
                    <td class="text-muted" style="font-size:13px;">{{ $user->created_at->format('d M Y') }}</td>
                    <td>
                        <a href="{{ route('users.edit', $user->id) }}" class="btn btn-warning btn-sm" id="btn-edit-user-{{ $user->id }}">
                            <i class="bi bi-pencil-fill me-1"></i>Edit
                        </a>

                        @if($user->id !== auth()->id())
                        <form action="{{ route('users.destroy', $user->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger btn-sm"
                                    id="btn-hapus-user-{{ $user->id }}"
                                    onclick="return confirm('Hapus user {{ $user->name }}?')">
                                <i class="bi bi-trash-fill me-1"></i>Hapus
                            </button>
                        </form>
                        @endif
                    </td>
                </tr>

                @empty
                <tr>
                    <td colspan="6" class="text-center py-5 text-muted">
                        <i class="bi bi-people fs-1 d-block mb-2 text-muted opacity-50"></i>
                        Belum ada data user
                    </td>
                </tr>
                @endforelse

            </tbody>

        </table>

    </div>
</div>

@endsection
