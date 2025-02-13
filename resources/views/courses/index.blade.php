@extends('layouts.app')

@section('title', 'Courses')

@section('content')
    <div class="container">
        <h3 class="mb-4">Available Courses</h3>

        <div class="row">
        @foreach ($courses as $course)
                <div class="col-md-4 d-flex align-items-stretch">  <!-- Ensures equal height columns -->
                    <div class="card h-100"> <!-- Ensures equal height cards -->
                        <div class="card-body d-flex flex-column"> <!-- Enables flexbox for consistent spacing -->
                            <h5 class="card-title">{{ $course->title }}</h5>
                            <p class="card-text flex-grow-1">{{ $course->description }}</p> <!-- Pushes button to bottom -->

                            @if ($subscription)
                                @if ($userDownloads < $subscription->course_limit)
                                    <a href="{{ route('courses.download', $course->id) }}" class="btn btn-success mt-auto">Download</a>
                                @else
                                    <button class="btn btn-danger mt-auto" disabled>You have reached your limit</button>
                                @endif
                            @else
                                <p class="text-danger">No subscription found. Please subscribe to a plan.</p>
                            @endif

                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection
