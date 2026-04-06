@extends('admin.layouts.master')

@section('title', 'Add New Cause')

@section('content')
<div class="container">
    <h1 class="mb-4">Add New Cause</h1>
    @include('admin.causes.form', ['cause' => null])
</div>
@endsection
