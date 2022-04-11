<?php

namespace App\Controller\Master;

use Exception;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use App\Entity\Master\MstOrderStatus;
use App\Form\Master\MstOrderStatusType;
use App\Repository\Master\MstOrderStatusRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Ramsey\Uuid\Uuid;

/**
 * @Route("/core/master/general/order_status", name="master_order_status_")
 * @IsGranted("ROLE_SYS_CONTENT_USER")
 */
class MstOrderStatusController extends AbstractController
{
    /**
     * @Route("/", name="index", methods={"GET"})
     * @param MstOrderStatusRepository $mstOrderStatusRepository
     * @param Request $request
     * @return Response
     */
    public function index(MstOrderStatusRepository $mstOrderStatusRepository, Request $request): Response
    {
        $order_status = $mstOrderStatusRepository->findAll();
        return $this->render('master/mst_order_status/index.html.twig', [
            'mst_order_statuses' => $order_status,
            'path_index' => 'master_order_status_index',
            'path_add' => 'master_order_status_add',
            'path_edit' => 'master_order_status_edit',
            'path_show' => 'master_order_status_show',
            'label_title' => 'label.order_status',
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
        $mstOrderStatus = new MstOrderStatus();
        $form = $this->createForm(MstOrderStatusType::class, $mstOrderStatus);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $mstOrderStatus->setRowId(Uuid::uuid4()->toString());
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($mstOrderStatus);
            $entityManager->flush();
            $this->addFlash('success', 'form.created_successfully');
            return $this->redirectToRoute('master_order_status_index');
        }

        return $this->render('form/form.html.twig', [
            'master_order_status' => $mstOrderStatus,
            'form' => $form->createView(),
            'index_path' => 'master_order_status_index',
            'label_title' => 'label.order_status',
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
        $order_status = ucwords($request->query->get('order_statusSearch'));

        $mstOrderStatus = $this->getDoctrine()->getRepository(MstOrderStatus::class)->getCityListByCountryId($order_status, $countryId);
        return $this->render('master/mst_order_status/_ajax_listing.html.twig', [
            'mst_cities' => $mstOrderStatus,
            'country_id' => $countryId,
            'path_add' => 'master_order_status_add',
            'path_edit' => 'master_order_status_edit',
            'path_show' => 'master_order_status_show',
            'label_title' => 'label.order_status',
            'mode' => 'add'
        ]);
    }

    /**
     * @Route("/edit/{id}", name="edit", methods={"GET","POST"})
     * @param Request $request
     * @param MstOrderStatus $mstOrderStatus
     * @return Response
     */
    public function edit(Request $request, MstOrderStatus $mstOrderStatus): Response
    {
        $form = $this->createForm(MstOrderStatusType::class, $mstOrderStatus);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', 'form.updated_successfully');
            return $this->redirectToRoute('master_order_status_index');
        }

        return $this->render('form/form.html.twig', [
            'master_order_status' => $mstOrderStatus,
            'form' => $form->createView(),
            'index_path' => 'master_order_status_index',
            'label_title' => 'label.order_status',
            'label_button' => 'label.update',
            'mode' => 'edit'
        ]);
    }

    /**
     * @Route("/{id}", name="delete", methods={"DELETE"})
     * @param Request $request
     * @param MstOrderStatus $mstOrderStatus
     * @return Response
     */
    public function delete(Request $request, MstOrderStatus $mstOrderStatus): Response
    {
        if ($this->isCsrfTokenValid('delete'.$mstOrderStatus->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($mstOrderStatus);
            $entityManager->flush();
        }

        return $this->redirectToRoute('master_order_status_index');
    }
}
