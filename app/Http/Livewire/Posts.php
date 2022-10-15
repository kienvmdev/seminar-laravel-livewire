<?php

namespace App\Http\Livewire;

use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;
use App\Services\PostService;
use Cviebrock\EloquentSluggable\Services\SlugService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;
use Livewire\WithPagination;

class Posts extends Component
{
    use WithPagination, AuthorizesRequests;

    public $title;
    public $slug;
    public $content;
    public $category_id;
    public $status = ACTIVE;
    public $post_id = null;
    public $tag_ids = [];

    public $listStatus = [
        'Draf',
        'Resolved',
        'Verified',
        'Private',
        'Published'
    ];

    protected function rules()
    {
        return [
            'title' => 'required',
            'slug' => 'required|unique:posts,slug,'.$this->post_id,
            'content' => 'required',
            'category_id' => 'required',
            'status' => 'nullable',
            'tag_ids' => 'nullable',
        ];
    }

    public function updatedTitle()
    {
        $this->slug = SlugService::createSlug(Post::class, 'slug', $this->title);
    }

    public function store(PostService $postService)
    {
        // Validate Form Request
        $params = $this->validate();

        if($this->post_id) {
            $result = $postService->update($this->post_id, $params);
        } else {
            $result = $postService->store($params);
        }

        if($result) {
            $this->dispatchBrowserEvent('showFlashMessage', ['status' => 1, 'message' => 'Post Created successfully!!']);
//            session()->flash('success', 'Post Created Successfully.');
            $this->emit('setTagsSelect', []);
            $this->resetFields();
        } else {
            $this->dispatchBrowserEvent('showFlashMessage', ['status' => 0, 'message' => 'Post Created Error!!']);
            //session()->flash('error', 'Post Created Error.');
        }
    }

    public function edit(PostService $postService, $id)
    {
        $this->post_id = $id;
        $post = $postService->findById($this->post_id, ['*'], ['tags']);
        $this->title = $post->title;
        $this->slug = $post->slug;
        $this->category_id = $post->category_id;
        $this->content = $post->content;
        $this->status = $post->status;
        $this->tag_ids = collect($post->tags->pluck('id'))->all();
    }

    public function destroy(PostService $postService)
    {
        if($postService->deleteById($this->post_id)) {
            //$this->dispatchBrowserEvent('showFlashMessage', ['status' => 1, 'message' => 'Posts deleted successfully!!']);
            session()->flash('success', "Post deleted successfully!!");
        } else {
            //$this->dispatchBrowserEvent('showFlashMessage', ['status' => 0, 'message' => 'Something goes wrong while deleting post!!']);
            session()->flash('error', "Something goes wrong while deleting post!!");
        }
        $this->dispatchBrowserEvent('closeDeleteModal');
    }

    public function confirmDelete($id)
    {
        $this->post_id = $id;
        $this->dispatchBrowserEvent('openDeleteModal');
    }

    public function closeConfirmDelete()
    {
        $this->post_id = null;
        $this->dispatchBrowserEvent('closeDeleteModal');
    }

    public function resetFields()
    {
        $this->post_id = null;
        $this->title = '';
        $this->slug = '';
        $this->content = '';
        $this->category_id = null;
        $this->status = ACTIVE;
        $this->tag_ids = [];
    }

    public function render()
    {
        return view('livewire.posts', [
            "posts" => Post::all(),
            "categories" => Category::all(),
            "tags" => Tag::all(),
        ]);
    }
}
