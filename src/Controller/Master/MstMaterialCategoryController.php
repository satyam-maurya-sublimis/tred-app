<?php

namespace App\Controller\Master;

use Doctrine\Persistence\ManagerRegistry;
use Exception;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use App\Entity\Master\MstMaterialCategory;
use App\Form\Master\MstMaterialCategoryType;
use App\Repository\Master\MstMaterialCategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Ramsey\Uuid\Uuid;

/**
 * @Route("/core/master/tred/material_category", name="master_material_category_")
 * @IsGranted("ROLE_SYS_CONTENT_USER")
 */
class MstMaterialCategoryController extends AbstractController
{
    private ManagerRegistry $managerRegistry;

    public function __construct(ManagerRegistry $managerRegistry)
    {
        $this->managerRegistry = $managerRegistry;
    }
    /**
     * @Route("/", name="index", methods={"GET"})
     * @param MstMaterialCategoryRepository $mstMaterialCategoryRepository
     * @param Request $request
     * @return Response
     */
    public function index(MstMaterialCategoryRepository $mstMaterialCategoryRepository, Request $request): Response
    {
        $mstMaterialCategory = $mstMaterialCategoryRepository->findAll();
        return $this->render('master/mst_material_category/index.html.twig', [
            'mst_material_categories' => $mstMaterialCategory,
            'path_index' => 'master_material_category_index',
            'path_add' => 'master_material_category_add',
            'path_edit' => 'master_material_category_edit',
            'path_show' => 'master_material_category_show',
            'label_title' => 'label.material_category',
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
        $mstMaterialCategory = new MstMaterialCategory();
        $form = $this->createForm(MstMaterialCategoryType::class, $mstMaterialCategory);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $mstMaterialCategory->setRowId(Uuid::uuid4()->toString());
            $entityManager = $this->managerRegistry->getManager();
            $entityManager->persist($mstMaterialCategory);
            $entityManager->flush();
            $this->addFlash('success', 'form.created_successfully');
            return $this->redirectToRoute('master_material_category_index');
        }

        return $this->render('form/form.html.twig', [
            'master_material_category' => $mstMaterialCategory,
            'form' => $form->createView(),
            'index_path' => 'master_material_category_index',
            'label_title' => 'label.material_category',
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
        $mstMaterialCategory = ucwords($request->query->get('material_categorySearch'));

        $mstMaterialCategory = $this->managerRegistry->getRepository(MstMaterialCategory::class)->getCityListByCountryId($mstMaterialCategory, $countryId);
        return $this->render('master/mst_material_category/_ajax_listing.html.twig', [
            'mst_cities' => $mstMaterialCategory,
            'country_id' => $countryId,
            'path_add' => 'master_material_category_add',
            'path_edit' => 'master_material_category_edit',
            'path_show' => 'master_material_category_show',
            'label_title' => 'label.material_category',
            'mode' => 'add'
        ]);
    }

    /**
     * @Route("/edit/{id}", name="edit", methods={"GET","POST"})
     * @param Request $request
     * @param MstMaterialCategory $mstMaterialCategory
     * @return Response
     */
    public function edit(Request $request, MstMaterialCategory $mstMaterialCategory): Response
    {
        $form = $this->createForm(MstMaterialCategoryType::class, $mstMaterialCategory);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->managerRegistry->getManager()->flush();
            $this->addFlash('success', 'form.updated_successfully');
            return $this->redirectToRoute('master_material_category_index');
        }

        return $this->render('form/form.html.twig', [
            'master_material_category' => $mstMaterialCategory,
            'form' => $form->createView(),
            'index_path' => 'master_material_category_index',
            'label_title' => 'label.material_category',
            'label_button' => 'label.update',
            'mode' => 'edit'
        ]);
    }

    /**
     * @Route("/{id}", name="delete", methods={"DELETE"})
     * @param Request $request
     * @param MstMaterialCategory $mstMaterialCategory
     * @return Response
     */
    public function delete(Request $request, MstMaterialCategory $mstMaterialCategory): Response
    {
        if ($this->isCsrfTokenValid('delete'.$mstMaterialCategory->getId(), $request->request->get('_token'))) {
            $entityManager = $this->managerRegistry->getManager();
            $entityManager->remove($mstMaterialCategory);
            $entityManager->flush();
        }

        return $this->redirectToRoute('master_material_category_index');
    }
}
