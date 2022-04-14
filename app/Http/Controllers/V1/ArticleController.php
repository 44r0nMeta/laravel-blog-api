<?php

namespace App\Http\Controllers\V1;

use App\Models\Article;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreArticleRequest;
use App\Http\Resources\V1\ArticleResource;
use App\Http\Requests\UpdateArticleRequest;
use App\Models\Category;

class ArticleController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum')->only(['store','update','destroy']);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return ArticleResource::collection(Article::paginate());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreArticleRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreArticleRequest $request)
    {
        $dir = 'images/'.Str::random().'/';

        $image = $request->image;
        unset($request->image);

        $filename = $image->getClientOriginalName();
        $image->move($dir, $filename);
        $path = $dir.$filename;

        $data = [
            'title' => $request->title,
            'content' => $request->content,
            'category_id' => $request->category,
            'image' => $path,
            'user_id' => $request->user()->id,
        ];


        $article = Article::create($data);

        return new ArticleResource($article);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Article  $article
     * @return \Illuminate\Http\Response
     */
    public function show(Article $article)
    {
        return new ArticleResource($article);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateArticleRequest  $request
     * @param  \App\Models\Article  $article
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateArticleRequest $request, Article $article)
    {
        //return $request->bearerToken(); Get decoded sanctum token
        $dir = pathinfo($article->image, PATHINFO_DIRNAME).'/';
        $image = $request->image ?? false;

        $data = [
            'title' => $request->title,
            'content' => $request->content,
            'category_id' => $request->category,
        ];

        if ($image) {
            unset($request->image);

            $filename = $image->getClientOriginalName();
            $image->move($dir, $filename);
            $path = $dir.$filename;
            $data['image'] = $path;
        }

        $article->update($data);

        return new ArticleResource($article);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Article  $article
     * @return \Illuminate\Http\Response
     */
    public function destroy(Article $article)
    {
        $article->delete();
        return response(status:204);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function byCategory(Category $category)
    {
        return ArticleResource::collection($category->articles()->paginate());
    }
}
