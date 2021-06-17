<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NewsItem extends Model
{
    protected $table = 'news_items';

    protected $fillable = ['title', 'guid', 'description', 'link', 'published_at', 'author', 'img_link'];

    /**
     * Set relation
     *
     * @return array
     */
    public function newsMedia()
    {
      return $this->hasMany(newsMedia::class);
    } 
}
