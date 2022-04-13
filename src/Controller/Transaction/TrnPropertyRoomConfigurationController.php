<?php

namespace App\Controller\Transaction;
use App\Entity\SystemApp\AppUser;
use App\Entity\Transaction\TrnProject;
use App\Entity\Transaction\TrnProjectRoomConfiguration;
use App\Form\Transaction\TrnProjectRoomConfigurationType;
use App\Repository\Transaction\TrnProjectRoomConfigurationRepository;
use App\Service\CommonHelper;
use App\Service\FileUploaderHelper;
use Doctrine\Persistence\ManagerRegistry;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/core/product/room-configuration", name="product_room_configuration_")
 * @IsGranted("ROLE_VENDOR_USER")
 */
class TrnPropertyRoomConfigurationController extends AbstractController
{
    private ManagerRegistry $managerRegistry;

    public function __construct(ManagerRegistry $managerRegistry)
    {
        $this->managerRegistry = $managerRegistry;
    }
    /**
     * @Route("/", name="index", methods={"GET"})
     * @param TrnProjectRoomConfigurationRepository $trnProjectRoomConfigurationRepository
     * @param Request $request
     * @return Response
     */
    public function index(TrnProjectRoomConfigurationRepository $trnProjectRoomConfigurationRepository, Request $request): Response
    {
        $projectId = $request->query->get('projectId');
        if(!$projectId) {
            return $this->redirectToRoute('product_properties_index');
        }
        $trnProject = $this->managerRegistry->getRepository(TrnProject::class)->find($projectId);
        return $this->render('transaction/property/room_configuration/index.html.twig', [
            'trnProjectRoomConfigurations' => $trnProjectRoomConfigurationRepository->findBy(['trnProject' => $projectId,'createdBy'=>$this->getUser()]),
            'trnProject' => $trnProject,
            'path_index' => 'product_room_configuration_index',
            'path_add' => 'product_room_configuration_add_from_another',
            'path_show' => 'product_room_configuration_show',
            'path_edit' => 'product_room_configuration_edit',
            'label_title' => 'label.room_configuration',
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
        if(!$projectId) {
            return $this->redirectToRoute('product_properties_index');
        }
        $trnProjectRoomConfiguration = new TrnProjectRoomConfiguration();
        $existingTrnProjectRoomConfigurationId = $request->query->get('trnProjectRoomConfiguration');
        if ($existingTrnProjectRoomConfigurationId){
            if($existingTrnProjectRoomConfigurationId != "New"){
                $existingTrnProjectRoomConfiguration = $this->managerRegistry->getRepository(TrnProjectRoomConfiguration::class)->find($existingTrnProjectRoomConfigurationId);
                $trnProjectRoomConfiguration->setMstPropertyTransactionCategory($existingTrnProjectRoomConfiguration->getMstPropertyTransactionCategory());
                $trnProjectRoomConfiguration->setMstRoomConfiguration($existingTrnProjectRoomConfiguration->getMstRoomConfiguration());
                $trnProjectRoomConfiguration->setMstFacing($existingTrnProjectRoomConfiguration->getMstFacing());
                $trnProjectRoomConfiguration->setFloor($existingTrnProjectRoomConfiguration->getFloor());
                $trnProjectRoomConfiguration->setNoOfBedRoom($existingTrnProjectRoomConfiguration->getNoOfBedRoom());
                $trnProjectRoomConfiguration->setNoOfBathRooms($existingTrnProjectRoomConfiguration->getNoOfBathRooms());
                $trnProjectRoomConfiguration->setNoOfBalcony($existingTrnProjectRoomConfiguration->getNoOfBalcony());
                $trnProjectRoomConfiguration->setMstProjectArea($existingTrnProjectRoomConfiguration->getMstProjectArea());
                $trnProjectRoomConfiguration->setMstProjectAreaCategory($existingTrnProjectRoomConfiguration->getMstProjectAreaCategory());
                $trnProjectRoomConfiguration->setAreaValue($existingTrnProjectRoomConfiguration->getAreaValue());
                $trnProjectRoomConfiguration->setMstCurrency($existingTrnProjectRoomConfiguration->getMstCurrency());
            }
        }
        $trnProject = $this->managerRegistry->getRepository(TrnProject::class)->find($projectId);
        $form = $this->createForm(TrnProjectRoomConfigurationType::class, $trnProjectRoomConfiguration);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->managerRegistry->getManager();
            $imageFile = $form['mediaFileName']->getData();
            if ($imageFile){
                $newFilename = $fileUploaderHelper->uploadPublicFile($imageFile, $commonHelper->slugify($form['mediaName']->getData()), $existingMedia = null);
                $trnProjectRoomConfiguration->setMediaFileName($newFilename);
                $trnProjectRoomConfiguration->setMediaFilePath($this->getParameter('public_file_folder'));
            }
            $trnProjectRoomConfiguration->setTrnProject($trnProject);
            $trnProjectRoomConfiguration->setCreatedOn(new \DateTime());
            $trnProjectRoomConfiguration->setCreatedBy($this->getUser());
            $entityManager->persist($trnProjectRoomConfiguration);
            $entityManager->flush();
            $this->addFlash('success', 'form.created_successfully');
            return $this->redirectToRoute('product_room_configuration_index', $request->query->all());
        }

        return $this->render('transaction/property/room_configuration/form.html.twig', [
            'trnProjectRoomConfiguration' => $trnProjectRoomConfiguration,
            'trnProject' => $trnProject,
            'form' => $form->createView(),
            'index_path' => 'product_room_configuration_index',
            'label_title' => 'label.room_configuration',
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
        $trnProjectRoomConfigurations = $this->managerRegistry->getRepository(TrnProjectRoomConfiguration::class)->getUniqueRoomConfiguration(['trnProjectId'=>$trnProject->getId()]);
        return $this->render('transaction/property/room_configuration/another.html.twig', [
            'trnProjectRoomConfigurations' => $trnProjectRoomConfigurations,
            'trnProject' => $trnProject,
            'index_path' => 'product_room_configuration_index',
            'path_add' => 'product_room_configuration_add',
            'label_title' => 'label.room_configuration',
            'label_button' => 'label.create',
            'label_heading' => 'label.project_name',
            'mode' => 'add-from-another'
        ]);
    }

    /**
     * @Route("/{id}/edit/{appUser}", name="edit", methods={"GET","POST"})
     * @param Request $request
     * @param TrnProjectRoomConfiguration $trnProjectRoomConfiguration
     * @param AppUser $appUser
     * @param CommonHelper $commonHelper
     * @param FileUploaderHelper $fileUploaderHelper
     * @return Response
     * @throws \Exception
     */
    public function edit(Request $request, TrnProjectRoomConfiguration $trnProjectRoomConfiguration, AppUser $appUser, CommonHelper  $commonHelper, FileUploaderHelper $fileUploaderHelper): Response
    {
        $projectId = $request->query->get('projectId');
        if(!$projectId) {
            return $this->redirectToRoute('product_properties_index');
        }
        $existingMedia = $trnProjectRoomConfiguration->getMediaFileName();
        $trnProject = $this->managerRegistry->getRepository(TrnProject::class)->find($projectId);
        $form = $this->createForm(TrnProjectRoomConfigurationType::class, $trnProjectRoomConfiguration);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $imageFile = $form['mediaFileName']->getData();
            if ($imageFile){
                if($existingMedia != ''){
                    $newFilename = $fileUploaderHelper->uploadPublicFile($imageFile, $commonHelper->slugify($form['mediaName']->getData()), $existingMedia);
                } else {
                    $newFilename = $fileUploaderHelper->uploadPublicFile($imageFile, $commonHelper->slugify($form['mediaName']->getData()), $existingMedia = null);
                }
                $trnProjectRoomConfiguration->setMediaFileName($newFilename);
                $trnProjectRoomConfiguration->setMediaFilePath($this->getParameter('public_file_folder'));
            }
            $this->managerRegistry->getManager()->flush();
            $this->addFlash('success', 'form.updated_successfully');
            return $this->redirectToRoute('product_room_configuration_index', $request->query->all());
        }

        return $this->render('transaction/property/room_configuration/form.html.twig', [
            'trnProjectRoomConfiguration' => $trnProjectRoomConfiguration,
            'trnProject' => $trnProject,
            'form' => $form->createView(),
            'index_path' => 'product_room_configuration_index',
            'label_title' => 'label.room_configuration',
            'label_button' => 'label.update',
            'label_heading' => 'label.project_name',
            'mode' => 'edit'
        ]);
    }

    /**
     * @Route("/{id}", name="show", methods={"GET","POST"})
     * @param TrnProjectRoomConfiguration $trnProjectRoomConfiguration
     * @return Response
     */
    public function show(TrnProjectRoomConfiguration $trnProjectRoomConfiguration): Response
    {
        return $this->render('transaction/property/room_configuration/show.html.twig', [
            'data' => $trnProjectRoomConfiguration,
            'label_title' => 'label.room_configuration',
            'label_button' => 'label.update',
            'path_index' => 'product_room_configuration_index',
            'path_edit' => 'product_room_configuration_edit'
        ]);
    }
}
