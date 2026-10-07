@extends('layouts.admin')

@section('title', 'New Menu')
@section('heading', 'New Menu')

@section('content')
    <form method="POST" action="{{ route('admin.menus.store') }}">
        @csrf
        @include('admin.menus.partials.form')
    </form>
@endsection
