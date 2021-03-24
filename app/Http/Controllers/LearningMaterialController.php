<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreLearningMaterial;
use App\Http\Requests\UpdateLearningMaterial;
use App\Models\Chapter;
use App\Models\LearningMaterial;
use App\StatusCode;
use Illuminate\Http\Request;

class LearningMaterialController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = $request->user();
        return LearningMaterial::with(['subject', 'grade', 'chapter'])->where('user_id', $user->id)->paginate(15);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreLearningMaterial $request)
    {
        $learningMaterial = new LearningMaterial($request->validated());
        $learningMaterial->user_id = $request->user()->id;
        try {
            $learningMaterial->save();
        } catch (\Throwable $th) {
            return $th;
            return response()->error('Gagal menambahkan materi pembelajaran');
        }
        return response()->successWithMessage('Sukses menambahkan materi pembelajaran');
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
            $learningMaterial = LearningMaterial::findOrFail($id);
        } catch (\Throwable $th) {
            return response()->error('Materi pembelajaran tidak ditemukan!', StatusCode::UNPROCESSABLE_ENTITY);
        }
        return response()->successWithKey($learningMaterial, 'learning_material');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateLearningMaterial $request, $id)
    {
        try {
            $learningMaterial = LearningMaterial::findOrFail($id);
        } catch (\Throwable $th) {
            return response()->error('Materi pembelajaran tidak ditemukan!', StatusCode::UNPROCESSABLE_ENTITY);
        }

        $learningMaterial->fill($request->validated());
        try {
            $learningMaterial->update();
        } catch (\Throwable $th) {
            return response()->error('Gagal mengubah materi pembelajaran!', StatusCode::INTERNAL_SERVER_ERROR);
        }

        return response()->successWithMessage('Berhasil mengubah materi pembelajaran!', StatusCode::OK);
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
            $learningMaterial = LearningMaterial::findOrFail($id);
        } catch (\Throwable $th) {
            return response()->error('Materi pembelajaran tidak ditemukan!', StatusCode::UNPROCESSABLE_ENTITY);
        }

        try {
            $learningMaterial->delete();
        } catch (\Throwable $th) {
            return response()->error('Gagal menghapus materi pembelajaran!', StatusCode::INTERNAL_SERVER_ERROR);
        }
        return response()->successWithMessage('Berhasil menghapus materi pembelajaran!', StatusCode::OK);
    }


    public function search($q)
    {
        return LearningMaterial::whereHas('subject', function ($query) use ($q) {
            return $query->where('name', 'LIKE', '%' . $q . '%');
        })->orWhereHas('chapter', function ($query) use ($q) {
            return $query->where('name', 'LIKE', '%' . $q . '%');
        })->orWhere('title', 'LIKE', '%' . $q . '%')->with(['subject', 'grade', 'chapter'])->paginate(15);
    }
}
