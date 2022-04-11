<?php

namespace App\Controller\Partner;

use App\Entity\Master\MstCountry;
use App\Entity\Transaction\TrnVendorPartnerDetails;
use App\Form\SystemApp\AppUserpartnerType;
use App\Form\Transaction\TrnVendorPartnerDetailsType;
use App\Repository\Transaction\TrnVendorPartnerDetailsRepository;
use App\Service\Mailer;
use Exception;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;
use Ramsey\Uuid\Uuid;
use App\Service\CommonHelper;
use App\Service\FileUploaderHelper;

/**
 * @Route("/core/partner/partner_vendor", name="partner_")
 * @IsGranted("ROLE_SYS_CONTENT_USER")
 */
class PartnerController extends AbstractController
{
    /**
     * @Route("/", name="vendor_index", methods={"GET"})
     * @param Request $request
     * @return Response
     */
    public function index(Request $request): Response
    {
        $user = $this->getUser();

        if ($user->getAppUserInfo()->getTrnVendorPartnerDetails())
        {
            $trnVendorPartnerDetails = $user->getAppUserInfo()->getTrnVendorPartnerDetails();
            $queryBuilder = $this->getDoctrine()->getRepository(TrnVendorPartnerDetails::class)->findBy(["id"=>$trnVendorPartnerDetails->getId(),"isActive"=>1]);
        }
        if (in_array("ROLE_SUPER_ADMIN", $user->getRoles())) {
            $queryBuilder = $this->getDoctrine()->getRepository(TrnVendorPartnerDetails::class)->findBy(["isActive"=>1]);
        }

        return $this->render('partner/register/index.html.twig', [
            'vendorPartnerDetails' => $queryBuilder,
            'path_index' => 'partner_index',
            'path_add' => 'partner_add',
            'path_edit' => 'partner_edit',
            'path_show' => 'partner_show',
            'path_upload' => 'partner_upload',
            'label_title' => 'label.vendor_partner',
        ]);
    }

    /**
     * @Route("/add", name="add", methods={"GET","POST"})
     * @param Request $request
     * @param FileUploaderHelper $fileUploaderHelper
     * @param CommonHelper $commonHelper
     * @return Response
     * @throws Exception
     */
    public function new(Request $request, FileUploaderHelper $fileUploaderHelper, CommonHelper $commonHelper): Response
    {
        $trnVendorPartnerDetails = new TrnVendorPartnerDetails();
        $form = $this->createForm(TrnVendorPartnerDetailsType::class, $trnVendorPartnerDetails);
        $mstCountry = $this->getDoctrine()->getRepository(MstCountry::class)->find(101);
        $trnVendorPartnerDetails->setMstCountry($mstCountry);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $imageContentFile = $form['companyLogo']->getData();
            if (!empty($imageContentFile)) {
                $strSaveFileName = $commonHelper->clean(strtolower($form['vendorPartnerName']->getData())).Uuid::uuid4()->toString();
                $newFilename = $fileUploaderHelper->uploadPublicFile($imageContentFile, $strSaveFileName,null);

                $trnVendorPartnerDetails->setCompanyLogo($newFilename);
            }
            $trnVendorPartnerDetails->setCreatedOn(new \DateTime());
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($trnVendorPartnerDetails);
            $this->getDoctrine()->getManager()->flush();
            #Send Reset Password Email
            $this->addFlash('success', 'form.created_successfully');
            return $this->redirectToRoute('partner_vendor_index');
        }
        return $this->render('partner/register/form.html.twig', [
            'trnVendorPartnerDetails' => $trnVendorPartnerDetails,
            'form' => $form->createView(),
            'label_title' => 'label.partner',
            'label_button' => 'label.create',
            'mode' => 'add'
        ]);
    }

    /**
     * @Route("/edit/{id}", name="edit", methods={"GET","POST"})
     * @param Request $request
     * @param TrnVendorPartnerDetails $trnVendorPartnerDetails
     * @param FileUploaderHelper $fileUploaderHelper
     * @param CommonHelper $commonHelper
     * @return Response
     * @throws Exception
     */
    public function edit(Request $request, TrnVendorPartnerDetails $trnVendorPartnerDetails, FileUploaderHelper $fileUploaderHelper, CommonHelper $commonHelper): Response
    {
        $form = $this->createForm(TrnVendorPartnerDetailsType::class, $trnVendorPartnerDetails);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $imageContentFile = $form['companyLogo']->getData();
            if (!empty($imageContentFile)) {
                $strSaveFileName = $commonHelper->clean(strtolower($form['vendorPartnerName']->getData())).Uuid::uuid4()->toString();
                $newFilename = $fileUploaderHelper->uploadPublicFile($imageContentFile, $strSaveFileName,null);
                $trnVendorPartnerDetails->setCompanyLogo($newFilename);
            }
            $trnVendorPartnerDetails->setCreatedOn(new \DateTime());
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($trnVendorPartnerDetails);
            $this->getDoctrine()->getManager()->flush();
            #Send Reset Password Email
            $this->addFlash('success', 'form.created_successfully');
            return $this->redirectToRoute('partner_vendor_index');
        }
        return $this->render('partner/register/form.html.twig', [
            'trnVendorPartnerDetails' => $trnVendorPartnerDetails,
            'form' => $form->createView(),
            'label_title' => 'label.partner',
            'label_button' => 'label.create',
            'mode' => 'edit'
        ]);
    }

    /**
     * @Route("/{id}", name="show", methods={"GET"})
     * @param TrnVendorPartnerDetails $trnVendorPartnerDetails
     * @return Response
     */
    public function show(TrnVendorPartnerDetails $trnVendorPartnerDetails): Response
    {
        return $this->render('partner/register/show.html.twig', [
            'data' => $trnVendorPartnerDetails,
            'label_title' => 'label.register',
            'label_button' => 'label.update',
            'path_index' => 'partner_vendor_index',
            'path_edit' => 'partner_edit'
        ]);
    }
}