<?php

use App\User\UserModel;

$app->group('/user/', function () {

    $this->post('login', function ($req, $res, $args) {
        $um = new UserModel();
        
        return $res
           ->withHeader('Content-type', 'application/json')
           ->getBody()
           ->write(
            json_encode(
                $um->Login(
                    $req->getParsedBody()
                )
            )
        );
    });

    $this->post('signup', function($req, $res, $args) {
        $um = new UserModel();

        return $res
        ->withHeader('Content-type', 'application/json')
        ->getBody()
        ->write(
        json_encode(
                $um->Signup(
                    $req->getParsedBody()
                )
            )
        );
    });

    /**
     * Rutas para tomar de ejemplo
     */

    $this->get('test', function ($req, $res, $args) {
        return $res->getBody()
                   ->write('Hello Users');
    });

    $this->get('getAll', function ($req, $res, $args) {
        $um = new UserModel();
        
        return $res
           ->withHeader('Content-type', 'application/json')
           ->getBody()
           ->write(
            json_encode(
                $um->GetAll()
            )
        );
    });
    
    $this->get('get/{id}', function ($req, $res, $args) {
        $um = new UserModel();
        
        return $res
           ->withHeader('Content-type', 'application/json')
           ->getBody()
           ->write(
            json_encode(
                $um->Get($args['id'])
            )
        );
    });
    
    $this->post('save', function ($req, $res) {
        $um = new UserModel();
        
        return $res
           ->withHeader('Content-type', 'application/json')
           ->getBody()
           ->write(
            json_encode(
                $um->InsertOrUpdate(
                    $req->getParsedBody()
                )
            )
        );
    });
    
    $this->post('delete/{id}', function ($req, $res, $args) {
        $um = new UserModel();
        
        return $res
           ->withHeader('Content-type', 'application/json')
           ->getBody()
           ->write(
            json_encode(
                $um->Delete($args['id'])
            )
        );
    });
    
});
