<?php

namespace App\Controller\Master;

use Doctrine\Persistence\ManagerRegistry;
use Exception;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use App\Entity\Master\MstProjectSpecification;
use App\Form\Master\MstProjectSpecificationType;
use App\Repository\Master\MstProjectSpecificationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Ramsey\Uuid\Uuid;

/**
 * @Route("/core/master/tred_project/project_specification", name="master_project_specification_")
 * @IsGranted("ROLE_SYS_CONTENT_USER")
 */
class MstProjectSpecificationController extends AbstractController
{
    private ManagerRegistry $managerRegistry;

    public function __construct(ManagerRegistry $managerRegistry)
    {
        $this->managerRegistry = $managerRegistry;
    }
    /**
     * @Route("/", name="index", methods={"GET"})
     * @param MstProjectSpecificationRepository $mstProjectSpecificationRepository
     * @param Request $request
     * @return Response
     */
    public function index(MstProjectSpecificationRepository $mstProjectSpecificationRepository, Request $request): Response
    {
        $project_specifications = $mstProjectSpecificationRepository->findAll();
        return $this->render('master/mst_project_specification/index.html.twig', [
            'mst_project_specifications' => $project_specifications,
            'path_index' => 'master_project_specification_index',
            'path_add' => 'master_project_specification_add',
            'path_edit' => 'master_project_specification_edit',
            'path_show' => 'master_project_specification_show',
            'label_title' => 'label.project_specification',
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
        $mstProjectSpecification = new MstProjectSpecification();
        $form = $this->createForm(MstProjectSpecificationType::class, $mstProjectSpecification);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $mstProjectSpecification->setRowId(Uuid::uuid4()->toString());
            $entityManager = $this->managerRegistry->getManager();
            $entityManager->persist($mstProjectSpecification);
            $entityManager->flush();
            $this->addFlash('success', 'form.created_successfully');
            return $this->redirectToRoute('master_project_specification_index');
        }

        return $this->render('form/form.html.twig', [
            'master_project_specification' => $mstProjectSpecification,
            'form' => $form->createView(),
            'index_path' => 'master_project_specification_index',
            'label_title' => 'label.project_specification',
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
        $project_specification = ucwords($request->query->get('project_specificationSearch'));

        $mstProjectSpecification = $this->managerRegistry->getRepository(MstProjectSpecification::class)->getCityListByCountryId($project_specification, $countryId);
        return $this->render('master/mst_project_specification/_ajax_listing.html.twig', [
            'mst_cities' => $mstProjectSpecification,
            'country_id' => $countryId,
            'path_add' => 'master_project_specification_add',
            'path_edit' => 'master_project_specification_edit',
            'path_show' => 'master_project_specification_show',
            'label_title' => 'label.project_specification',
            'mode' => 'add'
        ]);
    }

    /**
     * @Route("/edit/{id}", name="edit", methods={"GET","POST"})
     * @param Request $request
     * @param MstProjectSpecification $mstProjectSpecification
     * @return Response
     */
    public function edit(Request $request, MstProjectSpecification $mstProjectSpecification): Response
    {
        $form = $this->createForm(MstProjectSpecificationType::class, $mstProjectSpecification);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->managerRegistry->getManager()->flush();
            $this->addFlash('success', 'form.updated_successfully');
            return $this->redirectToRoute('master_project_specification_index');
        }

        return $this->render('form/form.html.twig', [
            'master_project_specification' => $mstProjectSpecification,
            'form' => $form->createView(),
            'index_path' => 'master_project_specification_index',
            'label_title' => 'label.project_specification',
            'label_button' => 'label.update',
            'mode' => 'edit'
        ]);
    }

    /**
     * @Route("/{id}", name="delete", methods={"DELETE"})
     * @param Request $request
     * @param MstProjectSpecification $mstProjectSpecification
     * @return Response
     */
    public function delete(Request $request, MstProjectSpecification $mstProjectSpecification): Response
    {
        if ($this->isCsrfTokenValid('delete'.$mstProjectSpecification->getId(), $request->request->get('_token'))) {
            $entityManager = $this->managerRegistry->getManager();
            $entityManager->remove($mstProjectSpecification);
            $entityManager->flush();
        }

        return $this->redirectToRoute('master_project_specification_index');
    }
}
