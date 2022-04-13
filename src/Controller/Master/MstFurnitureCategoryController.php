<?php

namespace App\Controller\Master;

use Doctrine\Persistence\ManagerRegistry;
use Exception;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use App\Entity\Master\MstFurnitureCategory;
use App\Form\Master\MstFurnitureCategoryType;
use App\Repository\Master\MstFurnitureCategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Ramsey\Uuid\Uuid;

/**
 * @Route("/core/master/tred_product/furniture_category", name="master_furniture_category_")
 * @IsGranted("ROLE_SYS_CONTENT_USER")
 */
class MstFurnitureCategoryController extends AbstractController
{
    private ManagerRegistry $managerRegistry;

    public function __construct(ManagerRegistry $managerRegistry)
    {
        $this->managerRegistry = $managerRegistry;
    }
    /**
     * @Route("/", name="index", methods={"GET"})
     * @param MstFurnitureCategoryRepository $mstFurnitureCategoryRepository
     * @param Request $request
     * @return Response
     */
    public function index(MstFurnitureCategoryRepository $mstFurnitureCategoryRepository, Request $request): Response
    {
        $furniture_category = $mstFurnitureCategoryRepository->findAll();
        return $this->render('master/mst_furniture_category/index.html.twig', [
            'mst_furniture_categorys' => $furniture_category,
            'path_index' => 'master_furniture_category_index',
            'path_add' => 'master_furniture_category_add',
            'path_edit' => 'master_furniture_category_edit',
            'path_show' => 'master_furniture_category_show',
            'label_title' => 'label.furniture_category',
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
        $mstFurnitureCategory = new MstFurnitureCategory();
        $form = $this->createForm(MstFurnitureCategoryType::class, $mstFurnitureCategory);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $mstFurnitureCategory->setRowId(Uuid::uuid4()->toString());
            $entityManager = $this->managerRegistry->getManager();
            $entityManager->persist($mstFurnitureCategory);
            $entityManager->flush();
            $this->addFlash('success', 'form.created_successfully');
            return $this->redirectToRoute('master_furniture_category_index');
        }

        return $this->render('master/mst_furniture_category/form.html.twig', [
            'master_furniture_category' => $mstFurnitureCategory,
            'form' => $form->createView(),
            'index_path' => 'master_furniture_category_index',
            'label_title' => 'label.furniture_category',
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
        $furniture_category = ucwords($request->query->get('furniture_categorySearch'));

        $mstFurnitureCategory = $this->managerRegistry->getRepository(MstFurnitureCategory::class)->getCityListByCountryId($furniture_category, $countryId);
        return $this->render('master/mst_furniture_category/_ajax_listing.html.twig', [
            'mstFurnitureCategory' => $mstFurnitureCategory,
            'path_add' => 'master_furniture_category_add',
            'path_edit' => 'master_furniture_category_edit',
            'path_show' => 'master_furniture_category_show',
            'label_title' => 'label.furniture_category',
            'mode' => 'add'
        ]);
    }

    /**
     * @Route("/edit/{id}", name="edit", methods={"GET","POST"})
     * @param Request $request
     * @param MstFurnitureCategory $mstFurnitureCategory
     * @return Response
     */
    public function edit(Request $request, MstFurnitureCategory $mstFurnitureCategory): Response
    {
        $form = $this->createForm(MstFurnitureCategoryType::class, $mstFurnitureCategory);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->managerRegistry->getManager()->flush();
            $this->addFlash('success', 'form.updated_successfully');
            return $this->redirectToRoute('master_furniture_category_index');
        }

        return $this->render('master/mst_furniture_category/form.html.twig', [
            'master_furniture_category' => $mstFurnitureCategory,
            'form' => $form->createView(),
            'index_path' => 'master_furniture_category_index',
            'label_title' => 'label.furniture_category',
            'label_button' => 'label.update',
            'mode' => 'edit'
        ]);
    }

    /**
     * @Route("/{id}", name="delete", methods={"DELETE"})
     * @param Request $request
     * @param MstFurnitureCategory $mstFurnitureCategory
     * @return Response
     */
    public function delete(Request $request, MstFurnitureCategory $mstFurnitureCategory): Response
    {
        if ($this->isCsrfTokenValid('delete'.$mstFurnitureCategory->getId(), $request->request->get('_token'))) {
            $entityManager = $this->managerRegistry->getManager();
            $entityManager->remove($mstFurnitureCategory);
            $entityManager->flush();
        }

        return $this->redirectToRoute('master_furniture_category_index');
    }
}
