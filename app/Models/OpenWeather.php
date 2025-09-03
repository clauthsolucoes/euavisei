<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OpenWeather extends Model
{
   private $apiKey = '4090397abb3606d25464437f3b53ad00';
   private $url = 'https://api.openweathermap.org/data/2.5/weather?';
   private $latitude;
   private $Longitude;
   
   public function getUrl(){
       return $this->url;
   }
   
   public function setLatitude($latitude){
       $this->latitude = $latitude;
   }
   
   public function getLatitude(){
       return $this->latitude;
   }
   
   public function setLongitude($longitude){
       $this->longitude = $longitude;
   }
   
   public function getLongitude(){
       return $this->longitude;
   }
   
   public function getApiKey(){
       return $this->apiKey;
   }
   
   private function ordenaUrl(){
       $status = 'lat=' . $this->getLatitude() . '&lon='. $this->getLongitude() . '&lang=pt_br&units=metric&cnt=5&appid=4090397abb3606d25464437f3b53ad00';
       $new_url = $this->getUrl() . $status;
       return $new_url;
   }
   
   public function callApi(){
        $curl = curl_init();
            curl_setopt_array($curl, [
    	        CURLOPT_URL => $this->ordenaUrl(),
            	CURLOPT_RETURNTRANSFER => true,
            	CURLOPT_FOLLOWLOCATION => true,
            	CURLOPT_ENCODING => "",
            	CURLOPT_MAXREDIRS => 10,
            	CURLOPT_TIMEOUT => 30,
            	CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            	CURLOPT_CUSTOMREQUEST => "GET",
            ]);
    
            $response = curl_exec($curl);
            $err = curl_error($curl);
    
            curl_close($curl);
            
            function toArray($data) {
	            if (is_object($data)) $data = get_object_vars($data);
	                return is_array($data) ? array_map(__FUNCTION__, $data) : $data;
	        }
            
            $resp = json_encode($response);
            return $resp;
        /*$requestClient = new \GuzzleHttp\Client();
        try {
            $res = $requestClient->request('GET', $this->ordenaUrl(), []);
            $content = $res->getBody();
            $decoded = json_decode($content);
            return $decoded;    
        }
        catch (\GuzzleHttp\Exception\BadResponseException $e) {
            $response = $e->getResponse();
            $responseBodyAsString = $response->getBody()->getContents();
            $decoded = json_decode($responseBodyAsString);
            return $decoded;
        }*/
    }
}
