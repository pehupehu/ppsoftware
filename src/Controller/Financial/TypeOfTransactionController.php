<?php

namespace App\Controller\Financial;

use App\Entity\Financial\TypeOfTransaction;
use App\Form\Financial\TypeOfTransactionType;
use App\Repository\Financial\TypeOfTransactionRepository;
use App\Tools\FlashBagTranslator;
use App\Tools\Pager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class TypeOfTransactionController
 * @package App\Controller\Financial
 */
class TypeOfTransactionController extends AbstractController
{
    /**
     * @Route("/financial/type_transaction", name="financial_type_transaction")
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request): Response
    {
        /** @var TypeOfTransactionRepository $typeOfTransactionRepo */
        $typeOfTransactionRepo = $this->getDoctrine()->getRepository(TypeOfTransaction::class);
        $pager = new Pager($typeOfTransactionRepo->getTypesQuery());
        $pager->setPage($request->get('page', 1));
        $pager->setRouteName('financial_type_transaction');

        return $this->render('financial/type_transaction/list.html.twig', [
            'pager' => $pager,
        ]);
    }

    /**
     * @Route("/financial/type_transaction/new", name="financial_type_transaction_new")
     *
     * @param Request $request
     * @param FlashBagTranslator $flashBagTranslator
     *
     * @return Response
     */
    public function new(Request $request, FlashBagTranslator $flashBagTranslator): Response
    {
        $typeOfTransaction = new TypeOfTransaction();
        $typeOfTransaction->setId(0);
        $typeOfTransaction->setName('');
        $typeOfTransaction->setSurname('');

        $form = $this->createForm(TypeOfTransactionType::class, $typeOfTransaction);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var TypeOfTransaction $typeOfTransaction */
            $typeOfTransaction = $form->getData();

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($typeOfTransaction);
            $entityManager->flush();

            $flashBagTranslator->add('success', 'financial.type_transaction.message.success.new');

            return $this->redirectToRoute('financial_type_transaction');
        }

        return $this->render('financial/type_transaction/form.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/financial/type_transaction/{id}/edit", name="financial_type_transaction_edit")
     *
     * @param Request $request
     * @param FlashBagTranslator $flashBagTranslator
     * @param TypeOfTransaction $typeOfTransaction
     *
     * @return Response
     */
    public function edit(Request $request, FlashBagTranslator $flashBagTranslator, TypeOfTransaction $typeOfTransaction): Response
    {
        $form = $this->createForm(TypeOfTransactionType::class, $typeOfTransaction);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var TypeOfTransaction $typeOfTransaction */
            $typeOfTransaction = $form->getData();

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($typeOfTransaction);
            $entityManager->flush();

            $flashBagTranslator->add('success', 'financial.type_transaction.message.success.edit');

            return $this->redirectToRoute('financial_type_transaction');
        }

        return $this->render('financial/type_transaction/form.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/financial/type_transaction/{id}/remove", name="financial_type_transaction_remove")
     *
     * @param FlashBagTranslator $flashBagTranslator
     * @param TypeOfTransaction $typeOfTransaction
     *
     * @return Response
     */
    public function remove(FlashBagTranslator $flashBagTranslator, TypeOfTransaction $typeOfTransaction): Response
    {
        if ($typeOfTransaction->remove()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($typeOfTransaction);
            $entityManager->flush();

            $flashBagTranslator->add('success', 'financial.type_transaction.message.success.remove');
        } else {
            $flashBagTranslator->add('warning', 'financial.type_transaction.message.warning.remove');
        }

        return $this->redirectToRoute('financial_type_transaction');
    }
}
