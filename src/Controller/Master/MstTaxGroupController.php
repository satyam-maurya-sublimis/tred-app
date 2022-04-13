<?php

namespace App\Controller\Master;

use Doctrine\Persistence\ManagerRegistry;
use Exception;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use App\Entity\Master\MstTaxGroup;
use App\Form\Master\MstTaxGroupType;
use App\Repository\Master\MstTaxGroupRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Ramsey\Uuid\Uuid;

/**
 * @Route("/core/master/commercial/tax_group", name="master_tax_group_")
 * @IsGranted("ROLE_SYS_CONTENT_USER")
 */
class MstTaxGroupController extends AbstractController
{
    private ManagerRegistry $managerRegistry;

    public function __construct(ManagerRegistry $managerRegistry)
    {
        $this->managerRegistry = $managerRegistry;
    }
    /**
     * @Route("/", name="index", methods={"GET"})
     * @param MstTaxGroupRepository $mstTaxGroupRepository
     * @param Request $request
     * @return Response
     */
    public function index(MstTaxGroupRepository $mstTaxGroupRepository, Request $request): Response
    {
        $mstTaxGroup = $mstTaxGroupRepository->findAll();
        return $this->render('master/mst_tax_group/index.html.twig', [
            'mst_tax_groups' => $mstTaxGroup,
            'path_index' => 'master_tax_group_index',
            'path_add' => 'master_tax_group_add',
            'path_edit' => 'master_tax_group_edit',
            'path_show' => 'master_tax_group_show',
            'label_title' => 'label.tax_group',
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
        $mstTaxGroup = new MstTaxGroup();
        $form = $this->createForm(MstTaxGroupType::class, $mstTaxGroup);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $mstTaxGroup->setRowId(Uuid::uuid4()->toString());
            $entityManager = $this->managerRegistry->getManager();
            $entityManager->persist($mstTaxGroup);
            $entityManager->flush();
            $this->addFlash('success', 'form.created_successfully');
            return $this->redirectToRoute('master_tax_group_index');
        }

        return $this->render('form/form.html.twig', [
            'master_tax_group' => $mstTaxGroup,
            'form' => $form->createView(),
            'index_path' => 'master_tax_group_index',
            'label_title' => 'label.tax_group',
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
        $mstTaxGroup = ucwords($request->query->get('tax_groupSearch'));

        $mstTaxGroup = $this->managerRegistry->getRepository(MstTaxGroup::class)->getCityListByCountryId($mstTaxGroup, $countryId);
        return $this->render('master/mst_tax_group/_ajax_listing.html.twig', [
            'mst_cities' => $mstTaxGroup,
            'country_id' => $countryId,
            'path_add' => 'master_tax_group_add',
            'path_edit' => 'master_tax_group_edit',
            'path_show' => 'master_tax_group_show',
            'label_title' => 'label.tax_group',
            'mode' => 'add'
        ]);
    }

    /**
     * @Route("/edit/{id}", name="edit", methods={"GET","POST"})
     * @param Request $request
     * @param MstTaxGroup $mstTaxGroup
     * @return Response
     */
    public function edit(Request $request, MstTaxGroup $mstTaxGroup): Response
    {
        $form = $this->createForm(MstTaxGroupType::class, $mstTaxGroup);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->managerRegistry->getManager()->flush();
            $this->addFlash('success', 'form.updated_successfully');
            return $this->redirectToRoute('master_tax_group_index');
        }

        return $this->render('form/form.html.twig', [
            'master_tax_group' => $mstTaxGroup,
            'form' => $form->createView(),
            'index_path' => 'master_tax_group_index',
            'label_title' => 'label.tax_group',
            'label_button' => 'label.update',
            'mode' => 'edit'
        ]);
    }

    /**
     * @Route("/{id}", name="delete", methods={"DELETE"})
     * @param Request $request
     * @param MstTaxGroup $mstTaxGroup
     * @return Response
     */
    public function delete(Request $request, MstTaxGroup $mstTaxGroup): Response
    {
        if ($this->isCsrfTokenValid('delete'.$mstTaxGroup->getId(), $request->request->get('_token'))) {
            $entityManager = $this->managerRegistry->getManager();
            $entityManager->remove($mstTaxGroup);
            $entityManager->flush();
        }

        return $this->redirectToRoute('master_tax_group_index');
    }
}
