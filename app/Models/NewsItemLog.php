<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NewsItemLog extends Model
{
    protected $table = 'news_item_logs';

    protected $fillable = ['request_method', 'request_url', 'response_body', 'response_code'];
}
