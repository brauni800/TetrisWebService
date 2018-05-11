<?php

use App\Message\MessageModel;

$app->group('/message/', function (){

    /**
     * parameters {
     *      id_user: int
     *      id_room: int
     *      time: Time
     *      content: str
     * }
     */
    $this->post('send', function ($req, $res, $args) {
        $mm = new MessageModel();
        
        return $res
           ->withHeader('Content-type', 'application/json')
           ->getBody()
           ->write(
            json_encode(
                $mm->sendMessage(
                    $req->getParsedBody()
                )
            )
        );
    });
});
