<?php

namespace App\Http\Livewire;

use App\Services\PostService;
use Livewire\Component;

class PostDetail extends Component
{
    public $slug;
    public $title;
    public $content;
    public $columns = ["*"];
    public $filters = [
        "status" => STATUS_RESOLVED
    ];
    public $relations = [
        "tags"
    ];

    public function mount($slug)
    {
        $this->slug = $slug;
    }

    public function render(PostService $postService)
    {
        $post = $postService->findBySlug(
            $this->slug,
            $this->columns,
            $this->relations,
            $this->filters
        );

        if(!$post) abort(404);

        $this->title = $post->title;
        $this->content = $post->content;

        return view('livewire.post-detail', [
            'post' => $post
        ]);
    }
}
