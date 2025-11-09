<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login Page</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        html,
        body {
            height: 100%;
            margin: 0;
            font-family: 'sans-serif', sans-serif;
            background-color: #e8ebf1;
        }

        .login-wrapper {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .login-container {
            display: flex;
            width: 900px;
            background-color: #fff;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 8px 20px rgba(1, 20, 104, 0.05);
        }

        .left-panel {
            flex: 1;
            background-color: #2d9cdb;
            color: #fff;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 0 60px;
        }

        .left-panel h1 {
            font-weight: 800;
            font-size: 2.5rem;
            margin-bottom: 15px;
            line-height: 1.1;
        }

        .left-panel p {
            font-size: 0.95rem;
            opacity: 0.9;
        }

        .right-panel {
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 50px 40px;
            background-color: #fff;
        }

        .login-card {
            width: 100%;
            max-width: 350px;
        }

        .login-card h2 {
            color: #2d9cdb;
            font-weight: 700;
            text-align: center;
        }

        .login-card small {
            display: block;
            text-align: center;
            color: #6c757d;
            margin-bottom: 25px;
        }

        .form-control {
            height: 45px;
            border-radius: 8px;
            border: 1px solid #bcd3e9;
        }

        .input-group-text {
            background-color: #fff;
        }

        .input-group .form-control {
            padding-left: 10px;
        }

        .form-control:focus {
            border-color: #2d9cdb;
            box-shadow: none;
        }

        .btn-login {
            background-color: #0b5fa4;
            color: #fff;
            height: 45px;
            border-radius: 8px;
            font-weight: 500;
            border: none;
        }

        .btn-login:hover {
            background-color: #094c82;
            color: #fff;
        }

        .forgot-link {
            color: #2d9cdb;
            font-size: 0.9rem;
            text-decoration: none;
        }

        .signup-link {
            color: #2d9cdb;
            text-decoration: none;
            font-weight: 500;
        }

        .form-check-label {
            font-size: 0.9rem;
        }

        @media (max-width: 768px) {
            .login-container {
                flex-direction: column;
                width: 100%;
                border-radius: 0;
            }

            .left-panel {
                text-align: center;
                align-items: center;
                padding: 40px 20px;
            }

            .right-panel {
                padding: 30px 20px;
            }
        }
    </style>
</head>

<body>
    <div class="login-wrapper">
        <div class="login-container">

            <div class="left-panel">
                <h1>HELLO,<br>WELCOME!</h1>
                <p>Glad to have you back. Sign in to continue your deliveries.</p>
            </div>

            <div class="right-panel">
                <div class="login-card">
                    <h2>SIGN IN</h2>
                    <small>Sign in to your account</small>

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            @foreach ($errors->all() as $error)
                                <div>{{ $error }}</div>
                            @endforeach
                        </div>
                    @endif

                    @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    <form action="/login" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                                <input type="email" name="username" class="form-control"
                                    placeholder="Enter your Email" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Password</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-lock"></i></span>

                                <input type="password" id="password" name="password" class="form-control"
                                    placeholder="Enter your Password" required>

                                <span class="input-group-text" id="togglePassword" style="cursor:pointer;">
                                    <i class="bi bi-eye"></i>
                                </span>
                            </div>
                        </div>


                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="remember">
                                <label class="form-check-label" for="remember">Remember me</label>
                            </div>
                            <a href="#" class="forgot-link">Forgot Password?</a>
                        </div>

                        <button type="submit" class="btn btn-login w-100 mb-3">Sign In</button>

                        <p class="text-center mb-0">
                            Don't have an account?
                            <a href="/register" class="signup-link">Sign Up</a>
                        </p>
                    </form>
                </div>
            </div>

        </div>
    </div>

    <script>
        document.getElementById('togglePassword').addEventListener('click', function() {
            const input = document.getElementById('password');
            const icon = this.querySelector('i');

            if (input.type === "password") {
                input.type = "text";
                icon.classList.remove("bi-eye");
                icon.classList.add("bi-eye-slash");
            } else {
                input.type = "password";
                icon.classList.remove("bi-eye-slash");
                icon.classList.add("bi-eye");
            }
        });
    </script>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
