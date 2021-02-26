<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreNews;
use App\Http\Requests\UpdateNews;
use App\Models\News;
use Illuminate\Http\Request;
use App\StatusCode;
use Illuminate\Support\Facades\Storage;

class NewsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return News::with('image:id,imageable_id')->get();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreNews $request)
    {
        try {
            $news = new News($request->validated());
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
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\News  $news
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return News::with('image:id,imageable_id')->find($id);
        try {
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
            $news->fill($request->validated());
            $news->update();

            $request->file('thumbnail')->storeAs(
                "public/images/news/$news->id",
                'thumbnail.png'
            );

            $news->image()->update(['path' => "news/$news->id/thumbnail.png", 'thumbnail' => true]);
            return response()->successWithMessage('Successfully edited news!', StatusCode::CREATED);
        } catch (\Throwable $th) {
            return response()->error('Failed to edit news!', StatusCode::INTERNAL_SERVER_ERROR);
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
        try {
            $news = News::find($id)->first();
            if ($news->delete()) {
                return response()->successWithMessage('Successfully deleted news!', StatusCode::CREATED);
            }
        } catch (\Throwable $th) {
            return response()->error('Failed to deleted news!', StatusCode::INTERNAL_SERVER_ERROR);
        }
    }
}
