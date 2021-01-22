@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                        <h3 class="card-title">{{ $question->text }}</h3>
                        <p class="text-muted">{{ $question->description }}</p>
                        <div class="form-check">
                            @if($question->required)
                                <input class="form-check-input" type="checkbox" checked id="defaultCheck2" disabled>
                            @else
                                <input class="form-check-input" type="checkbox" id="defaultCheck2" disabled>
                            @endif
                            <label class="form-check-label" for="defaultCheck2">
                                required
                            </label>
                        </div>
                    </div>
                </div>
                <ul class="list-unstyled py-4">
                    @foreach($responses as $index => $response)
                    <li class="media">
                        <h3 class="mr-3">{{ $index + 1}}</h3>
                        <div class="media-body card card-body">
                            <div class="d-flex justify-content-between">
                                <h5 class="mt-0 mb-1">{{ $response->formResponse->user->name }}</h5>
                                {{ $response->created_at->format('m-d-y') }}
                            </div>
                            @switch($question->type)
                                @case('checkbox')
                                @php
                                    $checkboxes = explode(",", $response->value);
                                @endphp
                                    <ol>
                                        @foreach($checkboxes as $checkbox)
                                            <li>{{ $checkbox }}</li>
                                        @endforeach
                                    </ol>
                                @break
                                @case('file')
                                    <a href="{{ route('downloadFile', $response->value) }}">Download File</a>
                                @break
                                @case('long_answer')
                                    <p>{!! $response->value !!}</p>
                                @break
                                @default
                                    {{ $response->value }}
                                @break
                            @endswitch
                        </div>
                    </li>
                    @endforeach
                </ul>
                <div class="d-flex justify-content-center">{{ $responses->links() }}</div>
            </div>
        </div>
    </div>
@endsection
