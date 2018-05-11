<?php

use App\Friend\FriendModel;

$app->group('/friend/', function (){

    /**
     * parameters {
     *      id_user: int        el que solicita
     *      id_friend: int      el solicitado
     * }
     */
    $this->post('sendRequest', function ($req, $res, $args) {
        $fm = new FriendModel();
        
        return $res
           ->withHeader('Content-type', 'application/json')
           ->getBody()
           ->write(
            json_encode(
                $fm->sendRequest(
                    $req->getParsedBody()
                )
            )
        );
    });

    /**
     * parameters {
     *      id_user: int        el que rechaza
     *      id_friend: int      el rechazado
     * }
     */
    $this->post('rejectRequest', function ($req, $res, $args) {
        $fm = new FriendModel();
        
        return $res
           ->withHeader('Content-type', 'application/json')
           ->getBody()
           ->write(
            json_encode(
                $fm->rejectRequest(
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

    /**
     * parameters {
     *      id_user: int        el que acepta
     *      id_friend: int      el aceptado
     * }
     */
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

    /**
     * parameters {
     *      id_user: int        el que elimina
     *      id_friend: int      el eliminado, como ella a ti :,v  https://www.youtube.com/watch?v=Bk3lknaWI9Q
     * }
     */
    $this->post('removeRequest', function ($req, $res, $args) {
        $fm = new FriendModel();
        
        return $res
           ->withHeader('Content-type', 'application/json')
           ->getBody()
           ->write(
            json_encode(
                $fm->removeFriend(
                    $req->getParsedBody()
                )
            )
        );
    });
});
