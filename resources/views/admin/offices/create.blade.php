@extends('layouts.admin')

@section('title', 'New Office')
@section('heading', 'New Office')

@section('content')
    <form method="POST" action="{{ route('admin.offices.store') }}">
        @csrf
        @include('admin.offices.partials.form')
    </form>
@endsection
