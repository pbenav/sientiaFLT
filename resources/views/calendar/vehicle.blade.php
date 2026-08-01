@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <livewire:vehicle-calendar :vehicle_id="$vehicle->id" />
</div>
@endsection
