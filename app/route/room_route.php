<?php

use App\Room\RoomModel;

$app->group('/room/', function (){
    $this->post('new', function ($req, $res, $args) {
        $um = new RoomModel();
        
        return $res
           ->withHeader('Content-type', 'application/json')
           ->getBody()
           ->write(
            json_encode(
                $um->newRoom(
                    $req->getParsedBody()
                )
            )
        );
    });
}); 
