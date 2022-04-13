<?php

namespace App\Controller\Master;

use Doctrine\Persistence\ManagerRegistry;
use Exception;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use App\Entity\Master\MstDepartment;
use App\Form\Master\MstDepartmentType;
use App\Repository\Master\MstDepartmentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Ramsey\Uuid\Uuid;

/**
 * @Route("/core/master/general/department", name="master_department_")
 * @IsGranted("ROLE_SYS_CONTENT_USER")
 */
class MstDepartmentController extends AbstractController
{
    private ManagerRegistry $managerRegistry;

    public function __construct(ManagerRegistry $managerRegistry)
    {
        $this->managerRegistry = $managerRegistry;
    }
    /**
     * @Route("/", name="index", methods={"GET"})
     * @param MstDepartmentRepository $mstDepartmentRepository
     * @param Request $request
     * @return Response
     */
    public function index(MstDepartmentRepository $mstDepartmentRepository, Request $request): Response
    {
        $department = $mstDepartmentRepository->findAll();
        return $this->render('master/mst_department/index.html.twig', [
            'departments' => $department,
            'path_index' => 'master_department_index',
            'path_add' => 'master_department_add',
            'path_edit' => 'master_department_edit',
            'path_show' => 'master_department_show',
            'label_title' => 'label.department',
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
        $mstDepartment = new MstDepartment();
        $form = $this->createForm(MstDepartmentType::class, $mstDepartment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $mstDepartment->setRowId(Uuid::uuid4()->toString());
            $entityManager = $this->managerRegistry->getManager();
            $entityManager->persist($mstDepartment);
            $entityManager->flush();
            $this->addFlash('success', 'form.created_successfully');
            return $this->redirectToRoute('master_department_index');
        }

        return $this->render('form/form.html.twig', [
            'master_department' => $mstDepartment,
            'form' => $form->createView(),
            'index_path' => 'master_department_index',
            'label_title' => 'label.department',
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
        $department = ucwords($request->query->get('departmentSearch'));

        $mstDepartment = $this->managerRegistry->getRepository(MstDepartment::class)->getCityListByCountryId($department, $countryId);
        return $this->render('master/mst_department/_ajax_listing.html.twig', [
            'mst_cities' => $mstDepartment,
            'country_id' => $countryId,
            'path_add' => 'master_department_add',
            'path_edit' => 'master_department_edit',
            'path_show' => 'master_department_show',
            'label_title' => 'label.department',
            'mode' => 'add'
        ]);
    }

    /**
     * @Route("/edit/{id}", name="edit", methods={"GET","POST"})
     * @param Request $request
     * @param MstDepartment $mstDepartment
     * @return Response
     */
    public function edit(Request $request, MstDepartment $mstDepartment): Response
    {
        $form = $this->createForm(MstDepartmentType::class, $mstDepartment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->managerRegistry->getManager()->flush();
            $this->addFlash('success', 'form.updated_successfully');
            return $this->redirectToRoute('master_department_index');
        }

        return $this->render('form/form.html.twig', [
            'master_department' => $mstDepartment,
            'form' => $form->createView(),
            'index_path' => 'master_department_index',
            'label_title' => 'label.department',
            'label_button' => 'label.update',
            'mode' => 'edit'
        ]);
    }

    /**
     * @Route("/{id}", name="delete", methods={"DELETE"})
     * @param Request $request
     * @param MstDepartment $mstDepartment
     * @return Response
     */
    public function delete(Request $request, MstDepartment $mstDepartment): Response
    {
        if ($this->isCsrfTokenValid('delete'.$mstDepartment->getId(), $request->request->get('_token'))) {
            $entityManager = $this->managerRegistry->getManager();
            $entityManager->remove($mstDepartment);
            $entityManager->flush();
        }

        return $this->redirectToRoute('master_department_index');
    }
}
