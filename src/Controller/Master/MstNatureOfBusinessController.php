<?php

namespace App\Controller\Master;

use Doctrine\Persistence\ManagerRegistry;
use Exception;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use App\Entity\Master\MstNatureOfBusiness;
use App\Form\Master\MstNatureOfBusinessType;
use App\Repository\Master\MstNatureOfBusinessRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Ramsey\Uuid\Uuid;

/**
 * @Route("/core/master/tred/nature_of_business", name="master_nature_of_business_")
 * @IsGranted("ROLE_SYS_CONTENT_USER")
 */
class MstNatureOfBusinessController extends AbstractController
{
    private ManagerRegistry $managerRegistry;

    public function __construct(ManagerRegistry $managerRegistry)
    {
        $this->managerRegistry = $managerRegistry;
    }
    /**
     * @Route("/", name="index", methods={"GET"})
     * @param MstNatureOfBusinessRepository $mstNatureOfBusinessRepository
     * @param Request $request
     * @return Response
     */
    public function index(MstNatureOfBusinessRepository $mstNatureOfBusinessRepository, Request $request): Response
    {
        $nature_of_businesses = $mstNatureOfBusinessRepository->findAll();
        return $this->render('master/mst_nature_of_business/index.html.twig', [
            'mst_nature_of_businesses' => $nature_of_businesses,
            'path_index' => 'master_nature_of_business_index',
            'path_add' => 'master_nature_of_business_add',
            'path_edit' => 'master_nature_of_business_edit',
            'path_show' => 'master_nature_of_business_show',
            'label_title' => 'label.nature_of_business',
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
        $mstNatureOfBusiness = new MstNatureOfBusiness();
        $form = $this->createForm(MstNatureOfBusinessType::class, $mstNatureOfBusiness);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $mstNatureOfBusiness->setRowId(Uuid::uuid4()->toString());
            $entityManager = $this->managerRegistry->getManager();
            $entityManager->persist($mstNatureOfBusiness);
            $entityManager->flush();
            $this->addFlash('success', 'form.created_successfully');
            return $this->redirectToRoute('master_nature_of_business_index');
        }

        return $this->render('form/form.html.twig', [
            'master_nature_of_business' => $mstNatureOfBusiness,
            'form' => $form->createView(),
            'index_path' => 'master_nature_of_business_index',
            'label_title' => 'label.nature_of_business',
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
        $nature_of_business = ucwords($request->query->get('nature_of_businessSearch'));

        $mstNatureOfBusiness = $this->managerRegistry->getRepository(MstNatureOfBusiness::class)->getCityListByCountryId($nature_of_business, $countryId);
        return $this->render('master/mst_nature_of_business/_ajax_listing.html.twig', [
            'mst_cities' => $mstNatureOfBusiness,
            'country_id' => $countryId,
            'path_add' => 'master_nature_of_business_add',
            'path_edit' => 'master_nature_of_business_edit',
            'path_show' => 'master_nature_of_business_show',
            'label_title' => 'label.nature_of_business',
            'mode' => 'add'
        ]);
    }

    /**
     * @Route("/edit/{id}", name="edit", methods={"GET","POST"})
     * @param Request $request
     * @param MstNatureOfBusiness $mstNatureOfBusiness
     * @return Response
     */
    public function edit(Request $request, MstNatureOfBusiness $mstNatureOfBusiness): Response
    {
        $form = $this->createForm(MstNatureOfBusinessType::class, $mstNatureOfBusiness);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->managerRegistry->getManager()->flush();
            $this->addFlash('success', 'form.updated_successfully');
            return $this->redirectToRoute('master_nature_of_business_index');
        }

        return $this->render('form/form.html.twig', [
            'master_nature_of_business' => $mstNatureOfBusiness,
            'form' => $form->createView(),
            'index_path' => 'master_nature_of_business_index',
            'label_title' => 'label.nature_of_business',
            'label_button' => 'label.update',
            'mode' => 'edit'
        ]);
    }

    /**
     * @Route("/{id}", name="delete", methods={"DELETE"})
     * @param Request $request
     * @param MstNatureOfBusiness $mstNatureOfBusiness
     * @return Response
     */
    public function delete(Request $request, MstNatureOfBusiness $mstNatureOfBusiness): Response
    {
        if ($this->isCsrfTokenValid('delete'.$mstNatureOfBusiness->getId(), $request->request->get('_token'))) {
            $entityManager = $this->managerRegistry->getManager();
            $entityManager->remove($mstNatureOfBusiness);
            $entityManager->flush();
        }

        return $this->redirectToRoute('master_nature_of_business_index');
    }
}
