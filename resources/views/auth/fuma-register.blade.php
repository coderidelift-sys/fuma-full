<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Register - FUMA</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  
  <!-- Bootstrap 5 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  
  <style>
    body {
      background: linear-gradient(to right, #1e40af, #2563eb);
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
    }
    .register-card {
      background: #fff;
      border-radius: 16px;
      padding: 35px;
      max-width: 500px;
      width: 100%;
      box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    }
    .logo {
      font-size: 36px;
      font-weight: bold;
      color: #0059b3;
      text-align: center;
      margin-bottom: 20px;
    }
    .form-control:focus {
      border-color: #66aaff;
      box-shadow: 0 0 0 0.2rem rgba(102,170,255,0.25);
    }
    .btn-primary {
      background-color: #007bff;
      border: none;
    }
    .btn-primary:hover {
      background-color: #0056b3;
    }
    .text-muted a {
      color: #007bff;
    }
    .alert {
      border-radius: 8px;
    }
  </style>
</head>
<body>

  <div class="register-card">
    <div class="logo">
        <i class="fas fa-futbol me-2"></i>FUMA
    </div>
    <h4 class="text-center mb-3">Create Your Tournament Account</h4>
    
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    
    <form method="POST" action="{{ route('register') }}">
        @csrf
        
        <div class="mb-3">
            <label class="form-label">Full Name</label>
            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" 
                   placeholder="Your full name" value="{{ old('name') }}" required>
            @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        
        <div class="mb-3">
            <label class="form-label">Email Address</label>
            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" 
                   placeholder="example@mail.com" value="{{ old('email') }}" required>
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        
        <div class="mb-3">
            <label class="form-label">WhatsApp Number</label>
            <input type="text" name="whatsapp" class="form-control @error('whatsapp') is-invalid @enderror" 
                   placeholder="08xxxxxxxxxx" value="{{ old('whatsapp') }}">
            @error('whatsapp')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        
        <div class="mb-3">
            <label class="form-label">Password</label>
            <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" 
                   placeholder="Choose a secure password" required>
            @error('password')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        
        <div class="mb-3">
            <label class="form-label">Confirm Password</label>
            <input type="password" name="password_confirmation" class="form-control" 
                   placeholder="Confirm your password" required>
        </div>
        
        <button type="submit" class="btn btn-primary w-100">
            <i class="fas fa-user-plus me-2"></i> Register
        </button>
    </form>
    
    <div class="text-center mt-3 text-muted">
        Already have an account? <a href="{{ route('login') }}">Login</a>
    </div>
    
    <div class="text-center mt-4">
        <a href="{{ route('fuma.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="fas fa-arrow-left me-1"></i> Back to Homepage
        </a>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>