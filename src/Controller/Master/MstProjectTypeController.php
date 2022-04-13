<?php

namespace App\Controller\Master;

use App\Service\CommonHelper;
use App\Service\FileUploaderHelper;
use Doctrine\Persistence\ManagerRegistry;
use Exception;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use App\Entity\Master\MstProjectType;
use App\Form\Master\MstProjectTypeType;
use App\Repository\Master\MstProjectTypeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Ramsey\Uuid\Uuid;

/**
 * @Route("/core/master/tred_project/project_type", name="master_project_type_")
 * @IsGranted("ROLE_SYS_MODULE_ADMIN")
 */
class MstProjectTypeController extends AbstractController
{
    private ManagerRegistry $managerRegistry;

    public function __construct(ManagerRegistry $managerRegistry)
    {
        $this->managerRegistry = $managerRegistry;
    }
    /**
     * @Route("/", name="index", methods={"GET"})
     * @param MstProjectTypeRepository $mstProjectTypeRepository
     * @param Request $request
     * @return Response
     */
    public function index(MstProjectTypeRepository $mstProjectTypeRepository, Request $request): Response
    {
        $project_types = $mstProjectTypeRepository->findAll();
        return $this->render('master/mst_project_type/index.html.twig', [
            'mst_project_types' => $project_types,
            'path_index' => 'master_project_type_index',
            'path_add' => 'master_project_type_add',
            'path_edit' => 'master_project_type_edit',
            'path_show' => 'master_project_type_show',
            'label_title' => 'label.property_type_new',
        ]);
    }

    /**
     * @Route("/add", name="add", methods={"GET","POST"})
     * @param Request $request
     * @return Response
     * @throws Exception
     */
    public function new(Request $request, CommonHelper $helper, FileUploaderHelper $fileUploaderHelper): Response
    {
        $mstProjectType = new MstProjectType();
        $form = $this->createForm(MstProjectTypeType::class, $mstProjectType);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $mstProjectType->setRowId(Uuid::uuid4()->toString());
            $mediaType = $form['projectTypeMediaType']->getData();
            if ($mediaType == 'image'){
                $projectTypeFile = $form['projectTypeImage']->getData();
                if ($projectTypeFile)
                {
                    $newFilename = $fileUploaderHelper->uploadPublicFile($projectTypeFile, $helper->slugify($form['projectTypeImageName']->getData()), $existingBannerImage = null);
                    $mstProjectType->setProjectTypeImage($newFilename);
                    $mstProjectType->setProjectTypeImagePath($this->getParameter('public_file_folder'));
                }
            }elseif ($mediaType == 'video') {
                $mstProjectType->setProjectTypeVideo($form['projectTypeVideo']->getData());
                $mstProjectType->setProjectTypeVideoPath($form['projectTypeVideoPath']->getData());
            }
            $entityManager = $this->managerRegistry->getManager();
            $entityManager->persist($mstProjectType);
            $entityManager->flush();
            $this->addFlash('success', 'form.created_successfully');
            return $this->redirectToRoute('master_project_type_index');
        }

        return $this->render('master/mst_project_type/form.html.twig', [
            'master_project_type' => $mstProjectType,
            'form' => $form->createView(),
            'index_path' => 'master_project_type_index',
            'label_title' => 'label.property_type_new',
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
        $project_type = ucwords($request->query->get('project_typeSearch'));

        $mstProjectType = $this->managerRegistry->getRepository(MstProjectType::class)->getCityListByCountryId($project_type, $countryId);
        return $this->render('master/mst_project_type/_ajax_listing.html.twig', [
            'mst_cities' => $mstProjectType,
            'country_id' => $countryId,
            'path_add' => 'master_project_type_add',
            'path_edit' => 'master_project_type_edit',
            'path_show' => 'master_project_type_show',
            'label_title' => 'label.property_type_new',
            'mode' => 'add'
        ]);
    }

    /**
     * @Route("/edit/{id}", name="edit", methods={"GET","POST"})
     * @param Request $request
     * @param MstProjectType $mstProjectType
     * @return Response
     */
    public function edit(Request $request, MstProjectType $mstProjectType, CommonHelper $helper, FileUploaderHelper $fileUploaderHelper): Response
    {
        $existingImage = $mstProjectType->getProjectTypeImage();
        $form = $this->createForm(MstProjectTypeType::class, $mstProjectType);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $mediaType = $form['projectTypeMediaType']->getData();
            if ($mediaType == 'image'){
                $projectTypeFile = $form['projectTypeImage']->getData();
                if ($projectTypeFile)
                {
                    if($existingImage != '')
                    {
                        $newFilename = $fileUploaderHelper->uploadPublicFile($projectTypeFile, $helper->slugify($form['projectTypeImageName']->getData()), $existingImage);
                    } else {
                        $newFilename = $fileUploaderHelper->uploadPublicFile($projectTypeFile, $helper->slugify($form['projectTypeImageName']->getData()), null);
                    }
                    $mstProjectType->setProjectTypeImage($newFilename);
                    $mstProjectType->setProjectTypeImagePath($this->getParameter('public_file_folder'));
                }
            }elseif ($mediaType == 'video') {
                if($existingImage != '') {
                    $fileUploaderHelper->removeFile($existingImage);
                    $mstProjectType->setProjectTypeImage('');
                    $mstProjectType->setProjectTypeImageName('');
                    $mstProjectType->setProjectTypeImagePath('');
                }
                $mstProjectType->setProjectTypeVideo($form['projectTypeVideo']->getData());
                $mstProjectType->setProductTypeVideoPath($form['projectTypeVideoPath']->getData());
            }
            $this->managerRegistry->getManager()->flush();
            $this->addFlash('success', 'form.updated_successfully');
            return $this->redirectToRoute('master_project_type_index');
        }

        return $this->render('master/mst_project_type/form.html.twig', [
            'master_project_type' => $mstProjectType,
            'form' => $form->createView(),
            'index_path' => 'master_project_type_index',
            'label_title' => 'label.property_type_new',
            'label_button' => 'label.update',
            'mode' => 'edit'
        ]);
    }

    /**
     * @Route("/{id}", name="delete", methods={"DELETE"})
     * @param Request $request
     * @param MstProjectType $mstProjectType
     * @return Response
     */
    public function delete(Request $request, MstProjectType $mstProjectType): Response
    {
        if ($this->isCsrfTokenValid('delete'.$mstProjectType->getId(), $request->request->get('_token'))) {
            $entityManager = $this->managerRegistry->getManager();
            $entityManager->remove($mstProjectType);
            $entityManager->flush();
        }

        return $this->redirectToRoute('master_project_type_index');
    }
}
