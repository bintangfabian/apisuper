<?php

namespace App\Http\Controllers;

use App\Models\News;
use Illuminate\Http\Request;
use App\StatusCode;

class NewsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return News::get();
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
    public function store(Request $request)
    {
        try {
            $news = new News;
            $news->news_picture = $request->news_picture;
            $news->news_title = $request->news_title;
            $news->news_fill = $request->news_fill;
            if($news->save()){
                return response()->successWithMessage('Successfully created news!', StatusCode::CREATED);
            }else{
                return ["status"=>"Gagal Menyimpan data"];
            }
        } 
            catch (\Throwable $th) {
                return response()->error('Failed to create news!', StatusCode::INTERNAL_SERVER_ERROR);
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
            return News::where('id_news', $id)->first();
        } 
        catch (\Throwable $th) {
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
        try {
            return News::where('id_news', $id)->first();
        } 
        catch (\Throwable $th) {
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
    public function update(Request $request, $id)
    {
        try {
            $news = News::where('id_news', $id)->first();
            $news->news_picture = $request->news_picture;
            $news->news_title = $request->news_title;
            $news->news_fill = $request->news_fill;
            if($news->save()){
                return response()->successWithMessage('Successfully edited news!', StatusCode::CREATED);
            }
        } 
        catch (\Throwable $th) {
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
            $news = News::where('id_news', $id)->first();
            if($news->delete()){
                return response()->successWithMessage('Successfully deleted news!', StatusCode::CREATED);
            }
        } 
        catch (\Throwable $th) {
            return response()->error('Failed to deleted news!', StatusCode::INTERNAL_SERVER_ERROR);
        }
    }
}
