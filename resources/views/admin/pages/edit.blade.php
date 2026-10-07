@extends('layouts.admin')

@section('title', 'Edit Page')
@section('heading', $page->title)

@section('actions')
    @if($page->status === 'published')
        <a href="{{ route('page.show', $page->slug) }}" target="_blank" rel="noopener" class="btn-ghost btn-sm">View</a>
    @endif
@endsection

@section('content')
    <form method="POST" action="{{ route('admin.pages.update', $page) }}">
        @csrf
        @method('PUT')
        @include('admin.pages.partials.form')
    </form>
@endsection
