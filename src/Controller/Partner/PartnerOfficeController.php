<?php

namespace App\Controller\Partner;

use App\Form\SystemApp\AppUserpartnerType;
use App\Entity\Transaction\TrnVendorPartnerDetails;
use App\Entity\Transaction\TrnVendorPartnerOffices;
use App\Form\Transaction\TrnVendorPartnerOfficesType;
use App\Repository\Transaction\TrnVendorPartnerOfficesRepository;
use Exception;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;
use Ramsey\Uuid\Uuid;

/**
 * @Route("/core/partner/office", name="partner_office_")
 * @IsGranted("ROLE_SYS_CONTENT_USER")
 */
class PartnerOfficeController extends AbstractController
{
    /**
     * @Route("/", name="index", methods={"GET"})
     * @param TrnVendorPartnerOfficesRepository $trnVendorPartnerOfficesRepository
     * @param PaginatorInterface $paginator
     * @param Request $request
     * @return Response
     */
    public function index(TrnVendorPartnerOfficesRepository $trnVendorPartnerOfficesRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $user = $this->getUser();

        if ($user->getAppUserInfo()->getTrnVendorPartnerDetails())
        {
            $trnVendorPartnerDetails = $user->getAppUserInfo()->getTrnVendorPartnerDetails();
            $vendor_partner_id = $trnVendorPartnerDetails->getId();
        }else{
            $vendor_partner_id = $request->get('vendor_partner_id');
        }
        if (empty($vendor_partner_id))
            $queryBuilder = $trnVendorPartnerOfficesRepository->findAll();
        else{
            $TrnVendorPartnerDetails = $this->getDoctrine()->getRepository(TrnVendorPartnerDetails::class)->find($vendor_partner_id);
            $queryBuilder = $trnVendorPartnerOfficesRepository->findBy(['trnVendorPartnerDetails' => $TrnVendorPartnerDetails]);
        }

        $pagination = $paginator->paginate(
            $queryBuilder,
            $request->query->getInt('page', 1),
            10
        );
        return $this->render('partner/office/index.html.twig', [
            'vendorPartnerOffices' => $pagination,
            'path_index' => 'partner_office_index',
            'path_add' => 'partner_office_add',
            'path_edit' => 'partner_office_edit',
            'path_show' => 'partner_office_show',
            'path_upload' => 'partner_office_upload',
            'label_title' => 'label.vendor_partner_office',
            'vendor_partner_id' => $vendor_partner_id
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
        $trnVendorPartnerOffices = new TrnVendorPartnerOffices();
        $vendor_partner_id = $request->get('vendor_partner_id');
        if (!empty($vendor_partner_id)) {
            $TrnVendorPartnerDetails = $this->getDoctrine()->getRepository(TrnVendorPartnerDetails::class)->find($vendor_partner_id);
            $trnVendorPartnerOffices->setTrnVendorPartnerDetails($TrnVendorPartnerDetails);
        }
        $form = $this->createForm(TrnVendorPartnerOfficesType::class, $trnVendorPartnerOffices);
        $form->handleRequest($request); //echo '<pre>'; print_r($request->request); die;
        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($trnVendorPartnerOffices);
            $this->getDoctrine()->getManager()->flush();
            #Send Reset Password Email
            $this->addFlash('success', 'form.created_successfully');
            return $this->redirectToRoute('partner_office_index');
        }
        return $this->render('partner/office/form.html.twig', [
            'trnVendorPartnerOffices' => $trnVendorPartnerOffices,
            'form' => $form->createView(),
            'label_title' => 'label.vendor_partner_office',
            'label_button' => 'label.create',
            'mode' => 'add'
        ]);
    }

    /**
     * @Route("/edit/{id}", name="edit", methods={"GET","POST"})
     * @param Request $request
     * @param TrnVendorPartnerOffices $trnVendorPartnerOffices
     * @return Response
     * @throws Exception
     */
    public function edit(Request $request, TrnVendorPartnerOffices $trnVendorPartnerOffices): Response
    {
        $form = $this->createForm(TrnVendorPartnerOfficesType::class, $trnVendorPartnerOffices);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($trnVendorPartnerOffices);
            $this->getDoctrine()->getManager()->flush();
            #Send Reset Password Email
            $this->addFlash('success', 'form.created_successfully');
            return $this->redirectToRoute('partner_office_index');
        }
        return $this->render('partner/office/form.html.twig', [
            'trnVendorPartnerOffices' => $trnVendorPartnerOffices,
            'form' => $form->createView(),
            'label_title' => 'label.partner',
            'label_button' => 'label.create',
            'mode' => 'edit'
        ]);
    }

    /**
     * @Route("/{id}", name="show", methods={"GET"})
     * @param TrnVendorPartnerOffices $trnVendorPartnerOffices
     * @return Response
     */
    public function show(TrnVendorPartnerOffices $trnVendorPartnerOffices): Response
    {
        return $this->render('partner/office/show.html.twig', [
            'data' => $trnVendorPartnerOffices,
            'label_title' => 'label.register',
            'label_button' => 'label.update',
            'path_index' => 'partner_office_index',
            'path_edit' => 'partner_office_edit'
        ]);
    }
}