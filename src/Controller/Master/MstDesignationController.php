<?php

namespace App\Controller\Master;

use Exception;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use App\Entity\Master\MstDesignation;
use App\Form\Master\MstDesignationType;
use App\Repository\Master\MstDesignationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Ramsey\Uuid\Uuid;
use Knp\Component\Pager\PaginatorInterface;


/**
 * @Route("/core/master/general/designation", name="master_designation_")
 * @IsGranted("ROLE_SYS_CONTENT_USER")
 */

class MstDesignationController extends AbstractController
{
    /**
     * @Route("/", name="index", methods={"GET"})
     * @param MstDesignationRepository $mstDesignationRepository
     * @param Request $request
     * @param PaginatorInterface $paginator
     * @return Response
     */
    public function index(MstDesignationRepository $mstDesignationRepository, Request $request, PaginatorInterface $paginator): Response
    {
        $queryBuilder = $mstDesignationRepository->findAll();
        $pagination = $paginator->paginate(
            $queryBuilder,
            $request->query->getInt('page', 1),
            10
        );
        return $this->render('master/mst_designation/index.html.twig', [
            'designations' => $pagination,
            'path_index' => 'master_designation_index',
            'path_add' => 'master_designation_add',
            'path_edit' => 'master_designation_edit',
            'path_show' => 'master_designation_show',
            'label_title' => 'label.designation',
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
        $mstDesignation = new MstDesignation();
        $form = $this->createForm(MstDesignationType::class, $mstDesignation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $mstDesignation->setRowId(Uuid::uuid4()->toString());
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($mstDesignation);
            $entityManager->flush();
            $this->addFlash('success', 'form.created_successfully');
            return $this->redirectToRoute('master_designation_index');
        }
        return $this->render('form/form.html.twig', [
            'mst_designation' => $mstDesignation,
            'form' => $form->createView(),
            'index_path' => 'master_designation_index',
            'label_title' => 'label.designation',
            'label_button' => 'label.create',
            'mode' => 'add'
        ]);
    }

    /**
     * @Route("/edit/{id}", name="edit", methods={"GET","POST"})
     * @param Request $request
     * @param MstDesignation $mstDesignation
     * @return Response
     */
    public function edit(Request $request, MstDesignation $mstDesignation): Response
    {
        $form = $this->createForm(MstDesignationType::class, $mstDesignation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', 'form.updated_successfully');
            return $this->redirectToRoute('master_designation_index');
        }
        return $this->render('form/form.html.twig', [
            'mst_designation' => $mstDesignation,
            'form' => $form->createView(),
            'index_path' => 'master_designation_index',
            'label_title' => 'label.designation',
            'label_button' => 'label.update',
            'mode' => 'edit'
        ]);
    }

    /**
     * @Route("/delete/{id}", name="delete", methods={"DELETE"})
     * @param Request $request
     * @param MstDesignation $mstDesignation
     * @return Response
     */
    public function delete(Request $request, MstDesignation $mstDesignation): Response
    {
        if ($this->isCsrfTokenValid('delete'.$mstDesignation->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($mstDesignation);
            $entityManager->flush();
        }

        return $this->redirectToRoute('master_designation_index');
    }
}
