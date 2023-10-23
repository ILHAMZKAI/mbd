<?php

declare(strict_types=1);

use App\Application\Actions\User\ListUsersAction;
use App\Application\Actions\User\ViewUserAction;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;
use Slim\Interfaces\RouteCollectorProxyInterface as Group;

return function (App $app) {

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
        $total_harga = $data['total_harga'];

        $query = $db->prepare('CALL CreatePurchaseDetail(:in_id, :in_id_pembelian, :in_id_produk, :in_jumlah)');
        $query->bindParam(':in_id', $id, PDO::PARAM_INT);
        $query->bindParam(':in_id_pembelian', $id_pembeli, PDO::PARAM_INT);
        $query->bindParam(':in_id_produk', $id_produk, PDO::PARAM_INT);
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

    // //get tabel produk by id
    // $app->get('/produk/{id}', function (Request $request, Response $response, $args) {
    //     $db = $this->get(PDO::class);
    //     $query = $db->prepare('CALL selectProdukById(:id_produk)');
    //     $query->bindParam(':id_produk', $args['id'], PDO::PARAM_INT);
    //     $query->execute();
    //     $results = $query->fetchAll(PDO::FETCH_ASSOC);
    //     $response->getBody()->write(json_encode($results[0]));

    //     return $response->withHeader("Content-Type", "application/json");
    // });

    //post
    // $app->post('/produk', function (Request $request, Response $response) {
    //     $parsedBody = $request->getParsedBody();

    //     $id = $parsedBody["id"];
    //     $brand = $parsedBody["brand"];
    //     $model = $parsedBody["model"];
    //     $harga = $parsedBody["harga"];
    //     $stock = $parsedBody["stock"];

    //     $db = $this->get(PDO::class);
    //     $query = $db->prepare('INSERT INTO produk (id, brand, model, harga, stock) VALUES (:id, :brand, :model, :harga, :stock)');
    //     $query->execute([
    //         ':id' => $id,
    //         ':brand' => $brand,
    //         ':model' => $model,
    //         ':harga' => $harga,
    //         ':stock' => $stock
    //     ]);

    //     $response->getBody()->write(json_encode(
    //         [
    //             'message' => 'Data produk berhasil ditambahkan'
    //         ]
    //     ));
    //     return $response->withHeader("Content-Type", "application/json");
    // });

    // //put data
    // $app->put('/produk/{id}', function (Request $request, Response $response, $args) {
    //     $id = $args['id'];
    //     $parsedBody = $request->getParsedBody();

    //     // Validasi masukan
    //     if (empty($parsedBody) || !isset($parsedBody['harga_baru'])) {
    //         $response->getBody()->write(json_encode(['error' => 'Parameter harga_baru harus disertakan dalam body permintaan.']));
    //         return $response->withStatus(400)->withHeader("Content-Type", "application/json");
    //     }

    //     $harga_baru = $parsedBody["harga_baru"];

    //     $db = $this->get(PDO::class);

    //     // Persiapkan pernyataan SQL untuk memperbarui kolom harga_baru
    //     $query = $db->prepare('UPDATE produk SET harga_baru = :harga_baru WHERE id = :id');
    //     $query->bindParam(':id', $id, PDO::PARAM_INT);
    //     $query->bindParam(':harga_baru', $harga_baru, PDO::PARAM_INT);

    //     // Eksekusi pernyataan SQL
    //     if ($query->execute()) {
    //         $response->getBody()->write(json_encode(
    //             [
    //                 'message' => 'Harga produk dengan ID ' . $id . ' telah diupdate dengan harga baru ' . $harga_baru . ''
    //             ]
    //         ));
    //         return $response->withHeader("Content-Type", "application/json");
    //     } else {
    //         $response->getBody()->write(json_encode(['error' => 'Gagal memperbarui data produk.']));
    //         return $response->withStatus(500)->withHeader("Content-Type", "application/json");
    //     }
    // });


    // //delete data
    // $app->delete('/produk/{id}', function (Request $request, Response $response, $args) {
    //     $id = $args['id'];

    //     $db = $this->get(PDO::class);
    //     $query = $db->prepare('DELETE FROM produk WHERE id = :id');
    //     $query->execute([':id' => $id]);

    //     $response->getBody()->write(json_encode(
    //         [
    //             'message' => 'produk dengan id ' . $id . ' di hapus dari database'
    //         ]
    //     ));
    //     return $response->withHeader("Content-Type", "application/json");
    // });
};
