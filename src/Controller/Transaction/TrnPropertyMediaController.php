<?php

namespace App\Controller\Transaction;
use App\Entity\SystemApp\AppUser;
use App\Entity\Transaction\TrnProject;
use App\Entity\Transaction\TrnUploadDocument;
use App\Entity\Transaction\TrnProjectRoomConfiguration;
use App\Form\Transaction\TrnUploadDocumentType;
use App\Repository\Transaction\TrnUploadDocumentRepository;
use App\Service\CommonHelper;
use App\Service\FileUploaderHelper;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Persistence\ManagerRegistry;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/core/product/property-media", name="product_property_media_")
 * @IsGranted("ROLE_VENDOR_USER")
 */
class TrnPropertyMediaController extends AbstractController
{
    private ManagerRegistry $managerRegistry;

    public function __construct(ManagerRegistry $managerRegistry)
    {
        $this->managerRegistry = $managerRegistry;
    }
    /**
     * @Route("/", name="index", methods={"GET"})
     * @param TrnUploadDocumentRepository $trnUploadDocumentRepository
     * @param Request $request
     * @return Response
     */
    public function index(TrnUploadDocumentRepository $trnUploadDocumentRepository, Request $request): Response
    {
        $projectId = $request->query->get('projectId');
        if(!$projectId) {
            return $this->redirectToRoute('product_properties_index');
        }
        $trnProject = $this->managerRegistry->getRepository(TrnProject::class)->find($projectId);
        return $this->render('transaction/property/property_media/index.html.twig', [
            'trnUploadDocuments' => $trnUploadDocumentRepository->findBy(['trnProject' => $projectId,'createdBy'=>$this->getUser()]),
            'trnProject' => $trnProject,
            'path_index' => 'product_property_media_index',
            'path_add' => 'product_property_media_add_from_another',
            'path_show' => 'product_property_media_show',
            'path_edit' => 'product_property_media_edit',
            'label_title' => 'label.project_property_media',
            'label_heading' => 'label.project_name'
        ]);
    }

    /**
     * @Route("/add", name="add", methods={"GET","POST"})
     * @param Request $request
     * @param CommonHelper $commonHelper
     * @param FileUploaderHelper $fileUploaderHelper
     * @return Response
     * @throws \Exception
     */
    public function new(Request $request, CommonHelper $commonHelper, FileUploaderHelper $fileUploaderHelper): Response
    {
        $projectId = $request->query->get('projectId');
        $trnProjectRoomConfigurations = $request->query->get('trnProjectRoomConfiguration');
        if(!$projectId) {
            return $this->redirectToRoute('product_properties_index');
        }
        $position = $this->managerRegistry->getRepository(TrnUploadDocument::class)->getMediaBySeqNo($projectId);
        $trnUploadDocument = new TrnUploadDocument();
        if($trnProjectRoomConfigurations){
            foreach($trnProjectRoomConfigurations as $trnProjectRoomConfigurationId){
                $trnProjectRoomConfiguration = $this->managerRegistry->getRepository(TrnProjectRoomConfiguration::class)->find($trnProjectRoomConfigurationId);
                $trnUploadDocument->addTrnProjectRoomConfiguration($trnProjectRoomConfiguration);
            }
        }
        $trnUploadDocument->setPosition($position['cnt']+1);
        $trnProject = $this->managerRegistry->getRepository(TrnProject::class)->find($projectId);
        $form = $this->createForm(TrnUploadDocumentType::class, $trnUploadDocument,['projectId'=>$projectId]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $imageFile = $form['mediaFileName']->getData();
            $mediaType = $trnUploadDocument->getMstUploadDocumentType();
            if ($mediaType->getUploadDocumentType() == 'Image' ){
                $newFilename = $fileUploaderHelper->uploadPublicFile($imageFile, $commonHelper->slugify($form['mediaName']->getData()), $existingMedia = null);
                $trnUploadDocument->setMediaFileName($newFilename);
                $trnUploadDocument->setMediaFilePath($this->getParameter('public_file_folder'));
                $trnUploadDocument->setUploadUserIpAddress($_SERVER['SERVER_ADDR']);
                $trnUploadDocument->setCreatedOn(new \DateTime());
                $trnUploadDocument->setlocationLatitude($trnProject->getLocationLatitude());
                $trnUploadDocument->setLocationLongitude($trnProject->getLocationLongitude());
            }
            if ($mediaType->getUploadDocumentType() == 'Video') {
                $video = $form['mediaName']->getData();
                if ($video) {
                    $trnUploadDocument->setMediaFileName('');
                    $trnUploadDocument->setMediaAltText('');
                    $trnUploadDocument->setMediaTitle('');
                    $trnUploadDocument->setMediaFilePath('');
                    $trnUploadDocument->setMediaName($form['mediaName']->getData());
                    $trnUploadDocument->setMediaPath($form['mediaPath']->getData());
                }
            }
            $entityManager = $this->managerRegistry->getManager();
            $trnUploadDocument->setTrnProject($trnProject);
            $trnUploadDocument->setCreatedBy($this->getUser());
            $trnUploadDocument->setAppUser($this->getUser());
            $trnUploadDocument->setUploadedByAppUser($this->getUser());
            $entityManager->persist($trnUploadDocument);
            $entityManager->flush();
            $this->addFlash('success', 'form.created_successfully');
            return $this->redirectToRoute('product_property_media_index', $request->query->all());
        }

        return $this->render('transaction/property/property_media/form.html.twig', [
            'trnUploadDocument' => $trnUploadDocument,
            'trnProject' => $trnProject,
            'form' => $form->createView(),
            'index_path' => 'product_property_media_index',
            'label_title' => 'label.project_property_media',
            'label_button' => 'label.create',
            'label_heading' => 'label.project_name',
            'mode' => 'add'
        ]);
    }
    /**
     * @Route("/add-from-another", name="add_from_another", methods={"GET","POST"})
     * @param Request $request
     * @return Response
     */
    public function addFromAnother(Request $request): Response
    {
        $projectId = $request->query->get('projectId');
        if(!$projectId) {
            return $this->redirectToRoute('product_properties_index');
        }
        $trnProject = $this->managerRegistry->getRepository(TrnProject::class)->find($projectId);
        $trnUploadDocuments = $this->managerRegistry->getRepository(TrnUploadDocument::class)->findBy(['isActive'=>1,'createdBy'=>$this->getUser()]);
        $trnProjectRoomConfigurations = $this->managerRegistry->getRepository(TrnProjectRoomConfiguration::class)->findBy(['isActive'=>1,'trnProject'=>$trnProject,'createdBy'=>$this->getUser()]);

        return $this->render('transaction/property/property_media/another.html.twig', [
            'trnUploadDocuments' => $trnUploadDocuments,
            'trnProjectRoomConfigurations' => $trnProjectRoomConfigurations,
            'trnProject' => $trnProject,
            'index_path' => 'product_property_media_index',
            'path_add' => 'product_property_media_add',
            'label_title' => 'label.project_property_media',
            'label_button' => 'label.create',
            'label_heading' => 'label.project_name',
            'mode' => 'add-from-another'
        ]);
    }

