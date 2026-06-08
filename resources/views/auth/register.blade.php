<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Laundry App</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

    <style>
        body{
            background: linear-gradient(135deg, #1e293b, #0f172a);
            height:100vh;
            display:flex;
            align-items:center;
            justify-content:center;
            font-family: Arial;
        }

        .card-auth{
            width:400px;
            background:white;
            padding:30px;
            border-radius:15px;
            box-shadow:0 10px 30px rgba(0,0,0,.3);
        }

        .btn-primary{
            width:100%;
            border-radius:10px;
        }

        .password-input-group {
            position: relative;
        }

        .password-toggle-btn {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            cursor: pointer;
            color: #6c757d;
            padding: 5px 8px;
        }

        .password-toggle-btn:hover {
            color: #0d6efd;
        }

        .form-control-password {
            padding-right: 40px;
        }
    </style>
</head>
<body>

<div class="card-auth">

    <h3 class="text-center mb-3">Register</h3>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <div class="mb-3">
            <label>Nama</label>
            <input type="text" name="name" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Email</label>
            <input type="email" name="email" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Password</label>
            <div class="password-input-group">
                <input type="password" name="password" class="form-control form-control-password" id="registerPassword" required>
                <button type="button" class="password-toggle-btn" onclick="togglePasswordRegister()">
                    <i class="bi bi-eye" id="registerPasswordIcon"></i>
                </button>
            </div>
        </div>

        <div class="mb-3">
            <label>Konfirmasi Password</label>
            <div class="password-input-group">
                <input type="password" name="password_confirmation" class="form-control form-control-password" id="confirmPassword" required>
                <button type="button" class="password-toggle-btn" onclick="togglePasswordConfirm()">
                    <i class="bi bi-eye" id="confirmPasswordIcon"></i>
                </button>
            </div>
        </div>

        <button type="submit" class="btn btn-primary">
            Register
        </button>

    </form>

    <p class="text-center mt-3">
        Sudah punya akun?
        <a href="{{ route('login') }}">Login</a>
    </p>

</div>

<script>
    function togglePasswordRegister() {
        const passwordInput = document.getElementById('registerPassword');
        const passwordIcon = document.getElementById('registerPasswordIcon');
        
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

    function togglePasswordConfirm() {
        const passwordInput = document.getElementById('confirmPassword');
        const passwordIcon = document.getElementById('confirmPasswordIcon');
        
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

</body>
</html>