<?php

namespace App\Controller\Rest;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;

use App\Repository\UserRepository;

/**
 * @Route("api/usuarios")
 */
final class UserController extends AbstractController
{
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @Route("/", methods={"GET"})
     */
    public function getUsers(Request $request, SerializerInterface $serializer)
    {
      $data = $this->userRepository->getAll();
      return new Response(
          $serializer->serialize([ 'total' => count($data), 'data' => $data ], 'json'),
          Response::HTTP_OK,
          ['content-type' => 'application/json']
      );
    }

    /**
     * @Route("/{id}", methods={"GET"})
     */
    public function loadUser(Request $request, SerializerInterface $serializer, $id)
    {
      if ($this->userRepository->exists($id))
      {
          $data = $this->userRepository->get($id);
          return new Response(
              $serializer->serialize([ 'data' => $data ], 'json'),
              Response::HTTP_OK,
              ['content-type' => 'application/json']
          );
      }

      return new Response(
          $serializer->serialize([ 'message' => "The user with id = " . $id . " not exists" ], 'json'),
          Response::HTTP_NOT_FOUND,
          ['content-type' => 'application/json']
      );
    }

    /**
     * @Route("/", methods={"POST"})
     */
    public function addUser(Request $request, SerializerInterface $serializer)
    {
        $requestData = json_decode($request->getContent(), true);

        if (!$requestData)
        {
            return new Response(
                $serializer->serialize([ 'message' => "The request not has data" ], 'json'),
                Response::HTTP_BAD_REQUEST,
                ['content-type' => 'application/json']
            );
        }
        else if (!$this->hasRequiredFields($requestData))
        {
            return new Response(
                $serializer->serialize([ 'message' => "The request data has not the fields required" ], 'json'),
                Response::HTTP_BAD_REQUEST,
                ['content-type' => 'application/json']
            );
        }

        $responseCode = Response::HTTP_CONFLICT;
        $result = [
            'result' => 'failed',
            'message' => '',
        ];

        try
        {
            $user = $this->userRepository->add($requestData);

            $result['id'] = $user->getId();
            $result['result'] = 'created';
            unset($result['message']);
            $responseCode = Response::HTTP_OK;
        }
        catch(\Exception $e)
        {
            $result['message'] = $e->getMessage();
        }

        return new Response(
            $serializer->serialize($result, 'json'),
            $responseCode,
            ['content-type' => 'application/json']
        );
    }

    /**
     * @Route("/{id}", methods={"PUT"})
     */
    public function updateUser(Request $request, SerializerInterface $serializer, $id)
    {
        $requestData = json_decode($request->getContent(), true);

        if (!$requestData)
        {
            return new Response(
                $serializer->serialize([ 'message' => "The request not has data" ], 'json'),
                Response::HTTP_BAD_REQUEST,
                ['content-type' => 'application/json']
            );
        }
        else if (!$this->hasRequiredFields($requestData))
        {
            return new Response(
                $serializer->serialize([ 'message' => "The request data has not the fields required" ], 'json'),
                Response::HTTP_BAD_REQUEST
            );
        }

        $responseCode = Response::HTTP_CONFLICT;
        $result = [
            'result' => 'failed',
            'message' => '',
        ];

        try
        {
            $user = $this->userRepository->update($id, $requestData);

            if ($user)
            {
              $result['id'] = $user->getId();
              $result['result'] = 'updated';
              unset($result['message']);
              $responseCode = Response::HTTP_OK;
            }
        }
        catch(\Exception $e)
        {
            $result['message'] = $e->getMessage();
        }

        return new Response(
            $serializer->serialize($result, 'json'),
            $responseCode,
            ['content-type' => 'application/json']
        );
    }

    /**
     * @Route("/{id}", methods={"DELETE"})
     */
    public function delUser(Request $request, SerializerInterface $serializer, $id)
    {
        $responseCode = Response::HTTP_CONFLICT;

        $result = [
            'result' => 'failed',
            'message' => '',
        ];

        try
        {
            $isDeleted = $this->userRepository->del($id);

            $result['result'] = 'deleted';
            unset($result['message']);
            $responseCode = Response::HTTP_OK;
        }
        catch(\Exception $e)
        {
            $result['message'] = $e->getMessage();
        }

        return new Response(
            $serializer->serialize($result, 'json'),
            $responseCode,
            ['content-type' => 'application/json']
        );
      
        return new Response(
            $serializer->serialize($result, 'json'),
            $responseCode,
            ['content-type' => 'application/json']
        );
    }

    private function hasRequiredFields($data)
    {
        if (array_key_exists('email', $data) && array_key_exists('name', $data))
        {
            return true;
        }

        return false;
    }
}
