<?php

namespace App\Repositories;

use App\Models\NewsfeedComment;

abstract class BaseRepository
{
    protected $model;

    public function __construct(\Model $model)
    {
        $this->model = $model;
    }
    public function getById($id)
    {
        return $this->model->where('id', $id)->first();
    }
   
    public function getNewsfeedReaction($newsfeedId)
    {
        $newsfeedLikeCount = $this->model->where('newsfeed_id', $newsfeedId)->count();
        $newsfeedCommentCount = NewsfeedComment::where('newsfeed_id', $newsfeedId)->count();
        return [
            'like' => $newsfeedLikeCount,
            'comment' => $newsfeedCommentCount
        ];
    }
}
