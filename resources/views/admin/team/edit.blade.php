@extends('layouts.admin')

@section('title', 'Edit Team Member')
@section('heading', $member->name)

@section('actions')
    @if($member->exists)
        <a href="{{ route('team.show', ['member' => $member->slug]) }}" target="_blank" rel="noopener" class="btn-ghost btn-sm">View</a>
    @endif
@endsection

@section('content')
    <form method="POST" action="{{ route('admin.team.update', $member) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        @include('admin.team.partials.form')
    </form>
@endsection
