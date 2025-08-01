<?php

namespace App\Models\Blog;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use LakM\Comments\Concerns\Commentable;

class Tag extends Model
{
    protected $table = 'posts';

    use Commentable;
    use HasFactory;

    public $guarded = [];
}
