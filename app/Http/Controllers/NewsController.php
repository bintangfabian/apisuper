<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreNews;
use App\Http\Requests\UpdateNews;
use App\Models\News;
use Illuminate\Http\Request;
use App\StatusCode;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class NewsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $news = News::with('image:id,imageable_id')->paginate($request->query('per_page'));
        return $news;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function search($q)
    {
        $news = News::where('title', 'LIKE', '%' . $q . '%')->paginate(15);
        return $news;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreNews $request)
    {
        $user = $request->user();
        try {
            $news = new News($request->validated());
            $news->user_id = $user->id;
            $news->save();
            $request->file('thumbnail')->storeAs(
                "public/images/news/$news->id",
                'thumbnail.png'
            );
            $news->image()->create(['path' => "news/$news->id/thumbnail.png", 'thumbnail' => true]);
            return response()->successWithMessage('Successfully created news!', StatusCode::CREATED);
        } catch (\Throwable $th) {
            return response()->error($th, StatusCode::INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\News  $news
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $news = News::with('image:id,imageable_id')->find($id);
            return $news;
        } catch (\Throwable $th) {
            return response()->error('Failed to get news!', StatusCode::INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\News  $news
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateNews $request, $id)
    {
        try {
            $news = News::findOrFail($id);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $th) {
            return response()->error('Berita tidak ditemukan', StatusCode::UNAUTHORIZED);
        } catch (\Throwable $th) {
            return response()->error('Terjadi kesalahan', StatusCode::INTERNAL_SERVER_ERROR);
        }
        try {
            $news->fill($request->validated());
            $news->update();
        } catch (\Throwable $th) {
            return response()->error('Gagal edit berita!', StatusCode::INTERNAL_SERVER_ERROR);
        }
        if (request()->hasFile('image')) {
            try {
                $request->file('thumbnail')->storeAs(
                    "public/images/news/$news->id",
                    'thumbnail.png'
                );
                $news->image()->update(['path' => "news/$news->id/thumbnail.png", 'thumbnail' => true]);
                return response()->successWithMessage('Sukses edit berita!', StatusCode::CREATED);
            } catch (\Throwable $th) {
                return response()->error('Gagal mengubah gambar ketika mengedit berita!!', StatusCode::INTERNAL_SERVER_ERROR);
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\News  $news
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $news = News::findOrFail($id);
        try {
            if ($news->delete() && $news->image->delete() && Storage::delete("public/images/" .  $news->image->path)) {
                return response()->successWithMessage('Sukses menghapus berita!', StatusCode::CREATED);
            }
        } catch (\Throwable $th) {
            return response()->error('Gagal menghapus berita!', StatusCode::INTERNAL_SERVER_ERROR);
        }
    }
}
