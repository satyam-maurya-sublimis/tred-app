<?php

namespace App\Controller\Master;

use App\Service\CommonHelper;
use App\Service\FileUploaderHelper;
use Doctrine\Persistence\ManagerRegistry;
use Exception;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use App\Entity\Master\MstProductType;
use App\Form\Master\MstProductTypeType;
use App\Repository\Master\MstProductTypeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Ramsey\Uuid\Uuid;

/**
 * @Route("/core/master/tred_product/product_type", name="master_product_type_")
 * @IsGranted("ROLE_SYS_CONTENT_USER")
 */
class MstProductTypeController extends AbstractController
{
    private ManagerRegistry $managerRegistry;

    public function __construct(ManagerRegistry $managerRegistry)
    {
        $this->managerRegistry = $managerRegistry;
    }
    /**
     * @Route("/", name="index", methods={"GET"})
     * @param MstProductTypeRepository $mstProductTypeRepository
     * @param Request $request
     * @return Response
     */
    public function index(MstProductTypeRepository $mstProductTypeRepository, Request $request): Response
    {
        $product_type = $mstProductTypeRepository->findAll();
        return $this->render('master/mst_product_type/index.html.twig', [
            'mst_product_types' => $product_type,
            'path_index' => 'master_product_type_index',
            'path_add' => 'master_product_type_add',
            'path_edit' => 'master_product_type_edit',
            'path_show' => 'master_product_type_show',
            'label_title' => 'label.product_type',
        ]);
    }

    /**
     * @Route("/add", name="add", methods={"GET","POST"})
     * @param Request $request
     * @return Response
     * @throws Exception
     */
    public function new(Request $request, FileUploaderHelper $fileUploaderHelper, CommonHelper $helper): Response
    {
        $mstProductType = new MstProductType();
        $form = $this->createForm(MstProductTypeType::class, $mstProductType);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $mstProductType->setRowId(Uuid::uuid4()->toString());
            //$mstProductType->setProductTypeSlugName($helper->slugify($form['productType']->getData()));
            $mediaType = $form['productTypeMediaType']->getData();
            if ($mediaType == 'image'){
                $productTypeFile = $form['productTypeImage']->getData();
                if ($productTypeFile)
                {
                    $newFilename = $fileUploaderHelper->uploadPublicFile($productTypeFile, $helper->slugify($form['productTypeImageName']->getData()), $existingBannerImage = null);
                    $mstProductType->setProductTypeImage($newFilename);
                    $mstProductType->setProductTypeImagePath($this->getParameter('public_file_folder'));
                }
            }elseif ($mediaType == 'video') {
                $mstProductType->setProductTypeVideo($form['productTypeVideo']->getData());
                $mstProductType->setProductTypeVideoPath($form['productTypeVideoPath']->getData());
            }
            $entityManager = $this->managerRegistry->getManager();
            $entityManager->persist($mstProductType);
            $entityManager->flush();
            $this->addFlash('success', 'form.created_successfully');
            return $this->redirectToRoute('master_product_type_index');
        }

        return $this->render('master/mst_product_type/form.html.twig', [
            'master_product_type' => $mstProductType,
            'form' => $form->createView(),
            'index_path' => 'master_product_type_index',
            'label_title' => 'label.product_type',
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
        $product_type = ucwords($request->query->get('product_typeSearch'));

        $mstProductType = $this->managerRegistry->getRepository(MstProductType::class)->getCityListByCountryId($product_type, $countryId);
        return $this->render('master/mst_product_type/_ajax_listing.html.twig', [
            'mst_cities' => $mstProductType,
            'country_id' => $countryId,
            'path_add' => 'master_product_type_add',
            'path_edit' => 'master_product_type_edit',
            'path_show' => 'master_product_type_show',
            'label_title' => 'label.product_type',
            'mode' => 'add'
        ]);
    }

    /**
     * @Route("/edit/{id}", name="edit", methods={"GET","POST"})
     * @param Request $request
     * @param MstProductType $mstProductType
     * @return Response
     */
    public function edit(Request $request, MstProductType $mstProductType, FileUploaderHelper $fileUploaderHelper, CommonHelper $helper): Response
    {
        $existingImage = $mstProductType->getProductTypeImage();
        $form = $this->createForm(MstProductTypeType::class, $mstProductType);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $mediaType = $form['productTypeMediaType']->getData();
            //$mstProductType->setProductTypeSlugName($helper->slugify($form['productType']->getData()));
            if ($mediaType == 'image'){
                $productTypeFile = $form['productTypeImage']->getData();
                if ($productTypeFile)
                {
                    if($existingImage != '')
                    {
                        $newFilename = $fileUploaderHelper->uploadPublicFile($productTypeFile, $helper->slugify($form['productTypeImageName']->getData()), $existingImage);
                    } else {
                        $newFilename = $fileUploaderHelper->uploadPublicFile($productTypeFile, $helper->slugify($form['productTypeImageName']->getData()), null);
                    }
                    $mstProductType->setProductTypeImage($newFilename);
                    $mstProductType->setProductTypeImagePath($this->getParameter('public_file_folder'));
                }
            }elseif ($mediaType == 'video') {
                if($existingImage != '') {
                    $fileUploaderHelper->removeFile($existingImage);
                    $mstProductType->setProductTypeImage('');
                    $mstProductType->setProductTypeImageName('');
                    $mstProductType->setProductTypeImagePath('');
                }
                $mstProductType->setProductTypeVideo($form['productTypeVideo']->getData());
                $mstProductType->setProductTypeVideoPath($form['productTypeVideoPath']->getData());
            }
            $this->managerRegistry->getManager()->flush();
            $this->addFlash('success', 'form.updated_successfully');
            return $this->redirectToRoute('master_product_type_index');
        }

        return $this->render('master/mst_product_type/form.html.twig', [
            'master_product_type' => $mstProductType,
            'form' => $form->createView(),
            'index_path' => 'master_product_type_index',
            'label_title' => 'label.product_type',
            'label_button' => 'label.update',
            'mode' => 'edit'
        ]);
    }

    /**
     * @Route("/{id}", name="delete", methods={"DELETE"})
     * @param Request $request
     * @param MstProductType $mstProductType
     * @return Response
     */
    public function delete(Request $request, MstProductType $mstProductType): Response
    {
        if ($this->isCsrfTokenValid('delete'.$mstProductType->getId(), $request->request->get('_token'))) {
            $entityManager = $this->managerRegistry->getManager();
            $entityManager->remove($mstProductType);
            $entityManager->flush();
        }

        return $this->redirectToRoute('master_product_type_index');
    }
}
