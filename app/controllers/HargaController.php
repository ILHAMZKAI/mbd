<?php

namespace App\Controller;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use PDO;

//hargatotal ================================================================================================================
class HargaTotalController
{
    private $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    //create hargatotal (baru)
    public function createHargaTotal(Request $request, Response $response)
    {
        $data = $request->getParsedBody();
        $id = $data['id'];
        $id_pembeli = $data['id_pembeli'];
        $id_produk = $data['id_produk'];
        $jumlah_pem = $data['jumlah_pembelian'];
        $total_harga = $data['total_harga'];

        $query = $this->db->prepare('CALL CreateHargaTotal(:in_id, :in_id_pembelian, :in_id_produk, :in_jumlah_pem, :in_jumlah)');
        $query->bindParam(':in_id', $id, PDO::PARAM_INT);
        $query->bindParam(':in_id_pembelian', $id_pembeli, PDO::PARAM_INT);
        $query->bindParam(':in_id_produk', $id_produk, PDO::PARAM_INT);
        $query->bindParam(':in_jumlah_pem', $jumlah_pem, PDO::PARAM_INT);
        $query->bindParam(':in_jumlah', $total_harga, PDO::PARAM_INT);
        $query->execute();

        $response->getBody()->write(json_encode([
            'message' => 'data hargatotal berhasil ditambahkan'
        ]));

        return $response->withHeader("Content-Type", "application/json");
    }

    //read hargatotal (all)
    public function getAllHargaTotal(Request $request, Response $response)
    {
        $query = $this->db->query('CALL selectHargaTotal()');
        $results = $query->fetchAll(PDO::FETCH_ASSOC);

        $response->getBody()->write(json_encode($results));
        return $response->withHeader("Content-Type", "application/json");
    }

    //read tabel hargatotal by id
    public function getHargaTotalById(Request $request, Response $response, $args)
    {
        $query = $this->db->prepare('CALL selectHargaTotalById(:idhargatotal)');
        $query->bindParam(':idhargatotal', $args['id'], PDO::PARAM_INT);
        $query->execute();
        $results = $query->fetchAll(PDO::FETCH_ASSOC);

        $response->getBody()->write(json_encode($results[0]));
        return $response->withHeader("Content-Type", "application/json");
    }

    //update hargatotal (update total harga by id pembeli)
    public function updateTotalHarga(Request $request, Response $response, $args)
    {
        $idpembeli = $args['id_pembeli'];

        $data = $request->getParsedBody();
        $tharga = $data['total_harga'];

        $query = $this->db->prepare("CALL UpdateTotalHargaByIdPembeli(:pembeli_id, :new_total_harga)");
        $query->bindParam(':pembeli_id', $idpembeli, PDO::PARAM_INT);
        $query->bindParam(':new_total_harga', $tharga, PDO::PARAM_INT);
        $query->execute();

        $response->getBody()->write(json_encode([
            'message' => 'total harga berhasil diubah'
        ]));

        return $response->withHeader("Content-Type", "application/json");
    }

    //delete hargatotal (delete hargatotal by id)
    public function deleteHargaTotalById(Request $request, Response $response, $args)
    {
        $detailid = $args['id'];

        $query = $this->db->prepare('CALL deleteHargaTotalByID(:harga_total_id)');
        $query->bindParam(':harga_total_id', $detailid, PDO::PARAM_INT);
        $query->execute();

        $response->getBody()->write(json_encode([
            'message' => 'hargatotal berhasil dihapus berdasarkan id'
        ]));

        return $response->withHeader("Content-Type", "application/json");
    }
}