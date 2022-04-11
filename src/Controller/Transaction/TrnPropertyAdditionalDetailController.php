<?php

namespace App\Controller\Transaction;
use App\Entity\SystemApp\AppUser;
use App\Entity\Transaction\TrnProject;
use App\Entity\Transaction\TrnProjectAdditionalDetail;
use App\Entity\Transaction\TrnProjectRoomConfiguration;
use App\Form\Transaction\TrnProjectAdditionalDetailType;
use App\Repository\Transaction\TrnProjectAdditionalDetailRepository;
use App\Service\CommonHelper;
use App\Service\FileUploaderHelper;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/core/product/additional-detail", name="product_additional_detail_")
 * @IsGranted("ROLE_VENDOR_USER")
 */
class TrnPropertyAdditionalDetailController extends AbstractController
{
    /**
     * @Route("/", name="index", methods={"GET"})
     * @param TrnProjectAdditionalDetailRepository $trnProjectAdditionalDetailRepository
     * @param Request $request
     * @return Response
     */
    public function index(TrnProjectAdditionalDetailRepository $trnProjectAdditionalDetailRepository, Request $request): Response
    {
        $projectId = $request->query->get('projectId');
        if(!$projectId) {
            return $this->redirectToRoute('product_properties_index');
        }
        $trnProject = $this->getDoctrine()->getRepository(TrnProject::class)->find($projectId);
        return $this->render('transaction/property/additional_detail/index.html.twig', [
            'trnProjectAdditionalDetails' => $trnProjectAdditionalDetailRepository->findBy(['trnProject' => $projectId,'createdBy'=>$this->getUser()]),
            'trnProject' => $trnProject,
            'path_index' => 'product_additional_detail_index',
            'path_add' => 'product_additional_detail_add_from_another',
            'path_show' => 'product_additional_detail_show',
            'path_edit' => 'product_additional_detail_edit',
            'label_title' => 'label.project_additional_detail',
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
        $trnProjectAdditionalDetail = new TrnProjectAdditionalDetail();
        if($trnProjectRoomConfigurations){
            foreach($trnProjectRoomConfigurations as $trnProjectRoomConfigurationId){
                $trnProjectRoomConfiguration = $this->getDoctrine()->getRepository(TrnProjectRoomConfiguration::class)->find($trnProjectRoomConfigurationId);
                $trnProjectAdditionalDetail->addTrnProjectRoomConfiguration($trnProjectRoomConfiguration);
            }
        }
        $trnProject = $this->getDoctrine()->getRepository(TrnProject::class)->find($projectId);
        $form = $this->createForm(TrnProjectAdditionalDetailType::class, $trnProjectAdditionalDetail,['projectId'=>$projectId]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $trnProjectAdditionalDetail->setTrnProject($trnProject);
            $trnProjectAdditionalDetail->setCreatedBy($this->getUser());
            $entityManager->persist($trnProjectAdditionalDetail);
            $entityManager->flush();
            $this->addFlash('success', 'form.created_successfully');
            return $this->redirectToRoute('product_additional_detail_index', $request->query->all());
        }

        return $this->render('transaction/property/additional_detail/form.html.twig', [
            'trnProjectAdditionalDetail' => $trnProjectAdditionalDetail,
            'trnProject' => $trnProject,
            'form' => $form->createView(),
            'index_path' => 'product_additional_detail_index',
            'label_title' => 'label.project_additional_detail',
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
        $trnProject = $this->getDoctrine()->getRepository(TrnProject::class)->find($projectId);
        $trnProjectAdditionalDetails = $this->getDoctrine()->getRepository(TrnProjectAdditionalDetail::class)->findBy(['isActive'=>1,'createdBy'=>$this->getUser()]);
        $trnProjectRoomConfigurations = $this->getDoctrine()->getRepository(TrnProjectRoomConfiguration::class)->findBy(['isActive'=>1,'trnProject'=>$trnProject,'createdBy'=>$this->getUser()]);

        return $this->render('transaction/property/additional_detail/another.html.twig', [
            'trnProjectAdditionalDetails' => $trnProjectAdditionalDetails,
            'trnProjectRoomConfigurations' => $trnProjectRoomConfigurations,
            'trnProject' => $trnProject,
            'index_path' => 'product_additional_detail_index',
            'path_add' => 'product_additional_detail_add',
            'label_title' => 'label.project_additional_detail',
            'label_button' => 'label.create',
            'label_heading' => 'label.project_name',
            'mode' => 'add-from-another'
        ]);
    }

    /**
     * @Route("/{id}/edit/{appUser}", name="edit", methods={"GET","POST"})
     * @param Request $request
     * @param TrnProjectAdditionalDetail $trnProjectAdditionalDetail
     * @param AppUser $appUser
     * @param CommonHelper $commonHelper
     * @param FileUploaderHelper $fileUploaderHelper
     * @return Response
     * @throws \Exception
     */
    public function edit(Request $request, TrnProjectAdditionalDetail $trnProjectAdditionalDetail, AppUser $appUser, CommonHelper  $commonHelper, FileUploaderHelper $fileUploaderHelper): Response
    {
        $projectId = $request->query->get('projectId');
        if(!$projectId) {
            return $this->redirectToRoute('product_properties_index');
        }
        $trnProject = $this->getDoctrine()->getRepository(TrnProject::class)->find($projectId);
        $form = $this->createForm(TrnProjectAdditionalDetailType::class, $trnProjectAdditionalDetail,['projectId'=>$projectId]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', 'form.updated_successfully');
            return $this->redirectToRoute('product_additional_detail_index', $request->query->all());
        }

        return $this->render('transaction/property/additional_detail/form.html.twig', [
            'trnProjectAdditionalDetail' => $trnProjectAdditionalDetail,
            'trnProject' => $trnProject,
            'form' => $form->createView(),
            'index_path' => 'product_additional_detail_index',
            'label_title' => 'label.project_additional_detail',
            'label_button' => 'label.update',
            'label_heading' => 'label.project_name',
            'mode' => 'edit'
        ]);
    }

    /**
     * @Route("/{id}", name="show", methods={"GET","POST"})
     * @param TrnProjectAdditionalDetail $trnProjectAdditionalDetail
     * @return Response
     */
    public function show(TrnProjectAdditionalDetail $trnProjectAdditionalDetail): Response
    {
        return $this->render('transaction/property/room_configuration/show.html.twig', [
            'data' => $trnProjectAdditionalDetail,
            'label_title' => 'label.room_configuration',
            'label_button' => 'label.update',
            'path_index' => 'product_additional_detail_index',
            'path_edit' => 'product_additional_detail_edit'
        ]);
    }
}
