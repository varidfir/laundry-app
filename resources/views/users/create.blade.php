@extends('layouts.app')

@section('content')

<div class="d-flex align-items-center gap-3 mb-4">
    <a href="{{ route('users.index') }}" class="btn btn-light btn-sm">
        <i class="bi bi-arrow-left"></i>
    </a>
    <div>
        <h2 class="mb-0">Tambah User</h2>
        <p class="text-muted mb-0" style="font-size:14px;">Buat akun staf baru</p>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-lg-6">

        <div class="card shadow-sm border-0">
            <div class="card-body p-4">

                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('users.store') }}" method="POST" id="form-create-user">
                    @csrf

                    <div class="mb-3">
                        <label for="name" class="form-label fw-semibold">Nama Lengkap</label>
                        <input type="text"
                               name="name"
                               id="name"
                               class="form-control @error('name') is-invalid @enderror"
                               value="{{ old('name') }}"
                               placeholder="Masukkan nama lengkap"
                               required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label fw-semibold">Email</label>
                        <input type="email"
                               name="email"
                               id="email"
                               class="form-control @error('email') is-invalid @enderror"
                               value="{{ old('email') }}"
                               placeholder="contoh@email.com"
                               required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label fw-semibold">Password</label>
                        <div style="position: relative;">
                            <input type="password"
                                   name="password"
                                   id="password"
                                   class="form-control @error('password') is-invalid @enderror"
                                   placeholder="Minimal 8 karakter"
                                   style="padding-right: 40px;"
                                   required>
                            <button type="button" onclick="togglePasswordCreateUser()" 
                                    style="position: absolute; right: 12px; top: 50%; transform: translateY(-50%); background: none; border: none; cursor: pointer; color: #6c757d; padding: 5px 8px;">
                                <i class="bi bi-eye" id="createPasswordIcon" style="font-size: 18px;"></i>
                            </button>
                        </div>
                        @error('password')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="role" class="form-label fw-semibold">Role</label>
                        <select name="role" id="role" class="form-select @error('role') is-invalid @enderror" required>
                            <option value="" disabled selected>-- Pilih Role --</option>
                            <option value="kasir" {{ old('role') == 'kasir' ? 'selected' : '' }}>
                                Kasir
                            </option>
                            <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>
                                Admin (Pemilik)
                            </option>
                        </select>
                        @error('role')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary" id="btn-simpan-user">
                            <i class="bi bi-person-check-fill me-1"></i> Simpan User
                        </button>
                        <a href="{{ route('users.index') }}" class="btn btn-light">
                            Batal
                        </a>
                    </div>

                </form>

            </div>
        </div>

    </div>
</div>

<style>
    @import url('https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css');
</style>

<script>
    function togglePasswordCreateUser() {
        const passwordInput = document.getElementById('password');
        const passwordIcon = document.getElementById('createPasswordIcon');
        
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            passwordIcon.classList.remove('bi-eye');
            passwordIcon.classList.add('bi-eye-slash');
        } else {
            passwordInput.type = 'password';
            passwordIcon.classList.remove('bi-eye-slash');
            passwordIcon.classList.add('bi-eye');
        }
    }
</script>

@endsection
