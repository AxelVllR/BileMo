<?php

namespace App\Controller\Products;

use App\Api\ApiHelper;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ProductsRepository;


class ProductsController extends AbstractController {

    private ApiHelper $api;
    private ProductsRepository $pRepo;


    public function __construct(ProductsRepository $pRepo, ApiHelper $api)
    {
        $this->api = $api;
        $this->pRepo = $pRepo;    
    }

    /**
     * @Route(
     *     "/api/products",
     *     name="api_products_show",
     *     methods={"GET"}
     * )
     */
    public function showProducts()
    {
        $products = $this->pRepo->findAll();
        $productsNormalized = $this->api->normalizeProducts($products);
        return $this->api->response($productsNormalized, 200);
    }

}