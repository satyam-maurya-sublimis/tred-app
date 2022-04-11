<?php

namespace App\Controller\Master;

use App\Service\CommonHelper;
use App\Service\FileUploaderHelper;
use Exception;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use App\Entity\Master\MstProductFeature;
use App\Form\Master\MstProductFeatureType;
use App\Repository\Master\MstProductFeatureRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Ramsey\Uuid\Uuid;

/**
 * @Route("/core/master/tred_project/product_feature", name="master_product_feature_")
 * @IsGranted("ROLE_SYS_CONTENT_USER")
 */
class MstProductFeatureController extends AbstractController
{
    /**
     * @Route("/", name="index", methods={"GET"})
     * @param MstProductFeatureRepository $mstProductFeatureRepository
     * @param Request $request
     * @return Response
     */
    public function index(MstProductFeatureRepository $mstProductFeatureRepository, Request $request): Response
    {
        $mstProductFeature = $mstProductFeatureRepository->findAll();
        return $this->render('master/mst_product_feature/index.html.twig', [
            'mst_product_features' => $mstProductFeature,
            'path_index' => 'master_product_feature_index',
            'path_add' => 'master_product_feature_add',
            'path_edit' => 'master_product_feature_edit',
            'path_show' => 'master_product_feature_show',
            'label_title' => 'label.product_feature',
        ]);
    }

    /**
     * @Route("/add", name="add", methods={"GET","POST"})
     * @param Request $request
     * @param FileUploaderHelper $fileUploaderHelper
     * @param CommonHelper $commonHelper
     * @return Response
     */
    public function new(Request $request, FileUploaderHelper $fileUploaderHelper, CommonHelper $commonHelper): Response
    {
        $mstProductFeature = new MstProductFeature();
        $form = $this->createForm(MstProductFeatureType::class, $mstProductFeature);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $imageContentFile = $form['desktopImage']->getData();
            if (!empty($imageContentFile)) {
                $strSaveFileName = $commonHelper->clean(strtolower($form['productFeature']->getData())).'desktop'
                    .Uuid::uuid4()->toString();
                $newFilename = $fileUploaderHelper->uploadPublicFile($imageContentFile, $strSaveFileName,null);
                $mstProductFeature->setDesktopImage($newFilename);
            }
            $imageContentFile = $form['tabletImage']->getData();
            if (!empty($imageContentFile)) {
                $strSaveFileName = $commonHelper->clean(strtolower($form['productFeature']->getData())).'tablet'
                    .Uuid::uuid4()->toString();
                $newFilename = $fileUploaderHelper->uploadPublicFile($imageContentFile, $strSaveFileName,null);
                $mstProductFeature->setTabletImage($newFilename);
            }
            $imageContentFile = $form['mobileImage']->getData();
            if (!empty($imageContentFile)) {
                $strSaveFileName = $commonHelper->clean(strtolower($form['productFeature']->getData())).'mobile'
                    .Uuid::uuid4()->toString();
                $newFilename = $fileUploaderHelper->uploadPublicFile($imageContentFile, $strSaveFileName,null);
                $mstProductFeature->setMobileImage($newFilename);
            }
            $mstProductFeature->setRowId(Uuid::uuid4()->toString());
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($mstProductFeature);
            $entityManager->flush();
            $this->addFlash('success', 'form.created_successfully');
            return $this->redirectToRoute('master_product_feature_index');
        }

        return $this->render('master/mst_product_feature/form.html.twig', [
            'mstProductFeature' => $mstProductFeature,
            'form' => $form->createView(),
            'index_path' => 'master_product_feature_index',
            'label_title' => 'label.product_feature',
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
        $mstProductFeature = ucwords($request->query->get('product_featureSearch'));

        $mstProductFeature = $this->getDoctrine()->getRepository(MstProductFeature::class)->getCityListByCountryId($mstProductFeature, $countryId);
        return $this->render('master/mst_product_feature/_ajax_listing.html.twig', [
            'mst_cities' => $mstProductFeature,
            'country_id' => $countryId,
            'path_add' => 'master_product_feature_add',
            'path_edit' => 'master_product_feature_edit',
            'path_show' => 'master_product_feature_show',
            'label_title' => 'label.product_feature',
            'mode' => 'add'
        ]);
    }

    /**
     * @Route("/edit/{id}", name="edit", methods={"GET","POST"})
     * @param Request $request
     * @param MstProductFeature $mstProductFeature
     * @param FileUploaderHelper $fileUploaderHelper
     * @param CommonHelper $commonHelper
     * @return Response
     */
    public function edit(Request $request, MstProductFeature $mstProductFeature, FileUploaderHelper $fileUploaderHelper, CommonHelper $commonHelper): Response
    {
        $form = $this->createForm(MstProductFeatureType::class, $mstProductFeature);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $imageContentFile = $form['desktopImage']->getData();
            if (!empty($imageContentFile)) {
                $strSaveFileName = $commonHelper->clean(strtolower($form['productFeature']->getData())).'desktop'
                    .Uuid::uuid4()->toString();
                $newFilename = $fileUploaderHelper->uploadPublicFile($imageContentFile, $strSaveFileName,null);
                $mstProductFeature->setDesktopImage($newFilename);
            }
            $imageContentFile = $form['tabletImage']->getData();
            if (!empty($imageContentFile)) {
                $strSaveFileName = $commonHelper->clean(strtolower($form['productFeature']->getData())).'tablet'
                    .Uuid::uuid4()->toString();
                $newFilename = $fileUploaderHelper->uploadPublicFile($imageContentFile, $strSaveFileName,null);
                $mstProductFeature->setTabletImage($newFilename);
            }
            $imageContentFile = $form['mobileImage']->getData();
            if (!empty($imageContentFile)) {
                $strSaveFileName = $commonHelper->clean(strtolower($form['productFeature']->getData())).'mobile'
                    .Uuid::uuid4()->toString();
                $newFilename = $fileUploaderHelper->uploadPublicFile($imageContentFile, $strSaveFileName,null);
                $mstProductFeature->setMobileImage($newFilename);
            }
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($mstProductFeature);
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', 'form.updated_successfully');
            return $this->redirectToRoute('master_product_feature_index');
        }

        return $this->render('master/mst_product_feature/form.html.twig', [
            'mstProductFeature' => $mstProductFeature,
            'form' => $form->createView(),
            'index_path' => 'master_product_feature_index',
            'label_title' => 'label.product_feature',
            'label_button' => 'label.update',
            'mode' => 'edit'
        ]);
    }

    /**
     * @Route("/{id}", name="delete", methods={"DELETE"})
     * @param Request $request
     * @param MstProductFeature $mstProductFeature
     * @return Response
     */
    public function delete(Request $request, MstProductFeature $mstProductFeature): Response
    {
        if ($this->isCsrfTokenValid('delete'.$mstProductFeature->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($mstProductFeature);
            $entityManager->flush();
        }

        return $this->redirectToRoute('master_product_feature_index');
    }
}
