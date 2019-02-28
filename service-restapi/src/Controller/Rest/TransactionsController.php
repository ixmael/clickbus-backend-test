<?php

namespace App\Controller\Rest;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;

use App\Repository\TransactionRepository;

/**
 * @Route("api/transacciones")
 */
final class TransactionsController extends AbstractController
{
    private $transactionRepository;

    public function __construct(TransactionRepository $transactionRepository)
    {
        $this->transactionRepository = $transactionRepository;
    }

    /**
     * @Route("/", methods={"GET"})
     */
    public function getTransactions(Request $request, SerializerInterface $serializer)
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
    public function loadTransaction(Request $request, SerializerInterface $serializer, $id)
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
    public function addTransaction(Request $request, SerializerInterface $serializer)
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
    public function updateTransaction(Request $request, SerializerInterface $serializer, $id)
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
    public function delTransaction(Request $request, SerializerInterface $serializer, $id)
    {
      return new Response(
        $serializer->serialize([], 'json'),
        Response::HTTP_OK,
        ['content-type' => 'application/json']
    );
    }

    /**
     * @TODO: 
     */
    private function hasRequiredFields($data)
    {
        if (array_key_exists('user_id', $data) &&
            array_key_exists('account_kind', $data) &&
            (array_key_exists('amount', $data) || array_key_exists('credit', $data)))
        {
            return true;
        }

        return false;
    }
}
