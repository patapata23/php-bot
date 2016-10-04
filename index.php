<?php

$json_string = file_get_contents('php://input');
$json_object = json_decode($json_string);

echo $json_string;

foreach ($json_object->events as $event) {
     if('message' == $event->type){
           api_post_request($event->replyToken, $event->message->text, $event->type);
     }else if('beacon' == $event->type){
           api_post_request($event->replyToken, 'BEACONイベント!!', $event->type);
     }
}

function api_post_request($token, $message, $type) {
       $url = 'https://api.line.me/v2/bot/message/reply';
       $channel_access_token = 'ltj/YgPzQIGcUjDDGWOC2bTP8WjZCBD0S/COyVi6knuMwJJb8xNdTwrc61XILB8VOr35vrDUyU4sQzK8ZXOj7fhX2w9gPZ/FaTDPxrY7VUHuqrDc9+cmgFWRVrojc0KR+lydSt97n/u0oRVSq5kvcQdB04t89/1O/w1cDnyilFU=';
       $headers = array(
                  'Content-Type: application/json',
                  "Authorization: Bearer {$channel_access_token}"
          );
       if ($type == 'beacon') {
           $post = array(
                 'replyToken' => $token,
                 'messages' => array(
                              array(
                                'type' => 'video',
                                 'originalContentUrl' => 'https://example.com/original.mp4',
                                     "previewImageUrl" => "https://example.com/preview.jpg"
                              )
                    )
                );
       } else {
           $post = array(
                 'replyToken' => $token,
                 'messages' => array(
                              array(
                                'type' => 'text',
                                'text' => $message
                         )
                 )
           );
       } 


       $curl = curl_init($url);
       curl_setopt($curl, CURLOPT_POST, true);
       curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
       curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($post));
       curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
       curl_exec($curl);
}
