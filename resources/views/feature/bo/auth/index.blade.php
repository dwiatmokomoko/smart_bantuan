@extends("app.app")
@section('content')
    <div
        class="position-relative overflow-hidden radial-gradient min-vh-100 d-flex align-items-center justify-content-center">
        <div class="d-flex align-items-center justify-content-center w-100">
            <div class="row justify-content-center w-100">
                <div class="col-md-8 col-lg-6 col-xxl-3">
                    <div class="card mb-0">
                        <div class="card-body">
                            <div class="text-nowrap logo-img text-center d-block py-3 w-100">
                                <img src="{{asset("fo/img/silayak-logo2.png")}}" width="180" alt="" class="rounded-circle">
                            </div>
                            <p class="text-center">SISTEM INFORMASI MANAJEMEN</br>KELAYAKAN RUSUNAWA</p>
                            <form method="POST" action="{{ route("admin.auth") }}">
                                @csrf
                                <div class="mb-3">
                                    <label for="exampleInputEmail1" class="form-label">Email</label>
                                    <input type="email" name="email" class="form-control" id="email"
                                        aria-describedby="emailHelp">
                                </div>
                                <div class="mb-4">
                                    <label for="exampleInputPassword1" class="form-label">Password</label>
                                    <input type="password" class="form-control" name="password" id="password">
                                </div>
                                {{-- <a href="./index.html" class="btn btn-primary w-100 py-8 fs-4 mb-4 rounded-2">Sign In</a> --}}
                                <button class="btn btn-primary w-100 py-8 fs-4 mb-4 rounded-2">Sign In</a>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
