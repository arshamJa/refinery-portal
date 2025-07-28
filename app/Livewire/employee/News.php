<?php

namespace App\Livewire\employee;

use App\Models\Blog;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

class News extends Component
{
    use WithPagination, WithoutUrlPagination;

    public $blogId = '';
    public string $title ='';
    public ?string $search = '';
    public $selectedBlog;
    public $sort = 'newest';


    public function filterNews()
    {
        $this->resetPage();
    }

    public function render()
    {
        return view('livewire.employee.news');
    }
    #[Computed]
    public function blogs()
    {
        $query = Blog::where('title', 'like', '%' . strip_tags(trim($this->search)) . '%');
        if ($this->sort === 'oldest') {
            $query->orderBy('created_at', 'asc');
        } else {
            $query->orderBy('created_at', 'desc');
        }
        return $query->paginate(6);
    }
    public function showBlog($id)
    {
        $this->selectedBlog = Blog::with('images')->find($id);
        $this->dispatch('crud-modal', name: 'view');
    }
    public function confirmDelete($blog_id)
    {
        $this->title = Blog::where('id',$blog_id)->value('title');
        $this->blogId = $blog_id;
        $this->dispatch('crud-modal',name:'delete');
    }
    public function close()
    {
        $this->dispatch('close-modal');
        $this->redirectRoute('blogs.index');
    }

}
