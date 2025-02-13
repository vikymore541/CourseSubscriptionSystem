@extends('layouts.app')

@section('title', 'Choose Subscription Plan')

@section('content')
<div class="container">
    <h3>Select a Subscription Plan</h3>

    
    <div class="row">
        @foreach ($plans as $plan)
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">{{ $plan->name }}</h5>
                        <p class="card-text">Course Limit: {{ $plan->course_limit }}</p>

                        <form action="{{ route('subscriptions.subscribe', $plan->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-primary">Subscribe</button>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection
