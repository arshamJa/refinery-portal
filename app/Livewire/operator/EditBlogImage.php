<?php

namespace App\Livewire\operator;

use App\Models\BlogImage;
use Livewire\Attributes\Computed;
use Livewire\Component;

class EditBlogImage extends Component
{
    public function render()
    {
        return view('livewire.operator.edit-blog-image');
    }
    public $blog;

    #[Computed]
    public function blogImages(){
        return BlogImage::where('blog_id',$this->blog)->get();
    }

    public function deleteImage($id)
    {
        $image = BlogImage::findOrFail($id);
        $old_image_path = public_path('storage/'. $image->image);
        if (file_exists($old_image_path)){
            unlink($old_image_path);
        }
        $image->delete();
        $this->reset();
    }
}
