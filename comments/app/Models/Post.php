<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use LakM\Comments\Concerns\Commentable;
use LakM\Comments\Contracts\CommentableContract;

class Post extends Model implements CommentableContract
{
    use Commentable;
    use HasFactory;

    public $guarded = [];
}
