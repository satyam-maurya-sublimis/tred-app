<?php

namespace App\Controller\Master;

use Exception;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use App\Entity\Master\MstProjectFloor;
use App\Form\Master\MstProjectFloorType;
use App\Repository\Master\MstProjectFloorRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Ramsey\Uuid\Uuid;

/**
 * @Route("/core/master/tred_project/project_floor", name="master_project_floor_")
 * @IsGranted("ROLE_SYS_CONTENT_USER")
 */
class MstProjectFloorController extends AbstractController
{
    /**
     * @Route("/", name="index", methods={"GET"})
     * @param MstProjectFloorRepository $mstProjectFloorRepository
     * @param Request $request
     * @return Response
     */
    public function index(MstProjectFloorRepository $mstProjectFloorRepository, Request $request): Response
    {
        $project_floor = $mstProjectFloorRepository->findAll();
        return $this->render('master/mst_project_floor/index.html.twig', [
            'project_floors' => $project_floor,
            'path_index' => 'master_project_floor_index',
            'path_add' => 'master_project_floor_add',
            'path_edit' => 'master_project_floor_edit',
            'path_show' => 'master_project_floor_show',
            'label_title' => 'label.project_floor',
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
        $mstProjectFloor = new MstProjectFloor();
        $form = $this->createForm(MstProjectFloorType::class, $mstProjectFloor);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $mstProjectFloor->setRowId(Uuid::uuid4()->toString());
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($mstProjectFloor);
            $entityManager->flush();
            $this->addFlash('success', 'form.created_successfully');
            return $this->redirectToRoute('master_project_floor_index');
        }

        return $this->render('form/form.html.twig', [
            'master_project_floor' => $mstProjectFloor,
            'form' => $form->createView(),
            'index_path' => 'master_project_floor_index',
            'label_title' => 'label.project_floor',
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
        $project_floor = ucwords($request->query->get('project_floorSearch'));

        $mstProjectFloor = $this->getDoctrine()->getRepository(MstProjectFloor::class)->getCityListByCountryId($project_floor, $countryId);
        return $this->render('master/mst_project_floor/_ajax_listing.html.twig', [
            'mst_cities' => $mstProjectFloor,
            'country_id' => $countryId,
            'path_add' => 'master_project_floor_add',
            'path_edit' => 'master_project_floor_edit',
            'path_show' => 'master_project_floor_show',
            'label_title' => 'label.project_floor',
            'mode' => 'add'
        ]);
    }

    /**
     * @Route("/edit/{id}", name="edit", methods={"GET","POST"})
     * @param Request $request
     * @param MstProjectFloor $mstProjectFloor
     * @return Response
     */
    public function edit(Request $request, MstProjectFloor $mstProjectFloor): Response
    {
        $form = $this->createForm(MstProjectFloorType::class, $mstProjectFloor);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', 'form.updated_successfully');
            return $this->redirectToRoute('master_project_floor_index');
        }

        return $this->render('form/form.html.twig', [
            'master_project_floor' => $mstProjectFloor,
            'form' => $form->createView(),
            'index_path' => 'master_project_floor_index',
            'label_title' => 'label.project_floor',
            'label_button' => 'label.update',
            'mode' => 'edit'
        ]);
    }

    /**
     * @Route("/{id}", name="delete", methods={"DELETE"})
     * @param Request $request
     * @param MstProjectFloor $mstProjectFloor
     * @return Response
     */
    public function delete(Request $request, MstProjectFloor $mstProjectFloor): Response
    {
        if ($this->isCsrfTokenValid('delete'.$mstProjectFloor->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($mstProjectFloor);
            $entityManager->flush();
        }

        return $this->redirectToRoute('master_project_floor_index');
    }
}
