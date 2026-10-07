@extends('layouts.admin')

@section('title', 'New Category')
@section('heading', 'New Blog Category')

@section('content')
    <form method="POST" action="{{ route('admin.blog-categories.store') }}">
        @csrf
        @include('admin.categories.partials.form', ['routeBase' => 'admin.blog-categories'])
    </form>
@endsection
