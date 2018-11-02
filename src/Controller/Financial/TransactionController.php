<?php

namespace App\Controller\Financial;

use App\Entity\Financial\Account;
use App\Entity\Financial\Bank;
use App\Entity\Financial\Transaction;
use App\Entity\User;
use App\Form\Financial\TransactionType;
use App\Repository\Financial\BankRepository;
use App\Repository\Financial\TransactionRepository;
use App\Repository\Financial\AccountRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Query;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

/**
 * Class TransactionController
 * @package App\Controller\Financial
 */
class TransactionController extends AbstractController
{
    /**
     * @Route("/financial/transaction", name="financial_transaction")
     *
     * @return Response
     */
    public function index(): Response
    {
        /** @var User $loggedUser */
        $loggedUser = $this->getUser();

        if ($loggedUser->getAccountsCollection()->count() === 1) {
            return $this->redirectToRoute('financial_transaction_account', ['account_id' => $loggedUser->getAccountsCollection()->first()->getId()]);
        }

        return $this->redirectToRoute('financial_transaction_accounts');
    }

    /**
     * @Route("/financial/transaction/accounts", name="financial_transaction_accounts")
     *
     * @return Response
     */
    public function accounts()
    {
        /** @var User $loggedUser */
        $loggedUser = $this->getUser();

        /** @var AccountRepository $accountRepo */
        $accountRepo = $this->getDoctrine()->getRepository(Account::class);
        $accounts = $accountRepo->getAccountsQueryForOneUser($loggedUser)->execute();

        /** @var TransactionRepository $transactionRepo */
        $transactionRepo = $this->getDoctrine()->getRepository(Transaction::class);
        $transactionRepo->addLastTransactionsToManyAccounts($accounts);

        return $this->render('financial/transaction/accounts.html.twig', [
            'accounts' => $accounts,
        ]);
    }

    /**
     * @Route("/financial/transaction/accounts/{bank_id}", name="financial_transaction_accounts_bank", requirements={"bank_id":"\d+"})
     *
     * @ParamConverter("bank", options={"id" = "bank_id"})
     * 
     * @return Response
     */
    public function bankAccounts(Bank $bank)
    {
        /** @var User $loggedUser */
        $loggedUser = $this->getUser();

        /** @var AccountRepository $accountRepo */
        $accountRepo = $this->getDoctrine()->getRepository(Account::class);
        $accounts = $accountRepo->getAccountsQueryForOneUserAndOneBank($loggedUser, $bank)->execute();

        /** @var BankRepository $bankRepo */
        $bankRepo = $this->getDoctrine()->getRepository(Bank::class);
        $banks = $bankRepo->getBanksQueryForOneUser($loggedUser)->execute();

        /** @var TransactionRepository $transactionRepo */
        $transactionRepo = $this->getDoctrine()->getRepository(Transaction::class);
        $transactionRepo->addLastTransactionsToManyAccounts($accounts);

        return $this->render('financial/transaction/accounts.html.twig', [
            'accounts' => $accounts,
            'banks' => $banks,
            'bank' => $bank,
        ]);
    }

    /**
     * @Route("/financial/transaction/{account_id}/account", name="financial_transaction_account")
     *
     * @ParamConverter("account", options={"id" = "account_id"})
     * 
     * @return Response
     */
    public function account(Account $account): Response
    {
        /** @var TransactionRepository $transactionRepo */
        $transactionRepo = $this->getDoctrine()->getRepository(Transaction::class);
        $years = $transactionRepo->getTransactionsYearForOneAccount($account);

        return $this->render('financial/transaction/account.html.twig', [
            'account' => $account,
            'years' => $years,
        ]);
    }

    /**
     * @Route("/financial/transaction/{account_id}/{year}", name="financial_transaction_account_year", requirements={"year":"[0-9]{4}"})
     *
     * @ParamConverter("account", options={"id" = "account_id"})
     *
     * @return Response
     */
    public function transactions(Account $account, int $year, Request $request): Response
    {
        $isXmlHttpRequest = $request->isXmlHttpRequest();

        /** @var TransactionRepository $transactionRepo */
        $transactionRepo = $this->getDoctrine()->getRepository(Transaction::class);
        $transactions = $transactionRepo->getTransactionsForOneAccount($account, $year);

        return $this->render('financial/transaction/transactions.html.twig', [
            'isXmlHttpRequest' => $isXmlHttpRequest,
            'transactions' => $transactions,
        ]);
    }

    /**
     * @Route("/financial/transaction/new/{account_id}/{type}", name="financial_transaction_new", requirements={"account_id":"\d+","type":"credit|debit"})
     *
     * @ParamConverter("account", options={"id" = "account_id"})
     * 
     * @param Account $account
     * @param string $type
     * @param Request $request
     *
     * @return Response
     */
    public function new(Account $account, string $type, Request $request): Response
    {
        $isXmlHttpRequest = $request->isXmlHttpRequest();

        $options = [];
        if ($isXmlHttpRequest) {
            $options = ['hide_back_button' => true, 'hide_save_button' => true];
        }

        $transaction = new Transaction();
        $transaction->setId(0);
        $transaction->setAccount($account);
        if ($type === 'credit') {
            $transaction->setCredit(true);
        } else {
            $transaction->setDebit(true);
        }
        $transaction->setDate(new \DateTime());
        $transaction->setCreatedAt(new \DateTime());
        $transaction->setCreator($this->getUser());
        $transaction->setAmount(0);

        $form = $this->createForm(TransactionType::class, $transaction, $options);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Transaction $transaction */
            $transaction = $form->getData();

            $entityManager = $this->getDoctrine()->getManager();

            $entityManager->persist($transaction);
            $entityManager->flush();

            if ($isXmlHttpRequest) {
                return new JsonResponse([
                    'success' => true,
                    'transaction' => $transaction->getJsonData(),
                    'template' => $this->renderView('financial/transaction/transaction.html.twig', ['transaction' => $transaction]),
                ]);
            }
        }

        return $this->render('financial/transaction/form.html.twig', [
            'isXmlHttpRequest' => $isXmlHttpRequest,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/financial/transaction/{id}/edit", name="financial_transaction_edit")
     *
     * @param Request $request
     * @param Transaction $transaction
     *
     * @return Response
     */
    public function edit(Request $request, Transaction $transaction): Response
    {
        $isXmlHttpRequest = $request->isXmlHttpRequest();

        $options = [];
        if ($isXmlHttpRequest) {
            $options = ['hide_back_button' => true, 'hide_save_button' => true];
        }
        
        $form = $this->createForm(TransactionType::class, $transaction, $options);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Transaction $transaction */
            $transaction = $form->getData();

            $entityManager = $this->getDoctrine()->getManager();

            $entityManager->persist($transaction);
            $entityManager->flush();

            if ($isXmlHttpRequest) {
                return new JsonResponse([
                    'success' => true,
                    'transaction' => $transaction->getJsonData(),
                    'template' => $this->renderView('financial/transaction/transaction.html.twig', ['transaction' => $transaction]),
                ]);
            }
        }

        return $this->render('financial/transaction/form.html.twig', [
            'isXmlHttpRequest' => $isXmlHttpRequest,
            'form' => $form->createView(),
        ]);
    }
}
