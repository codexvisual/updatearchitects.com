@extends('layouts.admin')

@section('title', 'New Role')
@section('heading', 'New Role')

@section('content')
    <form method="POST" action="{{ route('admin.roles.store') }}">
        @csrf
        @include('admin.roles.partials.form')
    </form>
@endsection
