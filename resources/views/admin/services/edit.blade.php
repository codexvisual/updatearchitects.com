@extends('layouts.admin')

@section('title', 'Edit Service')
@section('heading', $service->name)

@section('actions')
    <a href="{{ route('services.show', $service->slug) }}" target="_blank" rel="noopener" class="btn-ghost btn-sm">View</a>
@endsection

@section('content')
    <form method="POST" action="{{ route('admin.services.update', $service) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        @include('admin.services.partials.form')
    </form>
@endsection
