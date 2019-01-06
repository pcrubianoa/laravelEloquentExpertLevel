<?php

namespace App;

use Dimsav\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Article extends Model
{
    use SoftDeletes;
    use Translatable;

    protected $fillable = ['title', 'article_text'];
    public $translatedAttributes = ['title', 'article_text'];
}
