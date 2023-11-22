<?php

namespace App\Controller;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use PDO;

//log ================================================================================================================
class LogController
{
    private $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    //read log (all)
    public function getAllLog(Request $request, Response $response)
    {
        $query = $this->db->query('CALL selectLog()');
        $results = $query->fetchAll(PDO::FETCH_ASSOC);

        $response->getBody()->write(json_encode($results));
        return $response->withHeader("Content-Type", "application/json");
    }

    //read tabel Log by id
    public function getLogById(Request $request, Response $response, $args)
    {
        $query = $this->db->prepare('CALL getLogById(:id_param)');
        $query->bindParam(':id_param', $args['id'], PDO::PARAM_INT);
        $query->execute();
        $results = $query->fetchAll(PDO::FETCH_ASSOC);

        $response->getBody()->write(json_encode($results[0]));
        return $response->withHeader("Content-Type", "application/json");
    }

    //delete log (delete log by id)
    public function deleteLogById(Request $request, Response $response, $args)
    {
        $detailid = $args['id'];

        $query = $this->db->prepare('CALL deleteLogById(:log_id)');
        $query->bindParam(':log_id', $detailid, PDO::PARAM_INT);
        $query->execute();

        $response->getBody()->write(json_encode([
            'message' => 'log berhasil dihapus berdasarkan id'
        ]));

        return $response->withHeader("Content-Type", "application/json");
    }

    //delete log (all)
    public function deleteAllLog(Request $request, Response $response, $args)
    {
        $query = $this->db->prepare('CALL deleteAllLog()');
        $query->execute();

        $response->getBody()->write(json_encode([
            'message' => 'log berhasil dihapus berdasarkan id'
        ]));

        return $response->withHeader("Content-Type", "application/json");
    }
}