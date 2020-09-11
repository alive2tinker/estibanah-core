<?php

namespace App\Http\Controllers;


use App\Form;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        //TODO: cache user forms
        $forms = Cache::get('user-' . Auth::user()->id .'-forms-list');
        if($forms === NULL)
            $forms = Auth::user()->forms()->orderby('created_at','desc')->paginate();

        return view('home', [
            'forms' => $forms
        ]);
    }
}
