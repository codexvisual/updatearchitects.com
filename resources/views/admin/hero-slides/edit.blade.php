@extends('layouts.admin')

@section('title', 'Edit Slide')
@section('heading', 'Edit Slide')

@section('content')
    <form method="POST" action="{{ route('admin.hero-slides.update', $slide) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        @include('admin.hero-slides.partials.form')
    </form>
@endsection
