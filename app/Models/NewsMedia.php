<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NewsMedia extends Model
{
    protected $table = 'news_media';

    protected $fillable = ['link', 'news_item_id'];

    /**
     * Set relation
     *
     * @return array
     */
    public function newsItem()
    {
        return $this->belongsTo(NewsItem::class);
    }  
}
