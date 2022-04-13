<?php

namespace App\Controller\Transaction;


use App\Entity\Master\MstCity;
use App\Entity\Master\MstCountry;
use App\Entity\Master\MstPincode;
use App\Entity\Master\MstState;
use App\Form\Transaction\CheckProjectType;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Persistence\ManagerRegistry;
use Exception;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use App\Entity\Transaction\TrnProject;
use App\Form\Transaction\TrnProjectType;
use App\Repository\Transaction\TrnProjectRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;
use Ramsey\Uuid\Uuid;
use App\Service\CommonHelper;
use App\Service\FileUploaderHelper;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

/**
 * @Route("/core/product/properties", name="product_project_")
 * @IsGranted("ROLE_VENDOR_USER")
 */
class TrnProjectController extends AbstractController
{
    private ManagerRegistry $managerRegistry;

    public function __construct(ManagerRegistry $managerRegistry)
    {
        $this->managerRegistry = $managerRegistry;
    }
    /**
     * @Route("/", name="index", methods={"GET"})
     * @param TrnProjectRepository $trnProjectRepository
     * @param PaginatorInterface $paginator
     * @param Request $request
     * @return Response
     */
    public function index(TrnProjectRepository $trnProjectRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $queryBuilder = $trnProjectRepository->findAll();
//        $pagination = $paginator->paginate(
//            $queryBuilder,
//            $request->query->getInt('page', 1),
//            10
//        );
        return $this->render('transaction/project/index.html.twig', [
            'projects' => $queryBuilder,
            'path_index' => 'product_project_index',
            'path_add' => 'product_project_add',
            'path_edit' => 'product_project_edit',
            'path_show' => 'product_project_show',
            'path_upload' => 'product_project_upload',
            'label_title' => 'label.project_button',
        ]);
    }

