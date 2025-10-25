@extends('app.app_fo')


@section('content_fo')
    <!-- Breadcrumb Begin -->
    <div class="breadcrumb-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="breadcrumb__option">
                        <a href="{{ route('fo.home.index') }}"><span class="fa fa-home"></span> Home</a>
                        <span>Pengembang</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Breadcrumb End -->

    <!-- Testimonial Section Begin -->
    <section class="spad">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="section-title text-center">
                        <h3>Our Team</h3>
                    </div>
                </div>
            </div>
<br><br><br><br>
            {{-- grid: 3 kartu di tengah --}}
            <div class="row g-4 justify-content-center">

                <div class="col-12 col-sm-6 col-lg-4 d-flex justify-content-center">
                    <div class="col-card testimonial__item h-100 text-center" style="max-width:360px;">
                        <img src="{{ asset('fo/img/testimonial/bumarlin.jpeg') }}" alt="" class="rounded-circle"
                            style="width:160px;height:160px;object-fit:cover;margin-top:-40px;">
                        <h5 class="mt-3">YUMARLIN MZ, S.Kom., M.P.d., M.Kom</h5>
                        <span>DOSEN PENGAMPU</span>
                        <div class="icons mt-3">
                            <a href="https://wa.me/628157986629" target="__blank"><i class="fa fa-whatsapp"></i></a>
                            <a href="mailto:yumarlin@janabadra.ac" target="__blank" class="ms-3"><i
                                    class="fa fa-envelope"></i></a>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-sm-6 col-lg-4 d-flex justify-content-center">
                    <div class="col-card testimonial__item h-100 text-center" style="max-width:360px;">
                        <img src="{{ asset('fo/img/testimonial/peni.jpeg') }}" alt="" class="rounded-circle"
                            style="width:160px;height:160px;object-fit:cover;margin-top:-40px;">
                        <h5 class="mt-3">Peni Kurniawati</h5>
                        <span>24330021</span>
                        <div class="icons mt-3">
                            <a href="https://wa.me/6287845666600" target="__blank"><i class="fa fa-whatsapp"></i></a>
                            <a href="mailto:devribudi@gmail.com" target="__blank" class="ms-3"><i
                                    class="fa fa-envelope"></i></a>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-sm-6 col-lg-4 d-flex justify-content-center">
                    <div class="col-card testimonial__item h-100 text-center" style="max-width:360px;">
                        <img src="{{ asset('fo/img/testimonial/peni.jpeg') }}" alt="" class="rounded-circle"
                            style="width:160px;height:160px;object-fit:cover;margin-top:-40px;">
                        <h5 class="mt-3">Lorem Ipsum</h5>
                        <span>24330021</span>
                        <div class="icons mt-3">
                            <a href="https://wa.me/6282136352767"><i class="fa fa-whatsapp"></i></a>
                            <a href="mailto:21330055@janabadra.web.id" class="ms-3"><i class="fa fa-envelope"></i></a>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- Testimonial Section End -->
@endsection
