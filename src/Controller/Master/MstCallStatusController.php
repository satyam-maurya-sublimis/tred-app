<?php

namespace App\Controller\Master;

use Doctrine\Persistence\ManagerRegistry;
use Exception;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use App\Entity\Master\MstCallStatus;
use App\Form\Master\MstCallStatusType;
use App\Repository\Master\MstCallStatusRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Ramsey\Uuid\Uuid;

/**
 * @Route("/core/master/general/call_status", name="master_call_status_")
 * @IsGranted("ROLE_SYS_CONTENT_USER")
 */
class MstCallStatusController extends AbstractController
{
    private ManagerRegistry $managerRegistry;

    public function __construct(ManagerRegistry $managerRegistry)
    {
        $this->managerRegistry = $managerRegistry;
    }
    /**
     * @Route("/", name="index", methods={"GET"})
     * @param MstCallStatusRepository $mstCallStatusRepository
     * @param Request $request
     * @return Response
     */
    public function index(MstCallStatusRepository $mstCallStatusRepository, Request $request): Response
    {
        $call_status = $mstCallStatusRepository->findAll();
        return $this->render('master/mst_call_status/index.html.twig', [
            'call_statuses' => $call_status,
            'path_index' => 'master_call_status_index',
            'path_add' => 'master_call_status_add',
            'path_edit' => 'master_call_status_edit',
            'path_show' => 'master_call_status_show',
            'label_title' => 'label.call_status',
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
        $mstCallStatus = new MstCallStatus();
        $form = $this->createForm(MstCallStatusType::class, $mstCallStatus);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $mstCallStatus->setRowId(Uuid::uuid4()->toString());
            $entityManager = $this->managerRegistry->getManager();
            $entityManager->persist($mstCallStatus);
            $entityManager->flush();
            $this->addFlash('success', 'form.created_successfully');
            return $this->redirectToRoute('master_call_status_index');
        }

        return $this->render('form/form.html.twig', [
            'master_call_status' => $mstCallStatus,
            'form' => $form->createView(),
            'index_path' => 'master_call_status_index',
            'label_title' => 'label.call_status',
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
        $call_status = ucwords($request->query->get('call_statusSearch'));

        $mstCallStatus = $this->managerRegistry->getRepository(MstCallStatus::class)->getCityListByCountryId($call_status, $countryId);
        return $this->render('master/mst_call_status/_ajax_listing.html.twig', [
            'mst_cities' => $mstCallStatus,
            'country_id' => $countryId,
            'path_add' => 'master_call_status_add',
            'path_edit' => 'master_call_status_edit',
            'path_show' => 'master_call_status_show',
            'label_title' => 'label.call_status',
            'mode' => 'add'
        ]);
    }

    /**
     * @Route("/edit/{id}", name="edit", methods={"GET","POST"})
     * @param Request $request
     * @param MstCallStatus $mstCallStatus
     * @return Response
     */
    public function edit(Request $request, MstCallStatus $mstCallStatus): Response
    {
        $form = $this->createForm(MstCallStatusType::class, $mstCallStatus);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->managerRegistry->getManager()->flush();
            $this->addFlash('success', 'form.updated_successfully');
            return $this->redirectToRoute('master_call_status_index');
        }

        return $this->render('form/form.html.twig', [
            'master_call_status' => $mstCallStatus,
            'form' => $form->createView(),
            'index_path' => 'master_call_status_index',
            'label_title' => 'label.call_status',
            'label_button' => 'label.update',
            'mode' => 'edit'
        ]);
    }

    /**
     * @Route("/{id}", name="delete", methods={"DELETE"})
     * @param Request $request
     * @param MstCallStatus $mstCallStatus
     * @return Response
     */
    public function delete(Request $request, MstCallStatus $mstCallStatus): Response
    {
        if ($this->isCsrfTokenValid('delete'.$mstCallStatus->getId(), $request->request->get('_token'))) {
            $entityManager = $this->managerRegistry->getManager();
            $entityManager->remove($mstCallStatus);
            $entityManager->flush();
        }

        return $this->redirectToRoute('master_call_status_index');
    }
}
