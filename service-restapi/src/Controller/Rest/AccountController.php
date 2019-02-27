<?php

namespace App\Controller\Rest;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @Route("api/cuentas")
 */
final class AccountController extends AbstractController
{
    /*
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        print_r($userRepository, true);
        $this->userRepository = $userRepository;
    }
    */

    /**
     * @Route("/", methods={"GET"})
     */
    public function getAccounts(Request $request, SerializerInterface $serializer)
    {
      return new Response(
          $serializer->serialize([], 'json'),
          Response::HTTP_OK,
          ['content-type' => 'application/json']
      );
    }

    /**
     * @Route("/{id}", methods={"GET"})
     */
    public function loadAccount(Request $request, SerializerInterface $serializer, $id)
    {
        return new Response(
            $serializer->serialize([], 'json'),
            Response::HTTP_OK,
            ['content-type' => 'application/json']
        );
    }

    /**
     * @Route("/", methods={"POST"})
     */
    public function addAccount(Request $request, SerializerInterface $serializer)
    {
        return new Response(
            $serializer->serialize([], 'json'),
            Response::HTTP_OK,
            ['content-type' => 'application/json']
        );
    }

    /**
     * @Route("/{id}", methods={"PUT"})
     */
    public function updateAccount(Request $request, SerializerInterface $serializer, $id)
    {
        return new Response(
            $serializer->serialize([], 'json'),
            Response::HTTP_OK,
            ['content-type' => 'application/json']
        );
    }

    /**
     * @Route("/{id}", methods={"DELETE"})
     */
    public function delUser(Request $request, SerializerInterface $serializer, $id)
    {
        return new Response(
            $serializer->serialize([], 'json'),
            Response::HTTP_OK,
            ['content-type' => 'application/json']
        );
    }

    /*
    private function hasRequiredFields($data)
    {
        if (array_key_exists('email', $data) && array_key_exists('name', $data))
        {
            return true;
        }

        return false;
    }
    */

}
