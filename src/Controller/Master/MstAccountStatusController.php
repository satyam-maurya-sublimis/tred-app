<?php

namespace App\Controller\Master;

use Exception;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use App\Entity\Master\MstAccountStatus;
use App\Form\Master\MstAccountStatusType;
use App\Repository\Master\MstAccountStatusRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Ramsey\Uuid\Uuid;

/**
 * @Route("/core/master/tred/account_status", name="master_account_status_")
 * @IsGranted("ROLE_SYS_CONTENT_USER")
 */
class MstAccountStatusController extends AbstractController
{
    /**
     * @Route("/", name="index", methods={"GET"})
     * @param MstAccountStatusRepository $mstAccountStatusRepository
     * @param Request $request
     * @return Response
     */
    public function index(MstAccountStatusRepository $mstAccountStatusRepository, Request $request): Response
    {
        $account_status = $mstAccountStatusRepository->findAll();
        return $this->render('master/mst_account_status/index.html.twig', [
            'mst_statuses' => $account_status,
            'path_index' => 'master_account_status_index',
            'path_add' => 'master_account_status_add',
            'path_edit' => 'master_account_status_edit',
            'path_show' => 'master_account_status_show',
            'label_title' => 'label.account_status',
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
        $mstAccountStatus = new MstAccountStatus();
        $form = $this->createForm(MstAccountStatusType::class, $mstAccountStatus);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $mstAccountStatus->setRowId(Uuid::uuid4()->toString());
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($mstAccountStatus);
            $entityManager->flush();
            $this->addFlash('success', 'form.created_successfully');
            return $this->redirectToRoute('master_account_status_index');
        }

        return $this->render('form/form.html.twig', [
            'master_account_status' => $mstAccountStatus,
            'form' => $form->createView(),
            'index_path' => 'master_account_status_index',
            'label_title' => 'label.account_status',
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
        $account_status = ucwords($request->query->get('account_statusSearch'));

        $mstAccountStatus = $this->getDoctrine()->getRepository(MstAccountStatus::class)->getCityListByCountryId($account_status, $countryId);
        return $this->render('master/mst_account_status/_ajax_listing.html.twig', [
            'mst_cities' => $mstAccountStatus,
            'country_id' => $countryId,
            'path_add' => 'master_account_status_add',
            'path_edit' => 'master_account_status_edit',
            'path_show' => 'master_account_status_show',
            'label_title' => 'label.account_status',
            'mode' => 'add'
        ]);
    }

    /**
     * @Route("/edit/{id}", name="edit", methods={"GET","POST"})
     * @param Request $request
     * @param MstAccountStatus $mstAccountStatus
     * @return Response
     */
    public function edit(Request $request, MstAccountStatus $mstAccountStatus): Response
    {
        $form = $this->createForm(MstAccountStatusType::class, $mstAccountStatus);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', 'form.updated_successfully');
            return $this->redirectToRoute('master_account_status_index');
        }

        return $this->render('form/form.html.twig', [
            'master_account_status' => $mstAccountStatus,
            'form' => $form->createView(),
            'index_path' => 'master_account_status_index',
            'label_title' => 'label.account_status',
            'label_button' => 'label.update',
            'mode' => 'edit'
        ]);
    }

    /**
     * @Route("/{id}", name="delete", methods={"DELETE"})
     * @param Request $request
     * @param MstAccountStatus $mstAccountStatus
     * @return Response
     */
    public function delete(Request $request, MstAccountStatus $mstAccountStatus): Response
    {
        if ($this->isCsrfTokenValid('delete'.$mstAccountStatus->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($mstAccountStatus);
            $entityManager->flush();
        }

        return $this->redirectToRoute('master_account_status_index');
    }
}
