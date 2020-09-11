<?php

use Illuminate\Database\Seeder;

class FormSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\User::create(['name' => "Abdulmalik Alsufayran", 'email' => "alsufiran@gmail.com", 'password' => bcrypt('Sufayr@n2020')]);

//        factory(\App\Form::class, 3)->create()->each(function ($form){
//            factory(\App\FormResponse::class, 10)->create(['form_id' => $form->id, 'user_id' => 1]);
//            factory(\App\Question::class, 6)->create(['form_id' => $form->id])->each(function ($question) {
//                if($question->type === 'multiple_choice' || $question->type === 'checkbox')
//                    factory(\App\Answer::class, rand(3, 6))->create(['question_id' => $question]);
//                factory(\App\Response::class, rand(5,10))->create(['question_id' => $question->id, 'form_response_id' => rand(1,10)]);
//            });
//        });
    }
}
