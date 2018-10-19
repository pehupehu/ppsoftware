<?php

namespace App\Controller\Financial;

use App\Entity\Financial\Account;
use App\Entity\Financial\Category;
use App\Entity\User;
use App\Form\Financial\CategoryType;
use App\Repository\Financial\CategoryRepository;
use App\Tools\FlashBagTranslator;
use App\Tools\Pager;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Finder\Exception\AccessDeniedException;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

/**
 * Class CategoryController
 * @package App\Controller\Financial
 */
class CategoryController extends AbstractController
{
    /**
     * @Route("/financial/category", name="financial_category")
     *
     * @return Response
     */
    public function index(): Response
    {
        /** @var CategoryRepository $categoryRepo */
        $categoryRepo = $this->getDoctrine()->getRepository(Category::class);
        $categoriesCredit = $categoryRepo->loadCredit()->execute();
        $categoriesDebit = $categoryRepo->loadDebit()->execute();

        return $this->render('financial/category/list.html.twig', [
            'categoriesCredit' => $categoriesCredit,
            'categoriesDebit' => $categoriesDebit,
        ]);
    }

    /**
     * @Route("/financial/category/{type}/new", name="financial_category_new", requirements={"type": "credit|debit"})
     *
     * @param Request $request
     * @param FlashBagTranslator $flashBagTranslator
     *
     * @return Response
     */
    public function new(Request $request, FlashBagTranslator $flashBagTranslator): Response
    {
        $type = $request->get('type');

        $category = new Category();
        $category->setId(0);
        $category->setName('');
        $category->setChildrens(new ArrayCollection());
        if ($type === 'credit') {
            $category->setCredit(true);
            $category->setDebit(false);
        } else {
            $category->setCredit(false);
            $category->setDebit(true);
        }

        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Category $category */
            $category = $form->getData();

            dump($form);

            die();

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($bank);
            $entityManager->flush();

            $flashBagTranslator->add('success', 'financial.category.message.success.new.' . $type);

            return $this->redirectToRoute('financial_category');
        }

        return $this->render('financial/category/form.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/financial/category/move/{children_id}/{parent_id}", name="financial_category_move")
     *
     * @ParamConverter("children", options={"id" = "children_id"})
     * @ParamConverter("parent", options={"id" = "parent_id"})
     * 
     * @param Category $children
     * @param Category $parent
     * 
     * @return Response
     */
    public function move(Category $children, Category $parent): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $parent->addChildren($children);
        $entityManager->flush();

        return new Response('ok', Response::HTTP_OK);
    }
}
