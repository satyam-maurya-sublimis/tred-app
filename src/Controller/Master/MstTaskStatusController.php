<?php

namespace App\Controller\Master;

use Exception;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use App\Entity\Master\MstTaskStatus;
use App\Form\Master\MstTaskStatusType;
use App\Repository\Master\MstTaskStatusRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Ramsey\Uuid\Uuid;

/**
 * @Route("/core/master/general/task_status", name="master_task_status_")
 * @IsGranted("ROLE_SYS_CONTENT_USER")
 */
class MstTaskStatusController extends AbstractController
{
    /**
     * @Route("/", name="index", methods={"GET"})
     * @param MstTaskStatusRepository $mstTaskStatusRepository
     * @param Request $request
     * @return Response
     */
    public function index(MstTaskStatusRepository $mstTaskStatusRepository, Request $request): Response
    {
        $mstTaskStatus = $mstTaskStatusRepository->findAll();
        return $this->render('master/mst_task_status/index.html.twig', [
            'mst_task_statuses' => $mstTaskStatus,
            'path_index' => 'master_task_status_index',
            'path_add' => 'master_task_status_add',
            'path_edit' => 'master_task_status_edit',
            'path_show' => 'master_task_status_show',
            'label_title' => 'label.task_status',
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
        $mstTaskStatus = new MstTaskStatus();
        $form = $this->createForm(MstTaskStatusType::class, $mstTaskStatus);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $mstTaskStatus->setRowId(Uuid::uuid4()->toString());
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($mstTaskStatus);
            $entityManager->flush();
            $this->addFlash('success', 'form.created_successfully');
            return $this->redirectToRoute('master_task_status_index');
        }

        return $this->render('form/form.html.twig', [
            'master_task_status' => $mstTaskStatus,
            'form' => $form->createView(),
            'index_path' => 'master_task_status_index',
            'label_title' => 'label.task_status',
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
        $mstTaskStatus = ucwords($request->query->get('task_statusSearch'));

        $mstTaskStatus = $this->getDoctrine()->getRepository(MstTaskStatus::class)->getCityListByCountryId($mstTaskStatus, $countryId);
        return $this->render('master/mst_task_status/_ajax_listing.html.twig', [
            'mst_cities' => $mstTaskStatus,
            'country_id' => $countryId,
            'path_add' => 'master_task_status_add',
            'path_edit' => 'master_task_status_edit',
            'path_show' => 'master_task_status_show',
            'label_title' => 'label.task_status',
            'mode' => 'add'
        ]);
    }

    /**
     * @Route("/edit/{id}", name="edit", methods={"GET","POST"})
     * @param Request $request
     * @param MstTaskStatus $mstTaskStatus
     * @return Response
     */
    public function edit(Request $request, MstTaskStatus $mstTaskStatus): Response
    {
        $form = $this->createForm(MstTaskStatusType::class, $mstTaskStatus);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', 'form.updated_successfully');
            return $this->redirectToRoute('master_task_status_index');
        }

        return $this->render('form/form.html.twig', [
            'master_task_status' => $mstTaskStatus,
            'form' => $form->createView(),
            'index_path' => 'master_task_status_index',
            'label_title' => 'label.task_status',
            'label_button' => 'label.update',
            'mode' => 'edit'
        ]);
    }

    /**
     * @Route("/{id}", name="delete", methods={"DELETE"})
     * @param Request $request
     * @param MstTaskStatus $mstTaskStatus
     * @return Response
     */
    public function delete(Request $request, MstTaskStatus $mstTaskStatus): Response
    {
        if ($this->isCsrfTokenValid('delete'.$mstTaskStatus->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($mstTaskStatus);
            $entityManager->flush();
        }

        return $this->redirectToRoute('master_task_status_index');
    }
}
