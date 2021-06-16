<?php

namespace App\Console\Commands;

use App\Http\Controllers\NewsController;
use App\NewsService;
use Carbon\Carbon;
use Illuminate\Console\Command;

class News extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'news:parse';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command to start parsing';

    /**
     *
     * @var App\Http\Controllers\NewsController
     */
    protected $newsService;

    /**
     *
     * @var App\NewsService
     */
    protected $newsController;   

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $this->newsController = new NewsController ();
        $this->newsService = new NewsService ();
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $start = now();
        $this->info(Carbon::now()->toDateTimeString() . ' Start');

        $news = $this->newsService->getNews();
       
        if(!$news || $news['error']) {

            $this->error(Carbon::now()->toDateTimeString() . ' Something went wrong');

        } else if(!$news['error'] && !$news['payload']) {

            $this->info(Carbon::now()->toDateTimeString() . ' No news in rss');

        } else {

            $count = count($news['payload']);
            $this->comment(Carbon::now()->toDateTimeString() . " $count  news in rss");
            $this->comment(Carbon::now()->toDateTimeString() . " Processing...");
            $result = $this->newsController->save($news['payload']);

            if (!$result) {
                $this->error(Carbon::now()->toDateTimeString() . ' Something went wrong');
                return;
            }

            $added = $result['added'];
            $existed = $result['existed'];
            $this->comment(Carbon::now()->toDateTimeString() . " Added: $added; existed $existed");      

        }

        $time = $start->diffInSeconds(now());
        $this->comment(Carbon::now()->toDateTimeString() . " Processed in $time seconds");
        $this->info(Carbon::now()->toDateTimeString() .' End');       
    }
}
