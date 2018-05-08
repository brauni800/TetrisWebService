<?php

use App\Room\RoomModel;

$app->group('/room/', function (){
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
});
