@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <livewire:booking-form :vehicle_id="$vehicle->id" :customer_id="auth()->id() ?? 1" />
</div>
@endsection
