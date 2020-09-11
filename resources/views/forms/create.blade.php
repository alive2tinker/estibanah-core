@extends('layouts.app')
@section('content')
    <form-component user="{{ \Illuminate\Support\Facades\Auth::user()->id }}"></form-component>
@endsection
