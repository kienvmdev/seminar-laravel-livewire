<?php

namespace App\Repositories;

use App\Models\Post;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use phpDocumentor\Reflection\Types\Integer;

class PostRepository extends BaseRepository
{
    /**
     * @var Model
     */
    protected $model;

    /**
     * BaseRepository constructor.
     *
     * @param Model $model
     */
    public function __construct(Post $model)
    {
        parent::__construct($model);
        $this->model = $model;
    }

    /**
     * @param array $filters
     */
    public function getAllByFilter(array $columns = ['*'], array $relations = [], array $filters = [], int $pagination = PAGINATE_10)
    {
        $query = $this->model->newQuery();

        if(isset($filters['title']) && $filters['title']) {
            $query->where('title', 'like', '%' .$filters['title'].'%');
        }

        if(isset($filters['slug']) && $filters['slug']) {
            $query->where('slug', 'like', '%' .$filters['slug'].'%');
        }

        if($filters['status'] != '') {
            $query->where('status', $filters['status']);
        }

        if(isset($filters['type']) && $filters['type']) {
            $query->where('type', $filters['type']);
        }

        if(isset($filters['seri_id']) && $filters['seri_id']) {
            $query->where('parent_id', $filters['seri_id']);
        }

        if(count($relations)) {
            $query->with($relations);
        }

        return $query->select($columns)
            ->orderBy('id', 'desc')
            ->simplePaginate($pagination);
    }

    public function store($params)
    {
        return $this->model->create($params);
    }


    public function getSeries()
    {
        return $this->model->series()->get();
    }
}
