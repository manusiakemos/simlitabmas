@foreach($features as $i => $feature)
    <section class="{{ $i%2 == 0 ? 'section bg-white' :'section bg-secondary' }}">
        <div class="container">
            <div class="row row-grid align-items-center">
                @if($i%2 == 0)
                    <div class="col-md-6">
                        <img src="{{ asset('images/right.png') }}" alt="" class="src img-fluid d-none d-sm-block">
                    </div>
                    <div class="col-md-6">
                        <div class="pl-md-5">
                            <div class="icon icon-lg icon-shape icon-shape-warning shadow rounded-circle mb-5">
                                <i class="">{{$i+=1}}</i>
                            </div>
                            <h3>{{$feature->setting_name}}</h3>
                            {!! $feature->setting_value !!}
                        </div>
                    </div>
                @else
                    <div class="col-md-6">
                        <div class="pl-md-5">
                            <div class="icon icon-lg icon-shape icon-shape-warning shadow rounded-circle mb-5">
                                <i class="">{{$i+=1}}</i>
                            </div>
                            <h3>{{ $feature->setting_name }}</h3>
                            {!! $feature->setting_value !!}
                        </div>
                    </div>
                    <div class="col-md-6">
                        <img src="{{ asset('images/left.png') }}" alt="" class="src img-fluid d-none d-sm-block">
                    </div>

                @endif
            </div>
        </div>
    </section>
@endforeach
