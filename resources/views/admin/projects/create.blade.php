@extends('layouts.admin')

@section('title', 'New Project')
@section('heading', 'New Project')

@section('content')
    <form method="POST" action="{{ route('admin.projects.store') }}" enctype="multipart/form-data">
        @csrf
        @include('admin.projects.partials.form')
    </form>
@endsection
