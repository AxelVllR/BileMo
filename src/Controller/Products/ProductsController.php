<?php

namespace App\Controller\Products;

use App\Api\ApiHelper;
use App\Api\PaginatorApi;
use App\Entity\Products;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ProductsRepository;
use Symfony\Component\HttpFoundation\Request;
use OpenApi\Annotations as OA;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;

class ProductsController extends AbstractController {

    private ApiHelper $api;
    private ProductsRepository $pRepo;
    private PaginatorApi $paginatorApi;

    public function __construct(ProductsRepository $pRepo, ApiHelper $api, PaginatorApi $paginatorApi)
    {
        $this->paginatorApi = $paginatorApi;
        $this->api = $api;
        $this->pRepo = $pRepo;    
    }

    /**
     * @Route(
     *     "/api/products",
     *     name="api_products_show",
     *     methods={"GET"}
     * )
     * 
     * @OA\Response(
     *     response=200,
     *     description="Returns Products collection (paginated)",
     *     @OA\JsonContent(
     *     )
     * )
     * 
     * @OA\Parameter(
     *     name="page",
     *     in="query",
     *     description="Page you need (leave empty if you want the first one)",
     *     @OA\Schema(type="int")
     * )
     * 
     * @OA\Tag(name="Products")
     * 
     */
    public function showProducts(Request $request)
    {
        $query = $this->pRepo->findAllQuery();

        $products = $this->paginatorApi->paginate(
            $request,
            $query
        );

        if($products instanceof Response) {
            return $products;
        }

        $productsNormalized = $this->api->normalizeProducts($products->getItems());

        array_unshift(
            $productsNormalized, 
            $products->getInfos()
        );

        return $this->api->response($productsNormalized, 200);
    }

    /**
     * @Route(
     *     "/api/products/{id}",
     *     name="api_product_show",
     *     methods={"GET"}
     * )
     * 
     * @OA\Response(
     *     response=200,
     *     description="Returns Product Entity",
     *     @OA\JsonContent(
     *          type="array",
     *          @OA\Items(ref=@Model(type=Products::class, groups={"product"}))
     *     )
     * )
     * 
     * @OA\Tag(name="Products")
     * 
     */
    public function showProduct($id)
    {
        $product = $this->pRepo->findOneBy(["id" => $id]);
        if(!empty($product)) {
            $productsNormalized = $this->api->normalizeProduct($product);
            return $this->api->response($productsNormalized, 200);
        }
        return $this->api->notFoundResonse();
    }

}