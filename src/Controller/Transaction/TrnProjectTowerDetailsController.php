<?php

namespace App\Controller\Transaction;

use DateTime;
use Exception;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use App\Entity\Transaction\TrnProjectTowerDetails;
use App\Form\Transaction\TrnProjectTowerDetailsType;
use App\Repository\Transaction\TrnProjectTowerDetailsRepository;
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
 * @Route("/core/project/project_tower", name="product_project_tower_")
 * @IsGranted("ROLE_SYS_MODULE_ADMIN")
 */
class TrnProjectTowerDetailsController extends AbstractController
{

    /**
     * @Route("/", name="index", methods={"GET"})
     * @param TrnProjectTowerDetailsRepository $trnProjectTowerDetailsRepository
     * @param PaginatorInterface $paginator
     * @param Request $request
     * @return Response
     */
    public function index(TrnProjectTowerDetailsRepository $trnProjectTowerDetailsRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $nProjectId = $request->query->get('project_id');
        $queryBuilder = $trnProjectTowerDetailsRepository->findAll();
        $pagination = $paginator->paginate(
            $queryBuilder,
            $request->query->getInt('page', 1),
            10
        );
        return $this->render('transaction/project_tower/index.html.twig', [
            'project_towers' => $pagination,
            'path_index' => 'product_project_tower_index',
            'path_add' => 'product_project_tower_add',
            'path_edit' => 'product_project_tower_edit',
            'path_show' => 'product_project_tower_show',
            'path_upload' => 'product_project_tower_upload',
            'label_title' => 'label.tower_button',
        ]);
    }

