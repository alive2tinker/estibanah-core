<?php

namespace App\Http\Controllers;

use App\Answer;
use App\Form;
use App\Http\Requests\StoreForm;
use App\Http\Resources\FormResource;
use App\Question;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class FormController extends Controller
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
    public function create()
    {
        return view('forms.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreForm $request)
    {
        $form = Auth::user()->forms()->create([
            'title' => $request->input('title'),
            'description' => $request->input('description')
        ]);

        foreach($request->input('questions') as $nuQuestion){
            $question = $form->questions()->create([
                'text' => $nuQuestion['text'],
                'description' => $nuQuestion['description'],
                'required' => $nuQuestion['required'],
                'type' => $nuQuestion['type'],
                'conditions' => $this->generateIfStatement($nuQuestion['conditions'])
            ]);

            if($question->type === 'checkbox' || $question->type === 'multiple_choice'){
                foreach ($nuQuestion['answers'] as $answer){
                    $question->answers()->create([
                        'text' => $answer['text']
                    ]);
                }
            }
        }

        return response()->json($form, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Form  $form
     * @return \Illuminate\Http\Response
     */
    public function show(Form $form)
    {
        return view('forms.show',[
            'form' => $form
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Form  $form
     * @return \Illuminate\Http\Response
     */
    public function edit(Form $form)
    {
        return view('forms.edit',[
            'form' => $form
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Form  $form
     * @return \Illuminate\Http\Response
     */
    public function update(StoreForm $request, Form $form)
    {
        Log::info($request);
        $form->update([
            'title' => $request->input('title'),
            'description' => $request->input('description')
        ]);

        foreach($request->input('questions') as $nuQuestion){
            if(isset($nuQuestion['id'])){
                $question = Question::findOrFail($nuQuestion['id']);
                $question->update([
                    'text' => $nuQuestion['text'],
                    'type' => $nuQuestion['type'],
                    'description' => $nuQuestion['description'],
                    'required' => $nuQuestion['required'],
                ]);
            } else
                $question = $form->questions()->create([
                    'text' => $nuQuestion['text'],
                    'type' => $nuQuestion['type'],
                    'description' => $nuQuestion['description'],
                    'required' => $nuQuestion['required'],
                ]);


            if($question->type === 'checkbox' || $question->type === 'multiple_choice'){
                foreach ($nuQuestion['answers'] as $nuAnswer){
                    if(isset($nuAnswer['id'])){
                        $answer = Answer::findOrFail($nuAnswer['id']);
                            $answer->update([
                                'text' => $nuAnswer['text']
                            ]);
                    } else
                        $question->answers()->create([
                            'text' => $nuAnswer['text']
                        ]);
                }
            }
        }

        return response()->json($form, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Form  $form
     * @return \Illuminate\Http\Response
     */
    public function destroy(Form $form)
    {
        $form->delete();

        return redirect()->back()
            ->with('success', "deleted successfully");
    }

    public function resource(Form $form)
    {
        return new FormResource($form);
    }

    public function generateIfStatement($conditions)
    {
        Log::info($conditions);
        $conditionalStatement = "";

        foreach($conditions as $index => $condition)
        {
            switch ($condition['operation']){
                case "notEmpty" :
                    $newCondition = $index === 0 ? "this.isNotEmpty('" . $condition['question']. "')"
                    : " && this.isNotEmpty('" . $condition['question'] . "')";
                    $conditionalStatement = $conditionalStatement . $newCondition;
                    break;
                case "answerEquals":
                    $newCondition = $index === 0 ? "this.answerEquals('" . $condition['question'] . "',"
                        . "'" . $condition['question']['value'] . "')"
                        : " && this.answerEquals(" . $condition['question'] . ","
                        . "'" . $condition['question']['value'] . "')";
                    $conditionalStatement = $conditionalStatement . $newCondition;
                    break;
                case "!answerEquals":
                    $newCondition = $index === 0 ? "this.doesNotAnswerEquals('" . $condition['question'] . "',"
                        . "'" . $condition['question']['value'] . "')"
                        : " && this.doesNotAnswerEquals(" . $condition['question'] . ","
                        . "'" . $condition['question']['value'] . "')";
                    $conditionalStatement = $conditionalStatement . $newCondition;
                    break;
            }

            return $conditionalStatement;
        }
    }
}
