@extends('layouts.admin')

@section('title', 'Edit Menu')
@section('heading', $menu->name)

@section('content')
    <form method="POST" action="{{ route('admin.menus.update', $menu) }}">
        @csrf
        @method('PUT')
        @include('admin.menus.partials.form')
    </form>
@endsection
