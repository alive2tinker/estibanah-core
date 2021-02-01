<?php

namespace App\Http\Controllers;

use App\Form;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function __invoke(Request $request){
        $forms = Form::where('title',$request->input('key'))->orderby('created_at')->paginate(10);
        return view('search_results', [
            'forms' => $forms
        ]);
    }
}
