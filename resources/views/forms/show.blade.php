@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="border-bottom">
            <h1>{{ $form->title }}</h1>
            <p class="text-muted">{{ $form->description }}</p>
        </div>
        <div class="list-group my-3">
            @forelse($form->questions as $question)
            <div class="list-group-item my-2">
                <a href="{{ route('questions.show', $question->id) }}"><h5 class="mb-1">{{ $question->text }}</h5></a>
                <div class="row">
                    <div class="col-md-10">
                        <p>{{ $question->type }}</p>
                        <p>{{ $question->description }}</p>
                        @if($question->type === 'multiple_choice' || $question->type === 'checkbox')
                            <ul>
                                @foreach($question->answers as $answer)
                                    <li>{{ $answer->text }}</li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                    <div class="col-md-2">
                        <h1 class="text-center">{{ count($question->responses) }}</h1>
                    </div>
                </div>
            </div>
            @empty
            <div class="list-group-item">
                <h4 class="text-center">No Data</h4>
            </div>
            @endforelse
        </div>
    </div>
@endsection
