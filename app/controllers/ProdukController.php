<?php

namespace App\Controller;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use PDO;

//produk ================================================================================================================
class ProdukController
{
    private $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    //create produk (baru)
    public function addProduk(Request $request, Response $response)
    {
        $data = $request->getParsedBody();
        $id = $data['id'];
        $brand = $data['brand'];
        $model = $data['model'];
        $harga = $data['harga'];
        $stock = $data['stock'];

        $query = $this->db->prepare('CALL AddProduk(:id_param, :brand_param, :model_param, :harga_param, :stock_param)');
        $query->bindParam(':id_param', $id, PDO::PARAM_INT);
        $query->bindParam(':brand_param', $brand, PDO::PARAM_STR);
        $query->bindParam(':model_param', $model, PDO::PARAM_STR);
        $query->bindParam(':harga_param', $harga, PDO::PARAM_INT);
        $query->bindParam(':stock_param', $stock, PDO::PARAM_INT);
        $query->execute();

        $response->getBody()->write(json_encode([
            'message' => 'data produk berhasil ditambahkan'
        ]));

        return $response->withHeader("Content-Type", "application/json");
    }

    //read produk (all)
    public function getAllProduk(Request $request, Response $response)
    {
        $query = $this->db->query('CALL selectProduk()');
        $results = $query->fetchAll(PDO::FETCH_ASSOC);

        $response->getBody()->write(json_encode($results));
        return $response->withHeader("Content-Type", "application/json");
    }

    //read tabel produk by id
    public function getProdukById(Request $request, Response $response, $args)
    {
        $query = $this->db->prepare('CALL selectProdukById(:id_produk)');
        $query->bindParam(':id_produk', $args['id'], PDO::PARAM_INT);
        $query->execute();
        $results = $query->fetchAll(PDO::FETCH_ASSOC);

        $response->getBody()->write(json_encode($results[0]));
        return $response->withHeader("Content-Type", "application/json");
    }

    //update produk (update stock by id)
    public function editProductStock(Request $request, Response $response, $args)
    {
        $idproduk = $args['id'];

        $data = $request->getParsedBody();
        $stock = $data['stock'];

        $query = $this->db->prepare("CALL EditProductStock(:productID, :newStock)");
        $query->bindParam(':productID', $idproduk, PDO::PARAM_INT);
        $query->bindParam(':newStock', $stock, PDO::PARAM_INT);
        $query->execute();

        $response->getBody()->write(json_encode([
            'message' => 'stok produk berhasil diubah'
        ]));

        return $response->withHeader("Content-Type", "application/json");
    }

    //delete produk (delete produk by id)
    public function deleteProductById(Request $request, Response $response, $args)
    {
        $productid = $args['id'];

        $query = $this->db->prepare('CALL deleteProductByID(:product_id)');
        $query->bindParam(':product_id', $productid, PDO::PARAM_INT);
        $query->execute();

        $response->getBody()->write(json_encode([
            'message' => 'produk berhasil dihapus berdasarkan id'
        ]));

        return $response->withHeader("Content-Type", "application/json");
    }
}