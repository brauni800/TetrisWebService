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

    $this->post('rejectRequest', function ($req, $res, $args) {
        $fm = new FriendModel();
        
        return $res
           ->withHeader('Content-type', 'application/json')
           ->getBody()
           ->write(
            json_encode(
                $fm->rejectFriendRequest(
                    $req->getParsedBody()
                )
            )
        );
    });

    $this->get('getAllRequest/{id_user}', function ($req, $res, $args) {
        $fm = new FriendModel();
        
        return $res
           ->withHeader('Content-type', 'application/json')
           ->getBody()
           ->write(
            json_encode(
                $fm->getAllRequest($args['id_user'])
            )
        );
    });

    $this->post('acceptRequest', function ($req, $res, $args) {
        $fm = new FriendModel();
        
        return $res
           ->withHeader('Content-type', 'application/json')
           ->getBody()
           ->write(
            json_encode(
                $fm->acceptFriend(
                    $req->getParsedBody()
                )
            )
        );
    });
});
