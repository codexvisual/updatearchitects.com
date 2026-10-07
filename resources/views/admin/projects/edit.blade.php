@extends('layouts.admin')

@section('title', 'Edit Project')
@section('heading', $project->title)

@section('actions')
    <a href="{{ route('projects.show', $project->slug) }}" target="_blank" rel="noopener" class="btn-ghost btn-sm">View</a>
    <a href="{{ route('admin.projects.progress.index', $project) }}" class="btn-secondary btn-sm">Progress</a>
@endsection

@section('content')
    <form method="POST" action="{{ route('admin.projects.update', $project) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        @include('admin.projects.partials.form')
    </form>
@endsection
