<?php

namespace App\Controller\Master;

use Exception;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use App\Entity\Master\MstProjectArea;
use App\Form\Master\MstProjectAreaType;
use App\Repository\Master\MstProjectAreaRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Ramsey\Uuid\Uuid;

/**
 * @Route("/core/master/tred_project/project_area", name="master_project_area_")
 * @IsGranted("ROLE_SYS_CONTENT_USER")
 */
class MstProjectAreaController extends AbstractController
{
    /**
     * @Route("/", name="index", methods={"GET"})
     * @param MstProjectAreaRepository $mstProjectAreaRepository
     * @param Request $request
     * @return Response
     */
    public function index(MstProjectAreaRepository $mstProjectAreaRepository, Request $request): Response
    {
        $project_area = $mstProjectAreaRepository->findAll();
        return $this->render('master/mst_project_area/index.html.twig', [
            'project_areas' => $project_area,
            'path_index' => 'master_project_area_index',
            'path_add' => 'master_project_area_add',
            'path_edit' => 'master_project_area_edit',
            'path_show' => 'master_project_area_show',
            'label_title' => 'label.project_area',
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
        $mstProjectArea = new MstProjectArea();
        $form = $this->createForm(MstProjectAreaType::class, $mstProjectArea);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $mstProjectArea->setRowId(Uuid::uuid4()->toString());
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($mstProjectArea);
            $entityManager->flush();
            $this->addFlash('success', 'form.created_successfully');
            return $this->redirectToRoute('master_project_area_index');
        }

        return $this->render('form/form.html.twig', [
            'master_project_area' => $mstProjectArea,
            'form' => $form->createView(),
            'index_path' => 'master_project_area_index',
            'label_title' => 'label.project_area',
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
        $project_area = ucwords($request->query->get('project_areaSearch'));

        $mstProjectArea = $this->getDoctrine()->getRepository(MstProjectArea::class)->getCityListByCountryId($project_area, $countryId);
        return $this->render('master/mst_project_area/_ajax_listing.html.twig', [
            'mst_cities' => $mstProjectArea,
            'country_id' => $countryId,
            'path_add' => 'master_project_area_add',
            'path_edit' => 'master_project_area_edit',
            'path_show' => 'master_project_area_show',
            'label_title' => 'label.project_area',
            'mode' => 'add'
        ]);
    }

    /**
     * @Route("/edit/{id}", name="edit", methods={"GET","POST"})
     * @param Request $request
     * @param MstProjectArea $mstProjectArea
     * @return Response
     */
    public function edit(Request $request, MstProjectArea $mstProjectArea): Response
    {
        $form = $this->createForm(MstProjectAreaType::class, $mstProjectArea);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', 'form.updated_successfully');
            return $this->redirectToRoute('master_project_area_index');
        }

        return $this->render('form/form.html.twig', [
            'master_project_area' => $mstProjectArea,
            'form' => $form->createView(),
            'index_path' => 'master_project_area_index',
            'label_title' => 'label.project_area',
            'label_button' => 'label.update',
            'mode' => 'edit'
        ]);
    }

    /**
     * @Route("/{id}", name="delete", methods={"DELETE"})
     * @param Request $request
     * @param MstProjectArea $mstProjectArea
     * @return Response
     */
    public function delete(Request $request, MstProjectArea $mstProjectArea): Response
    {
        if ($this->isCsrfTokenValid('delete'.$mstProjectArea->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($mstProjectArea);
            $entityManager->flush();
        }

        return $this->redirectToRoute('master_project_area_index');
    }
}
