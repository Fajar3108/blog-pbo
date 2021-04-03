<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Article;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class ArticleController extends Controller
{
    public function index()
    {
        $articles = Article::get();

        $response = [
                'status' => 'error',
                'msg' => 'Validator error',
                'errors' => $validate->errors(),
                'content' => null
        ];

        return response()->json($response, 200);
    }

    public function create(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'title' => 'required',
            'desc' => 'required',
            'category_id' => 'required',
            'thumbnail' => ['required','image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
        ]);

        if ($validate->fails()){
            $response = [
                'status' => 'error',
                'msg' => 'Validator error',
                'errors' => $validate->errors(),
                'content' => null
            ];
            return response()->json($response, 400);
        }

        $extension = $request->file('thumbnail')->extension();
        $thumbnailName = date('dmyHis').'.'.$extension;
        Storage::putFileAs('articles', $request->file('thumbnail'), $thumbnailName);

        $article = auth()->user()->articles()->create([
            'title' => $request->title,
            'desc' => $request->desc,
            'category_id' => $request->category_id,
            'thumbnail' => $thumbnailName,
        ]);

        $respon = [
            'status' => 'success',
            'msg' => 'Success create new category',
            'errors' => null,
            'content' => [
                'status_code' => 200,
                'data' => $article
            ]
        ];
        return response()->json($respon, 200);
    }

    public function update(Request $request, Article $article)
    {
        $validate = Validator::make($request->all(), [
            'title' => 'required',
            'desc' => 'required',
            'category_id' => 'required',
            'thumbnail' => 'required',
        ]);

        if ($validate->fails()){
            $response = [
                'status' => 'error',
                'msg' => 'Validator error',
                'errors' => $validate->errors(),
                'content' => null
            ];
            return response()->json($response, 400);
        }

        if($request->file('thumbnail')){
            \Storage::delete($article->thumbnail);
            $thumbnail = $request->file('thumbnail')->store("images/articles");
        } else{
            $thumbnail = $article->thumbnail;
        }

        $article->update([
            'title' => $request->title,
            'desc' => $request->desc,
            'category_id' => $request->category_id,
            'thumbnail' => $request->thumbnail,
        ]);

        $respon = [
            'status' => 'success',
            'msg' => 'Success updated category',
            'errors' => null,
            'content' => [
                'status_code' => 200,
                'data' => $article
            ]
        ];
        return response()->json($respon, 200);
    }

    public function delete(Article $article)
    {
        $article->delete();

        $respon = [
            'status' => 'success',
            'msg' => 'Success deleted category',
            'errors' => null,
            'content' => [
                'status_code' => 200,
                'category name' => $article
            ]
        ];
        return response()->json($respon, 200);
    }
}
