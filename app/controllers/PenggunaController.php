<?php

namespace App\Controller;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use PDO;

//pengguna ================================================================================================================
class PenggunaController
{
    private $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    //create pengguna (baru)
    public function createPengguna(Request $request, Response $response)
    {
        $data = $request->getParsedBody();
        $id = $data['id'];
        $nama = $data['nama'];
        $email = $data['email'];
        $phone = $data['phone'];

        $query = $this->db->prepare('CALL CreateUser(:in_id, :in_nama, :in_email, :in_phone)');
        $query->bindParam(':in_id', $id, PDO::PARAM_INT);
        $query->bindParam(':in_nama', $nama, PDO::PARAM_STR);
        $query->bindParam(':in_email', $email, PDO::PARAM_STR);
        $query->bindParam(':in_phone', $phone, PDO::PARAM_INT);
        $query->execute();

        $response->getBody()->write(json_encode([
            'message' => 'data pengguna berhasil ditambahkan'
        ]));
        return $response->withHeader("Content-Type", "application/json");
    }

    //read pengguna (all)
    public function getAllPengguna(Request $request, Response $response)
    {
        $query = $this->db->query('CALL selectPengguna()');
        $results = $query->fetchAll(PDO::FETCH_ASSOC);

        $response->getBody()->write(json_encode($results));
        return $response->withHeader("Content-Type", "application/json");
    }

    //read tabel pengguna by id
    public function getPenggunaById(Request $request, Response $response, $args)
    {
        $query = $this->db->prepare('CALL selectPenggunaById(:idpengguna)');
        $query->bindParam(':idpengguna', $args['id'], PDO::PARAM_INT);
        $query->execute();
        $results = $query->fetchAll(PDO::FETCH_ASSOC);

        $response->getBody()->write(json_encode($results[0]));
        return $response->withHeader("Content-Type", "application/json");
    }

    //update pengguna (update nama by id)
    public function editNamaPengguna(Request $request, Response $response, $args)
    {
        $idpengguna = $args['id'];

        $data = $request->getParsedBody();
        $name = $data['nama'];

        $query = $this->db->prepare("CALL EditNama(:id_user, :newNama)");
        $query->bindParam(':id_user', $idpengguna, PDO::PARAM_INT);
        $query->bindParam(':newNama', $name, PDO::PARAM_STR);
        $query->execute();

        $response->getBody()->write(json_encode([
            'message' => 'nama pengguna berhasil diubah'
        ]));

        return $response->withHeader("Content-Type", "application/json");
    }

    //delete pengguna (delete pengguna by id)
    public function deletePenggunaById(Request $request, Response $response, $args)
    {
        $detailid = $args['id'];

        $query = $this->db->prepare('CALL DeleteUserByID(:user_id)');
        $query->bindParam(':user_id', $detailid, PDO::PARAM_INT);
        $query->execute();

        $response->getBody()->write(json_encode([
            'message' => 'pengguna berhasil dihapus berdasarkan id'
        ]));

        return $response->withHeader("Content-Type", "application/json");
    }
}