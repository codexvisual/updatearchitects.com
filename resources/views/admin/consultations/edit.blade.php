@extends('layouts.admin')

@section('title', 'Edit Lead')
@section('heading', $lead->name)

@section('actions')
    <a href="{{ route('admin.consultations.show', $lead) }}" class="btn-ghost btn-sm">View</a>
@endsection

@section('content')
    <form method="POST" action="{{ route('admin.consultations.update', $lead) }}">
        @csrf
        @method('PUT')
        @include('admin.consultations.partials.form')
    </form>
@endsection
