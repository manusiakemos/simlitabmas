@extends('layouts.web')
@section('content')
    {{--hero banner--}}
    <div class="position-relative">
        @include('shared.hero_banner')
        @include('shared.features', compact('features'))
    </div>
    {{--end hero bannner--}}
@endsection
