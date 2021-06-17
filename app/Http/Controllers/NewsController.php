<?php

namespace App\Http\Controllers;

use App\Models\NewsMedia;
use App\Models\NewsItem;
use Carbon\Carbon;
use App\Facades\Media;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PhpParser\Node\Stmt\TryCatch;

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
   
        try {

            DB::statement ('SET FOREIGN_KEY_CHECKS = 0');
            NewsItem::truncate();
            NewsMedia::truncate();
            DB::statement ('SET FOREIGN_KEY_CHECKS = 1'); 

            foreach ($data as $news) {
                
    
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
    
                        // if you want to upload the file to the storage
                        // Warning: it's too long
                        //$fileName = Media::uploadResource($media);
                        $mediaItems []= new NewsMedia([
                            'link' => $media,
                            'news_item_id'=>$insertedData->id
                        ]);
                        
                    };                
                }
                $newsItem->newsMedia()->saveMany($mediaItems);
    
            }                

        } catch (Exception $e) {
            return [
                'added'=> $addedCount,
                'error' => $e->getMessage()
            ];
        }
   
        return [
            'added'=> $addedCount,
            'error' => false
        ];
    }

}
