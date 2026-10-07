@extends('layouts.admin')

@section('title', 'New Message')
@section('heading', 'New Contact Message')

@section('content')
    <form method="POST" action="{{ route('admin.contacts.store') }}">
        @csrf
        @include('admin.contacts.partials.form')
    </form>
@endsection
