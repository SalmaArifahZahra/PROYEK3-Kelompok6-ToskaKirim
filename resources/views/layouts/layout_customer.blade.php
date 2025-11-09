<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>@yield('title', 'Customer Page')</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

  <style>
    body {
      background-color: #f3f7fc;
      font-family: 'Poppins', sans-serif;
    }

    .navbar-custom {
      background-color: #2d9cdb;
    }

    .navbar-custom .navbar-brand,
    .navbar-custom .nav-link {
      color: white !important;
    }

    .content-wrapper {
      min-height: 100vh;
      padding: 40px 20px;
      display: flex;
      justify-content: center;
      align-items: flex-start;
    }
  </style>
</head>

<body>
  <nav class="navbar navbar-custom navbar-expand-lg shadow-sm">
    <div class="container-fluid">
      <a class="navbar-brand fw-semibold" href="{{ url('/') }}">MyApp</a>
      <div class="d-flex align-items-center">
        <span class="me-3 text-white">{{ Auth::user()->name ?? 'Guest' }}</span>
        {{-- <form method="POST" action="{{ route('logout') }}">
          @csrf
          <button class="btn btn-light btn-sm" type="submit">Logout</button>
        </form> --}}
      </div>
    </div>
  </nav>

  <div class="content-wrapper">
    @yield('content')
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
