<?php

namespace App\Http\Controllers;

use App\Enums\UserPermission;
use App\Enums\UserRole;
use App\Http\Requests\StoreBlogRequest;
use App\Models\Blog;
use App\Models\BlogImage;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\ValidationException;

class BlogController extends Controller
{

    public function create()
    {
        return view('blog.create');
    }

    public function store(StoreBlogRequest $request)
    {
        Gate::authorize('has-permission-and-role', [
            UserPermission::NEWS_PERMISSIONS->value, UserRole::ADMIN->value,
        ]);
        $validated = $request->validated();
        $blog = Blog::create([
            'user_id' => auth()->user()->id,
            'title'   => $validated['title'],
            'body'    => $validated['body']
        ]);

        if ($request->hasFile('image')) {
            foreach ($request->file('image') as $img) {
                if (in_array($img->extension(), ['jpg', 'jpeg', 'png', 'webp'])) {
                    $path = $img->store('blogs', 'public');

                    BlogImage::create([
                        'blog_id' => $blog->id,
                        'image'   => $path
                    ]);
                } else {
                    throw ValidationException::withMessages([
                        'image' => 'فرمت عکس اشتباه است.'
                    ]);
                }
            }
        }

        return to_route('blogs.index')->with('status', 'اخبار جدید درج شد');
    }

    public function edit(string $id)
    {
        Gate::authorize('has-permission-and-role', [
            UserPermission::NEWS_PERMISSIONS->value, UserRole::ADMIN->value,
        ]);
        $blog = Blog::with('images')->findOrFail($id);
        return view('blog.edit', [
            'blog' => $blog,
        ]);
    }

    public function update(StoreBlogRequest $request, string $id)
    {
        Gate::authorize('has-permission-and-role', [
            UserPermission::NEWS_PERMISSIONS->value, UserRole::ADMIN->value,
        ]);
        $validated = $request->validated();
        $blog = Blog::find($id);
        $blog->title = $validated['title'];
        $blog->body = $validated['body'];
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

    public function deleteImage(string $id)
    {
         Gate::authorize('has-permission-and-role', [
            UserPermission::NEWS_PERMISSIONS->value, UserRole::ADMIN->value,
        ]);
        $image = BlogImage::findOrFail($id);
        $old_image_path = public_path('storage/' . $image->image);
        if (file_exists($old_image_path)) {
            unlink($old_image_path);
        }
        $image->delete();
        return redirect()->back()->with('status', 'عکس با موفقیت حذف شد.');
    }

    public function destroy(string $id)
    {
         Gate::authorize('has-permission-and-role', [
            UserPermission::NEWS_PERMISSIONS->value, UserRole::ADMIN->value,
        ]);
        $blog = Blog::with('images')->findOrFail($id);
        foreach ($blog->images as $image) {
            $filePath = public_path('storage/' . $image->image);
            if (file_exists($filePath)) {
                unlink($filePath);
            }
            $image->delete();
        }
        $blog->delete();
        return redirect()->route('blogs.index')->with('status', 'خبر با موفقیت حذف شد.');
    }

}
