<?php

use App\Friend\FriendModel;

$app->group('/friend/', function (){
    $this->post('sendRequest', function ($req, $res, $args) {
        $fm = new FriendModel();
        
        return $res
           ->withHeader('Content-type', 'application/json')
           ->getBody()
           ->write(
            json_encode(
                $fm->sendFriendRequest(
                    $req->getParsedBody()
                )
            )
        );
    });
});
