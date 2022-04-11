<?php

namespace App\Controller\Master;

use App\Service\CommonHelper;
use Exception;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use App\Entity\Master\MstProductCategory;
use App\Form\Master\MstProductCategoryType;
use App\Repository\Master\MstProductCategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Ramsey\Uuid\Uuid;

/**
 * @Route("/core/master/tred_product/product_category", name="master_product_category_")
 * @IsGranted("ROLE_SYS_MODULE_ADMIN")
 */
class MstProductCategoryController extends AbstractController
{
    /**
     * @Route("/", name="index", methods={"GET"})
     * @param MstProductCategoryRepository $mstProductCategoryRepository
     * @param Request $request
     * @return Response
     */
    public function index(MstProductCategoryRepository $mstProductCategoryRepository, Request $request): Response
    {
        $mstProductCategorys = $mstProductCategoryRepository->findAll();
        return $this->render('master/mst_product_category/index.html.twig', [
            'mst_product_categories' => $mstProductCategorys,
            'path_index' => 'master_product_category_index',
            'path_add' => 'master_product_category_add',
            'path_edit' => 'master_product_category_edit',
            'path_show' => 'master_product_category_show',
            'label_title' => 'label.product_category',
        ]);
    }

    /**
     * @Route("/add", name="add", methods={"GET","POST"})
     * @param Request $request
     * @return Response
     * @throws Exception
     */
    public function new(Request $request, CommonHelper $commonHelper): Response
    {
        $mstProductCategory = new MstProductCategory();
        $form = $this->createForm(MstProductCategoryType::class, $mstProductCategory);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $mstProductCategory->setRowId(Uuid::uuid4()->toString());
            $mstProductCategory->setProductCategorySlugName($commonHelper->slugify($form->get('productCategory')->getData()));
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($mstProductCategory);
            $entityManager->flush();
            $this->addFlash('success', 'form.created_successfully');
            return $this->redirectToRoute('master_product_category_index');
        }

        return $this->render('master/mst_product_category/form.html.twig', [
            'master_product_category' => $mstProductCategory,
            'form' => $form->createView(),
            'index_path' => 'master_product_category_index',
            'label_title' => 'label.product_category',
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
        $mstProductCategory = ucwords($request->query->get('product_categorySearch'));

        $mstProductCategory = $this->getDoctrine()->getRepository(MstProductCategory::class)->getCityListByCountryId($mstProductCategory, $countryId);
        return $this->render('master/mst_product_category/_ajax_listing.html.twig', [
            'mst_cities' => $mstProductCategory,
            'country_id' => $countryId,
            'path_add' => 'master_product_category_add',
            'path_edit' => 'master_product_category_edit',
            'path_show' => 'master_product_category_show',
            'label_title' => 'label.product_category',
            'mode' => 'add'
        ]);
    }

    /**
     * @Route("/edit/{id}", name="edit", methods={"GET","POST"})
     * @param Request $request
     * @param MstProductCategory $mstProductCategory
     * @return Response
     */
    public function edit(Request $request, MstProductCategory $mstProductCategory, CommonHelper $commonHelper): Response
    {
        $form = $this->createForm(MstProductCategoryType::class, $mstProductCategory);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $mstProductCategory->setProductCategorySlugName($commonHelper->slugify($form->get('productCategory')->getData()));
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', 'form.updated_successfully');
            return $this->redirectToRoute('master_product_category_index');
        }

        return $this->render('master/mst_product_category/form.html.twig', [
            'master_product_category' => $mstProductCategory,
            'form' => $form->createView(),
            'index_path' => 'master_product_category_index',
            'label_title' => 'label.product_category',
            'label_button' => 'label.update',
            'mode' => 'edit'
        ]);
    }

    /**
     * @Route("/{id}", name="delete", methods={"DELETE"})
     * @param Request $request
     * @param MstProductCategory $mstProductCategory
     * @return Response
     */
    public function delete(Request $request, MstProductCategory $mstProductCategory): Response
    {
        if ($this->isCsrfTokenValid('delete'.$mstProductCategory->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($mstProductCategory);
            $entityManager->flush();
        }

        return $this->redirectToRoute('master_product_category_index');
    }
}
