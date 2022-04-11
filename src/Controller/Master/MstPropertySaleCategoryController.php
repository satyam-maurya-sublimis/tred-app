<?php

namespace App\Controller\Master;

use Exception;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use App\Entity\Master\MstPropertySaleCategory;
use App\Form\Master\MstPropertySaleCategoryType;
use App\Repository\Master\MstPropertySaleCategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Ramsey\Uuid\Uuid;

/**
 * @Route("/core/master/tred_project/properties_sale_type", name="master_properties_sale_category_")
 * @IsGranted("ROLE_SYS_CONTENT_USER")
 */
class MstPropertySaleCategoryController extends AbstractController
{
    /**
     * @Route("/", name="index", methods={"GET"})
     * @param MstPropertySaleCategoryRepository $mstPropertySaleCategoryRepository
     * @param Request $request
     * @return Response
     */
    public function index(MstPropertySaleCategoryRepository $mstPropertySaleCategoryRepository, Request $request): Response
    {
        $mstPropertySaleCategory = $mstPropertySaleCategoryRepository->findAll();
        return $this->render('master/mst_property_sale_category/index.html.twig', [
            'master_properties_sale_category' => $mstPropertySaleCategory,
            'path_index' => 'master_properties_sale_category_index',
            'path_add' => 'master_properties_sale_category_add',
            'path_edit' => 'master_properties_sale_category_edit',
            'path_show' => 'master_properties_sale_category_show',
            'label_title' => 'label.property_sale_category',
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
        $mstPropertySaleCategory = new MstPropertySaleCategory();
        $form = $this->createForm(MstPropertySaleCategoryType::class, $mstPropertySaleCategory);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $mstPropertySaleCategory->setRowId(Uuid::uuid4()->toString());
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($mstPropertySaleCategory);
            $entityManager->flush();
            $this->addFlash('success', 'form.created_successfully');
            return $this->redirectToRoute('master_properties_sale_category_index');
        }

        return $this->render('form/form.html.twig', [
            'master_properties_sale_category' => $mstPropertySaleCategory,
            'form' => $form->createView(),
            'index_path' => 'master_properties_sale_category_index',
            'label_title' => 'label.property_sale_category',
            'label_button' => 'label.create',
            'mode' => 'add'
        ]);
    }

    /**
     * @Route("/edit/{id}", name="edit", methods={"GET","POST"})
     * @param Request $request
     * @param MstPropertySaleCategory $mstPropertySaleCategory
     * @return Response
     */
    public function edit(Request $request, MstPropertySaleCategory $mstPropertySaleCategory): Response
    {
        $form = $this->createForm(MstPropertySaleCategoryType::class, $mstPropertySaleCategory);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', 'form.updated_successfully');
            return $this->redirectToRoute('master_properties_sale_category_index');
        }

        return $this->render('form/form.html.twig', [
            'master_properties_sale_category' => $mstPropertySaleCategory,
            'form' => $form->createView(),
            'index_path' => 'master_properties_sale_category_index',
            'label_title' => 'label.property_sale_category',
            'label_button' => 'label.update',
            'mode' => 'edit'
        ]);
    }

    /**
     * @Route("/{id}", name="delete", methods={"DELETE"})
     * @param Request $request
     * @param MstPropertySaleCategory $mstPropertySaleCategory
     * @return Response
     */
    public function delete(Request $request, MstPropertySaleCategory $mstPropertySaleCategory): Response
    {
        if ($this->isCsrfTokenValid('delete'.$mstPropertySaleCategory->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($mstPropertySaleCategory);
            $entityManager->flush();
        }
        return $this->redirectToRoute('master_properties_sale_category_index');
    }
}
