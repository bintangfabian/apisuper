<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreChapter;
use App\Http\Requests\UpdateChapter;
use App\Models\Chapter;
use App\StatusCode;
use Illuminate\Http\Request;

class ChapterController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = $request->user();
        try {
            $chapter = Chapter::where('user_id', $user->id)->with(['subject'])->paginate(1);
        } catch (\Throwable $th) {
            return $th;
            return response()->error('Bab tidak ditemukan!', StatusCode::UNPROCESSABLE_ENTITY);
        }
        return $chapter;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreChapter $request)
    {
        $chapter = new Chapter($request->validated());
        $chapter->user_id = $request->user()->id;
        try {
            $chapter->save();
        } catch (\Throwable $th) {
            return response()->error('Gagal menambahkan bab!', StatusCode::INTERNAL_SERVER_ERROR);
        }

        return response()->successWithMessage('Berhasil menambahkan Bab!');
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
            $chapter = Chapter::findOrFail($id);
        } catch (\Throwable $th) {
            return response()->error('Pengumuman tidak ditemukan!', StatusCode::UNPROCESSABLE_ENTITY);
        }
        return response()->successWithKey($chapter, 'chapter');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateChapter $request, $id)
    {
        try {
            $chapter = Chapter::findOrFail($id);
        } catch (\Throwable $th) {
            return response()->error('Bab tidak ditemukan!', StatusCode::UNPROCESSABLE_ENTITY);
        }

        $chapter->fill($request->validated());
        try {
            $chapter->update();
        } catch (\Throwable $th) {
            return response()->error('Gagal mengubah bab!', StatusCode::INTERNAL_SERVER_ERROR);
        }
        return response()->successWithMessage('Berhasil mengubah bab!', StatusCode::OK);
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
            $chapter = Chapter::findOrFail($id);
        } catch (\Throwable $th) {
            return response()->error('Bab tidak ditemukan!', StatusCode::UNPROCESSABLE_ENTITY);
        }

        try {
            $chapter->delete();
        } catch (\Throwable $th) {
            return response()->error('Gagal menghapus bab!', StatusCode::INTERNAL_SERVER_ERROR);
        }
        return response()->successWithMessage('Berhasil menghapus bab!', StatusCode::OK);
    }

    public function search($q)
    {
        return Chapter::whereHas('subject', function ($query) use ($q) {
            return $query->where('name', 'LIKE', '%' . $q . '%');
        })->orWhere('name', 'LIKE', '%' . $q . '%')->with(['subject'])->paginate(15);
    }
}
