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
     * @param TranslatorInterface $translator
     *
     * @return Response
     */
    public function new(Account $accountFrom, Request $request, TranslatorInterface $translator): Response
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

        $transactionFrom = new Transaction();
        $transactionTo = new Transaction();

        $transfer = new Transfer();
        $transfer->setAccountFrom($accountFrom);
        $transfer->setAccountTo($accountFrom);
        $transfer->setTransactionFrom($transactionFrom);
        $transfer->setTransactionTo($transactionTo);
        $transfer->setId(0);
        $transfer->setDate(new \DateTime());
        $transfer->setCreatedAt(new \DateTime());
        $transfer->setCreator($this->getUser());
        $transfer->setAmount(0);

        $transactionFrom->setDebit();
        $transactionFrom->setAccount($accountFrom);
        $transactionFrom->setCategory($categoryFrom);
        $transactionFrom->setTypeOfTransaction($typeOfTransaction);
        $transactionFrom->setTransfer($transfer);

        $transactionTo->setCredit();
        $transactionTo->setAccount($accountFrom);
        $transactionTo->setCategory($categoryTo);
        $transactionTo->setTypeOfTransaction($typeOfTransaction);
        $transactionTo->setTransfer($transfer);

        $form = $this->createForm(TransferType::class, $transfer, $options);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Transfer $transfer */
            $transfer = $form->getData();

            $entityManager = $this->getDoctrine()->getManager();

            $transactionFrom = $transfer->getTransactionFrom();
            $transactionFrom->setName($translator->trans('financial.transfer.field.transaction_from_name', ['%account_to%' => $transfer->getAccountTo()]));
            $transactionFrom->setAccount($transfer->getAccountFrom());
            $transactionTo = $transfer->getTransactionTo();
            $transactionTo->setName($translator->trans('financial.transfer.field.transaction_to_name', ['%account_from%' => $transfer->getAccountFrom()]));
            $transactionTo->setAccount($transfer->getAccountTo());
            $entityManager->persist($transactionFrom);
            $entityManager->persist($transactionTo);
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
     * @param TranslatorInterface $translator
     *
     * @return Response
     */
    public function edit(Transfer $transfer, Request $request, TranslatorInterface $translator): Response
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
            $transactionFrom->setName($translator->trans('financial.transfer.field.transaction_from_name', ['%account_to%' => $transfer->getAccountTo()]));
            $transactionFrom->setAccount($transfer->getAccountFrom());
            $transactionTo = $transfer->getTransactionTo();
            $transactionTo->setName($translator->trans('financial.transfer.field.transaction_to_name', ['%account_from%' => $transfer->getAccountFrom()]));
            $transactionTo->setAccount($transfer->getAccountTo());
            $entityManager->persist($transactionFrom);
            $entityManager->persist($transactionTo);
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