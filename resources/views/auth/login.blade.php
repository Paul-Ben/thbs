{{-- <x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
                <span class="ms-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
            </label>
        </div>

        <div class="flex items-center justify-end mt-4">
            @if (Route::has('password.request'))
                <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('password.request') }}">
                    {{ __('Forgot your password?') }}
                </a>
            @endif

            <x-primary-button class="ms-3">
                {{ __('Log in') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout> --}}

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Login - College Application System</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <style>
    body, html {
      height: 100%;
      background: #f8f9fa;
    }
    .login-container {
      min-height: calc(100vh - 56px); /* subtract navbar height */
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 2rem;
    }
    .login-form {
      background: white;
      padding: 2rem;
      border-radius: 0.5rem;
      box-shadow: 0 0.125rem 0.25rem rgba(0,0,0,0.075);
      width: 100%;
      max-width: 400px;
    }
    .info-cards .card {
      cursor: pointer;
      transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .info-cards .card:hover {
      transform: translateY(-5px);
      box-shadow: 0 0.5rem 1rem rgba(0,0,0,0.15);
    }
    @media (max-width: 767.98px) {
      .login-columns {
        flex-direction: column;
      }
      .info-cards .card {
        margin-bottom: 1rem;
      }
    }
    /* Optional: adjust navbar brand logo size */
    .navbar-brand img {
      height: 40px;
      width: auto;
    }
  </style>
</head>
<body>

  <!-- Navbar -->
  <nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm">
    <div class="container">
      <a class="navbar-brand d-flex align-items-center" href="#">
        <!-- Replace src with your logo path -->
        <img src="logo.png" alt="College Logo" class="me-2" />
        <span class="fw-bold">CollegeApp</span>
      </a>
      <button 
        class="navbar-toggler" 
        type="button" 
        data-bs-toggle="collapse" 
        data-bs-target="#navbarMenu" 
        aria-controls="navbarMenu" 
        aria-expanded="false" 
        aria-label="Toggle navigation"
      >
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="navbarMenu">
        <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
          <li class="nav-item">
            <a class="nav-link" href="/apply">Apply Now</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="/programs">All Programs</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="/about">About Us</a>
          </li>
        </ul>
      </div>
    </div>
  </nav>

  <!-- Main Content -->
  <div class="container login-container">
    <div class="d-flex login-columns gap-4 w-100">
     
      <!-- Login Form Column -->
      <div class="login-form flex-grow-1">
         @if ($errors->any())
          <div class="alert alert-danger">
              <ul>
                  @foreach ($errors->all() as $error)
                      <li>{{ $error }}</li>
                  @endforeach
              </ul>
          </div>
      @endif
        <h2 class="mb-4 text-center">Login</h2>
        <form action="{{route('login')}}" method="POST" novalidate>
            @csrf
          <div class="mb-3">
            <label for="email" class="form-label">Email address</label>
            <input 
              type="email" 
              class="form-control" 
              id="email" 
              name="email" 
              placeholder="Enter your email" 
              required 
              autofocus
              autocomplete="username"
            />
          </div>
          <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input 
              type="password" 
              class="form-control" 
              id="password" 
              name="password" 
              placeholder="Enter your password" 
              required
            />
          </div>
          <div class="mb-3 form-check">
            <input type="checkbox" class="form-check-input" id="remember_me" name="remember" />
            <label class="form-check-label" for="remember_me">Remember me</label>
          </div>
          <button type="submit" class="btn btn-primary w-100">Login</button>
        </form>
      </div>
      
      <!-- Cards Column -->
      <div class="info-cards d-flex flex-column flex-grow-1 gap-3">
        <div class="card text-center p-3" onclick="location.href='/apply'" role="button" tabindex="0" onkeypress="if(event.key==='Enter') location.href='/apply';">
          <div class="card-body">
            <h5 class="card-title">Apply Now</h5>
          </div>
        </div>
        <div class="card text-center p-3" onclick="location.href='/programs'" role="button" tabindex="0" onkeypress="if(event.key==='Enter') location.href='/programs';">
          <div class="card-body">
            <h5 class="card-title">All Programs</h5>
          </div>
        </div>
        <div class="card text-center p-3" onclick="location.href='/about'" role="button" tabindex="0" onkeypress="if(event.key==='Enter') location.href='/about';">
          <div class="card-body">
            <h5 class="card-title">About Us</h5>
          </div>
        </div>
      </div>
      
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
