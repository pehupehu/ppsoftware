<?php

namespace App\Controller\Financial;

use App\Entity\Financial\Bank;
use App\Form\Financial\BankType;
use App\Repository\Financial\BankRepository;
use App\Tools\FlashBagTranslator;
use App\Tools\Pager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class BankController
 * @package App\Controller\Financial
 */
class BankController extends AbstractController
{
    /**
     * @Route("/financial/bank", name="financial_bank")
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request): Response
    {
        /** @var BankRepository $bankRepo */
        $bankRepo = $this->getDoctrine()->getRepository(Bank::class);
        $pager = new Pager($bankRepo->getBanksQuery());
        $pager->setPage($request->get('page', 1));
        $pager->setRouteName('financial_bank');

        return $this->render('financial/bank/list.html.twig', [
            'pager' => $pager,
        ]);
    }

    /**
     * @Route("/financial/bank/new", name="financial_bank_new")
     *
     * @param Request $request
     * @param FlashBagTranslator $flashBagTranslator
     *
     * @return Response
     */
    public function new(Request $request, FlashBagTranslator $flashBagTranslator): Response
    {
        $bank = new Bank();
        $bank->setId(0);
        $bank->setName('');
        $bank->setSurname('');
        $bank->setLogo('');

        $form = $this->createForm(BankType::class, $bank);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Bank $bank */
            $bank = $form->getData();

            /** @var UploadedFile $file */
            $file = $form['file']->getData();
            if ($form['remove_file']->getNormData()) {
                $bank->setLogo('');
            } elseif ($file) {
                $fileName = strtolower($bank->getSurname()) . '.' . $file->guessExtension();

                try {
                    $file->move(
                        $this->getParameter('app.public_dir') . DIRECTORY_SEPARATOR . $this->getParameter('app.bank_logo_dir'),
                        $fileName
                    );

                    $bank->setLogo($fileName);
                } catch (FileException $e) {
                    $flashBagTranslator->add('warning', 'financial.bank.message.warning.new');

                    return $this->redirectToRoute('financial_bank');
                }
            }

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($bank);
            $entityManager->flush();

            $flashBagTranslator->add('success', 'financial.bank.message.success.new');

            return $this->redirectToRoute('financial_bank');
        }

        return $this->render('financial/bank/form.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/financial/bank/{id}/edit", name="financial_bank_edit")
     *
     * @param Request $request
     * @param FlashBagTranslator $flashBagTranslator
     * @param Bank $bank
     *
     * @return Response
     */
    public function edit(Request $request, FlashBagTranslator $flashBagTranslator, Bank $bank): Response
    {
        $form = $this->createForm(BankType::class, $bank);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Bank $bank */
            $bank = $form->getData();

            /** @var UploadedFile $file */
            $file = $form['file']->getData();
            if ($form['remove_file']->getNormData()) {
                $bank->setLogo('');
            } elseif ($file) {
                $fileName = strtolower($bank->getSurname()) . '.' . $file->guessExtension();

                try {
                    $ret = $file->move(
                        $this->getParameter('app.public_dir') . DIRECTORY_SEPARATOR . $this->getParameter('app.bank_logo_dir'),
                        $fileName
                    );
                    $bank->setLogo($fileName);
                } catch (FileException $e) {
                    $flashBagTranslator->add('warning', 'financial.bank.message.warning.edit');

                    return $this->redirectToRoute('financial_bank');
                }
            }

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($bank);
            $entityManager->flush();

            $flashBagTranslator->add('success', 'financial.bank.message.success.edit');

            return $this->redirectToRoute('financial_bank');
        }

        return $this->render('financial/bank/form.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/financial/bank/{id}/remove", name="financial_bank_remove")
     *
     * @param FlashBagTranslator $flashBagTranslator
     * @param Bank $bank
     *
     * @return Response
     */
    public function remove(FlashBagTranslator $flashBagTranslator, Bank $bank): Response
    {
        if ($bank->remove()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($bank);
            $entityManager->flush();

            $flashBagTranslator->add('success', 'financial.bank.message.success.remove');
        } else {
            $flashBagTranslator->add('warning', 'financial.bank.message.warning.remove');
        }

        return $this->redirectToRoute('financial_bank');
    }
}
