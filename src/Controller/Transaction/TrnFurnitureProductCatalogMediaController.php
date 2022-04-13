<?php

namespace App\Controller\Transaction;

use App\Entity\Transaction\TrnFurnitureProductCatalog;
use App\Entity\Media\MdaFurniture;
use App\Form\Media\MdaFurnitureCatalogType;
use App\Form\Media\MdaFurnitureType;
use App\Repository\Media\MdaFurnitureRepository;
use App\Service\CommonHelper;
use App\Service\FileUploaderHelper;
use Doctrine\Persistence\ManagerRegistry;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/core/product/furniture-product-catalog-media", name="product_furniture_product_catalog_media_")
 * @IsGranted("ROLE_SYS_CONTENT_USER")
 */
class TrnFurnitureProductCatalogMediaController extends AbstractController
{
    private ManagerRegistry $managerRegistry;

    public function __construct(ManagerRegistry $managerRegistry)
    {
        $this->managerRegistry = $managerRegistry;
    }
    /**
     * @Route("/", name="index", methods={"GET"})
     * @param MdaFurnitureRepository $mdaFurnitureRepository
     * @param Request $request
     * @return Response
     */
    public function index(MdaFurnitureRepository $mdaFurnitureRepository, Request $request): Response
    {
        $furniture_id = $request->query->get('furniture_product_catalog_id');
        if(!$furniture_id) {
            return $this->redirectToRoute('product_furniture_product_catalog_index');
        }
        $trnFurnitureProductCatalog = $this->managerRegistry->getRepository(TrnFurnitureProductCatalog::class)->find($furniture_id);
        return $this->render('transaction/trn_furniture_product_catalog_media/index.html.twig', [
            'medias' => $mdaFurnitureRepository->findBy(['trnFurnitureProductCatalog'=>$furniture_id]),
            'trnFurnitureProductCatalog' => $trnFurnitureProductCatalog,
            'path_index' => 'product_furniture_product_catalog_media_index',
            'path_add' => 'product_furniture_product_catalog_media_add',
            'path_edit' => 'product_furniture_product_catalog_media_edit',
            'path_show' => 'product_furniture_product_catalog_media_show',
            'label_title' => 'label.media',
            'label_heading' => 'label.furniture_product_catalog'
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
        $furniture_id = $request->query->get('furniture_product_catalog_id');
        if(!$furniture_id) {
            return $this->redirectToRoute('product_furniture_product_catalog_index');
        }
        $trnFurnitureProductCatalog = $this->managerRegistry->getRepository(TrnFurnitureProductCatalog::class)->find($furniture_id);
        $position = $this->managerRegistry->getRepository(MdaFurniture::class)->getProductCatalogueMediaBySeqNo($furniture_id);
        $trnFurnitureProductCatalogMedia = new MdaFurniture();
        $trnFurnitureProductCatalogMedia->setPosition($position['cnt']+1);
        $form = $this->createForm(MdaFurnitureCatalogType::class, $trnFurnitureProductCatalogMedia);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->managerRegistry->getManager();
            $imageFile = $form['mediaFileName']->getData();
            $newFilename = $fileUploaderHelper->uploadPublicFile($imageFile, $commonHelper->slugify($form['mediaName']->getData()), $existingMedia = null);
            $trnFurnitureProductCatalogMedia->setTrnFurnitureProductCatalog($trnFurnitureProductCatalog);
            $trnFurnitureProductCatalogMedia->setMediaFileName($newFilename);
            $trnFurnitureProductCatalogMedia->setMediaFilePath($this->getParameter('public_file_folder'));
            $entityManager->persist($trnFurnitureProductCatalogMedia);
            $entityManager->flush();
            $this->addFlash('success', 'form.created_successfully');
            return $this->redirectToRoute('product_furniture_product_catalog_media_index', $request->query->all());
        }

        return $this->render('transaction/trn_furniture_product_catalog_media/form.html.twig', [
            'media' => $trnFurnitureProductCatalogMedia,
            'trnFurnitureProductCatalog' => $trnFurnitureProductCatalog,
            'form' => $form->createView(),
            'index_path' => 'product_furniture_product_catalog_media_index',
            'label_title' => 'label.media',
            'label_button' => 'label.create',
            'label_heading' => 'label.furniture_product_catalog',
            'mode' => 'add'
        ]);
    }

    /**
     * @Route("/{id}/edit", name="edit", methods={"GET","POST"})
     * @param Request $request
     * @param MdaFurniture $trnFurnitureProductCatalogMedia
     * @param CommonHelper $commonHelper
     * @param FileUploaderHelper $fileUploaderHelper
     * @return Response
     * @throws \Exception
     */
    public function edit(Request $request, MdaFurniture $trnFurnitureProductCatalogMedia, CommonHelper  $commonHelper, FileUploaderHelper $fileUploaderHelper): Response
    {
        $furniture_id = $request->query->get('furniture_product_catalog_id');
        if(!$furniture_id) {
            return $this->redirectToRoute('product_furniture_product_catalog_index');
        }
        $existingMedia = $trnFurnitureProductCatalogMedia->getMediaFileName();
        $trnFurnitureProductCatalog = $this->managerRegistry->getRepository(TrnFurnitureProductCatalog::class)->find($furniture_id);
        $form = $this->createForm(MdaFurnitureCatalogType::class, $trnFurnitureProductCatalogMedia);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $imageFile = $form['mediaFileName']->getData();
            if ($imageFile){
                if($existingMedia != ''){
                    $newFilename = $fileUploaderHelper->uploadPublicFile($imageFile, $commonHelper->slugify($form['mediaName']->getData()), $existingMedia);
                } else {
                    $newFilename = $fileUploaderHelper->uploadPublicFile($imageFile, $commonHelper->slugify($form['mediaName']->getData()), $existingMedia = null);
                }
                $trnFurnitureProductCatalogMedia->setMediaFileName($newFilename);
                $trnFurnitureProductCatalogMedia->setMediaFilePath($this->getParameter('public_file_folder'));
            }
            $this->managerRegistry->getManager()->flush();
            $this->addFlash('success', 'form.updated_successfully');
            return $this->redirectToRoute('product_furniture_product_catalog_media_index', $request->query->all());
        }

        return $this->render('transaction/trn_furniture_product_catalog_media/form.html.twig', [
            'media' => $trnFurnitureProductCatalogMedia,
            'trnFurnitureProductCatalog' => $trnFurnitureProductCatalog,
            'form' => $form->createView(),
            'index_path' => 'product_furniture_product_catalog_media_index',
            'label_title' => 'label.media',
            'label_button' => 'label.update',
            'label_heading' => 'label.furniture_product_catalog',
            'mode' => 'edit'
        ]);
    }

    /**
     * @Route("/{id}", name="show", methods={"GET","POST"})
     * @param Request $request
     * @param MdaFurniture $trnFurnitureProductCatalogMedia
     * @return Response
     */
    public function show(Request $request, MdaFurniture $trnFurnitureProductCatalogMedia): Response
    {
        $furniture_id = $request->query->get('furniture_product_catalog_id');
        if (!$furniture_id) {
            return $this->redirectToRoute('product_furniture_product_catalog_index');
        }
        $trnFurnitureProductCatalog = $this->managerRegistry->getRepository(TrnFurnitureProductCatalog::class)->find($furniture_id);
        return $this->render('transaction/trn_furniture_product_catalog_media/show.html.twig', [
            'data' => $trnFurnitureProductCatalogMedia,
            'trnFurnitureProductCatalog'=>$trnFurnitureProductCatalog,
            'label_title' => 'label.media',
            'label_button' => 'label.update',
            'label_log' => 'label.log',
            'path_index' => 'product_furniture_product_catalog_media_index',
            'path_edit' => 'product_furniture_product_catalog_media_edit',
        ]);
    }
}