    /**
     * @Route("/add/{pincode}", name="add", methods={"GET","POST"})
     * @param Request $request
     * @param MstPincode $mstPincode
     * @param FileUploaderHelper $fileUploaderHelper
     * @param CommonHelper $commonHelper
     * @return Response
     * @throws Exception
     */
    public function new(Request $request,FileUploaderHelper $fileUploaderHelper, CommonHelper $commonHelper): Response
    {

        $pincode = $request->attributes->get('pincode');

        if ($pincode != ""){
            $options=[];
            if ($this->getUser()->getAppUserInfo()->getTrnVendorPartnerDetails()){
                $userVendorType = $this->getUser()->getAppUserInfo()->getTrnVendorPartnerDetails()->getMstVendorType();
                $userCompany = $this->getUser()->getAppUserInfo()->getTrnVendorPartnerDetails();
                $options['vendorTypeId'] = $userVendorType->getId();
                $options['vendorId'] = $userCompany->getId();
            }

            $mstPincode = $this->managerRegistry->getRepository(MstPincode::class)->find($pincode);
//            $mstCountry = $this->managerRegistry->getRepository(MstCountry::class)->findOneBy(["country"=>"India"]);
//            $mstState = $this->managerRegistry->getRepository(MstState::class)->findOneBy(["state"=>$mstPincode->getStateName()]);
            $mstcity = $this->managerRegistry->getRepository(MstCity::class)->getCityByPincodeCity($mstPincode->getDistrict(),"India");
            $trnProject = new TrnProject();
            $trnProject->setMstPincode($mstPincode);
//            $trnProject->setMstVendorType($userVendorType);
            $trnProject->setMstCountry($mstcity->getMstCountry());
            $trnProject->setMstState($mstcity->getMstState());
            $trnProject->setMstCity($mstcity);
            $form = $this->createForm(TrnProjectType::class, $trnProject,$options);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                if ($form->get("possessionDate")->getData()){
                    $trnProject->setActualPossessionDate($form->get("possessionDate")->getData());
                }else{
                    if ($form->get("possessionYear")->getData() && $form->get("possessionMonth")->getData()){
                        $mydate1 = date('Y-F-d', strtotime($form->get("possessionYear")->getData()."-".$form->get("possessionMonth")->getData()."-01"));
                        $mydatetimestamp = date(strtotime($mydate1));
                        $mydateMonth = date(strtotime('+1 month',$mydatetimestamp));
                        $mydate = date('Y-m-d',strtotime('-1 day',$mydateMonth));
                        $mydate = new \DateTime($mydate);
                        $trnProject->setActualPossessionDate($mydate);
                    }
                }
                // Upload the OG Image for SEO
                $ogImageFile = $form['ogImage']->getData();
                if ($ogImageFile) {
                    $newFilename = $fileUploaderHelper->uploadPublicFile($ogImageFile, $form['ogImage']->getData(),null);
                    $trnProject->setOgImage($newFilename);
                    $trnProject->setOgImagePath($this->getParameter('public_file_folder'));
                }
                $brochureImageFile = $form['brochure']->getData();
                if ($brochureImageFile) {
                    $newFilename = $fileUploaderHelper->uploadPublicFile($brochureImageFile, $form['brochure']->getData(),null);
                    $trnProject->setBrochure($newFilename);
                    $trnProject->setBrochurePath($this->getParameter('public_file_folder'));
                }
                foreach ($form->get('trnProjectRoomConfigurations') as $key => $content) {

                    $trnProjectRoomConfigurations = $trnProject->getTrnProjectRoomConfigurations()[$key];
                    $trnProjectRoomConfigurations->setTrnProject($trnProject);
                    $trnProjectRoomConfigurations->setIsActive(1);
                    $trnProjectRoomConfigurations->setCreatedOn(new DateTime());

                    $mediaType = $trnProjectRoomConfigurations->getMstUploadDocumentType();
                    if ($mediaType){
                        if ($mediaType->getUploadDocumentType() == 'Image' ){
                            $mediaFile = $content['mediaFileName']->getData();
                            if ($trnProjectRoomConfigurations) {
                                $newFilename = $fileUploaderHelper->uploadPublicFile($mediaFile, $commonHelper->slugify($content['mediaName']->getData()), null);
                                $trnProjectRoomConfigurations->setMediaName($content['mediaName']->getData());
                                $trnProjectRoomConfigurations->setMediaFileName($newFilename);
                                $trnProjectRoomConfigurations->setMediaAltText($content['mediaAltText']->getData());
                                $trnProjectRoomConfigurations->setMediaTitle($content['mediaTitle']->getData());
                                $trnProjectRoomConfigurations->setMediaFilePath($this->getParameter('public_file_folder'));
                                //$trnProjectRoomConfigurations->setMediaPath('');

                            }
                        }
                        if ($mediaType->getUploadDocumentType() == 'Video') {
                            $video = $content['mediaName']->getData();
                            if ($video) {
                                $trnProjectRoomConfigurations->setMediaFileName('');
                                $trnProjectRoomConfigurations->setMediaAltText('');
                                $trnProjectRoomConfigurations->setMediaTitle('');
                                $trnProjectRoomConfigurations->setMediaFilePath('');
                                $trnProjectRoomConfigurations->setMediaName($content['mediaName']->getData());
                                //$trnProjectRoomConfigurations->setMediaPath($content['mediaPath']->getData());
                            }
                        }
                    }
                }
                foreach ($form->get('trnUploadDocument') as $key => $content) {
                    $trnUploadDocuments = $trnProject->getTrnUploadDocument()[$key];
                    $trnUploadDocuments->setTrnProject($trnProject);
//                $trnUploadDocuments->setOrgCompany($trnProject->getOrgCompany());
                    $trnUploadDocuments->setAppUser($this->getUser());
                    $trnUploadDocuments->setUploadedByAppUser($this->getUser());
                    $mediaType = $trnUploadDocuments->getMstUploadDocumentType();
                    if ($mediaType->getUploadDocumentType() == 'Image' ){
                        $mediaFile = $content['mediaFileName']->getData();
                        if ($trnUploadDocuments) {
                            $newFilename = $fileUploaderHelper->uploadPublicFile($mediaFile, $commonHelper->slugify($content['mediaName']->getData()), null);
                            $trnUploadDocuments->setMediaName($content['mediaName']->getData());
                            $trnUploadDocuments->setMediaFileName($newFilename);
                            $trnUploadDocuments->setMediaAltText($content['mediaAltText']->getData());
                            $trnUploadDocuments->setMediaTitle($content['mediaTitle']->getData());
                            $trnUploadDocuments->setMediaFilePath($this->getParameter('public_file_folder'));
                            $trnUploadDocuments->setMediaPath('');
                            $trnUploadDocuments->setPosition($content['position']->getData());
                            $trnUploadDocuments->setUploadUserIpAddress($_SERVER['SERVER_ADDR']);
                            $trnUploadDocuments->setCreatedOn(new DateTime());
                            $trnUploadDocuments->setIsActive(true);
                            $trnUploadDocuments->setlocationLatitude($trnProject->getLocationLatitude());
                            $trnUploadDocuments->setLocationLongitude($trnProject->getLocationLongitude());
                        }
                    }
                    if ($mediaType->getUploadDocumentType() == 'Video') {
                        $video = $content['mediaName']->getData();
                        if ($video) {
                            $trnUploadDocuments->setMediaFileName('');
                            $trnUploadDocuments->setMediaAltText('');
                            $trnUploadDocuments->setMediaTitle('');
                            $trnUploadDocuments->setMediaFilePath('');
                            $trnUploadDocuments->setMediaName($content['mediaName']->getData());
                            $trnUploadDocuments->setMediaPath($content['mediaPath']->getData());
                        }
                    }
                }
                $entityManager = $this->managerRegistry->getManager();
                $trnProject->setCreatedOn(new DateTime());
                //$trnProject->setUserIpAddress($_SERVER['SERVER_ADDR']);
                $entityManager->persist($trnProject);
                $entityManager->flush();
                $this->addFlash('success', 'form.created_successfully');
                return $this->redirectToRoute('product_project_index');
            }
            return $this->render('transaction/project/form.html.twig', [
                'trnProject' => $trnProject,
                'form' => $form->createView(),
                'label_title' => 'label.project_button',
                'label_button' => 'label.create',
                'mode' => 'add'
            ]);
        }else{
            return $this->redirectToRoute('product_project_index');
        }
    }

    /**
     * @Route("/edit/{id}", name="edit", methods={"GET","POST"})
     * @param Request $request
     * @param TrnProject $trnProject
     * @param FileUploaderHelper $fileUploaderHelper
     * @param CommonHelper $commonHelper
     * @return Response
     * @throws Exception
     */
    public function edit(Request $request, TrnProject $trnProject, FileUploaderHelper $fileUploaderHelper, CommonHelper $commonHelper): Response
    {
        $existingOgImageFile = $trnProject->getOgImage();
        $existingBrochureFile = $trnProject->getBrochure();
        $originalTrnUploadDocument = new ArrayCollection();
        foreach($trnProject->getTrnUploadDocument() as $trnUploadDocument){
            $originalTrnUploadDocument->add($trnUploadDocument);
        }
        $form = $this->createForm(TrnProjectType::class, $trnProject);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get("possessionDate")->getData()){
                $trnProject->setActualPossessionDate($form->get("possessionDate")->getData());
            }else{
                if ($form->get("possessionYear")->getData() && $form->get("possessionMonth")->getData()){
                    $mydate1 = date('Y-F-d', strtotime($form->get("possessionYear")->getData()."-".$form->get("possessionMonth")->getData()."-01"));
                    $mydatetimestamp = date(strtotime($mydate1));
                    $mydateMonth = date(strtotime('+1 month',$mydatetimestamp));
                    $mydate = date('Y-m-d',strtotime('-1 day',$mydateMonth));
                    $mydate = new \DateTime($mydate);
                    $trnProject->setActualPossessionDate($mydate);
                }
            }
            foreach($originalTrnUploadDocument as $trnUploadDocument){
                if($trnProject->getTrnUploadDocument()->contains($trnUploadDocument)==false){
                    $this->managerRegistry->getManager()->remove($trnUploadDocument);
                }
            }
            // Upload the OG Image for SEO
            $ogImageFile = $form['ogImage']->getData();
            if ($ogImageFile) {
                if ($existingOgImageFile != '')
                {
                    $newFilename = $fileUploaderHelper->uploadPublicFile($ogImageFile, $form['ogImage']->getData(),$existingOgImageFile);
                } else {
                    $newFilename = $fileUploaderHelper->uploadPublicFile($ogImageFile, $form['ogImage']->getData(),null);
                }

                $trnProject->setOgImage($newFilename);
                $trnProject->setOgImagePath($this->getParameter('public_file_folder'));
            }
            // Upload the Brochure
            $brochureImageFile = $form['brochure']->getData();
            if ($brochureImageFile) {
                if ($existingBrochureFile != '')
                {
                    $newFilename = $fileUploaderHelper->uploadPublicFile($brochureImageFile, $form['brochure']->getData(),$existingBrochureFile);
                } else {
                    $newFilename = $fileUploaderHelper->uploadPublicFile($brochureImageFile, $form['brochure']->getData(),null);
                }
                $trnProject->setBrochure($newFilename);
                $trnProject->setBrochurePath($this->getParameter('public_file_folder'));
            }
            foreach ($form->get('trnProjectRoomConfigurations') as $key => $content) {

                $trnProjectRoomConfigurations = $trnProject->getTrnProjectRoomConfigurations()[$key];
                $trnProjectRoomConfigurations->setTrnProject($trnProject);
                $trnProjectRoomConfigurations->setCreatedOn(new DateTime());
                $trnProjectRoomConfigurations->setIsActive(true);
                $mediaType = $trnProjectRoomConfigurations->getMstUploadDocumentType();
                if($mediaType){
                    if ($mediaType->getUploadDocumentType() == 'Image' ){
                        $mediaFile = $content['mediaFileName']->getData();

                        if ($trnProjectRoomConfigurations && !empty($mediaFile)) {
                            $trnProjectRoomConfigurationsMedia = $trnProjectRoomConfigurations->getMediaFileName();
                            $newFilename = $fileUploaderHelper->uploadPublicFile($mediaFile, $commonHelper->slugify($content['mediaName']->getData()), $trnProjectRoomConfigurationsMedia);
                            $trnProjectRoomConfigurations->setMediaName($content['mediaName']->getData());
                            $trnProjectRoomConfigurations->setMediaFileName($newFilename);
                            $trnProjectRoomConfigurations->setMediaAltText($content['mediaAltText']->getData());
                            $trnProjectRoomConfigurations->setMediaTitle($content['mediaTitle']->getData());
                            $trnProjectRoomConfigurations->setMediaFilePath($this->getParameter('public_file_folder'));
                        }
                    }
                    if ($mediaType->getUploadDocumentType() == 'Video') {
                        $video = $content['mediaName']->getData();
                        if ($video) {
                            $trnProjectRoomConfigurations->setMediaFileName('');
                            $trnProjectRoomConfigurations->setMediaAltText('');
                            $trnProjectRoomConfigurations->setMediaTitle('');
                            $trnProjectRoomConfigurations->setMediaFilePath('');
                            $trnProjectRoomConfigurations->setMediaName($content['mediaName']->getData());
                            $trnProjectRoomConfigurations->setMediaPath($content['mediaPath']->getData());
                        }
                    }
                }
            }

            foreach ($form->get('trnUploadDocument') as $key => $content) {
                $trnUploadDocuments = $trnProject->getTrnUploadDocument()[$key];
                $trnUploadDocuments->setTrnProject($trnProject);
                $trnUploadDocuments->setAppUser($this->getUser());
                $trnUploadDocuments->setUploadedByAppUser($this->getUser());
                $mediaType = $trnUploadDocuments->getMstUploadDocumentType();
                $existingProjectMedia = $trnUploadDocuments->getMediaFileName();
                if ($mediaType){
                    if ($mediaType->getUploadDocumentType() == 'Image' ){
                        $mediaFile = $content['mediaFileName']->getData();
                        $oldMediaFile = $trnUploadDocuments->getMediaFileName();
                        if ($trnUploadDocuments && !empty($mediaFile)) {
                            $newFilename = $fileUploaderHelper->uploadPublicFile($mediaFile, $commonHelper->slugify($content['mediaName']->getData()),$oldMediaFile );
                            $trnUploadDocuments->setMediaName($content['mediaName']->getData());
                            $trnUploadDocuments->setMediaFileName($newFilename);
                            $trnUploadDocuments->setMediaAltText($content['mediaAltText']->getData());
                            $trnUploadDocuments->setMediaTitle($content['mediaTitle']->getData());
                            $trnUploadDocuments->setMediaFilePath($this->getParameter('public_file_folder'));
                            $trnUploadDocuments->setMediaPath('');
                            $trnUploadDocuments->setPosition($content['position']->getData());
                            $trnUploadDocuments->setUploadUserIpAddress($_SERVER['SERVER_ADDR']);
                            $trnUploadDocuments->setCreatedOn(new DateTime());
                            $trnUploadDocuments->setIsActive(true);
                            $trnUploadDocuments->setlocationLatitude($trnProject->getLocationLatitude());
                            $trnUploadDocuments->setLocationLongitude($trnProject->getLocationLongitude());
                        }
                    }
                    if ($mediaType->getUploadDocumentType() == 'Video') {
                        $video = $content['mediaName']->getData();
                        if ($video) {
                            $fileUploaderHelper->removeFile($existingProjectMedia);
                            $trnUploadDocuments->setMediaFileName('');
                            $trnUploadDocuments->setMediaAltText('');
                            $trnUploadDocuments->setMediaTitle('');
                            $trnUploadDocuments->setMediaFilePath('');
                            $trnUploadDocuments->setMediaName($content['mediaName']->getData());
                            $trnUploadDocuments->setMediaPath($content['mediaPath']->getData());
                        }
                    }
                }
            }
            $entityManager = $this->managerRegistry->getManager();
            $trnProject->setCreatedOn(new DateTime());
            $entityManager->persist($trnProject);
            $entityManager->flush();
            $this->addFlash('success', 'form.updated_successfully');
            return $this->redirectToRoute('product_project_index');
        }
        return $this->render('transaction/project/form.html.twig', [
            'trnProject' => $trnProject,
            'form' => $form->createView(),
            'label_title' => 'label.project_button',
            'label_button' => 'label.update',
            'mode' => 'edit'
        ]);
    }
    /**
     * @Route("/check-pincode", name="check_pincode", methods={"GET","POST"})
     * @param Request $request
     * @return Response
     */
    public function checkPincode(Request $request): Response
    {
        $form = $this->createForm(CheckProjectType::class);
        return $this->render('transaction/project/check.html.twig', [
            'form' => $form->createView(),
            'label_title' => 'label.project_button',
            'label_button' => 'label.search',
            'path_index' => 'product_project_index',
        ]);
    }

    /**
     * @Route("/search", name="search", methods={"GET","POST"})
     * @param Request $request
     * @return Response
     */
    public function search(Request $request): Response
    {
        $mstPincode = trim($request->query->get('mstPincode'));
        $projectName = ucwords($request->query->get('projectName'));
        $trnProject = $this->managerRegistry->getRepository(TrnProject::class)->getProjectByPincodeandName($projectName,$mstPincode);

        return $this-> render('transaction/project/_ajax_listing.html.twig', [
            'projects' => $trnProject,
            'mstPincode'=>$mstPincode,
            'path_index' => 'product_project_index',
            'path_add' => 'product_project_add',
            'path_edit' => 'product_project_edit',
            'path_show' => 'product_project_show',
            'path_upload' => 'product_project_upload',
            'label_title' => 'label.project_button',
        ]);
    }

    /**
     * @Route("/{id}", name="show", methods={"GET","POST"})
     * @param TrnProject $trnProject
     * @return Response
     */
    public function show(TrnProject $trnProject): Response
    {
        return $this->render('transaction/project/show.html.twig', [
            'data' => $trnProject,
            'label_title' => 'label.project_button',
            'label_button' => 'label.update',
            'path_index' => 'product_project_index',
            'path_edit' => 'product_project_edit'
        ]);
    }
}
