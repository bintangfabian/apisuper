<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreQuestion;
use App\Http\Requests\UpdateQuestion;
use App\Models\EssayAnswer;
use App\Models\Option;
use App\Models\Question;
use App\StatusCode;
use Illuminate\Http\Request;

class QuestionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = $request->user();
        return Question::where('user_id', $user->id)->with(['essayAnswer', 'options', 'subject', 'grade'])->paginate(15);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreQuestion $request)
    {
        $question = new Question($request->validated());
        $question->user_id = $request->user()->id;
        $question->save();
        if ($request->type === 'Multiple Choice') {
            $options = collect([]);
            collect($request->options)->each(function ($item, $key) use ($options, $question) {
                $options->push(collect($item)->put('question_id',  $question->id));
            });
            Option::insert($options->toArray());
            return response()->successWithMessage('Berhasil Menambahkan Soal!');
        }
        $essayAnswer = new EssayAnswer($request->validated());
        $essayAnswer->content = $request->essay_answer;
        $essayAnswer->question_id = $question->id;
        $essayAnswer->save();
        return response()->successWithMessage('Berhasil Menambahkan Soal!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $question = Question::with(['essayAnswer', 'options', 'subject', 'grade'])->findOrFail($id);
        } catch (\Throwable $th) {
            return response()->error('Pengumuman tidak ditemukan!', StatusCode::UNPROCESSABLE_ENTITY);
        }
        return $question;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateQuestion $request, $id)
    {
        try {
            $question = Question::findOrFail($id);
        } catch (\Throwable $th) {
            return response()->error('Soal tidak ditemukan!', StatusCode::UNPROCESSABLE_ENTITY);
        }

        $previousQuestionType = $question->type;
        $currentQuestionType = $request->type;

        $question->fill($request->validated());
        try {
            $question->update();
        } catch (\Throwable $th) {
            return response()->error('Gagal mengubah Soal!', StatusCode::INTERNAL_SERVER_ERROR);
        }

        if (($previousQuestionType !== $currentQuestionType) && ($currentQuestionType === 'Essay' && $previousQuestionType === 'Multiple Choice')) {
            Option::where('question_id', $question->id)->delete();
            $essayAnswer = new EssayAnswer($request->validated());
            $essayAnswer->content = $request->essay_answer;
            $essayAnswer->question_id = $question->id;
            $essayAnswer->save();
        } else if (($previousQuestionType !== $currentQuestionType) && ($currentQuestionType === 'Multiple Choice' && $previousQuestionType === 'Essay')) {
            EssayAnswer::where('question_id', $question->id)->delete();
            $options = collect([]);
            collect($request->options)->each(function ($item, $key) use ($options, $question) {
                $options->push(collect($item)->put('question_id',  $question->id));
            });
            Option::insert($options->toArray());
        } else if (($previousQuestionType === $currentQuestionType) && $currentQuestionType === 'Multiple Choice') {
            collect($request->options)->each(function ($option, $key) use ($question) {
                $data = collect($option)->put('question_id',  $question->id);
                Option::where('question_id', $question->id)->where('name', $option['name'])->update($data->toArray());
            });
        } else if (($previousQuestionType === $currentQuestionType) && $currentQuestionType === 'Essay') {
            $essayAnswer = EssayAnswer::where('question_id', $question->id)->first();
            $essayAnswer->fill($request->validated());
            $essayAnswer->content = $request->essay_answer;
            $essayAnswer->question_id = $question->id;
            $essayAnswer->update();
        }

        return response()->successWithMessage('Berhasil mengubah Soal!', StatusCode::OK);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        EssayAnswer::where('question_id', $id)->delete();
        Option::where('question_id', $id)->delete();
        Question::destroy($id);
        return response()->successWithMessage('Berhasil menghapus Soal!', StatusCode::OK);
    }
}
