<?php

namespace App\Controller\Rest;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;

use App\Repository\TransactionRepository;
use App\Entity\Account\AbstractAccount;

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
        $transactions = $this->transactionRepository->getAll();

        return new Response(
            $serializer->serialize([ 'total' => count($transactions), 'data' => $transactions ], 'json', ['groups' => 'basic']),
            Response::HTTP_OK,
            ['content-type' => 'application/json']
        );
    }

    /**
     * @Route("/{id}", methods={"GET"})
     */
    public function loadTransaction(Request $request, SerializerInterface $serializer, $id)
    {
        $transaction = $this->transactionRepository->get($id);
        return new Response(
            $serializer->serialize([ 'data' => $transaction ], 'json', ['groups' => 'basic']),
            Response::HTTP_OK,
            ['content-type' => 'application/json']
        );
    }

    /**
     * @Route("/", methods={"POST"})
     */
    public function addTransaction(Request $request, SerializerInterface $serializer)
    {
        $requestData = json_decode($request->getContent(), true);

        if (!$requestData)
        {
            return new Response(
                $serializer->serialize([ 'message' => "The request not has data to create an account" ], 'json'),
                Response::HTTP_BAD_REQUEST,
                ['content-type' => 'application/json']
            );
        }

        // Check if the data required exists in the request body
        $checkedFields = $this->checkFieldsToCreate($requestData, $serializer);
        if (gettype($checkedFields) === 'object' && get_class($checkedFields) === Response::class)
        {
            return $checkedFields;
        }

        $responseCode = Response::HTTP_CONFLICT;
        $result = [
            'result' => 'failed',
            'message' => '',
        ];
        $groups = [];

        try
        {
            $transaction = $this->transactionRepository->add($requestData);

            if ($transaction)
            {
                $account = $transaction->getAccount();
                $result['id'] = $transaction->getId();
                $result['result'] = 'created';
                $result['current_amount'] = $account->getKind() === AbstractAccount::DEBIT_KIND ? $account->getCurrentAmount() : $account->getCredit();
                $groups = ['groups' => 'basic'];
                unset($result['message']);
                $responseCode = Response::HTTP_OK;
            }
        }
        catch(\Exception $e)
        {
            $result['message'] = $e->getMessage();
        }

        return new Response(
            $serializer->serialize($result, 'json', $groups),
            $responseCode,
            ['content-type' => 'application/json']
        );
    }

    /**
     * @Route("/{id}", methods={"DELETE"})
     */
    public function delTransaction(Request $request, SerializerInterface $serializer, $id)
    {
        $account = $this->transactionRepository->del($id);

        if ($account)
        {
            return new Response(
                $serializer->serialize([ 'result' => 'deleted', 'current_amount' => $account->getCurrentAmount() ], 'json', ['groups' => 'basic']),
                Response::HTTP_OK,
                ['content-type' => 'application/json']
            );
        }

        return new Response(
            $serializer->serialize([ 'message' => 'The transaction not exists' ], 'json'),
            Response::HTTP_NOT_FOUND,
            ['content-type' => 'application/json']
        );
    }

    /**
     * @TODO: 
     */
    private function checkFieldsToCreate($data, $serializer)
    {
        if (!array_key_exists('account_id', $data))
        {
            return new Response(
                $serializer->serialize([ 'message' => "There is not account to create a transaction" ], 'json'),
                Response::HTTP_BAD_REQUEST,
                ['content-type' => 'application/json']
            );
        }

        if (!array_key_exists('transaction_kind', $data))
        {
            return new Response(
                $serializer->serialize([ 'message' => "There is not a transaction kind to create a transaction" ], 'json'),
                Response::HTTP_BAD_REQUEST,
                ['content-type' => 'application/json']
            );
        }

        if (!array_key_exists('amount', $data))
        {
            return new Response(
                $serializer->serialize([ 'message' => "There is not the amount to create a transaction" ], 'json'),
                Response::HTTP_BAD_REQUEST,
                ['content-type' => 'application/json']
            );
        }
    }
}
