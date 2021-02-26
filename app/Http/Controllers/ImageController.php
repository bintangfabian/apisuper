<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreImage;
use App\Models\Image;
use App\StatusCode;
use Intervention\Image\Facades\Image as ImageTools;
use Illuminate\Support\Facades\Storage;

class ImageController extends Controller
{
    private $imagePath = 'public/images';

    public function store(StoreImage $request)
    {
        try {
            $path = date('mdYHis') . uniqid();
            $request->file('image')->storeAs(
                "$this->imagePath/random/" . $path,
                'image.png'
            );

            $image = new Image(['path' => "random/$path/image.png", 'thumbnail' => true]);
            $image->save();

            return response()->successWithKey($image->id, 'image_id', StatusCode::CREATED);
        } catch (\Throwable $th) {
            return response()->error("Gagal menyimpan gambar $th", StatusCode::INTERNAL_SERVER_ERROR);
        }
    }

    public function show($id)
    {
        try {
            $image = Image::findOrFail($id);
        } catch (\Throwable $th) {
            return response()->error('Image is not found', StatusCode::NOT_FOUND);
        }

        $path = Storage::disk('local')->path("public/images/$image->path");
        // $imageTools = ImageTools::make($path);
        // $imageTools->fit(500, 500, null, 'center');
        return response()->file($path);
    }
}
