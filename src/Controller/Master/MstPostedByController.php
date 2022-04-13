<?php

namespace App\Controller\Master;

use Doctrine\Persistence\ManagerRegistry;
use Exception;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use App\Entity\Master\MstPostedBy;
use App\Form\Master\MstPostedByType;
use App\Repository\Master\MstPostedByRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Ramsey\Uuid\Uuid;

/**
 * @Route("/core/master/tred_project/posted_by", name="master_posted_by_")
 * @IsGranted("ROLE_SYS_CONTENT_USER")
 */
class MstPostedByController extends AbstractController
{
    private ManagerRegistry $managerRegistry;

    public function __construct(ManagerRegistry $managerRegistry)
    {
        $this->managerRegistry = $managerRegistry;
    }
    /**
     * @Route("/", name="index", methods={"GET"})
     * @param MstPostedByRepository $mstPostedByRepository
     * @param Request $request
     * @return Response
     */
    public function index(MstPostedByRepository $mstPostedByRepository, Request $request): Response
    {
        $posted_by = $mstPostedByRepository->findAll();
        return $this->render('master/mst_posted_by/index.html.twig', [
            'posted_by' => $posted_by,
            'path_index' => 'master_posted_by_index',
            'path_add' => 'master_posted_by_add',
            'path_edit' => 'master_posted_by_edit',
            'path_show' => 'master_posted_by_show',
            'label_title' => 'label.posted_by',
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
        $mstPostedBy = new MstPostedBy();
        $form = $this->createForm(MstPostedByType::class, $mstPostedBy);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $mstPostedBy->setRowId(Uuid::uuid4()->toString());
            $entityManager = $this->managerRegistry->getManager();
            $entityManager->persist($mstPostedBy);
            $entityManager->flush();
            $this->addFlash('success', 'form.created_successfully');
            return $this->redirectToRoute('master_posted_by_index');
        }

        return $this->render('form/form.html.twig', [
            'master_posted_by' => $mstPostedBy,
            'form' => $form->createView(),
            'index_path' => 'master_posted_by_index',
            'label_title' => 'label.posted_by',
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
        $posted_by = ucwords($request->query->get('posted_bySearch'));

        $mstPostedBy = $this->managerRegistry->getRepository(MstPostedBy::class)->getCityListByCountryId($posted_by, $countryId);
        return $this->render('master/mst_posted_by/_ajax_listing.html.twig', [
            'mst_cities' => $mstPostedBy,
            'country_id' => $countryId,
            'path_add' => 'master_posted_by_add',
            'path_edit' => 'master_posted_by_edit',
            'path_show' => 'master_posted_by_show',
            'label_title' => 'label.posted_by',
            'mode' => 'add'
        ]);
    }

    /**
     * @Route("/edit/{id}", name="edit", methods={"GET","POST"})
     * @param Request $request
     * @param MstPostedBy $mstPostedBy
     * @return Response
     */
    public function edit(Request $request, MstPostedBy $mstPostedBy): Response
    {
        $form = $this->createForm(MstPostedByType::class, $mstPostedBy);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->managerRegistry->getManager()->flush();
            $this->addFlash('success', 'form.updated_successfully');
            return $this->redirectToRoute('master_posted_by_index');
        }

        return $this->render('form/form.html.twig', [
            'master_posted_by' => $mstPostedBy,
            'form' => $form->createView(),
            'index_path' => 'master_posted_by_index',
            'label_title' => 'label.posted_by',
            'label_button' => 'label.update',
            'mode' => 'edit'
        ]);
    }

    /**
     * @Route("/{id}", name="delete", methods={"DELETE"})
     * @param Request $request
     * @param MstPostedBy $mstPostedBy
     * @return Response
     */
    public function delete(Request $request, MstPostedBy $mstPostedBy): Response
    {
        if ($this->isCsrfTokenValid('delete'.$mstPostedBy->getId(), $request->request->get('_token'))) {
            $entityManager = $this->managerRegistry->getManager();
            $entityManager->remove($mstPostedBy);
            $entityManager->flush();
        }

        return $this->redirectToRoute('master_posted_by_index');
    }
}
