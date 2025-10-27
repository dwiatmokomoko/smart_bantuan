@extends("app.app")

@section('content')
<div class="position-relative overflow-hidden radial-gradient min-vh-100 d-flex align-items-center justify-content-center">
  <div class="d-flex align-items-center justify-content-center w-100">
    <div class="row justify-content-center w-100">
      <div class="col-md-8 col-lg-6 col-xxl-3">
        <div class="card mb-0">
          <div class="card-body">
            <div class="text-nowrap logo-img text-center d-block py-3 w-100">
              <img src="{{ asset('fo/img/silayak-logo2.png') }}" width="180" alt="" class="rounded-circle">
            </div>
            <p class="text-center">SISTEM INFORMASI MANAJEMEN</br>KELAYAKAN RUSUNAWA</p>

            @if ($errors->any())
              <div class="alert alert-danger py-2">
                {{ $errors->first() }}
              </div>
            @endif

            <form method="POST" action="{{ route('user.login.post') }}">
              @csrf
              <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input
                  type="email"
                  name="email"
                  id="email"
                  class="form-control @error('email') is-invalid @enderror"
                  value="{{ old('email') }}"
                  required
                  autofocus
                >
                @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
              </div>

              <div class="mb-4">
                <label for="password" class="form-label">Password</label>
                <input
                  type="password"
                  name="password"
                  id="password"
                  class="form-control @error('password') is-invalid @enderror"
                  required
                >
                @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
              </div>

              <div class="d-flex justify-content-between align-items-center mb-4">
                {{-- <div class="form-check">
                  <input class="form-check-input" type="checkbox" name="remember" id="remember">
                  <label class="form-check-label" for="remember">Ingat saya</label>
                </div> --}}
                <a class="small text-decoration-none" href="{{ route('pre-eligibility.form') }}">Belum punya akun?Daftar Di sini </a> <br>
                <a class="small text-decoration-none" href="{{ route('admin.login') }}">Login Sebagai Admin</a>
              </div>

              <button type="submit" class="btn btn-primary w-100 py-8 fs-4 mb-2 rounded-2">Sign In</button>
            </form>

          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
