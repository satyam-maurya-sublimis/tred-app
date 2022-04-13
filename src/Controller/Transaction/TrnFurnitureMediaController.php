<?php

namespace App\Controller\Transaction;

use App\Entity\Transaction\TrnFurniture;
use App\Entity\Media\MdaFurniture;
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
 * @Route("/core/product/furniture-media", name="product_furniture_media_")
 * @IsGranted("ROLE_SYS_CONTENT_USER")
 */
class TrnFurnitureMediaController extends AbstractController
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
        $furniture_id = $request->query->get('furniture_id');
        if(!$furniture_id) {
            return $this->redirectToRoute('product_furniture_index');
        }
        $trnFurniture = $this->managerRegistry->getRepository(TrnFurniture::class)->find($furniture_id);
        return $this->render('transaction/trn_furniture_media/index.html.twig', [
            'medias' => $mdaFurnitureRepository->findBy(['trnFurniture' => $furniture_id]),
            'trnFurniture' => $trnFurniture,
            'path_index' => 'product_furniture_media_index',
            'path_add' => 'product_furniture_media_add',
            'path_edit' => 'product_furniture_media_edit',
            'path_show' => 'product_furniture_media_show',
            'label_title' => 'label.media',
            'label_heading' => 'label.furniture_product_name'
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
        $furniture_id = $request->query->get('furniture_id');
        if(!$furniture_id) {
            return $this->redirectToRoute('product_furniture_index');
        }
        $trnFurniture = $this->managerRegistry->getRepository(TrnFurniture::class)->find($furniture_id);
        $trnFurnitureMedia = new MdaFurniture();
        $form = $this->createForm(MdaFurnitureType::class, $trnFurnitureMedia);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->managerRegistry->getManager();
            $imageFile = $form['mediaFileName']->getData();
            $newFilename = $fileUploaderHelper->uploadPublicFile($imageFile, $commonHelper->slugify($form['mediaName']->getData()), $existingMedia = null);
            $trnFurnitureMedia->setTrnFurniture($trnFurniture);
            $trnFurnitureMedia->setMediaFileName($newFilename);
            $trnFurnitureMedia->setMediaFilePath($this->getParameter('public_file_folder'));
            $entityManager->persist($trnFurnitureMedia);
            $entityManager->flush();
            $this->addFlash('success', 'form.created_successfully');
            return $this->redirectToRoute('product_furniture_media_index', $request->query->all());
        }

        return $this->render('transaction/trn_furniture_media/form.html.twig', [
            'media' => $trnFurnitureMedia,
            'trnFurniture' => $trnFurniture,
            'form' => $form->createView(),
            'index_path' => 'product_furniture_media_index',
            'label_title' => 'label.media',
            'label_button' => 'label.create',
            'label_heading' => 'label.furniture_product_name',
            'mode' => 'add'
        ]);
    }

    /**
     * @Route("/{id}/edit", name="edit", methods={"GET","POST"})
     * @param Request $request
     * @param MdaFurniture $trnFurnitureMedia
     * @param CommonHelper $commonHelper
     * @param FileUploaderHelper $fileUploaderHelper
     * @return Response
     * @throws \Exception
     */
    public function edit(Request $request, MdaFurniture $trnFurnitureMedia, CommonHelper  $commonHelper, FileUploaderHelper $fileUploaderHelper): Response
    {
        $furniture_id = $request->query->get('furniture_id');
        if(!$furniture_id) {
            return $this->redirectToRoute('product_furniture_index');
        }
        $existingMedia = $trnFurnitureMedia->getMediaFileName();
        $trnFurniture = $this->managerRegistry->getRepository(TrnFurniture::class)->find($furniture_id);
        $form = $this->createForm(MdaFurnitureType::class, $trnFurnitureMedia);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $imageFile = $form['mediaFileName']->getData();
            if ($imageFile){
                if($existingMedia != ''){
                    $newFilename = $fileUploaderHelper->uploadPublicFile($imageFile, $commonHelper->slugify($form['mediaName']->getData()), $existingMedia);
                } else {
                    $newFilename = $fileUploaderHelper->uploadPublicFile($imageFile, $commonHelper->slugify($form['mediaName']->getData()), $existingMedia = null);
                }
                $trnFurnitureMedia->setMediaFileName($newFilename);
                $trnFurnitureMedia->setMediaFilePath($this->getParameter('public_file_folder'));
            }
            $this->managerRegistry->getManager()->flush();
            $this->addFlash('success', 'form.updated_successfully');
            return $this->redirectToRoute('product_furniture_media_index', $request->query->all());
        }

        return $this->render('transaction/trn_furniture_media/form.html.twig', [
            'media' => $trnFurnitureMedia,
            'trnFurniture' => $trnFurniture,
            'form' => $form->createView(),
            'index_path' => 'product_furniture_media_index',
            'label_title' => 'label.media',
            'label_button' => 'label.update',
            'label_heading' => 'label.furniture_product_name',
            'mode' => 'edit'
        ]);
    }

    /**
     * @Route("/{id}", name="show", methods={"GET","POST"})
     * @param Request $request
     * @param MdaFurniture $trnFurnitureMedia
     * @return Response
     */
    public function show(Request $request, MdaFurniture $trnFurnitureMedia): Response
    {
        $furniture_id = $request->query->get('furniture_id');
        if (!$furniture_id) {
            return $this->redirectToRoute('product_furniture_index');
        }
        $trnFurniture = $this->managerRegistry->getRepository(TrnFurniture::class)->find($furniture_id);

        return $this->render('transaction/trn_furniture_media/show.html.twig', [
            'data' => $trnFurnitureMedia,
            'trnFurniture' => $trnFurniture,
            'label_title' => 'label.media',
            'label_button' => 'label.update',
            'label_log' => 'label.log',
            'path_index' => 'product_furniture_media_index',
            'path_edit' => 'product_furniture_media_edit',
        ]);
    }
}
