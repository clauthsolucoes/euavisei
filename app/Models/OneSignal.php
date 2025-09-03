<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OneSignal extends Model
{
    private $appId = '586d8421-1c29-452a-95e7-d5733e0672ae';
    private $apiUrl = 'https://onesignal.com/api/v1/notifications';
    private $users;
    private $content = 'Uma nova ocorrência foi relatada próximo a você.'; //Conteúdo
    private $headings = 'Nova ocorrência'; //Título
    private $post;
    private $icon = 'ic_launcher_round.png';

    private function getHeaders(){
        return array(
            'Content-Type: application/json; charset=utf-8',
            'Authorization: Basic OWI5Zjk3MGEtNGE1Ni00YzkwLWI4ODQtNGNkMjE4NjdkNTA3'
        );
    } 
    
    private function getAppId(){
        return $this->appId;
    }

    public function setUsers($users){
        $this->users = $users;
    }

    private function getUsers(){
        return $this->users;
    }

    public function setContent($content){
        $this->content = $content;
    }

    private function getContent(){
        return array(
            "en" => $this->content
        );
    }

    public function setHeadings($headings){
        $this->headings = $headings;
    }

    private function getHeadings(){
        return array(
            "en" => $this->headings
        );
    }

    public function setIcon($icon){
        $this->icon = $icon;
    }

    public function getIcon(){
        return $this->icon;
    }

    public function setPost(){
        $this->post = array(
            'app_id' => $this->getAppId(),
			'include_player_ids' => $this->getUsers(),
	        'data' => array('dados' => 'ex'),
	        'large_icon' => $this->getIcon(),
	        'contents' => $this->getContent(),
	        'headings' => $this->getHeadings()
        );
    }

    private function getPost(){
        return json_encode($this->post);
    }

    private function getUrl(){
        return $this->apiUrl;
    }

    public function callApi(){
        $post = json_decode(json_encode($this->getPost()));
        try{
            $ch = curl_init();
    	    curl_setopt($ch, CURLOPT_URL, $this->getUrl());
    	    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json; charset=utf-8',
    	                                               'Authorization: Basic NzllOGE4MmMtZDgyOC00Mjk5LWE1N2YtYjIzNWY4MjczODQ2'));
    	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    	    curl_setopt($ch, CURLOPT_HEADER, FALSE);
    	    curl_setopt($ch, CURLOPT_POST, TRUE);
    	    curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
    	    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);    
    
    	    $response = curl_exec($ch);
    	    curl_close($ch);
    
    	    return $response;
        }catch(Exception $e){
            return $e;
        }
    }
}
