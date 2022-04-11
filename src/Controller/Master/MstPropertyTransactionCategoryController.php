<?php

namespace App\Controller\Master;

use Exception;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use App\Entity\Master\MstPropertyTransactionCategory;
use App\Form\Master\MstPropertyTransactionCategoryType;
use App\Repository\Master\MstPropertyTransactionCategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Ramsey\Uuid\Uuid;

/**
 * @Route("/core/master/tred_project/properties_transaction_type", name="master_properties_transaction_category_")
 * @IsGranted("ROLE_SYS_CONTENT_USER")
 */
class MstPropertyTransactionCategoryController extends AbstractController
{
    /**
     * @Route("/", name="index", methods={"GET"})
     * @param MstPropertyTransactionCategoryRepository $mstPropertyTransactionCategoryRepository
     * @param Request $request
     * @return Response
     */
    public function index(MstPropertyTransactionCategoryRepository $mstPropertyTransactionCategoryRepository, Request $request): Response
    {
        $properties_in = $mstPropertyTransactionCategoryRepository->findAll();
        return $this->render('master/mst_property_transaction_category/index.html.twig', [
            'master_properties_transaction_category' => $properties_in,
            'path_index' => 'master_properties_transaction_category_index',
            'path_add' => 'master_properties_transaction_category_add',
            'path_edit' => 'master_properties_transaction_category_edit',
            'path_show' => 'master_properties_transaction_category_show',
            'label_title' => 'label.property_transaction_category',
        ]);
    }

    /**
     * @Route("/add", name="add", methods={"GET","POST"})
     * @param Request $request
     * @return Response
     * @throws Exception
     */
    public function new(Request $request): Response
    {
        $mstPropertyTransactionCategory = new MstPropertyTransactionCategory();
        $form = $this->createForm(MstPropertyTransactionCategoryType::class, $mstPropertyTransactionCategory);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $mstPropertyTransactionCategory->setRowId(Uuid::uuid4()->toString());
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($mstPropertyTransactionCategory);
            $entityManager->flush();
            $this->addFlash('success', 'form.created_successfully');
            return $this->redirectToRoute('master_properties_transaction_category_index');
        }

        return $this->render('form/form.html.twig', [
            'master_properties_transaction_category' => $mstPropertyTransactionCategory,
            'form' => $form->createView(),
            'index_path' => 'master_properties_transaction_category_index',
            'label_title' => 'label.property_transaction_category',
            'label_button' => 'label.create',
            'mode' => 'add'
        ]);
    }

    /**
     * @Route("/edit/{id}", name="edit", methods={"GET","POST"})
     * @param Request $request
     * @param MstPropertyTransactionCategory $mstPropertyTransactionCategory
     * @return Response
     */
    public function edit(Request $request, MstPropertyTransactionCategory $mstPropertyTransactionCategory): Response
    {
        $form = $this->createForm(MstPropertyTransactionCategoryType::class, $mstPropertyTransactionCategory);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', 'form.updated_successfully');
            return $this->redirectToRoute('master_properties_transaction_category_index');
        }

        return $this->render('form/form.html.twig', [
            'master_properties_transaction_category' => $mstPropertyTransactionCategory,
            'form' => $form->createView(),
            'index_path' => 'master_properties_transaction_category_index',
            'label_title' => 'label.property_transaction_category',
            'label_button' => 'label.update',
            'mode' => 'edit'
        ]);
    }

    /**
     * @Route("/{id}", name="delete", methods={"DELETE"})
     * @param Request $request
     * @param MstPropertyTransactionCategory $mstPropertyTransactionCategory
     * @return Response
     */
    public function delete(Request $request, MstPropertyTransactionCategory $mstPropertyTransactionCategory): Response
    {
        if ($this->isCsrfTokenValid('delete'.$mstPropertyTransactionCategory->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($mstPropertyTransactionCategory);
            $entityManager->flush();
        }

        return $this->redirectToRoute('master_properties_transaction_category_index');
    }
}
