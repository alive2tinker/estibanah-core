@extends('layouts.frontend')
@section('content')
    <div class="page-header header-filter" data-parallax="true" style="background: linear-gradient(45deg, #0b75c9, #0C9A9A) !important">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h1 class="title">{{ __('Your best form starts with us') }}</h1>
                    <h4>{{ __('You must have created countless forms to gather data, but your greatest one is yet to come with our software. starting from integration with popular data services such as microsoft power bi or excel, all the way to data encryption, we have got you covered. Create your form now...') }}</h4>
                    <br>
                    <a href="{{ route('forms.create') }}" target="_blank" class="btn btn-danger btn-raised btn-lg">
                        <i class="fa fa-edit"></i> {{ __('create your form now') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="main main-raised">
        <div class="container">
            <div class="section text-center">
                <div class="row">
                    <div class="col-md-8 ml-auto mr-auto">
                        <h2 class="title">{{ __('Features') }}</h2>
                        <h5 class="description">{{ __('Here, we will let the product talk about itself.') }}</h5>
                    </div>
                </div>
                <div class="features">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="info">
                                <div class="icon icon-info">
                                    <i class="material-icons">public</i>
                                </div>
                                <h4 class="info-title">{{ __('Hosted Locally') }}</h4>
                                <p>{{ __('To enforce one more layer of security over your data, and prevent outsider access to your data') }}</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="info">
                                <img src="{{ asset('images/special_icons/ux.svg') }}" width="64" height="64" alt="">
                                <h4 class="info-title">{{ __('User Experience') }}</h4>
                                <p>{{ __('We simplifed form creation and data collection. Almost everyhting is with a press of a button') }}</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="info">
                                <div class="icon icon-danger">
                                    <i class="material-icons">fingerprint</i>
                                </div>
                                <h4 class="info-title">{{ __('Encryption') }}</h4>
                                <p>{{ __('Site is fully encrypted! no one can access your data except you. Not even us') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="pricing-2" id="pricing-2">
                <div class="container">
                    <div class="row">
                        <div class="col-md-6 ml-auto mr-auto text-center">
                            <h2 class="title">{{ __('Pick the best plan for you') }}</h2>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="card card-pricing card-plain">
                                <div class="card-body ">
                                    <h6 class="card-category">{{ __('Free') }}</h6>
                                    <h1 class="card-title">
                                        0
                                        <br><small>{{__('Saudi Riyal')}}/{{__('mo')}}</small>
                                    </h1>
                                    <ul>
                                        <li>
                                            <b>5</b> {{ __('Forms') }}</li>
                                        <li>
                                            <b>100</b> {{ __('Responses') }}</li>
                                        <li>
                                            {{ __('Contains Ads') }}</li>
                                        <li>{!! __('Export To Excel &reg; Only') !!}</li>
                                    </ul>
                                </div>
                                <div class="card-footer justify-content-center">
                                    <a href="{{ route('forms.create') }}" class="btn btn-rose btn-round">
                                        {{ __('Get Started') }}
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card card-pricing card-background card-raised" style="background: linear-gradient(45deg, #0b75c9, #0C9A9A) !important">
                                <div class="card-body">
                                    <h6 class="card-category text-info">{{ __('Premium') }}</h6>
                                    <h1 class="card-title">
                                        89
                                        <br><small>{{__('Saudi Riyal')}}/{{__('mo')}}</small>
                                    </h1>
                                    <ul>
                                        <li>
                                            <b>{{ __('Unlimited') }}</b> {{ __('Forms') }}/{{ __('Responses') }}</li>
                                        <li><b>{{ __('No Ads') }}</b></li>
                                        <li><b>{{ __('Analytical Tools') }}</b></li>
                                        <li><b>{{ __('Full Result Export') }}</b></li>
                                    </ul>
                                </div>
                                <div class="card-footer justify-content-center">
                                    <a href="{{ route('forms.create') }}" class="btn btn-white btn-round">
                                        {{ __('Get Started') }}
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card card-pricing card-plain">
                                <div class="card-body ">
                                    <h6 class="card-category">{{ __('On Premises') }}</h6>
                                    <h1 class="card-title">
                                        2500
                                        <br><small>{{__('Saudi Riyal')}}/{{__('Yearly')}}</small>
                                    </h1>
                                    <ul>
                                        <li><b>{{ __('Unlimited Surveys / Users') }}</b></li>
                                        <li><b>{{ __('Analytical Tools') }}</b></li>
                                        <li><b>{{ __('No Ads') }}</b></li>
                                        <li><b>{{ __('Email and Whatsapp Support *') }}</b></li>
                                        <li><b>{{ __('Ability to develop custom solutions **') }}</b></li>
                                    </ul>
                                </div>
                                <div class="card-footer justify-content-center">
                                    <a href="{{ route('forms.create') }}" class="btn btn-rose btn-round">
                                        {{ __('Get Started') }}
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <ul class="text-right list-unstyled text-muted">
                        <li>{{ __('* We will respond within 24 hours, and we will be available 24/7 (yes you can asks on weekends). Support is not included in the price above') }}</li>
                        <li>{{ __('* basic support is 210 SAR/mo. An in person support can be provided for 1500 SAR/Meeting')}}</li>
                        <li>{{ __('** We can develop custom solutions/integrations for a fee. Contact us and you you will not be disappointed') }}</li>
                    </ul>
                </div>
            </div>
            <div class="section section-contacts">
                <div class="row">
                    <div class="col-md-8 ml-auto mr-auto">
                        <h2 class="text-center title">{{ __('Work with us') }}</h2>
                        <h4 class="text-center description">{{ __('All your questions will be answered') }}</h4>
                        <form class="contact-form">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="bmd-label-floating">{{ __('Name') }}</label>
                                        <input type="email" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="bmd-label-floating">{{ __('E-Mail Address') }}</label>
                                        <input type="email" class="form-control">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="exampleMessage" class="bmd-label-floating">{{ __('Message') }}</label>
                                <textarea type="email" class="form-control" rows="4" id="exampleMessage"></textarea>
                            </div>
                            <div class="row">
                                <div class="col-md-4 ml-auto mr-auto text-center">
                                    <button class="btn btn-primary btn-raised">
                                        {{ __('Send Message') }}
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @section('footer')
        <footer class="footer footer-default">
            <div class="container">
                <div class="copyright">
                    &copy;
                    <script>
                        document.write(new Date().getFullYear())
                    </script>, {{__('Made with')}} <i class="material-icons">favorite</i> {{ __('by') }}
                    <a href="/" target="_blank">{{ __(config('app.name', 'Laravel')) }}</a> {{ __('for a better forms') }}.
                </div>
            </div>
        </footer>
    @endsection
@endsection
