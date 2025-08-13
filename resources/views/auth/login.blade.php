<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>BSUTH - Login</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="icon" href="{{ asset('assets/img/favicon_io/favicon.ico') }}" type="image/x-icon" />
  {!! ToastMagic::styles() !!}
  <style>
    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
      font-family: 'Poppins', sans-serif;
    }

    body {
      background: #f8f9fa;
      min-height: 100vh;
      position: relative;
      overflow: hidden;
    }

    .background-logo {
      position: absolute;
      top: 50%;
      left: 50%;
      width: 90vw;
      max-width: 900px;
      opacity: 0.05;
      transform: translate(-50%, -50%) scale(1);
      z-index: 0;
      animation: pulseLogo 20s ease-in-out infinite;
    }

    @keyframes pulseLogo {
      0%, 100% {
        transform: translate(-50%, -50%) scale(1);
      }
      50% {
        transform: translate(-50%, -50%) scale(1.1);
      }
    }

    .login-card {
      position: relative;
      z-index: 1;
      background: #fff;
      border-radius: 16px;
      box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
      padding: 3rem;
      width: 100%;
      animation: fadeIn 0.6s ease-in-out;
    }

    .login-card h3 {
      margin-bottom: 1.5rem;
      font-weight: 600;
      color: #1a2035;
    }

    .form-control {
      border-radius: 12px;
    }

    .form-control:focus {
      box-shadow: none;
      border-color: #f96332;
    }

    .btn-login {
      background-color: #1a2035;
      color: #fff;
      border-radius: 12px;
      padding: 0.75rem 1.25rem;
      font-weight: 500;
      transition: background-color 0.3s ease;
    }

    .btn-login:hover {
      background-color: #f96332;
    }

    .form-text a {
      color: #f96332;
      text-decoration: none;
    }

    .form-text a:hover {
      text-decoration: underline;
    }

    .extra-links {
      margin-top: 2rem;
      text-align: center;
      font-size: 0.95rem;
    }

    .extra-links a {
      color: #1a2035;
      font-weight: 500;
      margin: 0 8px;
    }

    .extra-links a:hover {
      color: #f96332;
    }

    @keyframes fadeIn {
      from {
        opacity: 0;
        transform: translateY(20px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }
  .input-eye-group .form-control {
  border-top-right-radius: 0.8rem !important;
  border-bottom-right-radius: 0.8rem !important;
}
</style>
</head>
<body>

<img src="{{ asset('assets/img/bsth-logo.jpeg') }}" alt="Logo" class="background-logo">
<div class="min-vh-100 d-flex align-items-center justify-content-center">
  <div class="container">
   
      <div class="row justify-content-center">
        <div class="col-8 col-md-5 col-lg-5">
          <div class="login-card mx-auto">
          <h3 class="text-center">Welcome Back 👋</h3>
          <form action="{{ route('login') }}" method="POST">
            @csrf
            <div class="mb-3">
              <label for="email" class="form-label">Email address</label>
              <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="mb-3 position-relative">
              <label for="password" class="form-label">Password</label>
              <div class="input-group position-relative input-eye-group">
                <input type="password" class="form-control" id="password" name="password" required>
                <span id="togglePassword" style="position:absolute; right:12px; top:50%; transform:translateY(-50%); cursor:pointer; z-index:2;">
                  <svg id="eyeIcon" xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-eye" viewBox="0 0 16 16">
                    <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8zM1.173 8a13.133 13.133 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13.133 13.133 0 0 1 14.828 8a13.133 13.133 0 0 1-1.66 2.043C11.879 11.332 10.12 12.5 8 12.5c-2.12 0-3.879-1.168-5.168-2.457A13.133 13.133 0 0 1 1.172 8z"/>
                    <path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5zm0 1a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3z"/>
                  </svg>
                </span>
              </div>
            </div>
          
            <div class="d-flex justify-content-between align-items-center mb-3">
              <div class="form-check">
                <input class="form-check-input" type="checkbox" id="rememberMe">
                <label class="form-check-label" for="rememberMe">
                  Remember me
                </label>
              </div>
              <div class="form-text">
                <a href="#">Forgot password?</a>
              </div>
            </div>
            <button type="submit" class="btn btn-login w-100">Login</button>
          </form>
          <div class="text-center mt-4 form-text">
            Don’t have an account? <a href="#">Sign up</a>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script>
      document.addEventListener('DOMContentLoaded', function() {
        const passwordInput = document.getElementById('password');
        const togglePasswordBtn = document.getElementById('togglePassword');
        const eyeIcon = document.getElementById('eyeIcon');
        let revealed = false;

        togglePasswordBtn.addEventListener('click', function() {
          revealed = !revealed;
          passwordInput.type = revealed ? 'text' : 'password';
          // Optionally swap icon (eye/eye-slash)
          eyeIcon.innerHTML = revealed
            ? `<path d='M13.359 11.238l2.122 2.122a.75.75 0 1 1-1.06 1.06l-2.122-2.122A7.48 7.48 0 0 1 8 13.5c-5 0-8-5.5-8-5.5a16.978 16.978 0 0 1 3.478-4.504l-1.415-1.415a.75.75 0 1 1 1.06-1.06l14 14a.75.75 0 0 1-1.06 1.06l-2.122-2.122zM8 12.5c2.12 0 3.879-1.168 5.168-2.457A13.133 13.133 0 0 0 14.828 8a13.133 13.133 0 0 0-1.66-2.043A7.48 7.48 0 0 0 8 3.5c-2.12 0-3.879 1.168-5.168 2.457A13.133 13.133 0 0 0 1.172 8c.058.09.122.18.192.271L8 12.5z'/><path d='M11.354 8.354a3.5 3.5 0 1 1-4.708-4.708l1.06 1.06a2.5 2.5 0 1 0 2.588 2.588l1.06 1.06z'/>`
            : `<path d='M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8zM1.173 8a13.133 13.133 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13.133 13.133 0 0 1 14.828 8a13.133 13.133 0 0 1-1.66 2.043C11.879 11.332 10.12 12.5 8 12.5c-2.12 0-3.879-1.168-5.168-2.457A13.133 13.133 0 0 1 1.172 8z'/><path d='M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5zm0 1a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3z'/>`;
        });
      });
    </script>
    {!! ToastMagic::scripts() !!}
</body>
</html>
