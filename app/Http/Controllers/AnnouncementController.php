<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAnnouncement;
use App\Http\Requests\UpdateAnnouncement;
use App\Models\Announcement;
use App\StatusCode;

class AnnouncementController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Announcement::paginate(15);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreAnnouncement $request)
    {
        $announcement = new Announcement($request->validated());
        try {
            $announcement->save();
        } catch (\Throwable $th) {
            return response()->error('Gagal menambah pengumuman!', StatusCode::INTERNAL_SERVER_ERROR);
        }

        return response()->successWithMessage('Berhasil menambah pengumuman!', StatusCode::CREATED);
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
            $announcement = Announcement::findOrFail($id);
        } catch (\Throwable $th) {
            return response()->error('Pengumuman tidak ditemukan!', StatusCode::UNPROCESSABLE_ENTITY);
        }
        return $announcement;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateAnnouncement $request, $id)
    {
        try {
            $announcement = Announcement::findOrFail($id);
        } catch (\Throwable $th) {
            return response()->error('Pengumuman tidak ditemukan!', StatusCode::UNPROCESSABLE_ENTITY);
        }

        $announcement->fill($request->validated());
        try {
            $announcement->update();
        } catch (\Throwable $th) {
            return response()->error('Gagal mengubah pengumuman!', StatusCode::INTERNAL_SERVER_ERROR);
        }

        return response()->successWithMessage('Berhasil mengubah pengumuman!', StatusCode::CREATED);
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
            $announcement = Announcement::findOrFail($id);
        } catch (\Throwable $th) {
            return response()->error('Pengumuman tidak ditemukan!', StatusCode::UNPROCESSABLE_ENTITY);
        }

        try {
            $announcement->delete();
        } catch (\Throwable $th) {
            return response()->error('Gagal  menghapus pengumuman!', StatusCode::INTERNAL_SERVER_ERROR);
        }
        return response()->successWithMessage('Berhasil menghapus pengumuman!', StatusCode::CREATED);
    }
}
