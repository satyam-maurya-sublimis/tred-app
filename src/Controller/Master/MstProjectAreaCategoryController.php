<?php

namespace App\Controller\Master;

use Doctrine\Persistence\ManagerRegistry;
use Exception;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use App\Entity\Master\MstProjectAreaCategory;
use App\Form\Master\MstProjectAreaCategoryType;
use App\Repository\Master\MstProjectAreaCategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Ramsey\Uuid\Uuid;

/**
 * @Route("/core/master/tred_project/project_area_type", name="master_project_area_type_")
 * @IsGranted("ROLE_SYS_MODULE_ADMIN")
 */
class MstProjectAreaCategoryController extends AbstractController
{
    private ManagerRegistry $managerRegistry;

    public function __construct(ManagerRegistry $managerRegistry)
    {
        $this->managerRegistry = $managerRegistry;
    }
    /**
     * @Route("/", name="index", methods={"GET"})
     * @param MstProjectAreaCategoryRepository $mstProjectAreaCategoryRepository
     * @param Request $request
     * @return Response
     */
    public function index(MstProjectAreaCategoryRepository $mstProjectAreaCategoryRepository, Request $request): Response
    {
        $project_area_types = $mstProjectAreaCategoryRepository->findAll();
        return $this->render('master/mst_project_area_type/index.html.twig', [
            'project_area_types' => $project_area_types,
            'path_index' => 'master_project_area_type_index',
            'path_add' => 'master_project_area_type_add',
            'path_edit' => 'master_project_area_type_edit',
            'path_show' => 'master_project_area_type_show',
            'label_title' => 'label.project_area_type',
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
        $mstProjectAreaCategory = new MstProjectAreaCategory();
        $form = $this->createForm(MstProjectAreaCategoryType::class, $mstProjectAreaCategory);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $mstProjectAreaCategory->setRowId(Uuid::uuid4()->toString());
            $entityManager = $this->managerRegistry->getManager();
            $entityManager->persist($mstProjectAreaCategory);
            $entityManager->flush();
            $this->addFlash('success', 'form.created_successfully');
            return $this->redirectToRoute('master_project_area_type_index');
        }

        return $this->render('form/form.html.twig', [
            'master_project_area_type' => $mstProjectAreaCategory,
            'form' => $form->createView(),
            'index_path' => 'master_project_area_type_index',
            'label_title' => 'label.project_area_type',
            'label_button' => 'label.create',
            'mode' => 'add'
        ]);
    }

    /**
     * @Route("/edit/{id}", name="edit", methods={"GET","POST"})
     * @param Request $request
     * @param MstProjectAreaCategory $mstProjectAreaCategory
     * @return Response
     */
    public function edit(Request $request, MstProjectAreaCategory $mstProjectAreaCategory): Response
    {
        $form = $this->createForm(MstProjectAreaCategoryType::class, $mstProjectAreaCategory);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->managerRegistry->getManager()->flush();
            $this->addFlash('success', 'form.updated_successfully');
            return $this->redirectToRoute('master_project_area_type_index');
        }

        return $this->render('form/form.html.twig', [
            'master_project_area_type' => $mstProjectAreaCategory,
            'form' => $form->createView(),
            'index_path' => 'master_project_area_type_index',
            'label_title' => 'label.project_area_type',
            'label_button' => 'label.update',
            'mode' => 'edit'
        ]);
    }

    /**
     * @Route("/{id}", name="delete", methods={"DELETE"})
     * @param Request $request
     * @param MstProjectAreaCategory $mstProjectAreaCategory
     * @return Response
     */
    public function delete(Request $request, MstProjectAreaCategory $mstProjectAreaCategory): Response
    {
        if ($this->isCsrfTokenValid('delete'.$mstProjectAreaCategory->getId(), $request->request->get('_token'))) {
            $entityManager = $this->managerRegistry->getManager();
            $entityManager->remove($mstProjectAreaCategory);
            $entityManager->flush();
        }

        return $this->redirectToRoute('master_project_area_type_index');
    }
}
