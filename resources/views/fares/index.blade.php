@extends('layouts.app') <!-- or whatever your main layout is -->

@section('title', 'Fares')

@section('content')
<h1>Fares</h1>

@foreach($routes as $route)
    <h2>{{ $route->name }}</h2>

    <h3>Passenger Fares</h3>
    <ul>
        @foreach($route->passengerFares as $fare)
            <li>{{ $fare->fare_type }} - ₱{{ number_format($fare->amount, 2) }}</li>
        @endforeach
    </ul>

    <h3>Vehicle Fares</h3>
    <ul>
        @foreach($route->vehicleFares as $fare)
            <li>{{ $fare->vehicle_type }} - ₱{{ number_format($fare->fare_min, 2) }} 
                @if($fare->fare_max) - ₱{{ number_format($fare->fare_max, 2) }} @endif
            </li>
        @endforeach
    </ul>

@endforeach
@endsection