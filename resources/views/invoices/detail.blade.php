@extends('layouts.app')

@section('header_title', 'Invoice Details')

@section('content')
    <livewire:invoice-detail :invoice="$invoice" />
@endsection
