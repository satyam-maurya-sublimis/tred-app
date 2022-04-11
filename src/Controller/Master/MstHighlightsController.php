<?php

namespace App\Controller\Master;

use Exception;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use App\Entity\Master\MstHighlights;
use App\Form\Master\MstHighlightsType;
use App\Repository\Master\MstHighlightsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Ramsey\Uuid\Uuid;

/**
 * @Route("/core/master/tred/highlights", name="master_highlights_")
 * @IsGranted("ROLE_SYS_CONTENT_USER")
 */
class MstHighlightsController extends AbstractController
{
    /**
     * @Route("/", name="index", methods={"GET"})
     * @param MstHighlightsRepository $mstHighlightsRepository
     * @param Request $request
     * @return Response
     */
    public function index(MstHighlightsRepository $mstHighlightsRepository, Request $request): Response
    {
        $mstHighlights = $mstHighlightsRepository->findAll();
        return $this->render('master/mst_highlights/index.html.twig', [
            'mst_highlights' => $mstHighlights,
            'path_index' => 'master_highlights_index',
            'path_add' => 'master_highlights_add',
            'path_edit' => 'master_highlights_edit',
            'path_show' => 'master_highlights_show',
            'label_title' => 'label.highlights',
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
        $mstHighlights = new MstHighlights();
        $form = $this->createForm(MstHighlightsType::class, $mstHighlights);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $mstHighlights->setRowId(Uuid::uuid4()->toString());
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($mstHighlights);
            $entityManager->flush();
            $this->addFlash('success', 'form.created_successfully');
            return $this->redirectToRoute('master_highlights_index');
        }

        return $this->render('form/form.html.twig', [
            'master_highlights' => $mstHighlights,
            'form' => $form->createView(),
            'index_path' => 'master_highlights_index',
            'label_title' => 'label.highlights',
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
        $mstHighlights = ucwords($request->query->get('highlightsSearch'));

        $mstHighlights = $this->getDoctrine()->getRepository(MstHighlights::class)->getCityListByCountryId($mstHighlights, $countryId);
        return $this->render('master/mst_highlights/_ajax_listing.html.twig', [
            'mst_cities' => $mstHighlights,
            'country_id' => $countryId,
            'path_add' => 'master_highlights_add',
            'path_edit' => 'master_highlights_edit',
            'path_show' => 'master_highlights_show',
            'label_title' => 'label.highlights',
            'mode' => 'add'
        ]);
    }

    /**
     * @Route("/edit/{id}", name="edit", methods={"GET","POST"})
     * @param Request $request
     * @param MstHighlights $mstHighlights
     * @return Response
     */
    public function edit(Request $request, MstHighlights $mstHighlights): Response
    {
        $form = $this->createForm(MstHighlightsType::class, $mstHighlights);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', 'form.updated_successfully');
            return $this->redirectToRoute('master_highlights_index');
        }

        return $this->render('form/form.html.twig', [
            'master_highlights' => $mstHighlights,
            'form' => $form->createView(),
            'index_path' => 'master_highlights_index',
            'label_title' => 'label.highlights',
            'label_button' => 'label.update',
            'mode' => 'edit'
        ]);
    }

    /**
     * @Route("/{id}", name="delete", methods={"DELETE"})
     * @param Request $request
     * @param MstHighlights $mstHighlights
     * @return Response
     */
    public function delete(Request $request, MstHighlights $mstHighlights): Response
    {
        if ($this->isCsrfTokenValid('delete'.$mstHighlights->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($mstHighlights);
            $entityManager->flush();
        }

        return $this->redirectToRoute('master_highlights_index');
    }
}
