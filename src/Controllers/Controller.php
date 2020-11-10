<?php

namespace SRC\Controllers;

use SRC\Connection\Connection;
use SRC\Models\Model;

class Controller 
{
    private $response;

    public function __construct()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'GET') {

            $page = (empty($_GET['page']) ? 1 : (int)$_GET['page']);
            $limit = (empty($_GET['limit']) ? 100 : (int)$_GET['limit']);
            $data = json_decode(file_get_contents('php://input'), true);

            $model = new Model(Connection::getInstance(), $data, $page, $limit);
            $this->response = $model->getRespose();
        } else {
            header('HTTP/1.1 401 Unauthorized');
            $this->response = json_encode(array("response" => "Método não previsto na API"));  
        }
    }

    public function getRespose()
    {
        return $this->response;
    }
}
