<?php

namespace App\Controller\Master;

use Doctrine\Persistence\ManagerRegistry;
use Exception;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use App\Entity\Master\MstMessageStatus;
use App\Form\Master\MstMessageStatusType;
use App\Repository\Master\MstMessageStatusRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Ramsey\Uuid\Uuid;

/**
 * @Route("/core/master/general/message_status", name="master_message_status_")
 * @IsGranted("ROLE_SYS_CONTENT_USER")
 */
class MstMessageStatusController extends AbstractController
{
    private ManagerRegistry $managerRegistry;

    public function __construct(ManagerRegistry $managerRegistry)
    {
        $this->managerRegistry = $managerRegistry;
    }
    /**
     * @Route("/", name="index", methods={"GET"})
     * @param MstMessageStatusRepository $mstMessageStatusRepository
     * @param Request $request
     * @return Response
     */
    public function index(MstMessageStatusRepository $mstMessageStatusRepository, Request $request): Response
    {
        $message_status = $mstMessageStatusRepository->findAll();
        return $this->render('master/mst_message_status/index.html.twig', [
            'mst_statuses' => $message_status,
            'path_index' => 'master_message_status_index',
            'path_add' => 'master_message_status_add',
            'path_edit' => 'master_message_status_edit',
            'path_show' => 'master_message_status_show',
            'label_title' => 'label.message_status',
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
        $mstMessageStatus = new MstMessageStatus();
        $form = $this->createForm(MstMessageStatusType::class, $mstMessageStatus);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $mstMessageStatus->setRowId(Uuid::uuid4()->toString());
            $entityManager = $this->managerRegistry->getManager();
            $entityManager->persist($mstMessageStatus);
            $entityManager->flush();
            $this->addFlash('success', 'form.created_successfully');
            return $this->redirectToRoute('master_message_status_index');
        }

        return $this->render('form/form.html.twig', [
            'master_message_status' => $mstMessageStatus,
            'form' => $form->createView(),
            'index_path' => 'master_message_status_index',
            'label_title' => 'label.message_status',
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
        $message_status = ucwords($request->query->get('message_statusSearch'));

        $mstMessageStatus = $this->managerRegistry->getRepository(MstMessageStatus::class)->getCityListByCountryId($message_status, $countryId);
        return $this->render('master/mst_message_status/_ajax_listing.html.twig', [
            'mst_cities' => $mstMessageStatus,
            'country_id' => $countryId,
            'path_add' => 'master_message_status_add',
            'path_edit' => 'master_message_status_edit',
            'path_show' => 'master_message_status_show',
            'label_title' => 'label.message_status',
            'mode' => 'add'
        ]);
    }

    /**
     * @Route("/edit/{id}", name="edit", methods={"GET","POST"})
     * @param Request $request
     * @param MstMessageStatus $mstMessageStatus
     * @return Response
     */
    public function edit(Request $request, MstMessageStatus $mstMessageStatus): Response
    {
        $form = $this->createForm(MstMessageStatusType::class, $mstMessageStatus);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->managerRegistry->getManager()->flush();
            $this->addFlash('success', 'form.updated_successfully');
            return $this->redirectToRoute('master_message_status_index');
        }

        return $this->render('form/form.html.twig', [
            'master_message_status' => $mstMessageStatus,
            'form' => $form->createView(),
            'index_path' => 'master_message_status_index',
            'label_title' => 'label.message_status',
            'label_button' => 'label.update',
            'mode' => 'edit'
        ]);
    }

    /**
     * @Route("/{id}", name="delete", methods={"DELETE"})
     * @param Request $request
     * @param MstMessageStatus $mstMessageStatus
     * @return Response
     */
    public function delete(Request $request, MstMessageStatus $mstMessageStatus): Response
    {
        if ($this->isCsrfTokenValid('delete'.$mstMessageStatus->getId(), $request->request->get('_token'))) {
            $entityManager = $this->managerRegistry->getManager();
            $entityManager->remove($mstMessageStatus);
            $entityManager->flush();
        }

        return $this->redirectToRoute('master_message_status_index');
    }
}
