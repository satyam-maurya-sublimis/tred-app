<?php

namespace App\Controller\Master;

use Doctrine\Persistence\ManagerRegistry;
use Exception;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use App\Entity\Master\MstTax;
use App\Form\Master\MstTaxType;
use App\Repository\Master\MstTaxRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Ramsey\Uuid\Uuid;

/**
 * @Route("/core/master/commercial/tax", name="master_tax_")
 * @IsGranted("ROLE_SYS_CONTENT_USER")
 */
class MstTaxController extends AbstractController
{
    private ManagerRegistry $managerRegistry;

    public function __construct(ManagerRegistry $managerRegistry)
    {
        $this->managerRegistry = $managerRegistry;
    }
    /**
     * @Route("/", name="index", methods={"GET"})
     * @param MstTaxRepository $mstTaxRepository
     * @param Request $request
     * @return Response
     */
    public function index(MstTaxRepository $mstTaxRepository, Request $request): Response
    {
        $mstTax = $mstTaxRepository->findAll();
        return $this->render('master/mst_tax/index.html.twig', [
            'mst_taxes' => $mstTax,
            'path_index' => 'master_tax_index',
            'path_add' => 'master_tax_add',
            'path_edit' => 'master_tax_edit',
            'path_show' => 'master_tax_show',
            'label_title' => 'label.tax',
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
        $mstTax = new MstTax();
        $form = $this->createForm(MstTaxType::class, $mstTax);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $mstTax->setRowId(Uuid::uuid4()->toString());
            $entityManager = $this->managerRegistry->getManager();
            $entityManager->persist($mstTax);
            $entityManager->flush();
            $this->addFlash('success', 'form.created_successfully');
            return $this->redirectToRoute('master_tax_index');
        }

        return $this->render('form/form.html.twig', [
            'master_tax' => $mstTax,
            'form' => $form->createView(),
            'index_path' => 'master_tax_index',
            'label_title' => 'label.tax',
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
        $mstTax = ucwords($request->query->get('taxSearch'));

        $mstTax = $this->managerRegistry->getRepository(MstTax::class)->getCityListByCountryId($mstTax, $countryId);
        return $this->render('master/mst_tax/_ajax_listing.html.twig', [
            'mst_cities' => $mstTax,
            'country_id' => $countryId,
            'path_add' => 'master_tax_add',
            'path_edit' => 'master_tax_edit',
            'path_show' => 'master_tax_show',
            'label_title' => 'label.tax',
            'mode' => 'add'
        ]);
    }

    /**
     * @Route("/edit/{id}", name="edit", methods={"GET","POST"})
     * @param Request $request
     * @param MstTax $mstTax
     * @return Response
     */
    public function edit(Request $request, MstTax $mstTax): Response
    {
        $form = $this->createForm(MstTaxType::class, $mstTax);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->managerRegistry->getManager()->flush();
            $this->addFlash('success', 'form.updated_successfully');
            return $this->redirectToRoute('master_tax_index');
        }

        return $this->render('form/form.html.twig', [
            'master_tax' => $mstTax,
            'form' => $form->createView(),
            'index_path' => 'master_tax_index',
            'label_title' => 'label.tax',
            'label_button' => 'label.update',
            'mode' => 'edit'
        ]);
    }

    /**
     * @Route("/{id}", name="delete", methods={"DELETE"})
     * @param Request $request
     * @param MstTax $mstTax
     * @return Response
     */
    public function delete(Request $request, MstTax $mstTax): Response
    {
        if ($this->isCsrfTokenValid('delete'.$mstTax->getId(), $request->request->get('_token'))) {
            $entityManager = $this->managerRegistry->getManager();
            $entityManager->remove($mstTax);
            $entityManager->flush();
        }

        return $this->redirectToRoute('master_tax_index');
    }
}
