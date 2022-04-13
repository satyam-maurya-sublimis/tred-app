<?php

namespace App\Controller\Master;

use Doctrine\Persistence\ManagerRegistry;
use Exception;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use App\Entity\Master\MstSubCategory;
use App\Form\Master\MstSubCategoryType;
use App\Repository\Master\MstSubCategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Ramsey\Uuid\Uuid;

/**
 * @Route("/core/master/tred/sub_category", name="master_sub_category_")
 * @IsGranted("ROLE_SYS_CONTENT_USER")
 */
class MstSubCategoryController extends AbstractController
{
    private ManagerRegistry $managerRegistry;

    public function __construct(ManagerRegistry $managerRegistry)
    {
        $this->managerRegistry = $managerRegistry;
    }
    /**
     * @Route("/", name="index", methods={"GET"})
     * @param MstSubCategoryRepository $mstSubCategoryRepository
     * @param Request $request
     * @return Response
     */
    public function index(MstSubCategoryRepository $mstSubCategoryRepository, Request $request): Response
    {
        $mstSubCategory = $mstSubCategoryRepository->findAll();
        return $this->render('master/mst_sub_category/index.html.twig', [
            'mst_sub_categories' => $mstSubCategory,
            'path_index' => 'master_sub_category_index',
            'path_add' => 'master_sub_category_add',
            'path_edit' => 'master_sub_category_edit',
            'path_show' => 'master_sub_category_show',
            'label_title' => 'label.sub_category',
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
        $mstSubCategory = new MstSubCategory();
        $form = $this->createForm(MstSubCategoryType::class, $mstSubCategory);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $mstSubCategory->setRowId(Uuid::uuid4()->toString());
            $entityManager = $this->managerRegistry->getManager();
            $entityManager->persist($mstSubCategory);
            $entityManager->flush();
            $this->addFlash('success', 'form.created_successfully');
            return $this->redirectToRoute('master_sub_category_index');
        }

        return $this->render('form/form.html.twig', [
            'master_sub_category' => $mstSubCategory,
            'form' => $form->createView(),
            'index_path' => 'master_sub_category_index',
            'label_title' => 'label.sub_category',
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
        $mstSubCategory = ucwords($request->query->get('sub_categorySearch'));

        $mstSubCategory = $this->managerRegistry->getRepository(MstSubCategory::class)->getCityListByCountryId($mstSubCategory, $countryId);
        return $this->render('master/mst_sub_category/_ajax_listing.html.twig', [
            'mst_cities' => $mstSubCategory,
            'country_id' => $countryId,
            'path_add' => 'master_sub_category_add',
            'path_edit' => 'master_sub_category_edit',
            'path_show' => 'master_sub_category_show',
            'label_title' => 'label.sub_category',
            'mode' => 'add'
        ]);
    }

    /**
     * @Route("/edit/{id}", name="edit", methods={"GET","POST"})
     * @param Request $request
     * @param MstSubCategory $mstSubCategory
     * @return Response
     */
    public function edit(Request $request, MstSubCategory $mstSubCategory): Response
    {
        $form = $this->createForm(MstSubCategoryType::class, $mstSubCategory);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->managerRegistry->getManager()->flush();
            $this->addFlash('success', 'form.updated_successfully');
            return $this->redirectToRoute('master_sub_category_index');
        }

        return $this->render('form/form.html.twig', [
            'master_sub_category' => $mstSubCategory,
            'form' => $form->createView(),
            'index_path' => 'master_sub_category_index',
            'label_title' => 'label.sub_category',
            'label_button' => 'label.update',
            'mode' => 'edit'
        ]);
    }

    /**
     * @Route("/{id}", name="delete", methods={"DELETE"})
     * @param Request $request
     * @param MstSubCategory $mstSubCategory
     * @return Response
     */
    public function delete(Request $request, MstSubCategory $mstSubCategory): Response
    {
        if ($this->isCsrfTokenValid('delete'.$mstSubCategory->getId(), $request->request->get('_token'))) {
            $entityManager = $this->managerRegistry->getManager();
            $entityManager->remove($mstSubCategory);
            $entityManager->flush();
        }

        return $this->redirectToRoute('master_sub_category_index');
    }
}
