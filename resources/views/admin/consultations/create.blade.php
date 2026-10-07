@extends('layouts.admin')

@section('title', 'Add Lead')
@section('heading', 'Add Consultation Lead')

@section('content')
    <form method="POST" action="{{ route('admin.consultations.store') }}">
        @csrf
        @include('admin.consultations.partials.form')
    </form>
@endsection
