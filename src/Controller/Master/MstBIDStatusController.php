<?php

namespace App\Controller\Master;

use Doctrine\Persistence\ManagerRegistry;
use Exception;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use App\Entity\Master\MstBIDStatus;
use App\Form\Master\MstBIDStatusType;
use App\Repository\Master\MstBIDStatusRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Ramsey\Uuid\Uuid;

/**
 * @Route("/core/master/general/bid_status", name="master_bid_status_")
 * @IsGranted("ROLE_SYS_CONTENT_USER")
 */
class MstBIDStatusController extends AbstractController
{
    private ManagerRegistry $managerRegistry;

    public function __construct(ManagerRegistry $managerRegistry)
    {
        $this->managerRegistry = $managerRegistry;
    }
    /**
     * @Route("/", name="index", methods={"GET"})
     * @param MstBIDStatusRepository $mstBIDStatusRepository
     * @param Request $request
     * @return Response
     */
    public function index(MstBIDStatusRepository $mstBIDStatusRepository, Request $request): Response
    {
        $bid_status = $mstBIDStatusRepository->findAll();
        return $this->render('master/mst_bid_status/index.html.twig', [
            'bid_statuses' => $bid_status,
            'path_index' => 'master_bid_status_index',
            'path_add' => 'master_bid_status_add',
            'path_edit' => 'master_bid_status_edit',
            'path_show' => 'master_bid_status_show',
            'label_title' => 'label.bid_status',
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
        $mstBIDStatus = new MstBIDStatus();
        $form = $this->createForm(MstBIDStatusType::class, $mstBIDStatus);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $mstBIDStatus->setRowId(Uuid::uuid4()->toString());
            $entityManager = $this->managerRegistry->getManager();
            $entityManager->persist($mstBIDStatus);
            $entityManager->flush();
            $this->addFlash('success', 'form.created_successfully');
            return $this->redirectToRoute('master_bid_status_index');
        }

        return $this->render('form/form.html.twig', [
            'master_bid_status' => $mstBIDStatus,
            'form' => $form->createView(),
            'index_path' => 'master_bid_status_index',
            'label_title' => 'label.bid_status',
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
        $bid_status = ucwords($request->query->get('bid_statusSearch'));

        $mstBIDStatus = $this->managerRegistry->getRepository(MstBIDStatus::class)->getCityListByCountryId($bid_status, $countryId);
        return $this->render('master/mst_bid_status/_ajax_listing.html.twig', [
            'mst_cities' => $mstBIDStatus,
            'country_id' => $countryId,
            'path_add' => 'master_bid_status_add',
            'path_edit' => 'master_bid_status_edit',
            'path_show' => 'master_bid_status_show',
            'label_title' => 'label.bid_status',
            'mode' => 'add'
        ]);
    }

    /**
     * @Route("/edit/{id}", name="edit", methods={"GET","POST"})
     * @param Request $request
     * @param MstBIDStatus $mstBIDStatus
     * @return Response
     */
    public function edit(Request $request, MstBIDStatus $mstBIDStatus): Response
    {
        $form = $this->createForm(MstBIDStatusType::class, $mstBIDStatus);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->managerRegistry->getManager()->flush();
            $this->addFlash('success', 'form.updated_successfully');
            return $this->redirectToRoute('master_bid_status_index');
        }

        return $this->render('form/form.html.twig', [
            'master_bid_status' => $mstBIDStatus,
            'form' => $form->createView(),
            'index_path' => 'master_bid_status_index',
            'label_title' => 'label.bid_status',
            'label_button' => 'label.update',
            'mode' => 'edit'
        ]);
    }

    /**
     * @Route("/{id}", name="delete", methods={"DELETE"})
     * @param Request $request
     * @param MstBIDStatus $mstBIDStatus
     * @return Response
     */
    public function delete(Request $request, MstBIDStatus $mstBIDStatus): Response
    {
        if ($this->isCsrfTokenValid('delete'.$mstBIDStatus->getId(), $request->request->get('_token'))) {
            $entityManager = $this->managerRegistry->getManager();
            $entityManager->remove($mstBIDStatus);
            $entityManager->flush();
        }

        return $this->redirectToRoute('master_bid_status_index');
    }
}