    /**
     * @Route("/add", name="add", methods={"GET","POST"})
     * @param Request $request
     * @param FileUploaderHelper $fileUploaderHelper
     * @return Response
     * @throws Exception
     */
    public function new(Request $request, FileUploaderHelper $fileUploaderHelper, CommonHelper $commonHelper): Response
    {
        $trnProjectTowerDetails = new TrnProjectTowerDetails();
        $form = $this->createForm(TrnProjectTowerDetailsType::class, $trnProjectTowerDetails);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {


            foreach ($form->get('trnProjectTowerFloorPlans') as $key => $content) {
                $trnProjectTowerFloorPlan = $trnProjectTowerDetails->getTrnProjectTowerFloorPlans()[$key];
                $mediaFile = $content['mediaFileName']->getData();
                $trnProjectTowerFloorPlan->setTrnProject($trnProjectTowerDetails->getTrnProject());
                $trnProjectTowerFloorPlan->setTrnProjectTowerDetails($trnProjectTowerDetails);
                $trnProjectTowerFloorPlan->setUploadUserIpAddress($_SERVER['SERVER_ADDR']);
                $trnProjectTowerFloorPlan->setCreatedOn(new DateTime());
                $trnProjectTowerFloorPlan->setlocationLatitude($trnProjectTowerDetails->getTrnProject()->getLocationLatitude());
                $trnProjectTowerFloorPlan->setLocationLongitude($trnProjectTowerDetails->getTrnProject()->getLocationLongitude());
                if (!empty($mediaFile)) {
                    $newFilename = $fileUploaderHelper->uploadPublicFile($mediaFile, $commonHelper->slugify($content['mediaName']->getData()), null);
                    $trnProjectTowerFloorPlan->setMediaName($content['mediaName']->getData());
                    $trnProjectTowerFloorPlan->setMediaFileName($newFilename);
                    $trnProjectTowerFloorPlan->setMediaAltText($content['mediaAltText']->getData());
                    $trnProjectTowerFloorPlan->setMediaTitle($content['mediaTitle']->getData());
                    $trnProjectTowerFloorPlan->setMediaFilePath($this->getParameter('public_file_folder'));
                }
                $trnProjectTowerFloorPlan->setAppUser($this->getUser());
                $trnProjectTowerFloorPlan->setIsActive(true);
                //$trnProjectTowerFloorPlan->setOrgCompany($trnProjectTowerDetails->getTrnProject()->getOrgCompany());
                $trnProjectTowerFloorPlan->setlocationLatitude($trnProjectTowerDetails->getLocationLatitude());
                $trnProjectTowerFloorPlan->setLocationLongitude($trnProjectTowerDetails->getLocationLongitude());
            }
            foreach ($form->get('trnUploadDocuments') as $key => $content) {
                $trnUploadDocuments = $trnProjectTowerDetails->getTrnUploadDocuments()[$key];
                $trnUploadDocuments->setTrnProject($trnProjectTowerDetails->getTrnProject());
                $trnUploadDocuments->setTrnProjectTowerDetails($trnProjectTowerDetails);
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
                        $trnUploadDocuments->setlocationLatitude($trnProjectTowerDetails->getLocationLatitude());
                        $trnUploadDocuments->setLocationLongitude($trnProjectTowerDetails->getLocationLongitude());
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
            $entityManager = $this->getDoctrine()->getManager();
            $trnProjectTowerDetails->setUserIpAddress($_SERVER['SERVER_ADDR']);
            $trnProjectTowerDetails->setCreatedOn(new DateTime());
            $entityManager->persist($trnProjectTowerDetails);
            $entityManager->flush();
            $this->addFlash('success', 'form.created_successfully');
            return $this->redirectToRoute('product_project_tower_index');
        }
        return $this->render('transaction/project_tower/form.html.twig', [
            'trnProjectTowerDetails' => $trnProjectTowerDetails,
            'form' => $form->createView(),
            'label_title' => 'label.tower_button',
            'label_button' => 'label.create',
            'mode' => 'add'
        ]);
    }

    /**
     * @Route("/edit/{id}", name="edit", methods={"GET","POST"})
     * @param Request $request
     * @param TrnProjectTowerDetails $trnProjectTowerDetails
     * @param FileUploaderHelper $fileUploaderHelper
     * @param CommonHelper $commonHelper
     * @return Response
     * @throws Exception
     */
    public function edit(Request $request, TrnProjectTowerDetails $trnProjectTowerDetails, FileUploaderHelper $fileUploaderHelper, CommonHelper $commonHelper): Response
    {
        $form = $this->createForm(TrnProjectTowerDetailsType::class, $trnProjectTowerDetails);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            foreach ($form->get('trnProjectTowerFloorPlans') as $key => $content) {
                $trnProjectTowerFloorPlan = $trnProjectTowerDetails->getTrnProjectTowerFloorPlans()[$key];
                $mediaFile = $content['mediaFileName']->getData();
                $trnProjectTowerFloorPlan->setTrnProject($trnProjectTowerDetails->getTrnProject());
                $trnProjectTowerFloorPlan->setTrnProjectTowerDetails($trnProjectTowerDetails);
                $trnProjectTowerFloorPlan->setUploadUserIpAddress($_SERVER['SERVER_ADDR']);
                $trnProjectTowerFloorPlan->setCreatedOn(new DateTime());
                $trnProjectTowerFloorPlan->setlocationLatitude($trnProjectTowerDetails->getTrnProject()->getLocationLatitude());
                $trnProjectTowerFloorPlan->setLocationLongitude($trnProjectTowerDetails->getTrnProject()->getLocationLongitude());
                if (!empty($mediaFile)) {
                    $newFilename = $fileUploaderHelper->uploadPublicFile($mediaFile, $commonHelper->slugify($content['mediaName']->getData()), null);
                    $trnProjectTowerFloorPlan->setMediaName($content['mediaName']->getData());
                    $trnProjectTowerFloorPlan->setMediaFileName($newFilename);
                    $trnProjectTowerFloorPlan->setMediaAltText($content['mediaAltText']->getData());
                    $trnProjectTowerFloorPlan->setMediaTitle($content['mediaTitle']->getData());
                    $trnProjectTowerFloorPlan->setMediaFilePath($this->getParameter('public_file_folder'));
                }
                $trnProjectTowerFloorPlan->setAppUser($this->getUser());
                $trnProjectTowerFloorPlan->setIsActive(true);
                //$trnProjectTowerFloorPlan->setOrgCompany($trnProjectTowerDetails->getTrnProject()->getOrgCompany());
                $trnProjectTowerFloorPlan->setlocationLatitude($trnProjectTowerDetails->getLocationLatitude());
                $trnProjectTowerFloorPlan->setLocationLongitude($trnProjectTowerDetails->getLocationLongitude());
            }
            foreach ($form->get('trnUploadDocuments') as $key => $content) {
                $trnUploadDocuments = $trnProjectTowerDetails->getTrnUploadDocuments()[$key];
                $trnUploadDocuments->setTrnProject($trnProjectTowerDetails->getTrnProject());
                $trnUploadDocuments->setTrnProjectTowerDetails($trnProjectTowerDetails);
                $trnUploadDocuments->setAppUser($this->getUser());
                $trnUploadDocuments->setUploadedByAppUser($this->getUser());
                $mediaType = $trnUploadDocuments->getMstUploadDocumentType();
                if ($mediaType->getUploadDocumentType() == 'Image' ){
                    $mediaFile = $content['mediaFileName']->getData();
                    if ($trnUploadDocuments && !empty($mediaFile)) {
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
                        $trnUploadDocuments->setlocationLatitude($trnProjectTowerDetails->getLocationLatitude());
                        $trnUploadDocuments->setLocationLongitude($trnProjectTowerDetails->getLocationLongitude());
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
            $entityManager = $this->getDoctrine()->getManager();
            $trnProjectTowerDetails->setUserIpAddress($_SERVER['SERVER_ADDR']);
            $trnProjectTowerDetails->setCreatedOn(new DateTime());
            $entityManager->persist($trnProjectTowerDetails);
            $entityManager->flush();
            $this->addFlash('success', 'form.created_successfully');
            return $this->redirectToRoute('product_project_tower_index');
        }
        return $this->render('transaction/project_tower/form.html.twig', [
            'trnProjectTowerDetails' => $trnProjectTowerDetails,
            'form' => $form->createView(),
            'label_title' => 'label.tower_button',
            'label_button' => 'label.update',
            'mode' => 'add'
        ]);

    }

    /**
     * @Route("/{id}", name="show", methods={"GET","POST"})
     * @param TrnProjectTowerDetails $trnProjectTowerDetails
     * @return Response
     */
    public function show(TrnProjectTowerDetails $trnProjectTowerDetails): Response
    {
        return $this->render('transaction/project_tower/show.html.twig', [
            'data' => $trnProjectTowerDetails,
            'label_title' => 'label.tower_button',
            'label_button' => 'label.update',
            'path_index' => 'product_project_tower_index',
            'path_edit' => 'product_project_tower_edit'
        ]);
    }
}