    /**
     * @Route("/{id}/edit/{appUser}", name="edit", methods={"GET","POST"})
     * @param Request $request
     * @param TrnUploadDocument $trnUploadDocument
     * @param AppUser $appUser
     * @param CommonHelper $commonHelper
     * @param FileUploaderHelper $fileUploaderHelper
     * @return Response
     * @throws \Exception
     */
    public function edit(Request $request, TrnUploadDocument $trnUploadDocument, AppUser $appUser, CommonHelper  $commonHelper, FileUploaderHelper $fileUploaderHelper): Response
    {
        $projectId = $request->query->get('projectId');
        if(!$projectId) {
            return $this->redirectToRoute('product_properties_index');
        }
        $trnProject = $this->managerRegistry->getRepository(TrnProject::class)->find($projectId);
        $originalTrnUploadDocument = new ArrayCollection();
        $originalTrnUploadDocument->add($trnUploadDocument);
        $form = $this->createForm(TrnUploadDocumentType::class, $trnUploadDocument,['projectId'=>$projectId]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $mediaType = $trnUploadDocument->getMstUploadDocumentType();
            $existingMedia = $trnUploadDocument->getMediaFileName();
            if ($mediaType){
                if ($mediaType->getUploadDocumentType() == 'Image' ){
                    $mediaFile = $form['mediaFileName']->getData();
                    $oldMediaFile = $trnUploadDocument->getMediaFileName();
                    if ($trnUploadDocument && !empty($mediaFile)) {
                        $newFilename = $fileUploaderHelper->uploadPublicFile($mediaFile, $commonHelper->slugify($form['mediaName']->getData()),$oldMediaFile );
                        $trnUploadDocument->setMediaName($form['mediaName']->getData());
                        $trnUploadDocument->setMediaFileName($newFilename);
                        $trnUploadDocument->setMediaFilePath($this->getParameter('public_file_folder'));
                        $trnUploadDocument->setMediaPath('');
                        $trnUploadDocument->setUploadUserIpAddress($_SERVER['SERVER_ADDR']);
                        $trnUploadDocument->setlocationLatitude($trnProject->getLocationLatitude());
                        $trnUploadDocument->setLocationLongitude($trnProject->getLocationLongitude());
                    }
                }
                if ($mediaType->getUploadDocumentType() == 'Video') {
                    $video = $form['mediaName']->getData();
                    if ($video) {
                        $fileUploaderHelper->removeFile($existingMedia);
                        $trnUploadDocument->setMediaFileName('');
                        $trnUploadDocument->setMediaAltText('');
                        $trnUploadDocument->setMediaTitle('');
                        $trnUploadDocument->setMediaFilePath('');
                        $trnUploadDocument->setMediaName($form['mediaName']->getData());
                        $trnUploadDocument->setMediaPath($form['mediaPath']->getData());
                    }
                }
            }
            $this->managerRegistry->getManager()->flush();
            $this->addFlash('success', 'form.updated_successfully');
            return $this->redirectToRoute('product_property_media_index', $request->query->all());
        }

        return $this->render('transaction/property/property_media/form.html.twig', [
            'trnUploadDocument' => $trnUploadDocument,
            'trnProject' => $trnProject,
            'form' => $form->createView(),
            'index_path' => 'product_property_media_index',
            'label_title' => 'label.project_property_media',
            'label_button' => 'label.update',
            'label_heading' => 'label.project_name',
            'mode' => 'edit'
        ]);
    }

    /**
     * @Route("/{id}", name="show", methods={"GET","POST"})
     * @param TrnUploadDocument $trnUploadDocument
     * @return Response
     */
    public function show(TrnUploadDocument $trnUploadDocument): Response
    {
        return $this->render('transaction/property/room_configuration/show.html.twig', [
            'data' => $trnUploadDocument,
            'label_title' => 'label.room_configuration',
            'label_button'   => 'label.update',
            'path_index' => 'product_property_media_index',
            'path_edit' => 'product_property_media_edit'
        ]);
    }
}
