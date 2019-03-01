<?php

namespace App\Controller\Rest;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;

use App\Repository\AccountRepository;
use App\Entity\Account\AbstractAccount;

/**
 * @Route("api/cuentas")
 */
final class AccountController extends AbstractController
{
    private $accountRepository;

    public function __construct(AccountRepository $accountRepository)
    {
        $this->accountRepository = $accountRepository;
    }

    /**
     * @Route("/", methods={"GET"})
     */
    public function getAccounts(Request $request, SerializerInterface $serializer)
    {
        $accounts = $this->accountRepository->getAll();

        return new Response(
            $serializer->serialize([ 'total' => count($accounts), 'data' => $accounts ], 'json', ['groups' => 'basic']),
            Response::HTTP_OK,
            ['content-type' => 'application/json']
        );
    }

    /**
     * @Route("/{id}", methods={"GET"})
     */
    public function loadAccount(Request $request, SerializerInterface $serializer, $id)
    {
        $account = $this->accountRepository->exists($id);
        if ($account)
        {
            return new Response(
                $serializer->serialize([ 'data' => $account ], 'json', ['groups' => 'basic']),
                Response::HTTP_OK,
                ['content-type' => 'application/json']
            );
        }

        return new Response(
            $serializer->serialize([ 'message' => "The account with id = " . $id . " not exists" ], 'json'),
            Response::HTTP_NOT_FOUND,
            ['content-type' => 'application/json']
        );
    }

    /**
     * @Route("/", methods={"POST"})
     */
    public function addAccount(Request $request, SerializerInterface $serializer)
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
            $account = $this->accountRepository->add($requestData);

            $result['id'] = $account->getId();
            $result['result'] = 'created';
            $groups = ['groups' => 'basic'];
            unset($result['message']);
            $responseCode = Response::HTTP_OK;
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
     * @Route("/{id}", methods={"PUT"})
     */
    public function updateAccount(Request $request, SerializerInterface $serializer, $id)
    {
        $requestData = json_decode($request->getContent(), true);

        if (!$requestData)
        {
            return new Response(
                $serializer->serialize([ 'message' => "The request not has data to update an account" ], 'json'),
                Response::HTTP_BAD_REQUEST,
                ['content-type' => 'application/json']
            );
        }

        // Check if the request to update has the required data
        $checkedFields = $this->checkFieldsToUpdate($requestData, $serializer);
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
            $account = $this->accountRepository->update($id, $requestData);

            if ($account)
            {
              $result['id'] = $account->getId();
              $result['result'] = 'updated';
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
    public function delUser(Request $request, SerializerInterface $serializer, $id)
    {
        $responseCode = Response::HTTP_CONFLICT;

        $result = [
            'result' => 'failed',
            'message' => '',
        ];

        try
        {
            $isDeleted = $this->accountRepository->del($id);

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
    }

    /**
     * @TODO: validate if account_kind is 'credit' or 'debit'.
     * @TODO: validate if account_kind is 'credit' exists the key 'credit'
     * @TODO: validate if account_kind is 'debit' exists the key 'amount'
     */
    private function checkFieldsToCreate($data, $serializer)
    {
        if (!array_key_exists('user_id', $data))
        {
            return new Response(
                $serializer->serialize([ 'message' => "There is not 'user_id' in the request data" ], 'json'),
                Response::HTTP_BAD_REQUEST,
                ['content-type' => 'application/json']
            );
        }

        if (!array_key_exists('account_kind', $data))
        {
            return new Response(
                $serializer->serialize([ 'message' => "There is not 'accounts_kind' in the request data" ], 'json'),
                Response::HTTP_BAD_REQUEST,
                ['content-type' => 'application/json']
            );
        }

        if ($data['account_kind'] === AbstractAccount::CREDIT_KIND &&
            (!\array_key_exists('credit', $data) || !\array_key_exists('limit_credit', $data)))
        {
            return new Response(
                $serializer->serialize([ 'message' => 'The data is not complete to create a credit account' ], 'json'),
                Response::HTTP_BAD_REQUEST,
                ['content-type' => 'application/json']
            );
        }

        if ($data['account_kind'] === AbstractAccount::DEBIT_KIND && !\array_key_exists('amount', $data))
        {
            return new Response(
                $serializer->serialize([ 'message' => 'The data is not complete to create a debit account' ], 'json'),
                Response::HTTP_BAD_REQUEST,
                ['content-type' => 'application/json']
            );
        }
    }

    private function checkFieldsToUpdate($data, $serializer)
    {
        if (\array_key_exists('account_kind', $data))
        {
            if ($data['account_kind'] === AbstractAccount::CREDIT_KIND &&
            (!\array_key_exists('credit', $data) || !\array_key_exists('limit_credit', $data)))
            {
                return new Response(
                    $serializer->serialize([ 'message' => 'The data is not complete to update a credit account' ], 'json'),
                    Response::HTTP_BAD_REQUEST,
                    ['content-type' => 'application/json']
                );
            }

            if ($data['account_kind'] === AbstractAccount::DEBIT_KIND && !\array_key_exists('amount', $data))
            {
                return new Response(
                    $serializer->serialize([ 'message' => 'The data is not complete to update a debit account' ], 'json'),
                    Response::HTTP_BAD_REQUEST,
                    ['content-type' => 'application/json']
                );
            }
        }
    }
}
