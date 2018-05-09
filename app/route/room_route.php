<?php

use App\Room\RoomModel;

$app->group('/room/', function (){

    /**
     * params {
     *      id_user: int
     *      name: str
     *      description: str
     *      max_players: int
     *      difficulty: int
     * }
     */
    $this->post('new', function ($req, $res, $args) {
        $rm = new RoomModel();
        
        return $res
           ->withHeader('Content-type', 'application/json')
           ->getBody()
           ->write(
            json_encode(
                $rm->newRoom(
                    $req->getParsedBody()
                )
            )
        );
    });

    $this->get('getAll', function ($req, $res, $args) {
        $rm = new RoomModel();
        
        return $res
           ->withHeader('Content-type', 'application/json')
           ->getBody()
           ->write(
            json_encode(
                $rm->getAllRoom()
            )
        );
    });

    $this->put('enter/{id_user}&{id_room}', function ($req, $res, $args) {
        $rm = new RoomModel();
        
        return $res
           ->withHeader('Content-type', 'application/json')
           ->getBody()
           ->write(
            json_encode(
                $rm->enterRoom($args['id_user'], $args['id_room'])
            )
        );
    });

    $this->put('leave/{id_user}&{id_room}', function ($req, $res, $args) {
        $rm = new RoomModel();
        
        return $res
           ->withHeader('Content-type', 'application/json')
           ->getBody()
           ->write(
            json_encode(
                $rm->leaveRoom($args['id_user'], $args['id_room'])
            )
        );
    });
});
