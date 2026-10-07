@extends('layouts.admin')

@section('title', 'New Team Member')
@section('heading', 'New Team Member')

@section('content')
    <form method="POST" action="{{ route('admin.team.store') }}" enctype="multipart/form-data">
        @csrf
        @include('admin.team.partials.form')
    </form>
@endsection
