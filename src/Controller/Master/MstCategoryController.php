<?php

namespace App\Controller\Master;

use Doctrine\Persistence\ManagerRegistry;
use Exception;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use App\Entity\Master\MstCategory;
use App\Form\Master\MstCategoryType;
use App\Repository\Master\MstCategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Ramsey\Uuid\Uuid;

/**
 * @Route("/core/master/tred/category", name="master_category_")
 * @IsGranted("ROLE_SYS_CONTENT_USER")
 */
class MstCategoryController extends AbstractController
{
    private ManagerRegistry $managerRegistry;

    public function __construct(ManagerRegistry $managerRegistry)
    {
        $this->managerRegistry = $managerRegistry;
    }
    /**
     * @Route("/", name="index", methods={"GET"})
     * @param MstCategoryRepository $mstCategoryRepository
     * @param Request $request
     * @return Response
     */
    public function index(MstCategoryRepository $mstCategoryRepository, Request $request): Response
    {
        $mstCategory = $mstCategoryRepository->findAll();
        return $this->render('master/mst_category/index.html.twig', [
            'mst_categories' => $mstCategory,
            'path_index' => 'master_category_index',
            'path_add' => 'master_category_add',
            'path_edit' => 'master_category_edit',
            'path_show' => 'master_category_show',
            'label_title' => 'label.category',
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
        $mstCategory = new MstCategory();
        $form = $this->createForm(MstCategoryType::class, $mstCategory);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $mstCategory->setRowId(Uuid::uuid4()->toString());
            $entityManager = $this->managerRegistry->getManager();
            $entityManager->persist($mstCategory);
            $entityManager->flush();
            $this->addFlash('success', 'form.created_successfully');
            return $this->redirectToRoute('master_category_index');
        }

        return $this->render('form/form.html.twig', [
            'master_category' => $mstCategory,
            'form' => $form->createView(),
            'index_path' => 'master_category_index',
            'label_title' => 'label.category',
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
        $mstCategory = ucwords($request->query->get('categorySearch'));

        $mstCategory = $this->managerRegistry->getRepository(MstCategory::class)->getCityListByCountryId($mstCategory, $countryId);
        return $this->render('master/mst_category/_ajax_listing.html.twig', [
            'mst_cities' => $mstCategory,
            'country_id' => $countryId,
            'path_add' => 'master_category_add',
            'path_edit' => 'master_category_edit',
            'path_show' => 'master_category_show',
            'label_title' => 'label.category',
            'mode' => 'add'
        ]);
    }

    /**
     * @Route("/edit/{id}", name="edit", methods={"GET","POST"})
     * @param Request $request
     * @param MstCategory $mstCategory
     * @return Response
     */
    public function edit(Request $request, MstCategory $mstCategory): Response
    {
        $form = $this->createForm(MstCategoryType::class, $mstCategory);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->managerRegistry->getManager()->flush();
            $this->addFlash('success', 'form.updated_successfully');
            return $this->redirectToRoute('master_category_index');
        }

        return $this->render('form/form.html.twig', [
            'master_category' => $mstCategory,
            'form' => $form->createView(),
            'index_path' => 'master_category_index',
            'label_title' => 'label.category',
            'label_button' => 'label.update',
            'mode' => 'edit'
        ]);
    }

    /**
     * @Route("/{id}", name="delete", methods={"DELETE"})
     * @param Request $request
     * @param MstCategory $mstCategory
     * @return Response
     */
    public function delete(Request $request, MstCategory $mstCategory): Response
    {
        if ($this->isCsrfTokenValid('delete'.$mstCategory->getId(), $request->request->get('_token'))) {
            $entityManager = $this->managerRegistry->getManager();
            $entityManager->remove($mstCategory);
            $entityManager->flush();
        }

        return $this->redirectToRoute('master_category_index');
    }
}
