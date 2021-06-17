<?php

namespace app;

use App\Models\NewsItemLog;
use Exception;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Client;
use SimpleXMLElement;

/**
 * Service for getting data from rss and converting
 */
class NewsService
{
   /**
    * Get data from rss
    *
    * @return void
    */
   private function callApi () 
   {

      $requestMethod = 'GET';
      $requestUrl = config('services.rss.link');
    
      try {

         $client = new Client ();
         $request = new Request($requestMethod, $requestUrl);  
         $response = $client->send($request);
         
         $responseBody = $response->getBody()->getContents();
         $responseCode = $response->getStatusCode();
        
         NewsItemLog::create([
               'request_method' => $requestMethod,
               'request_url' => $requestUrl, 
               'response_body'=> $responseBody,
               'response_code' => $responseCode
         ]);

         $news = $this->convertNewsFromXMLToArray ( new SimpleXMLElement(mb_convert_encoding($responseBody, 'UTF-8')));

      } catch (Exception $e) {

         return [
            'error' => true,
            'payload' => null
         ] ;
      }

      return [
         'error' => false,
         'payload' => $news
      ] ;
        
    }

    /**
     * Conver XML data to array function
     *
     * @param SimpleXMLElement $xm
     * @return array
     */
    public function convertNewsFromXMLToArray ($simpleXmlElement) 
    {
         
        $channel = (array) $simpleXmlElement->channel;   
         
        $channelItems = collect($channel['item']);
    
        $result = $channelItems->map(function ($item) {
    
            $mediaLink = [];           

            foreach($item->enclosure as $media) {
                $mediaLink []= ((array) $media)['@attributes']['url'];
            }

            return array (
                'title' => ( string ) $item->title[0],
                'link' => ( string ) $item->link[0],
                'description'  => ( string ) $item->description[0],
                'author' => ( string ) $item->author[0],
                'guid' => ( string ) $item->guid[0],
                'pubDate' => ( string ) $item->pubDate[0],
                'media' => $mediaLink,

            ) ;
        });
    

        return $result->all();
        
    }

    /**
     * Get news
     *
     * @return array
     */
    public function getNews() 
    {

      return $this->callApi();
       
    }

   

}