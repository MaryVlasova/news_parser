<?php

namespace App\Http\Controllers;

use App\Models\NewsMedia;
use App\Models\NewsItem;
use Carbon\Carbon;
use App\Facades\Media;
use Illuminate\Support\Facades\Auth;

/**
 * Controller for news
 */
class NewsController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
     
        if(Auth::check()) {
            $news = NewsItem::with('newsMedia')->orderBy('published_at', 'desc')->get();;        
            return view('news', compact('news'));
        } else {
            return redirect()->route('login');
        }

    }

    /**
     * Function for saving news
     *
     * @param array $data
     * @return array
     */
    public function save(array $data)
    {
        $addedCount = 0;  
        $existedCount = 0;     
               
        foreach ($data as $news) {

            $existItem = NewsItem::where('guid', $news['guid'])->first();

            if($existItem === null) {                

                $insertedData = NewsItem::create([                
                    'title' => $news['title'],
                    'link' => $news['link'],
                    'description'  => $news['description'],
                    'author' => $news['author'] ? $news['author'] : null,
                    'guid' => $news['guid'],
                    'published_at' => Carbon::parse($news['pubDate'])->format('Y-m-d H:i:s')
                   
                ]);
                
                $newsItem = NewsItem::find($insertedData->id);

                $mediaItems = [];
                if($newsItem !== null) {

                    $addedCount++;     

                    foreach($news['media'] as $media) {
              
                        $fileName = Media::uploadResource($media);
                        $mediaItems []= new NewsMedia([
                            'link' => $fileName,
                            'news_item_id'=>$insertedData->id
                        ]);
                      
                    };                
                }
                $newsItem->newsMedia()->saveMany($mediaItems);
            } else {
                $existedCount++;
            }
        }
   
        return [
            'added'=> $addedCount,
            'existed' => $existedCount
        ];
    }

}
