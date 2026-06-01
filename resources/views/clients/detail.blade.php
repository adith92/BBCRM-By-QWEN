@extends('layouts.app')

@section('header_title', 'Client Profile')

@section('content')
    <livewire:client-detail :client="$client" />
@endsection
