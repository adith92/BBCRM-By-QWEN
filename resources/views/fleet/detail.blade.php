@extends('layouts.app')

@section('header_title', 'Vehicle Details')

@section('content')
    <livewire:fleet-detail :vehicle="$vehicle" />
@endsection
