@extends('layouts.admin')

@section('title', 'Edit Category')
@section('heading', $category->name)

@section('content')
    <form method="POST" action="{{ route('admin.blog-categories.update', $category) }}">
        @csrf
        @method('PUT')
        @include('admin.categories.partials.form', ['routeBase' => 'admin.blog-categories'])
    </form>
@endsection
