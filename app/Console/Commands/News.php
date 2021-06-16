<?php

namespace App\Console\Commands;

use App\Http\Controllers\NewsController;
use App\NewsService;
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
        $this->info('Start');

        $news = $this->newsService->getNews();
       
        if(!$news || $news['error']) {

            $this->error('Something went wrong');

        } else if(!$news['error'] && !$news['payload']) {

            $this->info('No news in rss');

        } else {

            $count = count($news['payload']);
            $this->comment("$count  news in rss");
            $this->comment("Processing...");
            $result = $this->newsController->save($news['payload']);

            if (!$result) {
                $this->error('Something went wrong');
                return;
            }

            $added = $result['added'];
            $existed = $result['existed'];
            $this->comment("Added: $added; existed $existed");      

        }

        $time = $start->diffInSeconds(now());
        $this->comment("Processed in $time seconds");
        $this->info('End');       
    }
}
