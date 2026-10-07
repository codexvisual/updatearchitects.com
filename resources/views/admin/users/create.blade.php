@extends('layouts.admin')

@section('title', 'New User')
@section('heading', 'New User')

@section('content')
    <form method="POST" action="{{ route('admin.users.store') }}">
        @csrf
        @include('admin.users.partials.form')
    </form>
@endsection
