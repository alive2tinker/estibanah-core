@extends('layouts.frontend')
@section('content')
    <div class="page-header header-filter" style="background-image: url('../assets/img/bg7.jpg'); background-size: cover; background-position: top center;">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 col-md-6 col-sm-8 ml-auto mr-auto">
                    <form method="POST" action="{{ route('login') }}">
                        @csrf
                        <div class="card card-login card-hidden">
                            <div class="card-header card-header-primary text-center">
                                <h4 class="card-title">{{ __('Login') }}</h4>
                                <div class="social-line">
                                    <a href="#pablo" class="btn btn-just-icon btn-link btn-white">
                                        <i class="fa fa-facebook-square"></i>
                                    </a>
                                    <a href="#pablo" class="btn btn-just-icon btn-link btn-white">
                                        <i class="fa fa-twitter"></i>
                                    </a>
                                    <a href="#pablo" class="btn btn-just-icon btn-link btn-white">
                                        <i class="fa fa-google-plus"></i>
                                    </a>
                                </div>
                            </div>
                            <div class="card-body ">
                                <span class="bmd-form-group @error('email') has-danger @enderror">
                                    @error('email')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                  <div class="input-group">
                    <div class="input-group-prepend">
                      <span class="input-group-text">
                        <i class="material-icons @error('email') text-danger @enderror">email</i>
                      </span>
                    </div>
                    <input type="email" name="email" class="form-control @error('email') has-danger text-danger @enderror" value="{{ old('email') }}" placeholder="{{ __('E-Mail Address') }}">
                  </div>
                </span>
                                <span class="bmd-form-group @error('password') has-danger text-danger @endif">
                                    @error('password')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                  <div class="input-group">
                    <div class="input-group-prepend">
                      <span class="input-group-text">
                        <i class="material-icons @error('password') has-danger text-danger @endif">lock_outline</i>
                      </span>
                    </div>
                    <input type="password" class="form-control @error('password') has-danger text-danger @endif" name="password" placeholder="{{ __('Password') }}">
                  </div>
                </span>
                            </div>
                            <div class="card-footer justify-content-center">
                                <button type="submit" class="btn btn-rose btn-link btn-lg">{{__('Login')}}</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
