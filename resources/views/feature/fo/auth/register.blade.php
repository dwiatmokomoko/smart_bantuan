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
            <p class="text-center">SISTEM INFORMASI MANAJEMEN</br>KELAYAKAN BPJS PBI</p>

            @if ($errors->any())
              <div class="alert alert-danger py-2">
                {{ $errors->first() }}
              </div>
            @endif

            <form method="POST" action="{{ route('user.register.post') }}">
              @csrf

              <div class="mb-3">
                <label for="nik" class="form-label">NIK</label>
                <input type="text" name="nik" id="nik"
                  class="form-control @error('nik') is-invalid @enderror"
                  value="{{ old('nik') }}" required>
                @error('nik') <div class="invalid-feedback">{{ $message }}</div> @enderror
              </div>

              <div class="mb-3">
                <label for="name" class="form-label">Nama Lengkap</label>
                <input type="text" name="name" id="name"
                  class="form-control @error('name') is-invalid @enderror"
                  value="{{ old('name') }}" required>
                @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
              </div>

              <div class="mb-3">
                <label for="tempat_lahir" class="form-label">Tempat Lahir</label>
                <input type="text" name="tempat_lahir" id="tempat_lahir"
                  class="form-control @error('tempat_lahir') is-invalid @enderror"
                  value="{{ old('tempat_lahir') }}" required>
                @error('tempat_lahir') <div class="invalid-feedback">{{ $message }}</div> @enderror
              </div>

              <div class="mb-3">
                <label for="tanggal_lahir" class="form-label">Tanggal Lahir</label>
                <input type="date" name="tanggal_lahir" id="tanggal_lahir"
                  class="form-control @error('tanggal_lahir') is-invalid @enderror"
                  value="{{ old('tanggal_lahir') }}" required>
                @error('tanggal_lahir') <div class="invalid-feedback">{{ $message }}</div> @enderror
              </div>

              <div class="mb-3">
                <label for="alamat" class="form-label">Alamat</label>
                <textarea name="alamat" id="alamat" rows="3"
                  class="form-control @error('alamat') is-invalid @enderror"
                  required>{{ old('alamat') }}</textarea>
                @error('alamat') <div class="invalid-feedback">{{ $message }}</div> @enderror
              </div>

              <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" name="email" id="email"
                  class="form-control @error('email') is-invalid @enderror"
                  value="{{ old('email') }}" required>
                @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
              </div>

              <div class="mb-3">
                <label for="no_hp" class="form-label">No HP</label>
                <input type="text" name="no_hp" id="no_hp"
                  class="form-control @error('no_hp') is-invalid @enderror"
                  value="{{ old('no_hp') }}" placeholder="+62..." required>
                @error('no_hp') <div class="invalid-feedback">{{ $message }}</div> @enderror
              </div>

              <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" name="password" id="password"
                  class="form-control @error('password') is-invalid @enderror" required>
                @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
              </div>

              <div class="mb-4">
                <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
                <input type="password" name="password_confirmation" id="password_confirmation"
                  class="form-control" required>
              </div>

              <div class="d-flex justify-content-between align-items-center mb-4">
                <a class="small text-decoration-none" href="{{ route('user.login') }}">Sudah punya akun? Masuk</a>
              </div>

              <button type="submit" class="btn btn-success w-100 py-8 fs-4 mb-2 rounded-2">Daftar</button>
            </form>

          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
