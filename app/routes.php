<?php

declare(strict_types=1);

use App\Application\Actions\User\ListUsersAction;
use App\Application\Actions\User\ViewUserAction;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;
use Slim\Interfaces\RouteCollectorProxyInterface as Group;

require 'controllers/controller.php';

return function (App $app) {

    //CRUD TABEL PRODUK
    $app->post('/produk', 'App\Controller\ProdukController:addProduk');
    $app->get('/produk', 'App\Controller\ProdukController:getAllProduk');
    $app->put('/produk/{id}', 'App\Controller\ProdukController:editProductStock');
    $app->delete('/produk/{id}', 'App\Controller\ProdukController:deleteProductById');

    //CRUD TABEL PENGGUNA
    $app->post('/pengguna', 'App\Controller\PenggunaController:createPengguna');
    $app->get('/pengguna', 'App\Controller\PenggunaController:getAllPengguna');
    $app->put('/pengguna/{id}', 'App\Controller\PenggunaController:editNamaPengguna');
    $app->delete('/pengguna/{id}', 'App\Controller\PenggunaController:deletePenggunaById');

    //CRUD TABEL DETAIL_PEMBELIAN
    $app->post('/detail_pembelian', 'App\Controller\DetailPembelianController:createDetailPembelian');
    $app->get('/detail_pembelian', 'App\Controller\DetailPembelianController:getAllDetailPembelian');
    $app->put('/detail_pembelian/{id}', 'App\Controller\DetailPembelianController:editDetailPembelian');
    $app->delete('/detail_pembelian/{id}', 'App\Controller\DetailPembelianController:deleteDetailPembelianById');

    //CRUD TABEL HARGATOTAL
    $app->post('/hargatotal', 'App\Controller\HargaTotalController:createHargaTotal');
    $app->get('/hargatotal', 'App\Controller\HargaTotalController:getAllHargaTotal');
    $app->put('/hargatotal/{id_pembeli}', 'App\Controller\HargaTotalController:updateTotalHarga');
    $app->delete('/hargatotal/{id}', 'App\Controller\HargaTotalController:deleteHargaTotalById');

    $app->get('/produk/{id}', 'App\Controller\ProdukController:getProdukById');
};
