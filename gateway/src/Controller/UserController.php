<?php

declare(strict_types=1);

namespace App\Controller;

use App\Annotations\AllowAccess;
use App\Exceptions\ValidationException;
use App\Repository\UserRepository;
use App\Service\UrlParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @AllowAccess(roles={"ROLE_ADMIN"})
 * Class UserController
 * @package App\Controller
 */
class UserController extends AbstractController
{
    private const CREATE_URL = '/user/create';
    private const SHOW_URL = '/user/{id}';
    private const EDIT_URL = '/user/{id}/edit';
    private const DELETE_URL = '/user/{id}';

    /**
     * @var UserRepository
     */
    private $repository;

    public function __construct(UserRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @Route("/user/create", name="user.create", methods={"POST"})
     * @param Request $request
     * @return JsonResponse
     * @throws ValidationException
     */
    public function create(Request $request): JsonResponse
    {
        $response = $this->repository->request(
            json_decode($request->getContent(), true),
            self::CREATE_URL,
            $request->getMethod()
        );
        return $this->json($response, Response::HTTP_CREATED);
    }

    /**
     * @Route("/user/{id}", name="user.show", methods={"GET"})
     * @param Request $request
     * @param string $id
     * @return JsonResponse
     * @throws ValidationException
     */
    public function show(Request $request, string $id): JsonResponse
    {
        $response = $this->repository->request(
            $request->request->all(),
            UrlParamConverter::convert(self::SHOW_URL, 'id', $id),
            $request->getMethod()
        );
        return $this->json($response, Response::HTTP_OK);
    }

    /**
     * @Route("/user/{id}/edit", name="user.edit", methods={"PUT"})
     * @param Request $request
     * @param string $id
     * @return JsonResponse
     * @throws ValidationException
     */
    public function edit(Request $request, string $id): JsonResponse
    {
        $this->repository->request(
            json_decode($request->getContent(), true),
            UrlParamConverter::convert(self::EDIT_URL, 'id', $id),
            $request->getMethod()
        );
        return $this->json([], Response::HTTP_OK);
    }

    /**
     * @Route("/user/{id}", name="user.delete", methods={"DELETE"})
     * @param Request $request
     * @param string $id
     * @return JsonResponse
     * @throws ValidationException
     */
    public function delete(Request $request, string $id): JsonResponse
    {
        $this->repository->request(
            json_decode($request->getContent(), true),
            UrlParamConverter::convert(self::DELETE_URL, 'id', $id),
            $request->getMethod()
        );
        return $this->json([], Response::HTTP_NO_CONTENT);
    }
}