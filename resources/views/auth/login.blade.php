<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Login - MyApp</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
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
          <form action="/login" method="POST">
            <div class="mb-3">
              <label for="email" class="form-label">Email address</label>
              <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="mb-3">
              <label for="password" class="form-label">Password</label>
              <input type="password" class="form-control" id="password" name="password" required>
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
          <!-- <div class="extra-links">
            <a href="{{ route('application.create') }}">Apply</a>
            <a href="#">All Programs</a>
            <a href="#">About Us</a>
          </div> -->
        </div>
      </div>
    </div>
  </div>

</body>
</html>
