<?php

namespace App\Controller\Master;

use Exception;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use App\Entity\Master\MstFurnishing;
use App\Form\Master\MstFurnishingType;
use App\Repository\Master\MstFurnishingRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Ramsey\Uuid\Uuid;

/**
 * @Route("/core/master/tred/furnishing", name="master_furnishing_")
 * @IsGranted("ROLE_SYS_CONTENT_USER")
 */
class MstFurnishingController extends AbstractController
{
    /**
     * @Route("/", name="index", methods={"GET"})
     * @param MstFurnishingRepository $furnishingRepository
     * @param Request $request
     * @return Response
     */
    public function index(MstFurnishingRepository $furnishingRepository, Request $request): Response
    {
        $furnishings = $furnishingRepository->findAll();
        return $this->render('master/mst_furnishing/index.html.twig', [
            'mst_furnishings' => $furnishings,
            'path_index' => 'master_furnishing_index',
            'path_add' => 'master_furnishing_add',
            'path_edit' => 'master_furnishing_edit',
            'path_show' => 'master_furnishing_show',
            'label_title' => 'label.furnishing',
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
        $furnishing = new MstFurnishing();
        $form = $this->createForm(MstFurnishingType::class, $furnishing);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $furnishing->setRowId(Uuid::uuid4()->toString());
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($furnishing);
            $entityManager->flush();
            $this->addFlash('success', 'form.created_successfully');
            return $this->redirectToRoute('master_furnishing_index');
        }

        return $this->render('form/form.html.twig', [
            'master_furnishing' => $furnishing,
            'form' => $form->createView(),
            'index_path' => 'master_furnishing_index',
            'label_title' => 'label.furnishing',
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
        $furnishing = ucwords($request->query->get('furnishingSearch'));

        $furnishing = $this->getDoctrine()->getRepository(MstFurnishing::class)->getCityListByCountryId($furnishing, $countryId);
        return $this->render('master/mst_furnishing/_ajax_listing.html.twig', [
            'mst_cities' => $furnishing,
            'country_id' => $countryId,
            'path_add' => 'master_furnishing_add',
            'path_edit' => 'master_furnishing_edit',
            'path_show' => 'master_furnishing_show',
            'label_title' => 'label.furnishing',
            'mode' => 'add'
        ]);
    }

    /**
     * @Route("/edit/{id}", name="edit", methods={"GET","POST"})
     * @param Request $request
     * @param MstFurnishing $furnishing
     * @return Response
     */
    public function edit(Request $request, MstFurnishing $furnishing): Response
    {
        $form = $this->createForm(MstFurnishingType::class, $furnishing);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', 'form.updated_successfully');
            return $this->redirectToRoute('master_furnishing_index');
        }

        return $this->render('form/form.html.twig', [
            'master_furnishing' => $furnishing,
            'form' => $form->createView(),
            'index_path' => 'master_furnishing_index',
            'label_title' => 'label.furnishing',
            'label_button' => 'label.update',
            'mode' => 'edit'
        ]);
    }

    /**
     * @Route("/{id}", name="delete", methods={"DELETE"})
     * @param Request $request
     * @param MstFurnishing $furnishing
     * @return Response
     */
    public function delete(Request $request, MstFurnishing $furnishing): Response
    {
        if ($this->isCsrfTokenValid('delete'.$furnishing->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($furnishing);
            $entityManager->flush();
        }

        return $this->redirectToRoute('master_furnishing_index');
    }
}
