@extends('layouts.admin')

@section('title', 'New Post')
@section('heading', 'New Blog Post')

@section('content')
    <form method="POST" action="{{ route('admin.blog.store') }}" enctype="multipart/form-data">
        @csrf
        @include('admin.blog.partials.form')
    </form>
@endsection
