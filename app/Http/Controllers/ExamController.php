<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreExam;
use App\Http\Requests\UpdateExam;
use App\Models\Exam;
use App\Models\ExamQuestion;
use App\Models\Question;
use App\StatusCode;
use Illuminate\Http\Request;

class ExamController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $exams = Exam::with(['subject', 'user', 'questions', 'questions.options', 'questions.essayAnswer', 'grade'])->paginate(15);

        return $exams;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreExam $request)
    {
        $exam = new Exam($request->validated());
        $exam->user_id = $request->user()->id;
        $exam->save();
        return response()->successWithMessage('Sukses membuat ujian');
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
            $exams = Exam::findOrFail($id)->with(['subject', 'user', 'questions', 'questions.options', 'questions.essayAnswer']);
        } catch (\Throwable $th) {
            return response()->error('Ujian tidak ditemukan!', StatusCode::UNPROCESSABLE_ENTITY);
        }
        return $exams->first();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateExam $request, $id)
    {
        try {
            $exam = Exam::findOrFail($id);
        } catch (\Throwable $th) {
            return response()->error('Ujian tidak ditemukan!', StatusCode::UNPROCESSABLE_ENTITY);
        }

        $exam->fill($request->validated());
        try {
            $exam->update();
        } catch (\Throwable $th) {
            return response()->error('Gagal mengubah ujian!', StatusCode::INTERNAL_SERVER_ERROR);
        }

        return response()->successWithMessage('Berhasil mengubah ujian!', StatusCode::OK);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $exam = Exam::findOrFail($id);
        } catch (\Throwable $th) {
            return response()->error('Ujian tidak ditemukan!', StatusCode::UNPROCESSABLE_ENTITY);
        }

        try {
            ExamQuestion::where('exam_id', $exam->id)->delete();
            $exam->delete();
        } catch (\Throwable $th) {
            return $th;
            return response()->error('Gagal menghapus ujian!', StatusCode::INTERNAL_SERVER_ERROR);
        }
        return response()->successWithMessage('Berhasil menghapus ujian!', StatusCode::OK);
    }
}
