@extends('layouts.admin')

@section('title', 'New Tag')
@section('heading', 'New Tag')

@section('content')
    <form method="POST" action="{{ route('admin.tags.store') }}">
        @csrf
        @include('admin.tags.partials.form')
    </form>
@endsection
