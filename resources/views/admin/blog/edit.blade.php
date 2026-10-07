@extends('layouts.admin')

@section('title', 'Edit Post')
@section('heading', $post->title)

@section('actions')
    <a href="{{ route('blog.show', $post->slug) }}" target="_blank" rel="noopener" class="btn-ghost btn-sm">View</a>
@endsection

@section('content')
    <form method="POST" action="{{ route('admin.blog.update', $post) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        @include('admin.blog.partials.form')
    </form>
@endsection
