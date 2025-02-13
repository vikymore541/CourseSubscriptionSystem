@extends('layouts.app')

@section('content')
<div class="container d-flex justify-content-center">
    <div class="card shadow-lg p-4" style="width: 50%;">
        <h2 class="text-center mb-4">Add New Course</h2>
        <form action="{{ route('courses.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group mb-3">
                <label class="fw-bold">Title</label>
                <input type="text" name="title" class="form-control" placeholder="Enter course title" required>
            </div>
            <div class="form-group mb-3">
                <label class="fw-bold">Description</label>
                <textarea name="description" class="form-control" rows="3" placeholder="Enter course description" required></textarea>
            </div>
            <div class="form-group mb-3">
                <label class="fw-bold">Upload File</label>
                <input type="file" name="file" class="form-control" required>
            </div>
            <div class="d-flex justify-content-center">
                <button type="submit" class="btn btn-success px-4 py-2">Save</button>
            </div>
        </form>
    </div>
</div>
@endsection
