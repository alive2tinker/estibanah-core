<?php

namespace App\Http\Controllers;

use App\Form;
use App\FormResponse;
use App\Http\Requests\StoreResponse;
use App\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class FormResponseController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Form $form)
    {
        return view('responses.create', [
            'form' => $form
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreResponse $request, Form $form)
    {
        $formResponse = $form->formResponses()->create([
            'user_id' => Auth::user()->id
        ]);

        $answers = $request->input('questions');
        foreach($form->questions as $index => $question)
        {
            try{

                if($question->type === 'file')
                {
                    $uploadedFile = $request->file('questions.' . ($index+1) . '.answer');
                    $uploadName = "form_response_". $formResponse->id . "_question_" . $question->id . "_upload."
                    .$uploadedFile->getClientOriginalExtension();
                    $path = Storage::put($uploadName, $uploadedFile);

                    $upload = $formResponse->uploads()->create([
                        'link' => $path,
                        'name' => $uploadedFile->getClientOriginalName(),
                        'type' => $uploadedFile->getClientMimeType()
                    ]);
                    $question->responses()->create([
                        'form_response_id' => $formResponse->id,
                        'value' => $upload->link
                    ]);
                }else{
                    $question->responses()->create([
                        'form_response_id' => $formResponse->id,
                        'value' => $question->type === 'checkbox' ? implode(",", $answers[$index+1]['answer'])
                            : $answers[$index+1]['answer']
                    ]);
                }
            }catch(\Exception $e){
                dd([
                    'question' => $question,
                    'error' => $e->getMessage(),
                    'answer' => $answers[$index]['answer'],
                    'index' => $index,

                ]);
            }
        }
        return redirect()->route('thankyou');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\FormResponse  $formResponse
     * @return \Illuminate\Http\Response
     */
    public function show(FormResponse $formResponse)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\FormResponse  $formResponse
     * @return \Illuminate\Http\Response
     */
    public function edit(FormResponse $formResponse)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\FormResponse  $formResponse
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, FormResponse $formResponse)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\FormResponse  $formResponse
     * @return \Illuminate\Http\Response
     */
    public function destroy(FormResponse $formResponse)
    {
        //
    }
}
