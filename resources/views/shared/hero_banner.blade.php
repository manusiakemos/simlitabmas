<!-- Hero for FREE version -->
<section class="section section-lg section-hero section-shaped bg-default">
    <!-- Background circles -->
    <div class="container shape-container d-flex align-items-center py-lg">
        <div class="col px-0">
            <div class="row align-items-center justify-content-center">
                <div class="col-lg-4">
                    <h1 class="text-warning">{{ config('app.name') }}</h1>
                    <div class="lead text-white">
                        {!! $tentang->setting_value !!}
                    </div>
                </div>
                <div class="col-lg-8">
                    @include('shared.web_slider')
                </div>
            </div>
        </div>
    </div>
    <!-- SVG separator -->
    <div class="separator separator-bottom separator-skew zindex-100">
        <svg x="0" y="0" viewBox="0 0 2560 100" preserveAspectRatio="none" version="1.1"
             xmlns="http://www.w3.org/2000/svg">
            <polygon class="fill-white" points="2560 0 2560 100 0 100"></polygon>
        </svg>
    </div>
</section>
