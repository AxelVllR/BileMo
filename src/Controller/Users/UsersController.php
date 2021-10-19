<?php

namespace App\Controller\Users;

use App\Api\ApiHelper;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\UsersRepository;


class UsersController extends AbstractController {

    private ApiHelper $api;
    private UsersRepository $uRepo;


    public function __construct(UsersRepository $uRepo, ApiHelper $api)
    {
        $this->api = $api;
        $this->uRepo = $uRepo;    
    }

    /**
     * @Route(
     *     "/api/users",
     *     name="api_users_show",
     *     methods={"GET"}
     * )
     */
    public function showUsers()
    {
        $client = $this->getUser();
        $users = $this->uRepo->findByClient($client);
        $usersNormalized = $this->api->normalizeUsers($users);
        return $this->api->response($usersNormalized, 200);
    }

}