<?php

namespace App\Controller\Master;

use Exception;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use App\Entity\Master\MstPaymentGatewayCharges;
use App\Form\Master\MstPaymentGatewayChargesType;
use App\Repository\Master\MstPaymentGatewayChargesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Ramsey\Uuid\Uuid;

/**
 * @Route("/core/master/commercial/payment_gateway_charges", name="master_payment_gateway_charges_")
 * @IsGranted("ROLE_SYS_CONTENT_USER")
 */
class MstPaymentGatewayChargesController extends AbstractController
{
    /**
     * @Route("/", name="index", methods={"GET"})
     * @param MstPaymentGatewayChargesRepository $mstPaymentGatewayChargesRepository
     * @param Request $request
     * @return Response
     */
    public function index(MstPaymentGatewayChargesRepository $mstPaymentGatewayChargesRepository, Request $request): Response
    {
        $mstPaymentGatewayCharges = $mstPaymentGatewayChargesRepository->findAll();
        return $this->render('master/mst_payment_gateway_charges/index.html.twig', [
            'mst_payment_gateway_charges' => $mstPaymentGatewayCharges,
            'path_index' => 'master_payment_gateway_charges_index',
            'path_add' => 'master_payment_gateway_charges_add',
            'path_edit' => 'master_payment_gateway_charges_edit',
            'path_show' => 'master_payment_gateway_charges_show',
            'label_title' => 'label.payment_gateway_charges',
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
        $mstPaymentGatewayCharges = new MstPaymentGatewayCharges();
        $form = $this->createForm(MstPaymentGatewayChargesType::class, $mstPaymentGatewayCharges);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $mstPaymentGatewayCharges->setRowId(Uuid::uuid4()->toString());
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($mstPaymentGatewayCharges);
            $entityManager->flush();
            $this->addFlash('success', 'form.created_successfully');
            return $this->redirectToRoute('master_payment_gateway_charges_index');
        }

        return $this->render('form/form.html.twig', [
            'master_payment_gateway_charges' => $mstPaymentGatewayCharges,
            'form' => $form->createView(),
            'index_path' => 'master_payment_gateway_charges_index',
            'label_title' => 'label.payment_gateway_charges',
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
        $mstPaymentGatewayCharges = ucwords($request->query->get('payment_gateway_chargesSearch'));

        $mstPaymentGatewayCharges = $this->getDoctrine()->getRepository(MstPaymentGatewayCharges::class)->getCityListByCountryId($mstPaymentGatewayCharges, $countryId);
        return $this->render('master/mst_payment_gateway_charges/_ajax_listing.html.twig', [
            'mst_cities' => $mstPaymentGatewayCharges,
            'country_id' => $countryId,
            'path_add' => 'master_payment_gateway_charges_add',
            'path_edit' => 'master_payment_gateway_charges_edit',
            'path_show' => 'master_payment_gateway_charges_show',
            'label_title' => 'label.payment_gateway_charges',
            'mode' => 'add'
        ]);
    }

    /**
     * @Route("/edit/{id}", name="edit", methods={"GET","POST"})
     * @param Request $request
     * @param MstPaymentGatewayCharges $mstPaymentGatewayCharges
     * @return Response
     */
    public function edit(Request $request, MstPaymentGatewayCharges $mstPaymentGatewayCharges): Response
    {
        $form = $this->createForm(MstPaymentGatewayChargesType::class, $mstPaymentGatewayCharges);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', 'form.updated_successfully');
            return $this->redirectToRoute('master_payment_gateway_charges_index');
        }

        return $this->render('form/form.html.twig', [
            'master_payment_gateway_charges' => $mstPaymentGatewayCharges,
            'form' => $form->createView(),
            'index_path' => 'master_payment_gateway_charges_index',
            'label_title' => 'label.payment_gateway_charges',
            'label_button' => 'label.update',
            'mode' => 'edit'
        ]);
    }

    /**
     * @Route("/{id}", name="delete", methods={"DELETE"})
     * @param Request $request
     * @param MstPaymentGatewayCharges $mstPaymentGatewayCharges
     * @return Response
     */
    public function delete(Request $request, MstPaymentGatewayCharges $mstPaymentGatewayCharges): Response
    {
        if ($this->isCsrfTokenValid('delete'.$mstPaymentGatewayCharges->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($mstPaymentGatewayCharges);
            $entityManager->flush();
        }

        return $this->redirectToRoute('master_payment_gateway_charges_index');
    }
}
