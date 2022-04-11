<?php

namespace App\Controller\Transaction;


use App\Entity\Master\MstProductCategory;
use App\Entity\Transaction\TrnFurnitureProductCatalog;
use App\Form\Transaction\TrnFurnitureProductCatalogType;
use App\Repository\Transaction\TrnFurnitureProductCatalogRepository;
use App\Service\CommonHelper;
use App\Service\FileUploaderHelper;
use Doctrine\Common\Collections\ArrayCollection;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;

/**
     * @Route("/core/product/furniture-product-catalog", name="product_furniture_product_catalog_")
 * @IsGranted("ROLE_VENDOR_USER")
 */
class TrnFurnitureProductCatalogController extends AbstractController
{
    /**
     * @Route("/", name="index", methods={"GET"})
     * @param TrnFurnitureProductCatalogRepository $trnFurnitureProductCatalogRepository
     * @param PaginatorInterface $paginator
     * @param Request $request
     * @return Response
     */
    public function index(TrnFurnitureProductCatalogRepository $trnFurnitureProductCatalogRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $queryBuilder = $trnFurnitureProductCatalogRepository->findAll();
        return $this->render('transaction/trn_furniture_product_catalog/index.html.twig', [
            'furnitures' => $queryBuilder,
            'path_index' => 'product_furniture_product_catalog_index',
            'path_add' => 'product_furniture_product_catalog_add',
            'path_edit' => 'product_furniture_product_catalog_edit',
            'path_show' => 'product_furniture_product_catalog_show',
            'path_upload' => 'product_furniture_product_catalog_upload',
            'label_title' => 'label.furniture_product_catalog',
            'path_media' => 'product_furniture_product_catalog_media_index',
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
    public function new(Request $request,FileUploaderHelper $fileUploaderHelper, CommonHelper $commonHelper): Response
    {
        $trnFurnitureProductCatalog = new TrnFurnitureProductCatalog();
        $mstProductCategory = $this->getDoctrine()->getRepository(MstProductCategory::class)->findOneBy(["isActive"=>true,"productCategorySlugName"=>"furniture"]);
        $trnFurnitureProductCatalog->setMstProductCategory($mstProductCategory);
        $form = $this->createForm(TrnFurnitureProductCatalogType::class, $trnFurnitureProductCatalog);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $ogImageFile = $form['ogImage']->getData();
            if ($ogImageFile) {
                $newFilename = $fileUploaderHelper->uploadPublicFile($ogImageFile, $form['ogImage']->getData(),null);
                $trnFurnitureProductCatalog->setOgImage($newFilename);
                $trnFurnitureProductCatalog->setOgImagePath($this->getParameter('public_file_folder'));
            }
            $entityManager = $this->getDoctrine()->getManager();
//            foreach ($form->get('trnFurnitureProductCatalogDimensionMedia') as $content) {
//                $trnFurnitureProductCatalogDimensionMedia = $content->getData();
//                $mediaType = $trnFurnitureProductCatalogDimensionMedia->getMediaType();
//                $mediaFile = $content['mediaFileName']->getData();
//                $trnFurnitureProductCatalogDimensionMedia->setIsActive(1);
//                $trnFurnitureProductCatalogDimensionMedia->setCreatedOn(new \DateTime());
//                if ($mediaType == "image"){
//                    if ($trnFurnitureProductCatalogDimensionMedia) {
//                        $newFilename = $fileUploaderHelper->uploadPublicFile($mediaFile, $commonHelper->slugify($content['mediaName']->getData()), null);
//                        $trnFurnitureProductCatalogDimensionMedia->setMediaName($content['mediaName']->getData());
//                        $trnFurnitureProductCatalogDimensionMedia->setMediaFileName($newFilename);
//                        $trnFurnitureProductCatalogDimensionMedia->setMediaAlText($content['mediaAlText']->getData());
//                        $trnFurnitureProductCatalogDimensionMedia->setMediaTitle($content['mediaTitle']->getData());
//                        $trnFurnitureProductCatalogDimensionMedia->setMediaFilePath($this->getParameter('public_file_folder'));
//                    }
//                }elseif ($mediaType == "video"){
//                    $video = $content['mediaName']->getData();
//                    if ($video) {
//                        $trnFurnitureProductCatalogDimensionMedia->setMediaFileName('');
//                        $trnFurnitureProductCatalogDimensionMedia->setMediaAlText('');
//                        $trnFurnitureProductCatalogDimensionMedia->setMediaTitle('');
//                        $trnFurnitureProductCatalogDimensionMedia->setMediaFilePath('');
//                        $trnFurnitureProductCatalogDimensionMedia->setMediaName($content['mediaName']->getData());
//                        $trnFurnitureProductCatalogDimensionMedia->setMediaPath($content['mediaPath']->getData());
//                    }
//                }
//                $entityManager->persist($trnFurnitureProductCatalogDimensionMedia);
//            }
            $trnFurnitureProductCatalog->setCreatedOn(new \DateTime());
            $entityManager->persist($trnFurnitureProductCatalog);
            $entityManager->flush();
            return $this->redirectToRoute('product_furniture_product_catalog_index');
        }
        return $this->render('transaction/trn_furniture_product_catalog/form.html.twig', [
            'trnFurnitureProductCatalog' => $trnFurnitureProductCatalog,
            'form' => $form->createView(),
            'label_title' => 'label.furniture_product_catalog',
            'label_button' => 'label.create',
            'mode' => 'add'
        ]);
    }

    /**
     * @Route("/edit/{id}", name="edit", methods={"GET","POST"})
     * @param Request $request
     * @param FileUploaderHelper $fileUploaderHelper
     * @param CommonHelper $commonHelper
     * @return Response
     * @throws Exception
     */
    public function edit(Request $request,TrnFurnitureProductCatalog $trnFurnitureProductCatalog, FileUploaderHelper $fileUploaderHelper, CommonHelper $commonHelper): Response
    {
//        $originalUploadFiles = new ArrayCollection();
//        foreach($trnFurnitureProductCatalog->getTrnFurnitureProductCatalogDimensionMedia() as $trnFurnitureProductCatalogDimensionMedia){
//            $originalUploadFiles->add($trnFurnitureProductCatalogDimensionMedia);
//        }
        $existingOgImageFile = $trnFurnitureProductCatalog->getOgImage();
        $form = $this->createForm(TrnFurnitureProductCatalogType::class, $trnFurnitureProductCatalog);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $ogImageFile = $form['ogImage']->getData();
            if ($ogImageFile) {
                if ($existingOgImageFile != ''){
                    $newFilename = $fileUploaderHelper->uploadPublicFile($ogImageFile, $form['ogImage']->getData(),$existingOgImageFile);
                } else {
                    $newFilename = $fileUploaderHelper->uploadPublicFile($ogImageFile, $form['ogImage']->getData(),null);
                }

                $trnFurnitureProductCatalog->setOgImage($newFilename);
                $trnFurnitureProductCatalog->setOgImagePath($this->getParameter('public_file_folder'));
            }
//            foreach($originalUploadFiles as $originalUploadFile){
//                if($trnFurnitureProductCatalog->getTrnFurnitureProductCatalogDimensionMedia()->contains($originalUploadFile)==false){
//                    $this->getDoctrine()->getManager()->remove($originalUploadFile);
//                }
//            }
//            foreach ($form->get('trnFurnitureProductCatalogDimensionMedia') as $content) {
//                $trnFurnitureProductCatalogDimensionMedia = $content->getData();
//                $mediaType = $trnFurnitureProductCatalogDimensionMedia->getMediaType();
//                $mediaFile = $content['mediaFileName']->getData();
//                $trnFurnitureProductCatalogDimensionMedia->setIsActive(1);
//                $trnFurnitureProductCatalogDimensionMedia->setCreatedOn(new \DateTime());
//                if ($mediaType == "image"){
//                    if ($trnFurnitureProductCatalogDimensionMedia) {
//                        $newFilename = $fileUploaderHelper->uploadPublicFile($mediaFile, $commonHelper->slugify($content['mediaName']->getData()), null);
//                        $trnFurnitureProductCatalogDimensionMedia->setMediaName($content['mediaName']->getData());
//                        $trnFurnitureProductCatalogDimensionMedia->setMediaFileName($newFilename);
//                        $trnFurnitureProductCatalogDimensionMedia->setMediaAlText($content['mediaAlText']->getData());
//                        $trnFurnitureProductCatalogDimensionMedia->setMediaTitle($content['mediaTitle']->getData());
//                        $trnFurnitureProductCatalogDimensionMedia->setMediaFilePath($this->getParameter('public_file_folder'));
//                    }
//                }elseif ($mediaType == "video"){
//                    $video = $content['mediaName']->getData();
//                    if ($video) {
//                        $trnFurnitureProductCatalogDimensionMedia->setMediaFileName('');
//                        $trnFurnitureProductCatalogDimensionMedia->setMediaAlText('');
//                        $trnFurnitureProductCatalogDimensionMedia->setMediaTitle('');
//                        $trnFurnitureProductCatalogDimensionMedia->setMediaFilePath('');
//                        $trnFurnitureProductCatalogDimensionMedia->setMediaName($content['mediaName']->getData());
//                        $trnFurnitureProductCatalogDimensionMedia->setMediaPath($content['mediaPath']->getData());
//                    }
//                }
//                $entityManager->persist($trnFurnitureProductCatalogDimensionMedia);
//            }
            $entityManager->persist($trnFurnitureProductCatalog);
            $entityManager->flush();
            return $this->redirectToRoute('product_furniture_product_catalog_index');
        }
        return $this->render('transaction/trn_furniture_product_catalog/form.html.twig', [
            'trnFurnitureProductCatalog' => $trnFurnitureProductCatalog,
            'form' => $form->createView(),
            'label_title' => 'label.furniture_product_catalog',
            'label_button' => 'label.update',
            'mode' => 'edit'
        ]);
    }
}
