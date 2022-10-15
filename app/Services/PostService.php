<?php

namespace App\Services;

use App\Models\Taggable;
use App\Repositories\PostRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Exception;

class PostService
{
    protected $postRepository;

    public function __construct(PostRepository $postRepository)
    {
        $this->postRepository = $postRepository;
    }

    public function create($params):bool
    {
        DB::beginTransaction();
        try {
            $this->postRepository->create($params);
            DB::commit();
            return true;
        } catch(Exception $ex) {
            DB::rollBack();
            Log::info($ex->getMessage());
            return false;
        }
    }

    public function update($id, $params)
    {
        DB::beginTransaction();
        try {
            if($post = $this->postRepository->update($id, $params)) {
                // Post Tag mapping
                if (isset($params['tag_ids']) && count($params['tag_ids']) > 0) {
                    DB::table('taggables')
                        ->where('taggable_id', $post->id)
                        ->where('taggable_type', 'App\Models\Post')
                        ->delete();

                    $post->tags()->attach($params['tag_ids']);
                }
            }
            DB::commit();
            return true;
        } catch(Exception $ex) {
            DB::rollBack();
            Log::info($ex->getMessage());
            return false;
        }
    }

    public function store($params):bool
    {
        DB::beginTransaction();
        try {
            if($post = $this->postRepository->store($params)) {
                // Post Tag mapping
                if (isset($params['tag_ids']) && count($params['tag_ids']) > 0) {
                    DB::table('taggables')
                        ->where('taggable_id', $post->id)
                        ->where('taggable_type', 'App\Models\Post')
                        ->delete();

                    $post->tags()->attach($params['tag_ids']);
                }
            }
            DB::commit();
            return true;
        } catch(Exception $ex) {
            DB::rollBack();
            Log::info($ex->getMessage());
            return false;
        }
    }

    public function deleteById($id):bool
    {
        DB::beginTransaction();
        try {
            $this->postRepository->deleteById($id);
            DB::commit();
            return true;
        } catch(Exception $ex) {
            DB::rollBack();
            Log::info($ex->getMessage());
            return false;
        }
    }

    public function findById(int $id, array $col, array $relation)
    {
        return $this->postRepository->findById($id, $col, $relation);
    }

    public function findBySlug(string $slug, array $col, array $relation, array $filters)
    {
        return $this->postRepository->findBySlug($slug, $col, $relation, $filters);
    }

    public function getAll()
    {
        return $this->postRepository->all();
    }

    public function getSeries()
    {
        return $this->postRepository->getSeries();
    }

    public function getAllPaginate()
    {
        return $this->postRepository->paginate();
    }

    public function getAllSimplePaginate()
    {
        return $this->postRepository->simplePaginate();
    }

    public function getAllByFilter($columns, $relations, $filters, $pagination)
    {
        return $this->postRepository->getAllByFilter($columns, $relations, $filters, $pagination);
    }
}
