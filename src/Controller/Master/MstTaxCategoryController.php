<?php

namespace App\Controller\Master;

use Exception;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use App\Entity\Master\MstTaxCategory;
use App\Form\Master\MstTaxCategoryType;
use App\Repository\Master\MstTaxCategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Ramsey\Uuid\Uuid;

/**
 * @Route("/core/master/commercial/tax_category", name="master_tax_category_")
 * @IsGranted("ROLE_SYS_CONTENT_USER")
 */
class MstTaxCategoryController extends AbstractController
{
    /**
     * @Route("/", name="index", methods={"GET"})
     * @param MstTaxCategoryRepository $mstTaxCategoryRepository
     * @param Request $request
     * @return Response
     */
    public function index(MstTaxCategoryRepository $mstTaxCategoryRepository, Request $request): Response
    {
        $mstTaxCategory = $mstTaxCategoryRepository->findAll();
        return $this->render('master/mst_tax_category/index.html.twig', [
            'mst_tax_categories' => $mstTaxCategory,
            'path_index' => 'master_tax_category_index',
            'path_add' => 'master_tax_category_add',
            'path_edit' => 'master_tax_category_edit',
            'path_show' => 'master_tax_category_show',
            'label_title' => 'label.tax_category',
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
        $mstTaxCategory = new MstTaxCategory();
        $form = $this->createForm(MstTaxCategoryType::class, $mstTaxCategory);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $mstTaxCategory->setRowId(Uuid::uuid4()->toString());
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($mstTaxCategory);
            $entityManager->flush();
            $this->addFlash('success', 'form.created_successfully');
            return $this->redirectToRoute('master_tax_category_index');
        }

        return $this->render('form/form.html.twig', [
            'master_tax_category' => $mstTaxCategory,
            'form' => $form->createView(),
            'index_path' => 'master_tax_category_index',
            'label_title' => 'label.tax_category',
            'label_button' => 'label.create',
            'mode' => 'add'
        ]);
    }

    /**
     * @Route("/search", name="search", methods={"GET","POST"})
     * @param Request $request
     * @return Response
     */
    public function search(Request $request): Response
    {
        $countryId = trim($request->query->get('countryId'));
        $mstTaxCategory = ucwords($request->query->get('tax_categorySearch'));

        $mstTaxCategory = $this->getDoctrine()->getRepository(MstTaxCategory::class)->getCityListByCountryId($mstTaxCategory, $countryId);
        return $this->render('master/mst_tax_category/_ajax_listing.html.twig', [
            'mst_cities' => $mstTaxCategory,
            'country_id' => $countryId,
            'path_add' => 'master_tax_category_add',
            'path_edit' => 'master_tax_category_edit',
            'path_show' => 'master_tax_category_show',
            'label_title' => 'label.tax_category',
            'mode' => 'add'
        ]);
    }

    /**
     * @Route("/edit/{id}", name="edit", methods={"GET","POST"})
     * @param Request $request
     * @param MstTaxCategory $mstTaxCategory
     * @return Response
     */
    public function edit(Request $request, MstTaxCategory $mstTaxCategory): Response
    {
        $form = $this->createForm(MstTaxCategoryType::class, $mstTaxCategory);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', 'form.updated_successfully');
            return $this->redirectToRoute('master_tax_category_index');
        }

        return $this->render('form/form.html.twig', [
            'master_tax_category' => $mstTaxCategory,
            'form' => $form->createView(),
            'index_path' => 'master_tax_category_index',
            'label_title' => 'label.tax_category',
            'label_button' => 'label.update',
            'mode' => 'edit'
        ]);
    }

    /**
     * @Route("/{id}", name="delete", methods={"DELETE"})
     * @param Request $request
     * @param MstTaxCategory $mstTaxCategory
     * @return Response
     */
    public function delete(Request $request, MstTaxCategory $mstTaxCategory): Response
    {
        if ($this->isCsrfTokenValid('delete'.$mstTaxCategory->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($mstTaxCategory);
            $entityManager->flush();
        }

        return $this->redirectToRoute('master_tax_category_index');
    }
}
