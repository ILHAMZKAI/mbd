<?php

declare(strict_types=1);

use App\Application\Actions\User\ListUsersAction;
use App\Application\Actions\User\ViewUserAction;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;
use Slim\Interfaces\RouteCollectorProxyInterface as Group;

return function (App $app) {

    //get tabel produk by id
    $app->get('/produk/{id}', function (Request $request, Response $response, $args) {
        $db = $this->get(PDO::class);
        $query = $db->prepare('CALL selectProdukById(:id_produk)');
        $query->bindParam(':id_produk', $args['id'], PDO::PARAM_INT);
        $query->execute();
        $results = $query->fetchAll(PDO::FETCH_ASSOC);
        $response->getBody()->write(json_encode($results[0]));

        return $response->withHeader("Content-Type", "application/json");
    });

    //produk
    //create produk (create produk baru)
    $app->post('/produk', function (Request $request, Response $response) {
        $db = $this->get(PDO::class);

        $data = $request->getParsedBody();
        $id = $data['id'];
        $brand = $data['brand'];
        $model = $data['model'];
        $harga = $data['harga'];
        $stock = $data['stock'];

        $query = $db->prepare('CALL AddProduk(:id_param, :brand_param, :model_param, :harga_param, :stock_param)');
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
    });

    //read produk (read all produk)
    $app->get('/produk', function (Request $request, Response $response) {
        $db = $this->get(PDO::class);

        $query = $db->query('CALL selectProduk()');
        $results = $query->fetchAll(PDO::FETCH_ASSOC);
        $response->getBody()->write(json_encode($results));

        return $response->withHeader("Content-Type", "application/json");
    });

    //update produk (update stock by id)
    $app->put('/produk/{id}', function (Request $request, Response $response, $args) {
        $db = $this->get(PDO::class);
        $idproduk = $args['id'];

        $data = $request->getParsedBody();
        $stock = $data['stock'];

        $query = $db->prepare("CALL EditProductStock(:productID, :newStock)");
        $query->bindParam(':productID', $idproduk, PDO::PARAM_INT);
        $query->bindParam(':newStock', $stock, PDO::PARAM_INT);
        $query->execute();

        $response->getBody()->write(json_encode([
            'message' => 'stok produk berhasil diubah'
        ]));

        return $response->withHeader("Content-Type", "application/json");
    });

    //delete produk (delete produk by id)
    $app->delete('/produk/{id}', function (Request $request, Response $response, $args) {
        $db = $this->get(PDO::class);
        $productid = $args['id'];

        $query = $db->prepare('CALL deleteProductByID(:product_id)');
        $query->bindParam(':product_id', $productid, PDO::PARAM_INT);
        $query->execute();

        $response->getBody()->write(json_encode([
            'message' => 'produk berhasil dihapus berdasarkan id'
        ]));

        return $response->withHeader("Content-Type", "application/json");
    });
    //================================================================================================================
    //detail_pembelian
    //create detail_pembelian (create detail_pembelian baru)
    $app->post('/detail_pembelian', function (Request $request, Response $response) {
        $db = $this->get(PDO::class);

        $data = $request->getParsedBody();
        $id = $data['id'];
        $pembelian = $data['id_pembelian'];
        $produk = $data['id_produk'];
        $jumlah = $data['jumlah'];

        $query = $db->prepare('CALL CreatePurchaseDetail(:in_id, :in_id_pembelian, :in_id_produk, :in_jumlah)');
        $query->bindParam(':in_id', $id, PDO::PARAM_INT);
        $query->bindParam(':in_id_pembelian', $pembelian, PDO::PARAM_STR);
        $query->bindParam(':in_id_produk', $produk, PDO::PARAM_STR);
        $query->bindParam(':in_jumlah', $jumlah, PDO::PARAM_INT);
        $query->execute();

        $response->getBody()->write(json_encode([
            'message' => 'data detail_pembelian berhasil ditambahkan'
        ]));

        return $response->withHeader("Content-Type", "application/json");
    });

    //read detail_pembelian (read all produk)
    $app->get('/detail_pembelian', function (Request $request, Response $response) {
        $db = $this->get(PDO::class);

        $query = $db->query('CALL selectDetailPembelian()');
        $results = $query->fetchAll(PDO::FETCH_ASSOC);
        $response->getBody()->write(json_encode($results));

        return $response->withHeader("Content-Type", "application/json");
    });

    //update detail_pembelian (update jumlah by id)
    $app->put('/detail_pembelian/{id}', function (Request $request, Response $response, $args) {
        $db = $this->get(PDO::class);
        $idpembelian = $args['id'];

        $data = $request->getParsedBody();
        $jumlah = $data['jumlah'];

        $query = $db->prepare("CALL EditJumlahPembelian(:IdPembelian, :newJumlah)");
        $query->bindParam(':IdPembelian', $idpembelian, PDO::PARAM_INT);
        $query->bindParam(':newJumlah', $jumlah, PDO::PARAM_INT);
        $query->execute();

        $response->getBody()->write(json_encode([
            'message' => 'jumlah pembelian berhasil diubah'
        ]));

        return $response->withHeader("Content-Type", "application/json");
    });

    //delete detail_pembelian (delete detail_pembelian by id)
    $app->delete('/detail_pembelian/{id}', function (Request $request, Response $response, $args) {
        $db = $this->get(PDO::class);
        $detailid = $args['id'];

        $query = $db->prepare('CALL DeletePurchaseDetailByID(:detail_pembelian_id)');
        $query->bindParam(':detail_pembelian_id', $detailid, PDO::PARAM_INT);
        $query->execute();

        $response->getBody()->write(json_encode([
            'message' => 'detail_pembelian berhasil dihapus berdasarkan id'
        ]));

        return $response->withHeader("Content-Type", "application/json");
    });
    //================================================================================================================
    //hargatotal
    //create hargatotal (create hargatotal baru)
    $app->post('/hargatotal', function (Request $request, Response $response) {
        $db = $this->get(PDO::class);

        $data = $request->getParsedBody();
        $id = $data['id'];
        $id_pembeli = $data['id_pembeli'];
        $id_produk = $data['id_produk'];
        $jumlah_pem = $data['jumlah_pembelian'];
        $total_harga = $data['total_harga'];

        $query = $db->prepare('CALL CreateHargaTotal(:in_id, :in_id_pembelian, :in_id_produk, :in_jumlah_pem, :in_jumlah)');
        $query->bindParam(':in_id', $id, PDO::PARAM_INT);
        $query->bindParam(':in_id_pembelian', $id_pembeli, PDO::PARAM_INT);
        $query->bindParam(':in_id_produk', $id_produk, PDO::PARAM_INT);
        $query->bindParam(':in_jumlah_pem', $jumlah_pem, PDO::PARAM_INT);
        $query->bindParam(':in_jumlah', $total_harga, PDO::PARAM_INT);
        $query->execute();

        $response->getBody()->write(json_encode([
            'message' => 'Data hargatotal berhasil ditambahkan'
        ]));

        return $response->withHeader("Content-Type", "application/json");
    });

    //read hargatotal (read all produk)
    $app->get('/hargatotal', function (Request $request, Response $response) {
        $db = $this->get(PDO::class);

        $query = $db->query('CALL selectHargaTotal()');
        $results = $query->fetchAll(PDO::FETCH_ASSOC);
        $response->getBody()->write(json_encode($results));

        return $response->withHeader("Content-Type", "application/json");
    });

    //update hargatotal (update total harga by id pembeli)
    $app->put('/hargatotal/{id_pembeli}', function (Request $request, Response $response, $args) {
        $db = $this->get(PDO::class);
        $idpembeli = $args['id_pembeli'];

        $data = $request->getParsedBody();
        $tharga = $data['total_harga'];

        $query = $db->prepare("CALL UpdateTotalHargaByIdPembeli(:pembeli_id, :new_total_harga)");
        $query->bindParam(':pembeli_id', $idpembeli, PDO::PARAM_INT);
        $query->bindParam(':new_total_harga', $tharga, PDO::PARAM_INT);
        $query->execute();

        $response->getBody()->write(json_encode([
            'message' => 'total harga berhasil diubah'
        ]));

        return $response->withHeader("Content-Type", "application/json");
    });

    //delete hargatotal (delete hargatotal by id)
    $app->delete('/hargatotal/{id}', function (Request $request, Response $response, $args) {
        $db = $this->get(PDO::class);
        $detailid = $args['id'];

        $query = $db->prepare('CALL deleteHargaTotalByID(:harga_total_id)');
        $query->bindParam(':harga_total_id', $detailid, PDO::PARAM_INT);
        $query->execute();

        $response->getBody()->write(json_encode([
            'message' => 'hargatotal berhasil dihapus berdasarkan id'
        ]));

        return $response->withHeader("Content-Type", "application/json");
    });
    //================================================================================================================
    //pengguna
    //create pengguna (create pengguna baru)
    $app->post('/pengguna', function (Request $request, Response $response) {
        $db = $this->get(PDO::class);

        $data = $request->getParsedBody();
        $id = $data['id'];
        $nama = $data['nama'];
        $email = $data['email'];
        $phone = $data['phone'];

        $query = $db->prepare('CALL CreateUser(:in_id, :in_nama, :in_email, :in_phone)');
        $query->bindParam(':in_id', $id, PDO::PARAM_INT);
        $query->bindParam(':in_nama', $nama, PDO::PARAM_STR);
        $query->bindParam(':in_email', $email, PDO::PARAM_STR);
        $query->bindParam(':in_phone', $phone, PDO::PARAM_INT);
        $query->execute();

        $response->getBody()->write(json_encode([
            'message' => 'Data pengguna berhasil ditambahkan'
        ]));
        return $response->withHeader("Content-Type", "application/json");
    });

    //read pengguna (read all produk)
    $app->get('/pengguna', function (Request $request, Response $response) {
        $db = $this->get(PDO::class);

        $query = $db->query('CALL selectPengguna()');
        $results = $query->fetchAll(PDO::FETCH_ASSOC);
        $response->getBody()->write(json_encode($results));

        return $response->withHeader("Content-Type", "application/json");
    });

    //update pengguna (update nama pengguna by id)
    $app->put('/pengguna/{id}', function (Request $request, Response $response, $args) {
        $db = $this->get(PDO::class);
        $idpengguna = $args['id'];

        $data = $request->getParsedBody();
        $name = $data['nama'];

        $query = $db->prepare("CALL EditNama(:id_user, :newNama)");
        $query->bindParam(':id_user', $idpengguna, PDO::PARAM_INT);
        $query->bindParam(':newNama', $name, PDO::PARAM_STR);
        $query->execute();

        $response->getBody()->write(json_encode([
            'message' => 'nama pengguna berhasil diubah'
        ]));

        return $response->withHeader("Content-Type", "application/json");
    });

    //delete pengguna (delete pengguna by id)
    $app->delete('/pengguna/{id}', function (Request $request, Response $response, $args) {
        $db = $this->get(PDO::class);
        $detailid = $args['id'];

        $query = $db->prepare('CALL DeleteUserByID(:user_id)');
        $query->bindParam(':user_id', $detailid, PDO::PARAM_INT);
        $query->execute();

        $response->getBody()->write(json_encode([
            'message' => 'pengguna berhasil dihapus berdasarkan id'
        ]));

        return $response->withHeader("Content-Type", "application/json");
    });
};
