<?php

namespace App\Controller\Product;

use App\Entity\Product\PrdColor;
use App\Form\Product\PrdColorType;
use App\Repository\Product\PrdColorRepository;
use App\Service\CommonHelper;
use App\Service\FileUploaderHelper;
use Ramsey\Uuid\Uuid;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/core/master/general/color", name="prd_color_")
 * @IsGranted("ROLE_SYS_CONTENT_USER")
 */
class PrdColorController extends AbstractController
{
    /**
     * @Route("/", name="index", methods={"GET"})
     * @param PrdColorRepository $prdColorRepository
     * @return Response
     */
    public function index(PrdColorRepository $prdColorRepository): Response
    {
        return $this->render('product/prd_color/index.html.twig', [
            'prd_colors' => $prdColorRepository->findAll(),
            'path_index' => 'prd_color_index',
            'path_add' => 'prd_color_add',
            'path_edit' => 'prd_color_edit',
            'path_show' => 'prd_color_show',
            'label_title' => 'label.color',
            'label_heading' => 'label.color'
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
        $prdColor = new PrdColor();
        $option = array('image_required' => false);
        $form = $this->createForm(PrdColorType::class, $prdColor, $option);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $prdColor->setRowId(Uuid::uuid4()->toString());
            if (isset($form['colorImage'])){
                $imageFile = $form['colorImage']->getData();
                if ($imageFile)
                {
                    $newFilename = $fileUploaderHelper->uploadPublicFile($imageFile, $commonHelper->slugify($form['colorName']->getData()), $existingMedia = null);
                    $prdColor->setColorImage($newFilename);
                    $prdColor->setColorImagePath($this->getParameter('public_file_folder'));
                }
            }
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($prdColor);
            $entityManager->flush();
            $this->addFlash('success', 'form.created_successfully');
            return $this->redirectToRoute('prd_color_index', $request->query->all());
        }

        return $this->render('product/prd_color/form.html.twig', [
            'prd_color' => $prdColor,
            'form' => $form->createView(),
            'index_path' => 'prd_color_index',
            'label_title' => 'label.color',
            'label_button' => 'label.create',
            'label_heading' => 'label.color',
            'mode' => 'add'
        ]);
    }

    /**
     * @Route("/edit/{id}", name="edit", methods={"GET","POST"})
     * @param Request $request
     * @param PrdColor $prdColor
     * @param CommonHelper $commonHelper
     * @param FileUploaderHelper $fileUploaderHelper
     * @return Response
     * @throws \Exception
     */
    public function edit(Request $request, PrdColor $prdColor, CommonHelper  $commonHelper, FileUploaderHelper $fileUploaderHelper): Response
    {
        $existingMedia = $prdColor->getColorImage();
        $option = array('image_required' => false);
        $form = $this->createForm(PrdColorType::class, $prdColor, $option);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if (isset($form['colorImage'])) {
                $imageFile = $form['colorImage']->getData();
                if ($imageFile) {
                    if ($existingMedia != '') {
                        $newFilename = $fileUploaderHelper->uploadPublicFile($imageFile, $commonHelper->slugify($form['colorName']->getData()), $existingMedia);
                    } else {
                        $newFilename = $fileUploaderHelper->uploadPublicFile($imageFile, $commonHelper->slugify($form['colorName']->getData()), $existingMedia = null);
                    }
                    $prdColor->setColorImage($newFilename);
                    $prdColor->setColorImagePath($this->getParameter('public_file_folder'));
                }
            }
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', 'form.updated_successfully');
            return $this->redirectToRoute('prd_color_index', $request->query->all());
        }

        return $this->render('product/prd_color/form.html.twig', [
            'prd_color' => $prdColor,
            'form' => $form->createView(),
            'index_path' => 'prd_color_index',
            'label_title' => 'label.color',
            'label_button' => 'label.update',
            'label_heading' => 'label.color',
            'mode' => 'edit'
        ]);
    }

    /**
     * @Route("/{id}", name="show", methods={"GET","POST"})
     * @param PrdColor $prdColor
     * @return Response
     */
    public function show(PrdColor $prdColor): Response
    {
        return $this->render('product/prd_color/show.html.twig', [
            'data' => $prdColor,
            'label_title' => 'label.color',
            'label_button' => 'label.update',
            'label_log' => 'label.log',
            'path_index' => 'prd_color_index',
            'path_edit' => 'prd_color_edit',
        ]);
    }

}
