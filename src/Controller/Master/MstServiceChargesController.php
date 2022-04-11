<?php

namespace App\Controller\Master;

use Exception;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use App\Entity\Master\MstServiceCharges;
use App\Form\Master\MstServiceChargesType;
use App\Repository\Master\MstServiceChargesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Ramsey\Uuid\Uuid;

/**
 * @Route("/core/master/commercial/service_charges", name="master_service_charges_")
 * @IsGranted("ROLE_SYS_CONTENT_USER")
 */
class MstServiceChargesController extends AbstractController
{
    /**
     * @Route("/", name="index", methods={"GET"})
     * @param MstServiceChargesRepository $mstServiceChargesRepository
     * @param Request $request
     * @return Response
     */
    public function index(MstServiceChargesRepository $mstServiceChargesRepository, Request $request): Response
    {
        $mstServiceCharges = $mstServiceChargesRepository->findAll();
        return $this->render('master/mst_service_charges/index.html.twig', [
            'mst_service_charges' => $mstServiceCharges,
            'path_index' => 'master_service_charges_index',
            'path_add' => 'master_service_charges_add',
            'path_edit' => 'master_service_charges_edit',
            'path_show' => 'master_service_charges_show',
            'label_title' => 'label.service_charges',
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
        $mstServiceCharges = new MstServiceCharges();
        $form = $this->createForm(MstServiceChargesType::class, $mstServiceCharges);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $mstServiceCharges->setRowId(Uuid::uuid4()->toString());
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($mstServiceCharges);
            $entityManager->flush();
            $this->addFlash('success', 'form.created_successfully');
            return $this->redirectToRoute('master_service_charges_index');
        }

        return $this->render('form/form.html.twig', [
            'master_service_charges' => $mstServiceCharges,
            'form' => $form->createView(),
            'index_path' => 'master_service_charges_index',
            'label_title' => 'label.service_charges',
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
        $mstServiceCharges = ucwords($request->query->get('service_chargesSearch'));

        $mstServiceCharges = $this->getDoctrine()->getRepository(MstServiceCharges::class)->getCityListByCountryId($mstServiceCharges, $countryId);
        return $this->render('master/mst_service_charges/_ajax_listing.html.twig', [
            'mst_cities' => $mstServiceCharges,
            'country_id' => $countryId,
            'path_add' => 'master_service_charges_add',
            'path_edit' => 'master_service_charges_edit',
            'path_show' => 'master_service_charges_show',
            'label_title' => 'label.service_charges',
            'mode' => 'add'
        ]);
    }

    /**
     * @Route("/edit/{id}", name="edit", methods={"GET","POST"})
     * @param Request $request
     * @param MstServiceCharges $mstServiceCharges
     * @return Response
     */
    public function edit(Request $request, MstServiceCharges $mstServiceCharges): Response
    {
        $form = $this->createForm(MstServiceChargesType::class, $mstServiceCharges);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', 'form.updated_successfully');
            return $this->redirectToRoute('master_service_charges_index');
        }

        return $this->render('form/form.html.twig', [
            'master_service_charges' => $mstServiceCharges,
            'form' => $form->createView(),
            'index_path' => 'master_service_charges_index',
            'label_title' => 'label.service_charges',
            'label_button' => 'label.update',
            'mode' => 'edit'
        ]);
    }

    /**
     * @Route("/{id}", name="delete", methods={"DELETE"})
     * @param Request $request
     * @param MstServiceCharges $mstServiceCharges
     * @return Response
     */
    public function delete(Request $request, MstServiceCharges $mstServiceCharges): Response
    {
        if ($this->isCsrfTokenValid('delete'.$mstServiceCharges->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($mstServiceCharges);
            $entityManager->flush();
        }

        return $this->redirectToRoute('master_service_charges_index');
    }
}
