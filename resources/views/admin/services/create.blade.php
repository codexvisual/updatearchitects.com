@extends('layouts.admin')

@section('title', 'New Service')
@section('heading', 'New Service')

@section('content')
    <form method="POST" action="{{ route('admin.services.store') }}" enctype="multipart/form-data">
        @csrf
        @include('admin.services.partials.form')
    </form>
@endsection
