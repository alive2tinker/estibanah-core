@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between border-bottom">
        <h1>My Forms</h1>
        <a href="{{ route('forms.create') }}" class="btn btn-link">+</a>
    </div>
    <div class="list-group">
        @forelse($forms as $form)
            <div class="list-group-item">
                <div class="d-flex w-100 justify-content-between">
                    <a href="{{ route('forms.show', $form->id) }}"><h5 class="mb-1">{{ $form->title }}</h5></a>
                    <small class="text-muted">{{ $form->created_at->diffForHumans() }}</small>
                </div>
                <p class="mb-1">{{ \Illuminate\Support\Str::limit($form->description, 100, '...') }}</p>
                <a href="{{ route('forms.edit', $form->id) }}" class="btn btn-secondary">Edit</a>
                <button type="button" class="btn btn-danger form-delete-button" data-id="{{ $form->id }}">Delete</button>
                <form action="{{ route('forms.destroy', $form->id) }}" id="form-{{$form->id}}" method="post">
                    @method('DELETE')
                    @csrf
                </form>
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
        $(document).ready(function(){
            $(".form-delete-button").click(function(){
                $("#form-"+$(this).attr('data-id')).submit();
            })
        })
    </script>
@endsection
