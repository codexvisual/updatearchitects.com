@extends('layouts.admin')

@section('title', 'New Category')
@section('heading', 'New Category')

@section('content')
    <form method="POST" action="{{ route('admin.service-categories.store') }}">
        @csrf
        @include('admin.categories.partials.form', ['routeBase' => 'admin.service-categories'])
    </form>
@endsection
