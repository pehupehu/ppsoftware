<?php

namespace App\Controller\Financial;

use App\Entity\Financial\TypeOfAccount;
use App\Form\Financial\TypeOfAccountType;
use App\Repository\Financial\TypeOfAccountRepository;
use App\Tools\FlashBagTranslator;
use App\Tools\Pager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class TypeOfAccountController
 * @package App\Controller\Financial
 */
class TypeOfAccountController extends AbstractController
{
    /**
     * @Route("/financial/type_account", name="financial_type_account")
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request): Response
    {
        /** @var TypeOfAccountRepository $typeOfAccountRepo */
        $typeOfAccountRepo = $this->getDoctrine()->getRepository(TypeOfAccount::class);
        $pager = new Pager($typeOfAccountRepo->getTypesQuery());
        $pager->setPage($request->get('page', 1));
        $pager->setRouteName('financial_type_account');

        return $this->render('financial/type_account/list.html.twig', [
            'pager' => $pager,
        ]);
    }

    /**
     * @Route("/financial/type_account/new", name="financial_type_account_new")
     *
     * @param Request $request
     * @param FlashBagTranslator $flashBagTranslator
     *
     * @return Response
     */
    public function new(Request $request, FlashBagTranslator $flashBagTranslator): Response
    {
        $typeOfAccount = new TypeOfAccount();
        $typeOfAccount->setId(0);
        $typeOfAccount->setName('');
        $typeOfAccount->setSurname('');

        $form = $this->createForm(TypeOfAccountType::class, $typeOfAccount);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var TypeOfAccount $typeOfAccount */
            $typeOfAccount = $form->getData();

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($typeOfAccount);
            $entityManager->flush();

            $flashBagTranslator->add('success', 'financial.type_account.message.success.new');

            return $this->redirectToRoute('financial_type_account');
        }

        return $this->render('financial/type_account/form.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/financial/type_account/{id}/edit", name="financial_type_account_edit")
     *
     * @param Request $request
     * @param FlashBagTranslator $flashBagTranslator
     * @param TypeOfAccount $typeOfAccount
     *
     * @return Response
     */
    public function edit(Request $request, FlashBagTranslator $flashBagTranslator, TypeOfAccount $typeOfAccount): Response
    {
        $form = $this->createForm(TypeOfAccountType::class, $typeOfAccount);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var TypeOfAccount $typeOfAccount */
            $typeOfAccount = $form->getData();

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($typeOfAccount);
            $entityManager->flush();

            $flashBagTranslator->add('success', 'financial.type_account.message.success.edit');

            return $this->redirectToRoute('financial_type_account');
        }

        return $this->render('financial/type_account/form.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/financial/type_account/{id}/remove", name="financial_type_account_remove")
     *
     * @param FlashBagTranslator $flashBagTranslator
     * @param TypeOfAccount $typeOfAccount
     *
     * @return Response
     */
    public function remove(FlashBagTranslator $flashBagTranslator, TypeOfAccount $typeOfAccount): Response
    {
        if ($typeOfAccount->remove()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($typeOfAccount);
            $entityManager->flush();

            $flashBagTranslator->add('success', 'financial.type_account.message.success.remove');
        } else {
            $flashBagTranslator->add('warning', 'financial.type_account.message.warning.remove');
        }

        return $this->redirectToRoute('financial_type_account');
    }
}
