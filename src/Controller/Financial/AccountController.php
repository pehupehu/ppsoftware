<?php

namespace App\Controller\Financial;

use App\Entity\Financial\Account;
use App\Entity\Financial\Bank;
use App\Entity\Financial\TypeOfAccount;
use App\Entity\User;
use App\Form\Financial\AccountShareType;
use App\Form\Financial\AccountType;
use App\Repository\Financial\AccountRepository;
use App\Tools\Currency;
use App\Tools\FlashBagTranslator;
use App\Tools\Pager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Finder\Exception\AccessDeniedException;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class AccountController
 * @package App\Controller\Financial
 */
class AccountController extends AbstractController
{
    /**
     * @Route("/financial/account", name="financial_account")
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request): Response
    {
        /** @var AccountRepository $accountRepo */
        $accountRepo = $this->getDoctrine()->getRepository(Account::class);
        $pager = new Pager($accountRepo->loadAccounts($this->getUser()));
        $pager->setPage($request->get('page', 1));
        $pager->setRouteName('financial_account');

        return $this->render('financial/account/list.html.twig', [
            'loggedUser' => $this->getUser(),
            'pager' => $pager,
        ]);
    }

    /**
     * @Route("/financial/account/new", name="financial_account_new")
     *
     * @param Request $request
     * @param FlashBagTranslator $flashBagTranslator
     *
     * @return Response
     */
    public function new(Request $request, FlashBagTranslator $flashBagTranslator): Response
    {
        $account = new Account();
        $account->setId(0);
        $account->setCreatorId($this->getUser()->getId());
        $account->setCreator($this->getUser());
        $typeOfAccount = new TypeOfAccount();
        $typeOfAccount->setId(0);
        $typeOfAccount->setName('');
        $account->setTypeOfAccount($typeOfAccount);
        $bank = new Bank();
        $bank->setId(0);
        $bank->setName('');
        $account->setBank($bank);
        $account->setName('');
        $account->setSurname('');
        $account->setDescription('');
        $account->setInitialBalance(0);
        $account->setCurrentBalance(0);
        $account->setAmountCurrency(Currency::EUR);

        $form = $this->createForm(AccountType::class, $account);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Account $account */
            $account = $form->getData();

            $account->setBankId($account->getBank()->getId());
            $account->setTypeOfAccountId($account->getTypeOfAccount()->getId());

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($account);
            $entityManager->flush();

            $flashBagTranslator->add('success', 'financial.account.message.success.new');

            return $this->redirectToRoute('financial_account');
        }

        return $this->render('financial/account/form.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/financial/account/{id}/edit", name="financial_account_edit")
     *
     * @param Request $request
     * @param FlashBagTranslator $flashBagTranslator
     * @param Account $account
     *
     * @return Response
     */
    public function edit(Request $request, FlashBagTranslator $flashBagTranslator, Account $account): Response
    {
        if (!$account->canEditAccount($this->getUser())) {
            throw new AccessDeniedException();
        }

        $form = $this->createForm(AccountType::class, $account);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Account $account */
            $account = $form->getData();

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($account);
            $entityManager->flush();

            $flashBagTranslator->add('success', 'financial.account.message.success.edit');

            return $this->redirectToRoute('financial_account');
        }

        return $this->render('financial/account/form.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/financial/account/{id}/remove", name="financial_account_remove")
     *
     * @param FlashBagTranslator $flashBagTranslator
     * @param Account $account
     *
     * @return Response
     */
    public function remove(FlashBagTranslator $flashBagTranslator, Account $account): Response
    {
        if ($account->remove($this->getUser())) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($account);
            $entityManager->flush();

            $flashBagTranslator->add('success', 'financial.account.message.success.remove');
        } else {
            $flashBagTranslator->add('warning', 'financial.account.message.warning.remove');
        }

        return $this->redirectToRoute('financial_account');
    }

    /**
     * @Route("/financial/account/{id}/share", name="financial_account_share")
     *
     * @param Request $request
     * @param FlashBagTranslator $flashBagTranslator
     * @param Account $account
     *
     * @return Response
     */
    public function share(Request $request, FlashBagTranslator $flashBagTranslator, Account $account): Response
    {
        if (!$account->canShareAccount($this->getUser())) {
            throw new AccessDeniedException();
        }

        /** @var Form $form */
        $form = $this->createForm(AccountShareType::class, $account);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Account $account */
            $account = $form->getData();

            $entityManager = $this->getDoctrine()->getManager();

            /** @var User $user */
            foreach ($account->getUsers() as $user) {
                if (in_array($user, $form->get('unallowed')->getData())) {
                    $account->removeUser($user);
                }
            }
            foreach ($form->get('allowed')->getData() as $user) {
                $account->addUser($user);
            }

            $entityManager->persist($account);
            $entityManager->flush();

            $flashBagTranslator->add('success', 'financial.account.message.success.share');

            return $this->redirectToRoute('financial_account');
        }

        return $this->render('financial/account/share.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
