@extends('admin.layouts.master')


@section('content')
<div class="container">
    <h1 class="mb-4">Edit Cause</h1>
   @include('admin.causes.form', ['cause'=>$cause])
</div>
@endsection
