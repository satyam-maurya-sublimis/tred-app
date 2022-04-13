<?php

namespace App\Controller\Master;

use Doctrine\Persistence\ManagerRegistry;
use Exception;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use App\Entity\Master\MstMeetingStatus;
use App\Form\Master\MstMeetingStatusType;
use App\Repository\Master\MstMeetingStatusRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Ramsey\Uuid\Uuid;

/**
 * @Route("/core/master/general/meeting_status", name="master_meeting_status_")
 * @IsGranted("ROLE_SYS_CONTENT_USER")
 */
class MstMeetingStatusController extends AbstractController
{
    private ManagerRegistry $managerRegistry;

    public function __construct(ManagerRegistry $managerRegistry)
    {
        $this->managerRegistry = $managerRegistry;
    }
    /**
     * @Route("/", name="index", methods={"GET"})
     * @param MstMeetingStatusRepository $mstMeetingStatusRepository
     * @param Request $request
     * @return Response
     */
    public function index(MstMeetingStatusRepository $mstMeetingStatusRepository, Request $request): Response
    {
        $meeting_status = $mstMeetingStatusRepository->findAll();
        return $this->render('master/mst_meeting_status/index.html.twig', [
            'mst_meeting_statuses' => $meeting_status,
            'path_index' => 'master_meeting_status_index',
            'path_add' => 'master_meeting_status_add',
            'path_edit' => 'master_meeting_status_edit',
            'path_show' => 'master_meeting_status_show',
            'label_title' => 'label.meeting_status',
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
        $mstMeetingStatus = new MstMeetingStatus();
        $form = $this->createForm(MstMeetingStatusType::class, $mstMeetingStatus);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $mstMeetingStatus->setRowId(Uuid::uuid4()->toString());
            $entityManager = $this->managerRegistry->getManager();
            $entityManager->persist($mstMeetingStatus);
            $entityManager->flush();
            $this->addFlash('success', 'form.created_successfully');
            return $this->redirectToRoute('master_meeting_status_index');
        }

        return $this->render('form/form.html.twig', [
            'master_meeting_status' => $mstMeetingStatus,
            'form' => $form->createView(),
            'index_path' => 'master_meeting_status_index',
            'label_title' => 'label.meeting_status',
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
        $meeting_status = ucwords($request->query->get('meeting_statusSearch'));

        $mstMeetingStatus = $this->managerRegistry->getRepository(MstMeetingStatus::class)->getCityListByCountryId($meeting_status, $countryId);
        return $this->render('master/mst_meeting_status/_ajax_listing.html.twig', [
            'mst_cities' => $mstMeetingStatus,
            'country_id' => $countryId,
            'path_add' => 'master_meeting_status_add',
            'path_edit' => 'master_meeting_status_edit',
            'path_show' => 'master_meeting_status_show',
            'label_title' => 'label.meeting_status',
            'mode' => 'add'
        ]);
    }

    /**
     * @Route("/edit/{id}", name="edit", methods={"GET","POST"})
     * @param Request $request
     * @param MstMeetingStatus $mstMeetingStatus
     * @return Response
     */
    public function edit(Request $request, MstMeetingStatus $mstMeetingStatus): Response
    {
        $form = $this->createForm(MstMeetingStatusType::class, $mstMeetingStatus);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->managerRegistry->getManager()->flush();
            $this->addFlash('success', 'form.updated_successfully');
            return $this->redirectToRoute('master_meeting_status_index');
        }

        return $this->render('form/form.html.twig', [
            'master_meeting_status' => $mstMeetingStatus,
            'form' => $form->createView(),
            'index_path' => 'master_meeting_status_index',
            'label_title' => 'label.meeting_status',
            'label_button' => 'label.update',
            'mode' => 'edit'
        ]);
    }

    /**
     * @Route("/{id}", name="delete", methods={"DELETE"})
     * @param Request $request
     * @param MstMeetingStatus $mstMeetingStatus
     * @return Response
     */
    public function delete(Request $request, MstMeetingStatus $mstMeetingStatus): Response
    {
        if ($this->isCsrfTokenValid('delete'.$mstMeetingStatus->getId(), $request->request->get('_token'))) {
            $entityManager = $this->managerRegistry->getManager();
            $entityManager->remove($mstMeetingStatus);
            $entityManager->flush();
        }

        return $this->redirectToRoute('master_meeting_status_index');
    }
}
