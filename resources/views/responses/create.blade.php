@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="border-bottom">
            <h1>{{ $form->title }}</h1>
            <p class="text-muted">{{ $form->description }}</p>
        </div>

        <div class="list-group">
            <form action="{{ route('response.store', $form->id) }}" method="post" enctype="multipart/form-data">
                @csrf
                @foreach($form->questions as $index => $question)
                    <div class="list-group-item">
                        <h5>{{ $question->text }}<small><span class="badge badge-danger mx-2">{{ $question->required ? "required" : '' }}</span></small></h5>
                        <p class="text-muted">{{ $question->description }}</p>
                        <!-- show input for each question type -->
                        @switch($question->type)
                            @case('short_answer')
                            <input type="text" name="questions[{{$question->id}}][answer]" class="form-control">
                            <input type="hidden" name="questions[{{$question->id}}][required]" value="{{ $question->required }}">
                            @break
                            @case('date')
                            <input type="date" name="questions[{{$question->id}}][answer]" class="form-control">
                            <input type="hidden" name="questions[{{$question->id}}][required]" value="{{ $question->required }}">
                            @break
                            @case('multiple_choice')
                                @foreach($question->answers as $index => $answer)
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="questions[{{$question->id}}][answer]" id="question-{{ $question->id }}-answers-{{$index}}" value="{{ $answer->text }}">
                                    <label class="form-check-label" for="question-{{ $question->id }}-answers-{{$index}}">
                                        {{ $answer->text }}
                                    </label>
                                </div>
                                @endforeach
                            <input type="hidden" name="questions[{{$question->id}}][required]" value="{{ $question->required }}">
                            @break
                            @case('checkbox')
                            @foreach($question->answers as $index => $answer)
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="questions[{{$question->id}}][answer][]" id="question-{{ $question->id }}-answers-{{$index}}" value="{{ $answer->text }}">
                                    <label class="form-check-label" for="question-{{ $question->id }}-answers-{{$index}}">
                                        {{ $answer->text }}
                                    </label>
                                </div>
                            @endforeach
                            <input type="hidden" name="questions[{{$question->id}}][required]" value="{{ $question->required }}">
                            @break
                            @case('long_answer')
                                <textarea name="questions[{{$question->id}}][answer]" cols="30" rows="4" class="form-control"></textarea>
                                <input type="hidden" name="questions[{{$question->id}}][required]" value="{{ $question->required }}">
                            @break
                            @case('file')
                                <input type="file" name="questions[{{$question->id}}][answer]" class="form-control">
                                <input type="hidden" name="questions[{{$question->id}}][required]" value="{{ $question->required }}">
                            @break
                        @endswitch
                    </div>
                @endforeach
                <div class="form-group row my-4 justify-content-center">
                    <div class="col-md-5">
                        <button class="btn btn-primary btn-lg btn-block" type="submit">Submit</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@stop
