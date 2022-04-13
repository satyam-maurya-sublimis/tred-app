<?php

namespace App\Controller\Master;

use Doctrine\Persistence\ManagerRegistry;
use Exception;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use App\Entity\Master\MstPossession;
use App\Form\Master\MstPossessionType;
use App\Repository\Master\MstPossessionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Ramsey\Uuid\Uuid;

/**
 * @Route("/core/master/tred_project/possession", name="master_possession_")
 * @IsGranted("ROLE_SYS_CONTENT_USER")
 */
class MstPossessionController extends AbstractController
{
    private ManagerRegistry $managerRegistry;

    public function __construct(ManagerRegistry $managerRegistry)
    {
        $this->managerRegistry = $managerRegistry;
    }
    /**
     * @Route("/", name="index", methods={"GET"})
     * @param MstPossessionRepository $mstPossessionRepository
     * @param Request $request
     * @return Response
     */
    public function index(MstPossessionRepository $mstPossessionRepository, Request $request): Response
    {
        $possession = $mstPossessionRepository->findAll();
        return $this->render('master/mst_possession/index.html.twig', [
            'mst_possessions' => $possession,
            'path_index' => 'master_possession_index',
            'path_add' => 'master_possession_add',
            'path_edit' => 'master_possession_edit',
            'path_show' => 'master_possession_show',
            'label_title' => 'label.possession',
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
        $mstPossession = new MstPossession();
        $form = $this->createForm(MstPossessionType::class, $mstPossession);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $mstPossession->setRowId(Uuid::uuid4()->toString());
            $entityManager = $this->managerRegistry->getManager();
            $entityManager->persist($mstPossession);
            $entityManager->flush();
            $this->addFlash('success', 'form.created_successfully');
            return $this->redirectToRoute('master_possession_index');
        }

        return $this->render('form/form.html.twig', [
            'master_possession' => $mstPossession,
            'form' => $form->createView(),
            'index_path' => 'master_possession_index',
            'label_title' => 'label.possession',
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
        $possession = ucwords($request->query->get('possessionSearch'));

        $mstPossession = $this->managerRegistry->getRepository(MstPossession::class)->getCityListByCountryId($possession, $countryId);
        return $this->render('master/mst_possession/_ajax_listing.html.twig', [
            'mst_cities' => $mstPossession,
            'country_id' => $countryId,
            'path_add' => 'master_possession_add',
            'path_edit' => 'master_possession_edit',
            'path_show' => 'master_possession_show',
            'label_title' => 'label.possession',
            'mode' => 'add'
        ]);
    }

    /**
     * @Route("/edit/{id}", name="edit", methods={"GET","POST"})
     * @param Request $request
     * @param MstPossession $mstPossession
     * @return Response
     */
    public function edit(Request $request, MstPossession $mstPossession): Response
    {
        $form = $this->createForm(MstPossessionType::class, $mstPossession);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->managerRegistry->getManager()->flush();
            $this->addFlash('success', 'form.updated_successfully');
            return $this->redirectToRoute('master_possession_index');
        }

        return $this->render('form/form.html.twig', [
            'master_possession' => $mstPossession,
            'form' => $form->createView(),
            'index_path' => 'master_possession_index',
            'label_title' => 'label.possession',
            'label_button' => 'label.update',
            'mode' => 'edit'
        ]);
    }

    /**
     * @Route("/{id}", name="delete", methods={"DELETE"})
     * @param Request $request
     * @param MstPossession $mstPossession
     * @return Response
     */
    public function delete(Request $request, MstPossession $mstPossession): Response
    {
        if ($this->isCsrfTokenValid('delete'.$mstPossession->getId(), $request->request->get('_token'))) {
            $entityManager = $this->managerRegistry->getManager();
            $entityManager->remove($mstPossession);
            $entityManager->flush();
        }

        return $this->redirectToRoute('master_possession_index');
    }
}
