@extends('layouts.admin')

@section('title', 'Edit Category')
@section('heading', $category->name)

@section('content')
    <form method="POST" action="{{ route('admin.project-categories.update', $category) }}">
        @csrf
        @method('PUT')
        @include('admin.categories.partials.form', ['routeBase' => 'admin.project-categories'])
    </form>
@endsection
