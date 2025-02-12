<?php

namespace App\Http\Controllers;

use Validator;
use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
class ArticleController extends Controller implements HasMiddleware
{
    /**
     * Display a listing of the resource.
     */
    public static function middleware(): array
    {
        return[
        new Middleware('permission:view articles',only:['index']),
        new Middleware('permission:edit articles',only:['edit']),
        new Middleware('permission:create articles',only:['create']),
        new Middleware('permission:destroy articles',only:['destroy']),
        ];
    }
    public function index()
    {
        $articles = Article::paginate(10);

        return view('articles.list',[
            'articles'=>$articles
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('articles.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'title'=>'required|min:3',
            'author'=>'required|max:65535'
        ]);
        if($validator->passes()){
            $article =new  Article();
                $article->title=$request->title;
                $article->text=$request->text;
                $article->author=$request->author;
                $article->save();
         return redirect()->route('articles.index')->with('success', 'Articles added successfully!');

        }
        else{
            return redirect()->route('articles.create')->withInput()->withErrors($validator);

        }
    }

    /**
     * Display the specified resource.
     */


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $articles = Article::findOrFail($id);
        return view('articles.edit',compact('articles'));

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $article = Article::findOrFail($id);

        $validator = Validator::make($request->all(),[
            'title'=>'required|min:3',
            'author'=>'required|min:3'
        ]);
        if($validator->passes()){
                $article->title=$request->title;
                $article->text=$request->text;
                $article->author=$request->author;
                $article->save();
         return redirect()->route('articles.index')->with('success', 'Articles added successfully!');

        }
        else{
            return redirect()->route('articles.edit',$id)->withInput()->withErrors($validator);

        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Article::where('id',$id)->delete();
        return redirect()->route('articles.index')->with('success','Articles deleted successfully');
    }
}
