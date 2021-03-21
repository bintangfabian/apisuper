<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAnnouncement;
use App\Http\Requests\UpdateAnnouncement;
use App\Models\Announcement;
use App\StatusCode;
use Facade\Ignition\QueryRecorder\Query;
use Illuminate\Http\Request;

class AnnouncementController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = $request->user();
        if ($user->student) {
            $announcement = Announcement::with('user')->where('grade_id', $user->student->grade_id)->orWhere('grade_id', null)->latest('created_at');
            return $announcement->paginate($request->query('per_page'));
        }
        $announcement = Announcement::with('user')->where('grade_id', null)->latest('created_at');
        return $announcement->paginate($request->query('per_page'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function management(Request $request)
    {
        $user = $request->user();
        return Announcement::where('user_id', $user->id)->paginate(15);
        // return $user->hasPermissionTo('crud announcement');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreAnnouncement $request)
    {
        $user = $request->user();
        $announcement = new Announcement($request->validated());
        // return $request->validated();
        $announcement->user_id = $user->id;
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

        return response()->successWithMessage('Berhasil mengubah pengumuman!', StatusCode::OK);
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
            return response()->error('Gagal menghapus pengumuman!', StatusCode::INTERNAL_SERVER_ERROR);
        }
        return response()->successWithMessage('Berhasil menghapus pengumuman!', StatusCode::OK);
    }

    public function search($q)
    {
        return Announcement::where('title', 'LIKE', '%' . $q . '%')->paginate(15);
    }
}
