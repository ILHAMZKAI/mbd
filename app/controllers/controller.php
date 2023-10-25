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
