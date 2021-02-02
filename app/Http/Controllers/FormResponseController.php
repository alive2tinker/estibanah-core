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
        $this->middleware('auth', ['except' => ['create']]);
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

        foreach($request->input('responses') as $response)
        {
            try{
                if($response['question']['type'] === 'file')
                {
                    foreach($response['value'] as $filename)
                    {
                        foreach($request->allFiles() as $file){
                            if($file[0]->getClientOriginalName() === $filename){
                                $uploadName = "file_responses/";
                                $path = Storage::disk('s3')->put($uploadName, $file[0]);

                                $upload = $formResponse->uploads()->create([
                                    'link' => $path,
                                    'name' => $file[0]->getClientOriginalName(),
                                    'type' => $file[0]->getClientMimeType()
                                ]);
                                Response::create([
                                    'question_id' => $response['question']['id'],
                                    'form_response_id' => $formResponse->id,
                                    'value' => $upload->name
                                ]);
                            }
                        }
                    }
                }else if($response['question']['type'] !== 'file'){
                    Response::create([
                        'question_id' => $response['question']['id'],
                        'form_response_id' => $formResponse->id,
                        'value' => $response['question']['type'] === 'checkbox' ? implode(",", $response['value'])
                            : $response['value']
                    ]);
                }
            }catch (\Exception $e){
                return response()->json(['message' => $e->getMessage(), 'line' => $e->getLine()]);
            }
        }

        return \response()->json("created successfully", 201);
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
