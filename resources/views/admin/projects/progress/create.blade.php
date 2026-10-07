@extends('layouts.admin')

@section('title', 'Add Progress Stage')
@section('heading', 'Add Progress Stage')

@section('content')
    <form method="POST" action="{{ route('admin.projects.progress.store', $project) }}">
        @csrf
        @include('admin.projects.progress.partials.form')
    </form>
@endsection
