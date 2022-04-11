<?php

namespace App\Controller\Master;

use Exception;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use App\Entity\Master\MstFacing;
use App\Form\Master\MstFacingType;
use App\Repository\Master\MstFacingRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Ramsey\Uuid\Uuid;

/**
 * @Route("/core/master/tred_project/facing", name="master_facing_")
 * @IsGranted("ROLE_SYS_CONTENT_USER")
 */
class MstFacingController extends AbstractController
{
    /**
     * @Route("/", name="index", methods={"GET"})
     * @param MstFacingRepository $mstFacingRepository
     * @param Request $request
     * @return Response
     */
    public function index(MstFacingRepository $mstFacingRepository, Request $request): Response
    {
        $facing = $mstFacingRepository->findAll();
        return $this->render('master/mst_facing/index.html.twig', [
            'mst_facings' => $facing,
            'path_index' => 'master_facing_index',
            'path_add' => 'master_facing_add',
            'path_edit' => 'master_facing_edit',
            'path_show' => 'master_facing_show',
            'label_title' => 'label.facing',
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
        $mstFacing = new MstFacing();
        $form = $this->createForm(MstFacingType::class, $mstFacing);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $mstFacing->setRowId(Uuid::uuid4()->toString());
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($mstFacing);
            $entityManager->flush();
            $this->addFlash('success', 'form.created_successfully');
            return $this->redirectToRoute('master_facing_index');
        }

        return $this->render('form/form.html.twig', [
            'master_facing' => $mstFacing,
            'form' => $form->createView(),
            'index_path' => 'master_facing_index',
            'label_title' => 'label.facing',
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
        $facing = ucwords($request->query->get('facingSearch'));

        $mstFacing = $this->getDoctrine()->getRepository(MstFacing::class)->getCityListByCountryId($facing, $countryId);
        return $this->render('master/mst_facing/_ajax_listing.html.twig', [
            'mst_cities' => $mstFacing,
            'country_id' => $countryId,
            'path_add' => 'master_facing_add',
            'path_edit' => 'master_facing_edit',
            'path_show' => 'master_facing_show',
            'label_title' => 'label.facing',
            'mode' => 'add'
        ]);
    }

    /**
     * @Route("/edit/{id}", name="edit", methods={"GET","POST"})
     * @param Request $request
     * @param MstFacing $mstFacing
     * @return Response
     */
    public function edit(Request $request, MstFacing $mstFacing): Response
    {
        $form = $this->createForm(MstFacingType::class, $mstFacing);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', 'form.updated_successfully');
            return $this->redirectToRoute('master_facing_index');
        }

        return $this->render('form/form.html.twig', [
            'master_facing' => $mstFacing,
            'form' => $form->createView(),
            'index_path' => 'master_facing_index',
            'label_title' => 'label.facing',
            'label_button' => 'label.update',
            'mode' => 'edit'
        ]);
    }

    /**
     * @Route("/{id}", name="delete", methods={"DELETE"})
     * @param Request $request
     * @param MstFacing $mstFacing
     * @return Response
     */
    public function delete(Request $request, MstFacing $mstFacing): Response
    {
        if ($this->isCsrfTokenValid('delete'.$mstFacing->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($mstFacing);
            $entityManager->flush();
        }

        return $this->redirectToRoute('master_facing_index');
    }
}
