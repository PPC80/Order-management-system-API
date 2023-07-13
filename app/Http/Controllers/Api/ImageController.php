<?php

namespace App\Http\Controllers\Api;

use App\Models\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class ImageController extends Controller
{
    public function showUploadForm()
    {
        return view('upload');
    }


    public function store(Request $request){

        $request->validate([
            'file' => 'required|image|max:10240',
            'id_producto' => 'required|integer|numeric|gte:1'
        ]);

        $uploadedFileUrl = Cloudinary::upload($request->file('file')->getRealPath())->getSecurePath();
        $publicId = Cloudinary::getPublicId($uploadedFileUrl);

        try{
            Image::create([
                'product_id' => $request->input('id_producto'),
                'cloudinary_public_id' => $publicId,
                'cloudinary_url' => $uploadedFileUrl
            ]);

            return response()->json(['message' => "Image saved successfully"], 201);

        } catch (\Exception $e){
            Log::error("Error saving image: " . $e->getMessage());
            return response()->json(['error' => 'Failed to save image'], 500);
        }
    }



    public function show(Request $request){

        $request->validate([
            'id_producto' => 'required|integer|numeric|gte:1'
        ]);

        $imagenes = DB::table('images')
                ->where('product_id', $request->input('id_producto'))
                ->get();

        return response()->json([$imagenes], 201);
    }



    public function destroy(Request $request){

        $request->validate([
            'cloudinary_id' => 'required|string'
        ]);

        try{
            $imagen = Image::where('cloudinary_public_id', $request->input('cloudinary_id'))->first();

            if ($imagen) {
                $imagen->delete();
                Cloudinary::destroy($request->input('cloudinary_id'));
                return response()->json(['message' => 'Image deleted successfully']);
            } else {
                return response()->json(['message' => 'Image not found'], 404);
            }

        } catch (\Exception $e){
            Log::error("Error deleting image: " . $e->getMessage());
            return response()->json(['error' => 'Failed to delete image'], 500);
        }
    }



    public function destroyAll(String $id){

        try{
            $imagenes = Image::where('product_id', $id)->get();

            if ($imagenes) {

                foreach ($imagenes as $imgs) {
                    $imgs->delete();
                    Cloudinary::destroy($imgs->cloudinary_public_id);
                }

                return response()->json(['message' => 'Images deleted successfully']);
            } else {
                return response()->json(['message' => 'Images not found'], 404);
            }

        } catch (\Exception $e){
            Log::error("Error deleting images: " . $e->getMessage());
            return response()->json(['error' => 'Failed to delete images'], 500);
        }
    }
}
