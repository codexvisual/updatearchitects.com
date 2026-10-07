@extends('layouts.admin')

@section('title', 'Edit Tag')
@section('heading', $tag->name)

@section('content')
    <form method="POST" action="{{ route('admin.tags.update', $tag) }}">
        @csrf
        @method('PUT')
        @include('admin.tags.partials.form')
    </form>
@endsection
