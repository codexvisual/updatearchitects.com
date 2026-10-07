@extends('layouts.admin')

@section('title', 'Edit Category')
@section('heading', $category->name)

@section('content')
    <form method="POST" action="{{ route('admin.service-categories.update', $category) }}">
        @csrf
        @method('PUT')
        @include('admin.categories.partials.form', ['routeBase' => 'admin.service-categories'])
    </form>
@endsection
