@extends('layouts.admin')

@section('title', 'Edit Message')
@section('heading', 'Message from '.$message->name)

@section('actions')
    <a href="{{ route('admin.contacts.show', $message) }}" class="btn-ghost btn-sm">View</a>
@endsection

@section('content')
    <form method="POST" action="{{ route('admin.contacts.update', $message) }}">
        @csrf
        @method('PUT')
        @include('admin.contacts.partials.form')
    </form>
@endsection
