<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use LakM\Comments\Concerns\Commentable;
use LakM\Comments\Contracts\CommentableContract;

class Article extends Model implements CommentableContract
{
    use Commentable;
    use HasFactory;

    private $guestMode = false;
}
