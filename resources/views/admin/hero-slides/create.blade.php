@extends('layouts.admin')

@section('title', 'New Slide')
@section('heading', 'New Slide')

@section('content')
    <form method="POST" action="{{ route('admin.hero-slides.store') }}" enctype="multipart/form-data">
        @csrf
        @include('admin.hero-slides.partials.form')
    </form>
@endsection
