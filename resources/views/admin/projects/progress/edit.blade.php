@extends('layouts.admin')

@section('title', 'Edit Progress Stage')
@section('heading', $progress->title)

@section('actions')
    <a href="{{ route('admin.projects.progress.index', $progress->project_id) }}" class="btn-ghost btn-sm">All stages</a>
@endsection

@section('content')
    <form method="POST" action="{{ route('admin.progress.update', $progress) }}">
        @csrf
        @method('PUT')
        @include('admin.projects.progress.partials.form')
    </form>
@endsection
