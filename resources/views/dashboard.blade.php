@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            @include('pages.dashboard.cardsensor')
             <!-- Card actuator -->
             <div class="col-12 mb-4">
                <h2 class="text-center mb-4">ACTUATOR</h2>
            </div>
            @include('pages.dashboard.kontrol')
            @include('pages.dashboard.grafik')
        </div>
    </div>
@endsection
