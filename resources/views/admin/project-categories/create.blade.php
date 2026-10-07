@extends('layouts.admin')

@section('title', 'New Category')
@section('heading', 'New Category')

@section('content')
    <form method="POST" action="{{ route('admin.project-categories.store') }}">
        @csrf
        @include('admin.categories.partials.form', ['routeBase' => 'admin.project-categories'])
    </form>
@endsection
