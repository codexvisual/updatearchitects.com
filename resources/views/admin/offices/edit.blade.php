@extends('layouts.admin')

@section('title', 'Edit Office')
@section('heading', $office->name)

@section('actions')
    <a href="{{ route('admin.offices.show', $office) }}" class="btn-ghost btn-sm">View</a>
@endsection

@section('content')
    <form method="POST" action="{{ route('admin.offices.update', $office) }}">
        @csrf
        @method('PUT')
        @include('admin.offices.partials.form')
    </form>
@endsection
