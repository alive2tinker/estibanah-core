@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="border-bottom">
            <div class="d-flex justify-content-between">
                <h1>{{ $form->title }}</h1>
                <button class="btn btn-link" data-toggle="modal" data-target="#invitation-modal"><i
                        class="fa fa-share fa-3x"></i></button>
            </div>
            <p class="text-muted">{{ $form->description }}</p>
        </div>
        <div class="modal" tabindex="-1" id="invitation-modal">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Invitation Modal</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <email-component link="{{ request()->url() . "/answer" }}"></email-component>
                    </div>
                </div>
            </div>
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
@section('js')
    <script>
        function copyText(){
            var copyText = document.getElementById("form-answer-link");

            /* Select the text field */
            copyText.select();
            copyText.setSelectionRange(0, 99999); /* For mobile devices */

            /* Copy the text inside the text field */
            document.execCommand("copy");
        }
        $(document).ready(function(){
            $("#invitation-submit").click(function(){
                $("#invitation-form").submit();
            })
        })
    </script>
@endsection
