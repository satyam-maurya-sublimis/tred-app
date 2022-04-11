<?php

namespace App\Controller\Master;

use Exception;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use App\Entity\Master\MstFurnitureFinish;
use App\Form\Master\MstFurnitureFinishType;
use App\Repository\Master\MstFurnitureFinishRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Ramsey\Uuid\Uuid;

/**
 * @Route("/core/master/general/furniture-finish", name="master_furniture_finish_")
 * @IsGranted("ROLE_SYS_CONTENT_USER")
 */
class MstFurnitureFinishController extends AbstractController
{
    /**
     * @Route("/", name="index", methods={"GET"})
     * @param MstFurnitureFinishRepository $furnitureFinishRepository
     * @param Request $request
     * @return Response
     */
    public function index(MstFurnitureFinishRepository $furnitureFinishRepository, Request $request): Response
    {
        $furnitureFinish = $furnitureFinishRepository->findAll();
        return $this->render('master/mst_furniture_finish/index.html.twig', [
            'data' => $furnitureFinish,
            'path_index' => 'master_furniture_finish_index',
            'path_add' => 'master_furniture_finish_add',
            'path_edit' => 'master_furniture_finish_edit',
            'path_show' => 'master_furniture_finish_show',
            'label_title' => 'label.furniture_finish',
        ]);
    }

    /**
     * @Route("/add", name="add", methods={"GET","POST"})
     * @param Request $request
     * @return Response
     * @throws Exception
     */
    public function new(Request $request): Response
    {
        $furnitureFinish = new MstFurnitureFinish();
        $form = $this->createForm(MstFurnitureFinishType::class, $furnitureFinish);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $furnitureFinish->setRowId(Uuid::uuid4()->toString());
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($furnitureFinish);
            $entityManager->flush();
            $this->addFlash('success', 'form.created_successfully');
            return $this->redirectToRoute('master_furniture_finish_index');
        }

        return $this->render('form/form.html.twig', [
            'data' => $furnitureFinish,
            'form' => $form->createView(),
            'index_path' => 'master_furniture_finish_index',
            'label_title' => 'label.furniture_finish',
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
        $furnitureFinish = ucwords($request->query->get('furnitureFinishSearch'));
        $furnitureFinish = $this->getDoctrine()->getRepository(MstFurnitureFinish::class)->getFurnitureFinish($furnitureFinish);
        return $this->render('master/mst_furniture_finish/_ajax_listing.html.twig', [
            'data' => $furnitureFinish,
            'path_add' => 'master_furniture_finish_add',
            'path_edit' => 'master_furniture_finish_edit',
            'path_show' => 'master_furniture_finish_show',
            'label_title' => 'label.furniture_finish',
            'mode' => 'add'
        ]);
    }

    /**
     * @Route("/edit/{id}", name="edit", methods={"GET","POST"})
     * @param Request $request
     * @param MstFurnitureFinish $furnitureFinish
     * @return Response
     */
    public function edit(Request $request, MstFurnitureFinish $furnitureFinish): Response
    {
        $form = $this->createForm(MstFurnitureFinishType::class, $furnitureFinish);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', 'form.updated_successfully');
            return $this->redirectToRoute('master_furniture_finish_index');
        }

        return $this->render('form/form.html.twig', [
            'data' => $furnitureFinish,
            'form' => $form->createView(),
            'index_path' => 'master_furniture_finish_index',
            'label_title' => 'label.furniture_finish',
            'label_button' => 'label.update',
            'mode' => 'edit'
        ]);
    }

    /**
     * @Route("/{id}", name="delete", methods={"DELETE"})
     * @param Request $request
     * @param MstFurnitureFinish $furnitureFinish
     * @return Response
     */
    public function delete(Request $request, MstFurnitureFinish $furnitureFinish): Response
    {
        if ($this->isCsrfTokenValid('delete'.$furnitureFinish->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($furnitureFinish);
            $entityManager->flush();
        }

        return $this->redirectToRoute('master_furniture_finish_index');
    }
}
