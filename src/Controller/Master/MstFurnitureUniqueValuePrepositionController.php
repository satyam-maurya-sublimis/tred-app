<?php

namespace App\Controller\Master;

use App\Service\CommonHelper;
use App\Service\FileUploaderHelper;
use Doctrine\Persistence\ManagerRegistry;
use Exception;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use App\Entity\Master\MstFurnitureUniqueValuePreposition;
use App\Form\Master\MstFurnitureUniqueValuePrepositionType;
use App\Repository\Master\MstFurnitureUniqueValuePrepositionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Ramsey\Uuid\Uuid;

/**
 * @Route("/core/master/general/unique-value-preposition", name="master_furniture_uvp_")
 * @IsGranted("ROLE_SYS_CONTENT_USER")
 */
class MstFurnitureUniqueValuePrepositionController extends AbstractController
{
    private ManagerRegistry $managerRegistry;

    public function __construct(ManagerRegistry $managerRegistry)
    {
        $this->managerRegistry = $managerRegistry;
    }
    /**
     * @Route("/", name="index", methods={"GET"})
     * @param MstFurnitureUniqueValuePrepositionRepository $mstFurnitureUniqueValuePrepositionRepository
     * @param Request $request
     * @return Response
     */
    public function index(MstFurnitureUniqueValuePrepositionRepository $mstFurnitureUniqueValuePrepositionRepository, Request $request): Response
    {
        $mstFurnitureUniqueValuePreposition = $mstFurnitureUniqueValuePrepositionRepository->findAll();
        return $this->render('master/mst_furniture_unique_value_preposition/index.html.twig', [
            'datas' => $mstFurnitureUniqueValuePreposition,
            'path_index' => 'master_furniture_uvp_index',
            'path_add' => 'master_furniture_uvp_add',
            'path_edit' => 'master_furniture_uvp_edit',
            'path_show' => 'master_furniture_uvp_show',
            'label_title' => 'label.unique_value_preposition',
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
        $mstFurnitureUniqueValuePreposition = new MstFurnitureUniqueValuePreposition();
        $form = $this->createForm(MstFurnitureUniqueValuePrepositionType::class, $mstFurnitureUniqueValuePreposition);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $iconContentFile = $form['mediaIcon']['iconImage']->getData();
            if (!empty($iconContentFile)) {
                $strSaveFileName = $commonHelper->clean(strtolower($form['uniqueValuePreposition']->getData())).'_icon_'.Uuid::uuid4()->toString();
                $newFilename = $fileUploaderHelper->uploadPublicFile($iconContentFile, $strSaveFileName,null);
                $mstFurnitureUniqueValuePreposition->getMediaIcon()->setIconImage($newFilename);
            }
            $mstFurnitureUniqueValuePreposition->setRowId(Uuid::uuid4()->toString());
            $mstFurnitureUniqueValuePreposition->setCreatedOn(new \DateTime());
            $entityManager = $this->managerRegistry->getManager();
            $entityManager->persist($mstFurnitureUniqueValuePreposition);
            $entityManager->flush();
            $this->addFlash('success', 'form.created_successfully');
            return $this->redirectToRoute('master_furniture_uvp_index');
        }

        return $this->render('master/mst_furniture_unique_value_preposition/form.html.twig', [
            'master_furniture_uvp_' => $mstFurnitureUniqueValuePreposition,
            'form' => $form->createView(),
            'index_path' => 'master_furniture_uvp_index',
            'label_title' => 'label.unique_value_preposition',
            'label_button' => 'label.create',
            'mode' => 'add'
        ]);
    }

    /**
     * @Route("/edit/{id}", name="edit", methods={"GET","POST"})
     * @param Request $request
     * @param MstFurnitureUniqueValuePreposition $mstFurnitureUniqueValuePreposition
     * @return Response
     */
    public function edit(Request $request, MstFurnitureUniqueValuePreposition $mstFurnitureUniqueValuePreposition,FileUploaderHelper $fileUploaderHelper, CommonHelper $commonHelper): Response
    {
        $form = $this->createForm(MstFurnitureUniqueValuePrepositionType::class, $mstFurnitureUniqueValuePreposition);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $iconContentFile = $form['mediaIcon']['iconImage']->getData();
            if (!empty($iconContentFile)) {
                $strSaveFileName = $commonHelper->clean(strtolower($form['uniqueValuePreposition']->getData())).'_icon_'.Uuid::uuid4()->toString();
                $newFilename = $fileUploaderHelper->uploadPublicFile($iconContentFile, $strSaveFileName,null);
                $mstFurnitureUniqueValuePreposition->getMediaIcon()->setIconImage($newFilename);
            }
            $this->managerRegistry->getManager()->flush();
            $this->addFlash('success', 'form.updated_successfully');
            return $this->redirectToRoute('master_furniture_uvp_index');
        }

        return $this->render('master/mst_furniture_unique_value_preposition/form.html.twig', [
            'master_furniture_uvp_' => $mstFurnitureUniqueValuePreposition,
            'form' => $form->createView(),
            'index_path' => 'master_furniture_uvp_index',
            'label_title' => 'label.unique_value_preposition',
            'label_button' => 'label.update',
            'mode' => 'edit'
        ]);
    }

    /**
     * @Route("/{id}", name="delete", methods={"DELETE"})
     * @param Request $request
     * @param MstFurnitureUniqueValuePreposition $mstFurnitureUniqueValuePreposition
     * @return Response
     */
    public function delete(Request $request, MstFurnitureUniqueValuePreposition $mstFurnitureUniqueValuePreposition): Response
    {
        if ($this->isCsrfTokenValid('delete'.$mstFurnitureUniqueValuePreposition->getId(), $request->request->get('_token'))) {
            $entityManager = $this->managerRegistry->getManager();
            $entityManager->remove($mstFurnitureUniqueValuePreposition);
            $entityManager->flush();
        }

        return $this->redirectToRoute('master_furniture_uvp_index');
    }
}
