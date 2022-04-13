<?php

namespace App\Controller\Partner;
use App\Entity\Transaction\TrnTopVendorPartners;
use App\Form\Transaction\TrnTopVendorPartnersType;
use App\Form\Transaction\TrnVendorPartnerDetailsType;
use App\Service\CommonHelper;
use App\Service\FileUploaderHelper;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Persistence\ManagerRegistry;
use Exception;
use Ramsey\Uuid\Uuid;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
/**
 * @Route("/core/partner/top-vendor-partner", name="partner_top_")
 * @IsGranted("ROLE_SUPER_ADMIN")
 */
class TopPartnerController extends AbstractController
{
    private ManagerRegistry $managerRegistry;

    public function __construct(ManagerRegistry $managerRegistry)
    {
        $this->managerRegistry = $managerRegistry;
    }
    /**
     * @Route("/", name="index", methods={"GET"})
     * @param Request $request
     * @return Response
     */
    public function index(Request $request): Response
    {
        $user = $this->getUser();
//        $queryBuilder=[];
//        if (in_array("ROLE_SUPER_ADMIN", $user->getRoles())) {
            $queryBuilder = $this->managerRegistry->getRepository(TrnTopVendorPartners::class)->findAll();
//        }
        return $this->render('partner/top_partner/index.html.twig', [
            'trnTopVendorPartners' => $queryBuilder,
            'path_index' => 'partner_top_index',
            'path_add' => 'partner_top_add',
            'path_edit' => 'partner_top_edit',
            'path_show' => 'partner_top_show',
            'label_title' => 'label.top_vendor_partner',
        ]);
    }

    /**
     * @Route("/add", name="add", methods={"GET","POST"})
     * @param Request $request
     * @return Response
     * @throws Exception
     */
    public function new(Request $request, CommonHelper $commonHelper, FileUploaderHelper $fileUploaderHelper): Response
    {
        $trnTopVendorPartners = new TrnTopVendorPartners();
        $form = $this->createForm(TrnTopVendorPartnersType::class, $trnTopVendorPartners);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $trnTopVendorPartnersLocalities = $form['trnTopVendorPartnersLocalities']->getData();
            foreach($trnTopVendorPartnersLocalities as $topVendorPartnersLocality){
                $topVendorPartnersLocality->setCreatedOn(new \DateTime());
                $topVendorPartnersLocality->setCreatedBy($this->getUser());
            }
            $imageContentFile = $form['contactPersonImage']->getData();
            if (!empty($imageContentFile)) {
                $strSaveFileName = $commonHelper->clean(strtolower($form['contactPersonName']->getData())).Uuid::uuid4()->toString();
                $newFilename = $fileUploaderHelper->uploadPublicFile($imageContentFile, $strSaveFileName,null);
                $trnTopVendorPartners->setContactPersonImage($newFilename);
            }
            $trnTopVendorPartners->setCreatedOn(new \DateTime());
            $trnTopVendorPartners->setCreatedBy($this->getUser());
            $entityManager = $this->managerRegistry->getManager();
            $entityManager->persist($trnTopVendorPartners);
            $entityManager->flush();
            #Send Reset Password Email
            $this->addFlash('success', 'form.created_successfully');
            return $this->redirectToRoute('partner_top_index');
        }
        return $this->render('partner/top_partner/form.html.twig', [
            'trnTopVendorPartners' => $trnTopVendorPartners,
            'form' => $form->createView(),
            'label_title' => 'label.partner',
            'label_button' => 'label.create',
            'mode' => 'add'
        ]);
    }

    /**
     * @Route("/edit/{id}", name="edit", methods={"GET","POST"})
     * @param Request $request
     * @param TrnTopVendorPartners $trnTopVendorPartners
     * @return Response
     * @throws Exception
     */
    public function edit(Request $request, TrnTopVendorPartners $trnTopVendorPartners, CommonHelper $commonHelper, FileUploaderHelper $fileUploaderHelper): Response
    {
        $originalContent = new ArrayCollection();
        foreach ($trnTopVendorPartners->getTrnTopVendorPartnersLocalities() as $trnTopVendorPartnersLocalities)
        {
            $originalContent->add($trnTopVendorPartnersLocalities);
        }
        $form = $this->createForm(TrnTopVendorPartnersType::class, $trnTopVendorPartners);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->managerRegistry->getManager();
            foreach($originalContent as $cntnt){
                if($trnTopVendorPartners->getTrnTopVendorPartnersLocalities()->contains($cntnt)==false){
                    $entityManager->remove($cntnt);
                }
            }
            $trnTopVendorPartnersLocalities = $form['trnTopVendorPartnersLocalities']->getData();
            $existingContactPersonImage = $trnTopVendorPartners->getContactPersonImage();
            foreach($trnTopVendorPartnersLocalities as $topVendorPartnersLocality){
                $topVendorPartnersLocality->setTrnTopVendorPartners($trnTopVendorPartners);
                if (empty($topVendorPartnersLocality->getCreatedOn())){
                    $topVendorPartnersLocality->setCreatedOn(new \DateTime());
                }
                if (empty($topVendorPartnersLocality->getCreatedBy())){
                    $topVendorPartnersLocality->setCreatedBy($this->getUser());
                }
            }
            if($form['removeContactPersonImage']->getData())
            {
                // Remove the image from the system
                $fileName = str_replace("files/","",$existingContactPersonImage);
                $trnTopVendorPartners->setContactPersonImage("");
                $fileUploaderHelper->removeFile($fileName);
            }
            $imageContentFile = $form['contactPersonImage']->getData();
            if (!empty($imageContentFile)) {
                $strSaveFileName = $commonHelper->clean(strtolower($form['contactPersonName']->getData())).Uuid::uuid4()->toString();
                $newFilename = $fileUploaderHelper->uploadPublicFile($imageContentFile, $strSaveFileName,null);
                $trnTopVendorPartners->setContactPersonImage($newFilename);
            }

            $entityManager->persist($trnTopVendorPartners);
            $entityManager->flush();
            #Send Reset Password Email
            $this->addFlash('success', 'form.created_successfully');
            return $this->redirectToRoute('partner_top_index');
        }
        return $this->render('partner/top_partner/form.html.twig', [
            'trnTopVendorPartners' => $trnTopVendorPartners,
            'form' => $form->createView(),
            'label_title' => 'label.partner',
            'label_button' => 'label.create',
            'mode' => 'edit'
        ]);
    }

    /**
     * @Route("/{id}", name="show", methods={"GET"})
     * @param TrnTopVendorPartners $trnTopVendorPartners
     * @return Response
     */
    public function show(TrnTopVendorPartners $trnTopVendorPartners): Response
    {
        return $this->render('partner/top_partner/show.html.twig', [
            'data' => $trnTopVendorPartners,
            'label_title' => 'label.register',
            'label_button' => 'label.update',
            'path_index' => 'partner_top_index',
            'path_edit' => 'partner_top_edit'
        ]);
    }
}