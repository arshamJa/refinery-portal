<?php

namespace App\Livewire\employee;

use App\Models\Blog;
use Illuminate\Auth\Access\AuthorizationException;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Locked;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

class News extends Component
{
    use WithPagination, WithoutUrlPagination,WithFileUploads;

    #[Locked]
    public $blogId = '';

    public string $title ='';

    public ?string $search = '';

    public function render()
    {
        return view('livewire.employee.news');
    }
    #[Computed]
    public function blogs()
    {
        return Blog::where('title','like','%'.$this->search.'%')->latest()->paginate('6');
    }

    public function confirmDelete($blog_id)
    {
        $this->title = Blog::where('id',$blog_id)->value('title');
        $this->blogId = $blog_id;
        $this->dispatch('crud-modal',name:'delete');
    }
    /**
     * @throws AuthorizationException
     */
    public function delete($id)
    {
        $this->authorize('delete-blog',$id);
        $news = Blog::find($id);
        $news->delete();
        $this->close();
    }
    public function close()
    {
        $this->dispatch('close-modal');
        $this->redirectRoute('blogs.index');
    }


}
