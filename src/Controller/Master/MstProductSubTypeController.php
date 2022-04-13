<?php

namespace App\Controller\Master;

use App\Service\CommonHelper;
use App\Service\FileUploaderHelper;
use Doctrine\Persistence\ManagerRegistry;
use Exception;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use App\Entity\Master\MstProductSubType;
use App\Form\Master\MstProductSubTypeType;
use App\Repository\Master\MstProductSubTypeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Ramsey\Uuid\Uuid;

/**
 * @Route("/core/master/tred_product/product_subtype", name="master_product_subtype_")
 * @IsGranted("ROLE_SYS_CONTENT_USER")
 */
class MstProductSubTypeController extends AbstractController
{
    private ManagerRegistry $managerRegistry;

    public function __construct(ManagerRegistry $managerRegistry)
    {
        $this->managerRegistry = $managerRegistry;
    }
    /**
     * @Route("/", name="index", methods={"GET"})
     * @param MstProductSubTypeRepository $mstProductSubTypeRepository
     * @param Request $request
     * @return Response
     */
    public function index(MstProductSubTypeRepository $mstProductSubTypeRepository, Request $request): Response
    {
        $product_subtype = $mstProductSubTypeRepository->findAll();
        return $this->render('master/mst_product_subtype/index.html.twig', [
            'mst_product_subtypes' => $product_subtype,
            'path_index' => 'master_product_subtype_index',
            'path_add' => 'master_product_subtype_add',
            'path_edit' => 'master_product_subtype_edit',
            'path_show' => 'master_product_subtype_show',
            'label_title' => 'label.product_subtype',
        ]);
    }

    /**
     * @Route("/add", name="add", methods={"GET","POST"})
     * @param Request $request
     * @return Response
     * @throws Exception
     */
    public function new(Request $request, FileUploaderHelper $fileUploaderHelper, CommonHelper $commonHelper): Response
    {
        $mstProductSubType = new MstProductSubType();
        $form = $this->createForm(MstProductSubTypeType::class, $mstProductSubType);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $iconContentFile = $form['mediaIcon']['iconImage']->getData();
            if (!empty($iconContentFile)) {
                $strSaveFileName = $commonHelper->clean(strtolower($form['productSubType']->getData())).'_icon_'.Uuid::uuid4()->toString();
                $newFilename = $fileUploaderHelper->uploadPublicFile($iconContentFile, $strSaveFileName,null);
                $mstProductSubType->getMediaIcon()->setIconImage($newFilename);
            }
            $mstProductSubType->setRowId(Uuid::uuid4()->toString());
            $entityManager = $this->managerRegistry->getManager();
            $entityManager->persist($mstProductSubType);
            $entityManager->flush();
            $this->addFlash('success', 'form.created_successfully');
            return $this->redirectToRoute('master_product_subtype_index');
        }

        return $this->render('master/mst_product_subtype/form.html.twig', [
            'master_product_subtype' => $mstProductSubType,
            'form' => $form->createView(),
            'index_path' => 'master_product_subtype_index',
            'label_title' => 'label.product_subtype',
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
        $product_subtype = ucwords($request->query->get('product_subtypeSearch'));

        $mstProductSubType = $this->managerRegistry->getRepository(MstProductSubType::class)->getCityListByCountryId($product_subtype, $countryId);
        return $this->render('master/mst_product_subtype/_ajax_listing.html.twig', [
            'mst_cities' => $mstProductSubType,
            'country_id' => $countryId,
            'path_add' => 'master_product_subtype_add',
            'path_edit' => 'master_product_subtype_edit',
            'path_show' => 'master_product_subtype_show',
            'label_title' => 'label.product_subtype',
            'mode' => 'add'
        ]);
    }

    /**
     * @Route("/edit/{id}", name="edit", methods={"GET","POST"})
     * @param Request $request
     * @param MstProductSubType $mstProductSubType
     * @return Response
     */
    public function edit(Request $request, MstProductSubType $mstProductSubType,FileUploaderHelper $fileUploaderHelper, CommonHelper $commonHelper): Response
    {
        $form = $this->createForm(MstProductSubTypeType::class, $mstProductSubType);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $iconContentFile = $form['mediaIcon']['iconImage']->getData();
            if (!empty($iconContentFile)) {
                $strSaveFileName = $commonHelper->clean(strtolower($form['productSubType']->getData())).'_icon_'.Uuid::uuid4()->toString();
                $newFilename = $fileUploaderHelper->uploadPublicFile($iconContentFile, $strSaveFileName,null);
                $mstProductSubType->getMediaIcon()->setIconImage($newFilename);
            }
            $this->managerRegistry->getManager()->flush();
            $this->addFlash('success', 'form.updated_successfully');
            return $this->redirectToRoute('master_product_subtype_index');
        }

        return $this->render('master/mst_product_subtype/form.html.twig', [
            'master_product_subtype' => $mstProductSubType,
            'form' => $form->createView(),
            'index_path' => 'master_product_subtype_index',
            'label_title' => 'label.product_subtype',
            'label_button' => 'label.update',
            'mode' => 'edit'
        ]);
    }

    /**
     * @Route("/{id}", name="delete", methods={"DELETE"})
     * @param Request $request
     * @param MstProductSubType $mstProductSubType
     * @return Response
     */
    public function delete(Request $request, MstProductSubType $mstProductSubType): Response
    {
        if ($this->isCsrfTokenValid('delete'.$mstProductSubType->getId(), $request->request->get('_token'))) {
            $entityManager = $this->managerRegistry->getManager();
            $entityManager->remove($mstProductSubType);
            $entityManager->flush();
        }

        return $this->redirectToRoute('master_product_subtype_index');
    }
}
