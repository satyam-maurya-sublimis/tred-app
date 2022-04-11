<?php

namespace App\Controller\Master;

use Exception;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use App\Entity\Master\MstCallPurpose;
use App\Form\Master\MstCallPurposeType;
use App\Repository\Master\MstCallPurposeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Ramsey\Uuid\Uuid;

/**
 * @Route("/core/master/general/call_purpose", name="master_call_purpose_")
 * @IsGranted("ROLE_SYS_CONTENT_USER")
 */
class MstCallPurposeController extends AbstractController
{
    /**
     * @Route("/", name="index", methods={"GET"})
     * @param MstCallPurposeRepository $mstCallPurposeRepository
     * @param Request $request
     * @return Response
     */
    public function index(MstCallPurposeRepository $mstCallPurposeRepository, Request $request): Response
    {
        $call_purpose = $mstCallPurposeRepository->findAll();
        return $this->render('master/mst_call_purpose/index.html.twig', [
            'mst_call_purposes' => $call_purpose,
            'path_index' => 'master_call_purpose_index',
            'path_add' => 'master_call_purpose_add',
            'path_edit' => 'master_call_purpose_edit',
            'path_show' => 'master_call_purpose_show',
            'label_title' => 'label.call_purpose',
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
        $mstCallPurpose = new MstCallPurpose();
        $form = $this->createForm(MstCallPurposeType::class, $mstCallPurpose);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $mstCallPurpose->setRowId(Uuid::uuid4()->toString());
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($mstCallPurpose);
            $entityManager->flush();
            $this->addFlash('success', 'form.created_successfully');
            return $this->redirectToRoute('master_call_purpose_index');
        }

        return $this->render('form/form.html.twig', [
            'master_call_purpose' => $mstCallPurpose,
            'form' => $form->createView(),
            'index_path' => 'master_call_purpose_index',
            'label_title' => 'label.call_purpose',
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
        $call_purpose = ucwords($request->query->get('call_purposeSearch'));

        $mstCallPurpose = $this->getDoctrine()->getRepository(MstCallPurpose::class)->getCityListByCountryId($call_purpose, $countryId);
        return $this->render('master/mst_call_purpose/_ajax_listing.html.twig', [
            'mst_cities' => $mstCallPurpose,
            'country_id' => $countryId,
            'path_add' => 'master_call_purpose_add',
            'path_edit' => 'master_call_purpose_edit',
            'path_show' => 'master_call_purpose_show',
            'label_title' => 'label.call_purpose',
            'mode' => 'add'
        ]);
    }

    /**
     * @Route("/edit/{id}", name="edit", methods={"GET","POST"})
     * @param Request $request
     * @param MstCallPurpose $mstCallPurpose
     * @return Response
     */
    public function edit(Request $request, MstCallPurpose $mstCallPurpose): Response
    {
        $form = $this->createForm(MstCallPurposeType::class, $mstCallPurpose);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', 'form.updated_successfully');
            return $this->redirectToRoute('master_call_purpose_index');
        }

        return $this->render('form/form.html.twig', [
            'master_call_purpose' => $mstCallPurpose,
            'form' => $form->createView(),
            'index_path' => 'master_call_purpose_index',
            'label_title' => 'label.call_purpose',
            'label_button' => 'label.update',
            'mode' => 'edit'
        ]);
    }

    /**
     * @Route("/{id}", name="delete", methods={"DELETE"})
     * @param Request $request
     * @param MstCallPurpose $mstCallPurpose
     * @return Response
     */
    public function delete(Request $request, MstCallPurpose $mstCallPurpose): Response
    {
        if ($this->isCsrfTokenValid('delete'.$mstCallPurpose->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($mstCallPurpose);
            $entityManager->flush();
        }

        return $this->redirectToRoute('master_call_purpose_index');
    }
}
