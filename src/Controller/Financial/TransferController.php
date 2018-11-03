<?php

namespace App\Controller\Financial;

use App\Entity\Financial\Account;
use App\Entity\Financial\Category;
use App\Entity\Financial\Transaction;
use App\Entity\Financial\Transfer;
use App\Entity\Financial\TypeOfTransaction;
use App\Form\Financial\TransferType;
use App\Repository\Financial\CategoryRepository;
use App\Repository\Financial\TypeOfTransactionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * Class TransferController
 * @package App\Controller\Financial
 */
class TransferController extends AbstractController
{
    /**
     * @Route("/financial/transfer/new/{account_id}", name="financial_transfer_new", requirements={"account_id":"\d+"})
     *
     * @ParamConverter("accountFrom", options={"id" = "account_id"})
     *
     * @param Account $accountFrom
     * @param Request $request
     *
     * @return Response
     */
    public function new(Account $accountFrom, Request $request): Response
    {
        $isXmlHttpRequest = $request->isXmlHttpRequest();

        /** @var CategoryRepository $categoryRepo */
        $categoryRepo = $this->getDoctrine()->getRepository(Category::class);
        $categoryFrom = $categoryRepo->getTransferTransactionFromCategory();
        $categoryTo = $categoryRepo->getTransferTransactionToCategory();

        /** @var TypeOfTransactionRepository $typeOfTransactionRepo */
        $typeOfTransactionRepo = $this->getDoctrine()->getRepository(TypeOfTransaction::class);
        $typeOfTransaction = $typeOfTransactionRepo->getVirement();

        $options = [];
        if ($isXmlHttpRequest) {
            $options = ['hide_back_button' => true, 'hide_save_button' => true];
        }

        $transfer = Transfer::create($this->getUser(), $accountFrom, $accountFrom, $categoryFrom, $categoryTo, $typeOfTransaction);

        $form = $this->createForm(TransferType::class, $transfer, $options);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Transfer $transfer */
            $transfer = $form->getData();

            $entityManager = $this->getDoctrine()->getManager();

            $transactionFrom = $transfer->getTransactionFrom();
            $transactionFrom->setAccount($transfer->getAccountFrom());
            $transactionTo = $transfer->getTransactionTo();
            $transactionTo->setAccount($transfer->getAccountTo());
            $entityManager->persist($transfer);
            $entityManager->flush();

            $transactionFrom = $transfer->getTransactionFrom();

            if ($isXmlHttpRequest) {
                return new JsonResponse([
                    'success' => true,
                    'transaction' => $transactionFrom->getJsonData(),
                    'template' => $this->renderView('financial/transaction/transaction.html.twig', ['transaction' => $transactionFrom]),
                ]);
            }
        }

        return $this->render('financial/transfer/form.html.twig', [
            'isXmlHttpRequest' => $isXmlHttpRequest,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/financial/transfer/{id}/edit", name="financial_transfer_edit")
     *
     * @param Transfer $transfer
     * @param Request $request
     *
     * @return Response
     */
    public function edit(Transfer $transfer, Request $request): Response
    {
        $isXmlHttpRequest = $request->isXmlHttpRequest();

        $options = [];
        if ($isXmlHttpRequest) {
            $options = ['hide_back_button' => true, 'hide_save_button' => true];
        }

        $form = $this->createForm(TransferType::class, $transfer, $options);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Transfer $transfer */
            $transfer = $form->getData();

            $entityManager = $this->getDoctrine()->getManager();

            $transactionFrom = $transfer->getTransactionFrom();
            $transactionFrom->setAccount($transfer->getAccountFrom());
            $transactionTo = $transfer->getTransactionTo();
            $transactionTo->setAccount($transfer->getAccountTo());
            $entityManager->persist($transfer);
            $entityManager->flush();

            $transactionFrom = $transfer->getTransactionFrom();

            if ($isXmlHttpRequest) {
                return new JsonResponse([
                    'success' => true,
                    'transaction' => $transactionFrom->getJsonData(),
                    'template' => $this->renderView('financial/transaction/transaction.html.twig', ['transaction' => $transactionFrom]),
                ]);
            }
        }

        return $this->render('financial/transfer/form.html.twig', [
            'isXmlHttpRequest' => $isXmlHttpRequest,
            'form' => $form->createView(),
        ]);
    }
}