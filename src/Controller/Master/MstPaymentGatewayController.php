<?php

namespace App\Controller\Master;

use Doctrine\Persistence\ManagerRegistry;
use Exception;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use App\Entity\Master\MstPaymentGateway;
use App\Form\Master\MstPaymentGatewayType;
use App\Repository\Master\MstPaymentGatewayRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Ramsey\Uuid\Uuid;

/**
 * @Route("/core/master/commercial/payment_gateway", name="master_payment_gateway_")
 * @IsGranted("ROLE_SYS_CONTENT_USER")
 */
class MstPaymentGatewayController extends AbstractController
{
    private ManagerRegistry $managerRegistry;

    public function __construct(ManagerRegistry $managerRegistry)
    {
        $this->managerRegistry = $managerRegistry;
    }
    /**
     * @Route("/", name="index", methods={"GET"})
     * @param MstPaymentGatewayRepository $mstPaymentGatewayRepository
     * @param Request $request
     * @return Response
     */
    public function index(MstPaymentGatewayRepository $mstPaymentGatewayRepository, Request $request): Response
    {
        $mstPaymentGateway = $mstPaymentGatewayRepository->findAll();
        return $this->render('master/mst_payment_gateway/index.html.twig', [
            'mst_payment_gateways' => $mstPaymentGateway,
            'path_index' => 'master_payment_gateway_index',
            'path_add' => 'master_payment_gateway_add',
            'path_edit' => 'master_payment_gateway_edit',
            'path_show' => 'master_payment_gateway_show',
            'label_title' => 'label.payment_gateway',
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
        $mstPaymentGateway = new MstPaymentGateway();
        $form = $this->createForm(MstPaymentGatewayType::class, $mstPaymentGateway);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $mstPaymentGateway->setRowId(Uuid::uuid4()->toString());
            $entityManager = $this->managerRegistry->getManager();
            $entityManager->persist($mstPaymentGateway);
            $entityManager->flush();
            $this->addFlash('success', 'form.created_successfully');
            return $this->redirectToRoute('master_payment_gateway_index');
        }

        return $this->render('form/form.html.twig', [
            'master_payment_gateway' => $mstPaymentGateway,
            'form' => $form->createView(),
            'index_path' => 'master_payment_gateway_index',
            'label_title' => 'label.payment_gateway',
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
        $mstPaymentGateway = ucwords($request->query->get('payment_gatewaySearch'));

        $mstPaymentGateway = $this->managerRegistry->getRepository(MstPaymentGateway::class)->getCityListByCountryId($mstPaymentGateway, $countryId);
        return $this->render('master/mst_payment_gateway/_ajax_listing.html.twig', [
            'mst_cities' => $mstPaymentGateway,
            'country_id' => $countryId,
            'path_add' => 'master_payment_gateway_add',
            'path_edit' => 'master_payment_gateway_edit',
            'path_show' => 'master_payment_gateway_show',
            'label_title' => 'label.payment_gateway',
            'mode' => 'add'
        ]);
    }

    /**
     * @Route("/edit/{id}", name="edit", methods={"GET","POST"})
     * @param Request $request
     * @param MstPaymentGateway $mstPaymentGateway
     * @return Response
     */
    public function edit(Request $request, MstPaymentGateway $mstPaymentGateway): Response
    {
        $form = $this->createForm(MstPaymentGatewayType::class, $mstPaymentGateway);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->managerRegistry->getManager()->flush();
            $this->addFlash('success', 'form.updated_successfully');
            return $this->redirectToRoute('master_payment_gateway_index');
        }

        return $this->render('form/form.html.twig', [
            'master_payment_gateway' => $mstPaymentGateway,
            'form' => $form->createView(),
            'index_path' => 'master_payment_gateway_index',
            'label_title' => 'label.payment_gateway',
            'label_button' => 'label.update',
            'mode' => 'edit'
        ]);
    }

    /**
     * @Route("/{id}", name="delete", methods={"DELETE"})
     * @param Request $request
     * @param MstPaymentGateway $mstPaymentGateway
     * @return Response
     */
    public function delete(Request $request, MstPaymentGateway $mstPaymentGateway): Response
    {
        if ($this->isCsrfTokenValid('delete'.$mstPaymentGateway->getId(), $request->request->get('_token'))) {
            $entityManager = $this->managerRegistry->getManager();
            $entityManager->remove($mstPaymentGateway);
            $entityManager->flush();
        }

        return $this->redirectToRoute('master_payment_gateway_index');
    }
}
