<?php

namespace App\Controller\Users;

use App\Api\ApiHelper;
use App\Api\FormHelper;
use App\Api\PaginatorApi;
use App\Entity\Users;
use App\Form\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\UsersRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use OpenApi\Annotations as OA;
use OpenApi\Annotations\RequestBody;
use Nelmio\ApiDocBundle\Annotation\Model;


class UsersController extends AbstractController {

    private ApiHelper $api;
    private UsersRepository $uRepo;
    private EntityManagerInterface $em;
    private PaginatorApi $paginatorApi;

    public function __construct(UsersRepository $uRepo, ApiHelper $api, EntityManagerInterface $em, PaginatorApi $paginatorApi)
    {
        $this->paginatorApi = $paginatorApi;
        $this->em = $em;
        $this->api = $api;
        $this->uRepo = $uRepo;    
    }

    /**
     * @Route(
     *     "/api/users",
     *     name="api_users_show",
     *     methods={"GET"}
     * )
     * 
     * @OA\Response(
     *     response=200,
     *     description="Returns Users collection (paginated)",
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
     * @OA\Tag(name="Users")
     */
    public function showUsers(Request $request)
    {
        $client = $this->getUser();
        $query = $this->uRepo->findByClient($client);

        $users = $this->paginatorApi->paginate(
            $request,
            $query
        );

        if($users instanceof Response) {
            return $users;
        }

        $usersNormalized = $this->api->normalizeUsers($users->getItems());

        array_unshift(
            $usersNormalized, 
            $users->getInfos()
        );

        return $this->api->response($usersNormalized, 200);
    }

    /**
     * @Route(
     *     "/api/users/{id}",
     *     name="api_user_show",
     *     methods={"GET"}
     * )
     * 
     * @OA\Response(
     *     response=200,
     *     description="Returns Users entity",
     *     @OA\JsonContent(
     *          type="array",
     *          @OA\Items(ref=@Model(type=Users::class, groups={"user:read"}))
     *     )
     * )

     * 
     * @OA\Tag(name="Users")
     */
    public function showUser($id)
    {
        $client = $this->getUser();
        $user = $this->uRepo->findOneByClient($client, $id);
        if(!empty($user)) {
            $userNormalized = $this->api->normalizeUser($user);
            return $this->api->response($userNormalized, 200);
        }
        return $this->api->notFoundResonse();
    }
    
    /**
     * @Route(
     *     "/api/users",
     *     name="api_user_add",
     *     methods={"POST"}
     * )
     * 
     * @OA\Response(
     *     response=201,
     *     description="Add User with all these informations (you can add 'phone_number' if you want to)",
     *     @OA\JsonContent(
     *     )
     * )
     * 
     * @OA\RequestBody(
     *      @Model(type=Users::class, groups={"user:add"}))
     * )
     * 
     * @OA\Tag(name="Users")
     * 
     */
    public function postUser(Request $request)
    {
        $data = json_decode($request->getContent(), true);

        $client = $this->getUser();
        $user = (new Users())->setClient($client);

        $userForm = $this->createForm(UserType::class, $user);
        $userForm->submit($data);
        

        if($userForm->isValid()) {
            $this->em->persist($user);
            $this->em->flush();
            $user = $this->api->normalizeUser($user);
            return $this->api->createdResponse($user);
        } else {
            $errors = FormHelper::getErrors($userForm);
            return $this->api->badRequest($errors);
        }
    
    }

    /**
     * @Route(
     *     "/api/users/{id}",
     *     name="api_user_patch",
     *     methods={"PUT"}
     * )
     * 
     * @OA\Response(
     *     response=200,
     *     description="Update User with all these informations (you can add 'phone_number' if you want to)",
     *     @OA\JsonContent(
     *          type="array",
     *          @OA\Items(ref=@Model(type=Users::class, groups={"user:add"}))
     *     )
     * )
     * 
     * @OA\RequestBody(
     *      @Model(type=Users::class, groups={"user:add"}))
     * )
     *
     * 
     * @OA\Tag(name="Users")
     */
    public function patchUser($id, Request $request)
    {
        $client = $this->getUser();
        $user = $this->uRepo->findOneByClient($client, $id);
        if(!empty($user)) {
            $data = json_decode($request->getContent(), true);
            $checker = FormHelper::checkFields($data);
            if(!empty($checker)) {
                return $this->api->badRequest($checker);
            }
            $client = $this->getUser();

            $userForm = $this->createForm(UserType::class, $user);
            $userForm->submit($data, false);
            if($userForm->isValid()) {
                $this->em->persist($user);
                $this->em->flush();
                $user = $this->api->normalizeUser($user);
                return $this->api->updatedResponse($user);
            } else {
                $errors = FormHelper::getErrors($userForm);
                return $this->api->response($errors, 400);
            }
        }
        return $this->api->notFoundResonse();
    }


    /**
     * @Route(
     *     "/api/users/{id}",
     *     name="api_user_delete",
     *     methods={"DELETE"}
     * )
     * 
     * @OA\Response(
     *     response=204,
     *     description="User deleted by id"
     * )
     * 
     * @OA\Tag(name="Users")
     */
    public function deleteUser($id)
    {
        $client = $this->getUser();
        $user = $this->uRepo->findOneByClient($client, $id);
        if(!empty($user)) {
            $this->em->remove($user);
            $this->em->flush();
            return $this->api->deleteReponse();
        }
        return $this->api->forbiddenResponse();
    }

}