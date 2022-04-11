<?php

namespace App\Controller\Transaction;


use App\Entity\Master\MstPincode;
use App\Entity\Master\MstProductCategory;
use App\Entity\Transaction\TrnFurniture;
use App\Entity\Transaction\TrnProject;
use App\Form\Transaction\TrnFurnitureType;
use App\Repository\Transaction\TrnFurnitureRepository;
use App\Service\CommonHelper;
use App\Service\FileUploaderHelper;
use Doctrine\Common\Collections\ArrayCollection;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use App\Repository\Transaction\TrnProjectRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;

/**
 * @Route("/core/product/furniture", name="product_furniture_")
 * @IsGranted("ROLE_VENDOR_USER")
 */
class TrnFurnitureController extends AbstractController
{
    /**
     * @Route("/", name="index", methods={"GET"})
     * @param TrnFurnitureRepository $trnFurnitureRepository
     * @param PaginatorInterface $paginator
     * @param Request $request
     * @return Response
     */
    public function index(TrnFurnitureRepository $trnFurnitureRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $queryBuilder = $trnFurnitureRepository->findAll();
        return $this->render('transaction/furniture/index.html.twig', [
            'furnitures' => $queryBuilder,
            'path_index' => 'product_furniture_index',
            'path_add' => 'product_furniture_add',
            'path_edit' => 'product_furniture_edit',
            'path_show' => 'product_furniture_show',
            'path_upload' => 'product_furniture_upload',
            'label_title' => 'label.furniture_button',
            'path_media' => 'product_furniture_media_index',
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
        $trnFurniture = new TrnFurniture();
        $mstProductCategory = $this->getDoctrine()->getRepository(MstProductCategory::class)->findOneBy(["isActive"=>true,"productCategorySlugName"=>"furniture"]);
        $trnFurniture->setMstProductCategory($mstProductCategory);
        $form = $this->createForm(TrnFurnitureType::class, $trnFurniture);
        $form->handleRequest($request);
        $entityManager = $this->getDoctrine()->getManager();
        if ($form->isSubmitted() && $form->isValid()) {
            $ogImageFile = $form['ogImage']->getData();
            if ($ogImageFile) {
                $newFilename = $fileUploaderHelper->uploadPublicFile($ogImageFile, $form['ogImage']->getData(),null);
                $trnFurniture->setOgImage($newFilename);
                $trnFurniture->setOgImagePath($this->getParameter('public_file_folder'));
            }
            foreach ($form->get('mdaFurniture') as $content) {
                $mdaFurniture = $content->getData();
                $mediaType = $mdaFurniture->getMediaType();
                $mediaFile = $content['mediaFileName']->getData();
                $mdaFurniture->setIsActive(1);
                if ($mediaType == "image"){
                    if ($mdaFurniture) {
                        $newFilename = $fileUploaderHelper->uploadPublicFile($mediaFile, $commonHelper->slugify($content['mediaName']->getData()), null);
                        $mdaFurniture->setMediaName($content['mediaName']->getData());
                        $mdaFurniture->setMediaFileName($newFilename);
                        $mdaFurniture->setMediaAlText($content['mediaAlText']->getData());
                        $mdaFurniture->setMediaTitle($content['mediaTitle']->getData());
                        $mdaFurniture->setMediaFilePath($this->getParameter('public_file_folder'));
                    }
                }elseif ($mediaType == "video"){
                    $video = $content['mediaName']->getData();
                    if ($video) {
                        $mdaFurniture->setMediaFileName('');
                        $mdaFurniture->setMediaAlText('');
                        $mdaFurniture->setMediaTitle('');
                        $mdaFurniture->setMediaFilePath('');
                        $mdaFurniture->setMediaName($content['mediaName']->getData());
                        $mdaFurniture->setMediaPath($content['mediaPath']->getData());
                    }
                }
                $entityManager->persist($mdaFurniture);
            }
            $trnFurniture->setCreatedOn(new \DateTime());
            $entityManager->persist($trnFurniture);
            $entityManager->flush();
            $this->addFlash('success', 'form.created_successfully');
            return $this->redirectToRoute('product_furniture_index');
        }
        return $this->render('transaction/furniture/form.html.twig', [
            'trnFurniture' => $trnFurniture,
            'form' => $form->createView(),
            'label_title' => 'label.furniture_button',
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
    public function edit(Request $request,TrnFurniture $trnFurniture, FileUploaderHelper $fileUploaderHelper, CommonHelper $commonHelper): Response
    {
        $originalUploadFiles = new ArrayCollection();
        foreach($trnFurniture->getMdaFurniture() as $mdaFurniture){
            $originalUploadFiles->add($mdaFurniture);
        }
        $existingOgImageFile = $trnFurniture->getOgImage();
        $form = $this->createForm(TrnFurnitureType::class, $trnFurniture);
        $form->handleRequest($request);
        $entityManager = $this->getDoctrine()->getManager();
        if ($form->isSubmitted() && $form->isValid()) {
            $ogImageFile = $form['ogImage']->getData();
            if ($ogImageFile) {
                if ($existingOgImageFile != ''){
                    $newFilename = $fileUploaderHelper->uploadPublicFile($ogImageFile, $form['ogImage']->getData(),$existingOgImageFile);
                } else {
                    $newFilename = $fileUploaderHelper->uploadPublicFile($ogImageFile, $form['ogImage']->getData(),null);
                }
                $trnFurniture->setOgImage($newFilename);
                $trnFurniture->setOgImagePath($this->getParameter('public_file_folder'));
            }
            foreach($originalUploadFiles as $originalUploadFile){
                if($trnFurniture->getMdaFurniture()->contains($originalUploadFile)==false){
                    $this->getDoctrine()->getManager()->remove($originalUploadFile);
                }
            }
            foreach ($form->get('mdaFurniture') as $key=>$content) {
                $mdaFurniture = $content->getData();
                $mdaFurnitureOld = $trnFurniture->getMdaFurniture()[$key];
                $mediaType = $mdaFurniture->getMediaType();
                $mediaFile = $content['mediaFileName']->getData();
                $mdaFurniture->setIsActive(1);
                if ($mediaFile){
                    if ($mediaType == "image"){
                        if ($mdaFurniture) {
                            $mediaFileOld = $mdaFurnitureOld->getMediaFileName();
                            $newFilename = $fileUploaderHelper->uploadPublicFile($mediaFile, $commonHelper->slugify($content['mediaName']->getData()), $mediaFileOld);
                            $mdaFurniture->setMediaName($content['mediaName']->getData());
                            $mdaFurniture->setMediaFileName($newFilename);
                            $mdaFurniture->setMediaAlText($content['mediaAlText']->getData());
                            $mdaFurniture->setMediaTitle($content['mediaTitle']->getData());
                            $mdaFurniture->setMediaFilePath($this->getParameter('public_file_folder'));
                        }
                    }elseif ($mediaType == "video"){
                        $video = $content['mediaName']->getData();
                        if ($video) {
                            $mdaFurniture->setMediaFileName('');
                            $mdaFurniture->setMediaAlText('');
                            $mdaFurniture->setMediaTitle('');
                            $mdaFurniture->setMediaFilePath('');
                            $mdaFurniture->setMediaName($content['mediaName']->getData());
                            $mdaFurniture->setMediaPath($content['mediaPath']->getData());
                        }
                    }
                }
                $entityManager->persist($mdaFurniture);
            }
            $this->addFlash('success', 'form.created_successfully');
            $entityManager->persist($trnFurniture);
            $entityManager->flush();
            return $this->redirectToRoute('product_furniture_index');
        }
        return $this->render('transaction/furniture/form.html.twig', [
            'trnFurniture' => $trnFurniture,
            'form' => $form->createView(),
            'label_title' => 'label.furniture_button',
            'label_button' => 'label.update',
            'mode' => 'edit'
        ]);
    }
}
