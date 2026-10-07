@extends('layouts.admin')

@section('title', 'Edit Role')
@section('heading', $role->name)

@section('content')
    <form method="POST" action="{{ route('admin.roles.update', $role) }}">
        @csrf
        @method('PUT')
        @include('admin.roles.partials.form')
    </form>
@endsection
