<?php

namespace App\Controller;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use PDO;

//detail_pembelian ================================================================================================================
class DetailPembelianController
{
    private $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    //create detail_pembelian (baru)
    public function createDetailPembelian(Request $request, Response $response)
    {
        $data = $request->getParsedBody();
        $id = $data['id'];
        $pembelian = $data['id_pembelian'];
        $produk = $data['id_produk'];
        $jumlah = $data['jumlah'];

        $query = $this->db->prepare('CALL CreatePurchaseDetail(:in_id, :in_id_pembelian, :in_id_produk, :in_jumlah)');
        $query->bindParam(':in_id', $id, PDO::PARAM_INT);
        $query->bindParam(':in_id_pembelian', $pembelian, PDO::PARAM_STR);
        $query->bindParam(':in_id_produk', $produk, PDO::PARAM_STR);
        $query->bindParam(':in_jumlah', $jumlah, PDO::PARAM_INT);
        $query->execute();

        $response->getBody()->write(json_encode([
            'message' => 'data detail_pembelian berhasil ditambahkan'
        ]));

        return $response->withHeader("Content-Type", "application/json");
    }

    //read detail_pembelian (all)
    public function getAllDetailPembelian(Request $request, Response $response)
    {
        $query = $this->db->query('CALL selectDetailPembelian()');
        $results = $query->fetchAll(PDO::FETCH_ASSOC);

        $response->getBody()->write(json_encode($results));
        return $response->withHeader("Content-Type", "application/json");
    }

    //read tabel detail_pembelian by id
    public function getPembelianById(Request $request, Response $response, $args)
    {
        $query = $this->db->prepare('CALL selectPembelianById(:pembelian_id)');
        $query->bindParam(':pembelian_id', $args['id'], PDO::PARAM_INT);
        $query->execute();
        $results = $query->fetchAll(PDO::FETCH_ASSOC);

        $response->getBody()->write(json_encode($results[0]));
        return $response->withHeader("Content-Type", "application/json");
    }

    //update detail_pembelian (update jumlah by id)
    public function editDetailPembelian(Request $request, Response $response, $args)
    {
        $idpembelian = $args['id'];

        $data = $request->getParsedBody();
        $jumlah = $data['jumlah'];

        $query = $this->db->prepare("CALL EditJumlahPembelian(:IdPembelian, :newJumlah)");
        $query->bindParam(':IdPembelian', $idpembelian, PDO::PARAM_INT);
        $query->bindParam(':newJumlah', $jumlah, PDO::PARAM_INT);
        $query->execute();

        $response->getBody()->write(json_encode([
            'message' => 'jumlah pembelian berhasil diubah'
        ]));

        return $response->withHeader("Content-Type", "application/json");
    }

    //delete detail_pembelian (delete detail_pembelian by id)
    public function deleteDetailPembelianById(Request $request, Response $response, $args)
    {
        $detailid = $args['id'];

        $query = $this->db->prepare('CALL DeletePurchaseDetailByID(:detail_pembelian_id)');
        $query->bindParam(':detail_pembelian_id', $detailid, PDO::PARAM_INT);
        $query->execute();

        $response->getBody()->write(json_encode([
            'message' => 'detail_pembelian berhasil dihapus berdasarkan id'
        ]));

        return $response->withHeader("Content-Type", "application/json");
    }
}