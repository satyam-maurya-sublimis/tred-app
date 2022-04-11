<?php

namespace App\Controller\Master;

use App\Entity\Master\MstCategory;
use App\Service\CommonHelper;
use App\Service\FileUploaderHelper;
use Exception;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use App\Entity\Master\MstProjectAmenities;
use App\Form\Master\MstProjectAmenitiesType;
use App\Repository\Master\MstProjectAmenitiesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Ramsey\Uuid\Uuid;

/**
 * @Route("/core/master/tred_project/project_amenities", name="master_project_amenities_")
 * @IsGranted("ROLE_SYS_MODULE_ADMIN")
 */
class MstProjectAmenitiesController extends AbstractController
{
    /**
     * @Route("/", name="index", methods={"GET"})
     * @param MstProjectAmenitiesRepository $mstProjectAmenitiesRepository
     * @param Request $request
     * @return Response
     */
    public function index(MstProjectAmenitiesRepository $mstProjectAmenitiesRepository, Request $request): Response
    {
        $project_amenities = $mstProjectAmenitiesRepository->findAll();
        return $this->render('master/mst_project_amenities/index.html.twig', [
            'mst_project_amenities' => $project_amenities,
            'path_index' => 'master_project_amenities_index',
            'path_add' => 'master_project_amenities_add',
            'path_edit' => 'master_project_amenities_edit',
            'path_show' => 'master_project_amenities_show',
            'label_title' => 'label.project_amenities',
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
        $mstProjectAmenities = new MstProjectAmenities();
        $mstCategory = $this->getDoctrine()->getRepository(MstCategory::class)->find(1);
        $mstProjectAmenities->setMstCategory($mstCategory);
        $form = $this->createForm(MstProjectAmenitiesType::class, $mstProjectAmenities);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $iconContentFile = $form['mediaIcon']['iconImage']->getData();
            if (!empty($iconContentFile)) {
                $strSaveFileName = $commonHelper->clean(strtolower($form['projectAmenities']->getData())).'_icon_'.Uuid::uuid4()->toString();
                $newFilename = $fileUploaderHelper->uploadPublicFile($iconContentFile, $strSaveFileName,null);
                $mstProjectAmenities->getMediaIcon()->setIconImage($newFilename);
            }

            $imageContentFile = $form['desktopImage']->getData();
            if (!empty($imageContentFile)) {
                $strSaveFileName = $commonHelper->clean(strtolower($form['projectAmenities']->getData())).'desktop'
                    .Uuid::uuid4()->toString();
                $newFilename = $fileUploaderHelper->uploadPublicFile($imageContentFile, $strSaveFileName,null);
                $mstProjectAmenities->setDesktopImage($newFilename);
            }
            $imageContentFile = $form['tabletImage']->getData();
            if (!empty($imageContentFile)) {
                $strSaveFileName = $commonHelper->clean(strtolower($form['projectAmenities']->getData())).'tablet'
                    .Uuid::uuid4()->toString();
                $newFilename = $fileUploaderHelper->uploadPublicFile($imageContentFile, $strSaveFileName,null);
                $mstProjectAmenities->setTabletImage($newFilename);
            }
            $imageContentFile = $form['mobileImage']->getData();
            if (!empty($imageContentFile)) {
                $strSaveFileName = $commonHelper->clean(strtolower($form['projectAmenities']->getData())).'mobile'
                    .Uuid::uuid4()->toString();
                $newFilename = $fileUploaderHelper->uploadPublicFile($imageContentFile, $strSaveFileName,null);
                $mstProjectAmenities->setMobileImage($newFilename);
            }
            $mstProjectAmenities->setRowId(Uuid::uuid4()->toString());
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($mstProjectAmenities);
            $entityManager->flush();
            $this->addFlash('success', 'form.created_successfully');
            return $this->redirectToRoute('master_project_amenities_index');
        }

        return $this->render('master/mst_project_amenities/form.html.twig', [
            'mstProjectAmenities' => $mstProjectAmenities,
            'form' => $form->createView(),
            'index_path' => 'master_project_amenities_index',
            'label_title' => 'label.project_amenities',
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
        $project_amenities = ucwords($request->query->get('project_amenitiesSearch'));

        $mstProjectAmenities = $this->getDoctrine()->getRepository(MstProjectAmenities::class)->getCityListByCountryId($project_amenities, $countryId);
        return $this->render('master/mst_project_amenities/_ajax_listing.html.twig', [
            'mst_cities' => $mstProjectAmenities,
            'country_id' => $countryId,
            'path_add' => 'master_project_amenities_add',
            'path_edit' => 'master_project_amenities_edit',
            'path_show' => 'master_project_amenities_show',
            'label_title' => 'label.project_amenities',
            'mode' => 'add'
        ]);
    }

    /**
     * @Route("/edit/{id}", name="edit", methods={"GET","POST"})
     * @param Request $request
     * @param MstProjectAmenities $mstProjectAmenities
     * @param FileUploaderHelper $fileUploaderHelper
     * @param CommonHelper $commonHelper
     * @return Response
     * @throws Exception
     */
    public function edit(Request $request, MstProjectAmenities $mstProjectAmenities, FileUploaderHelper $fileUploaderHelper, CommonHelper $commonHelper): Response
    {

        $form = $this->createForm(MstProjectAmenitiesType::class, $mstProjectAmenities);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $iconContentFile = $form['mediaIcon']['iconImage']->getData();
            if (!empty($iconContentFile)) {
                $strSaveFileName = $commonHelper->clean(strtolower($form['projectAmenities']->getData())).'_icon_'.Uuid::uuid4()->toString();
                $newFilename = $fileUploaderHelper->uploadPublicFile($iconContentFile, $strSaveFileName,null);
                $mstProjectAmenities->getMediaIcon()->setIconImage($newFilename);
            }

            $imageContentFile = $form['desktopImage']->getData();
            if (!empty($imageContentFile)) {
                $strSaveFileName = $commonHelper->clean(strtolower($form['projectAmenities']->getData())).'desktop'
                    .Uuid::uuid4()->toString();
                $newFilename = $fileUploaderHelper->uploadPublicFile($imageContentFile, $strSaveFileName,null);
                $mstProjectAmenities->setDesktopImage($newFilename);
            }
            $imageContentFile = $form['tabletImage']->getData();
            if (!empty($imageContentFile)) {
                $strSaveFileName = $commonHelper->clean(strtolower($form['projectAmenities']->getData())).'tablet'
                    .Uuid::uuid4()->toString();
                $newFilename = $fileUploaderHelper->uploadPublicFile($imageContentFile, $strSaveFileName,null);
                $mstProjectAmenities->setTabletImage($newFilename);
            }
            $imageContentFile = $form['mobileImage']->getData();
            if (!empty($imageContentFile)) {
                $strSaveFileName = $commonHelper->clean(strtolower($form['projectAmenities']->getData())).'mobile'
                    .Uuid::uuid4()->toString();
                $newFilename = $fileUploaderHelper->uploadPublicFile($imageContentFile, $strSaveFileName,null);
                $mstProjectAmenities->setMobileImage($newFilename);
            }
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($mstProjectAmenities);
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', 'form.updated_successfully');
            return $this->redirectToRoute('master_project_amenities_index');
        }

        return $this->render('master/mst_project_amenities/form.html.twig', [
            'mstProjectAmenities' => $mstProjectAmenities,
            'form' => $form->createView(),
            'index_path' => 'master_project_amenities_index',
            'label_title' => 'label.project_amenities',
            'label_button' => 'label.update',
            'mode' => 'edit'
        ]);
    }

    /**
     * @Route("/{id}", name="delete", methods={"DELETE"})
     * @param Request $request
     * @param MstProjectAmenities $mstProjectAmenities
     * @return Response
     */
    public function delete(Request $request, MstProjectAmenities $mstProjectAmenities): Response
    {
        if ($this->isCsrfTokenValid('delete'.$mstProjectAmenities->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($mstProjectAmenities);
            $entityManager->flush();
        }

        return $this->redirectToRoute('master_project_amenities_index');
    }
}
