<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBlogRequest;
use App\Http\Requests\UpdateBlogRequest;
use App\Models\Blog;
use App\Models\BlogImage;
use App\Models\Organization;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\ValidationException;
use function PHPUnit\Framework\throwException;

class BlogController extends Controller
{
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('blog.create');
    }

    /**
     * Store a newly created resource in storage.
     * @throws ValidationException
     */
    public function store(Request $request)
    {
        dd($request->all());

        Gate::authorize('create-blog');
        $request->validated();
        $blog = Blog::create([
            'user_id' => auth()->user()->id,
            'title' => $request->title,
            'body' => $request->body
        ]);
        if (is_array($request->images)){
            foreach ($request->images as $img){
                if ($img->extension() === 'jpg' || $img->extension() === 'png'
                    || $img->extension() ==='jpeg' || $img->extension() === 'webp'){
                    $path = $img->store('blogs','public');
                    $newImage = BlogImage::create([
                            'blog_id' => $blog->id,
                            'image' => $path
                        ]
                    );
                }else{
                    throw ValidationException::withMessages([
                        'images' => 'فرمت عکس اشتباه است'
                    ]);
                }

            }
        }
        return to_route('blogs.index')->with('status','اخبار جدید درج شد');
    }
    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $blog = Blog::find($id);
        $blogImages = BlogImage::where('blog_id',$blog->id)->get();
        return view('blog.show',['blog' => $blog , 'blogImages' => $blogImages]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        Gate::authorize('update-blog',$id);
        $blog = Blog::find($id);
        $blogImage = BlogImage::where('id',$blog->id)->get();
        return view('blog.edit',['blog' => $blog , 'blogImage' => $blogImage]);
    }

    /**
     * Update the specified resource in storage.
     * @throws ValidationException
     */
    public function update(StoreBlogRequest $request, string $id)
    {
        Gate::authorize('update-blog',$id);
        $request->validated();

        $blog = Blog::find($id);
        $blog->title = $request->title;
        $blog->body = $request->body;
        $blog->save();

        if (is_array($request->images)){
            foreach ($request->images as $img){
                if ($img->extension() === 'jpg' || $img->extension() === 'png'
                    || $img->extension() ==='jpeg' || $img->extension() === 'webp'){
                    $path = $img->store('blogs','public');
                    $newImage = BlogImage::where('blog_id',$blog->id)->create([
                        'blog_id' => $blog->id,
                        'image' => $path
                    ]);
                }else{
                    throw ValidationException::withMessages([
                        'images' => 'فرمت عکس اشتباه است'
                    ]);
                }

            }
        }
        return to_route('blogs.index')->with('status','اخبار با موفقیت بروز شد');
    }
}
