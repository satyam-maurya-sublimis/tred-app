<?php

namespace App\Controller\Master;

use Doctrine\Persistence\ManagerRegistry;
use Exception;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use App\Entity\Master\MstTaxOption;
use App\Form\Master\MstTaxOptionType;
use App\Repository\Master\MstTaxOptionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Ramsey\Uuid\Uuid;

/**
 * @Route("/core/master/commercial/tax_option", name="master_tax_option_")
 * @IsGranted("ROLE_SYS_CONTENT_USER")
 */
class MstTaxOptionController extends AbstractController
{
    private ManagerRegistry $managerRegistry;

    public function __construct(ManagerRegistry $managerRegistry)
    {
        $this->managerRegistry = $managerRegistry;
    }
    /**
     * @Route("/", name="index", methods={"GET"})
     * @param MstTaxOptionRepository $mstTaxOptionRepository
     * @param Request $request
     * @return Response
     */
    public function index(MstTaxOptionRepository $mstTaxOptionRepository, Request $request): Response
    {
        $mstTaxOption = $mstTaxOptionRepository->findAll();
        return $this->render('master/mst_tax_option/index.html.twig', [
            'mst_tax_options' => $mstTaxOption,
            'path_index' => 'master_tax_option_index',
            'path_add' => 'master_tax_option_add',
            'path_edit' => 'master_tax_option_edit',
            'path_show' => 'master_tax_option_show',
            'label_title' => 'label.tax_option',
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
        $mstTaxOption = new MstTaxOption();
        $form = $this->createForm(MstTaxOptionType::class, $mstTaxOption);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $mstTaxOption->setRowId(Uuid::uuid4()->toString());
            $entityManager = $this->managerRegistry->getManager();
            $entityManager->persist($mstTaxOption);
            $entityManager->flush();
            $this->addFlash('success', 'form.created_successfully');
            return $this->redirectToRoute('master_tax_option_index');
        }

        return $this->render('form/form.html.twig', [
            'master_tax_option' => $mstTaxOption,
            'form' => $form->createView(),
            'index_path' => 'master_tax_option_index',
            'label_title' => 'label.tax_option',
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
        $mstTaxOption = ucwords($request->query->get('tax_optionSearch'));

        $mstTaxOption = $this->managerRegistry->getRepository(MstTaxOption::class)->getCityListByCountryId($mstTaxOption, $countryId);
        return $this->render('master/mst_tax_option/_ajax_listing.html.twig', [
            'mst_cities' => $mstTaxOption,
            'country_id' => $countryId,
            'path_add' => 'master_tax_option_add',
            'path_edit' => 'master_tax_option_edit',
            'path_show' => 'master_tax_option_show',
            'label_title' => 'label.tax_option',
            'mode' => 'add'
        ]);
    }

    /**
     * @Route("/edit/{id}", name="edit", methods={"GET","POST"})
     * @param Request $request
     * @param MstTaxOption $mstTaxOption
     * @return Response
     */
    public function edit(Request $request, MstTaxOption $mstTaxOption): Response
    {
        $form = $this->createForm(MstTaxOptionType::class, $mstTaxOption);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->managerRegistry->getManager()->flush();
            $this->addFlash('success', 'form.updated_successfully');
            return $this->redirectToRoute('master_tax_option_index');
        }

        return $this->render('form/form.html.twig', [
            'master_tax_option' => $mstTaxOption,
            'form' => $form->createView(),
            'index_path' => 'master_tax_option_index',
            'label_title' => 'label.tax_option',
            'label_button' => 'label.update',
            'mode' => 'edit'
        ]);
    }

    /**
     * @Route("/{id}", name="delete", methods={"DELETE"})
     * @param Request $request
     * @param MstTaxOption $mstTaxOption
     * @return Response
     */
    public function delete(Request $request, MstTaxOption $mstTaxOption): Response
    {
        if ($this->isCsrfTokenValid('delete'.$mstTaxOption->getId(), $request->request->get('_token'))) {
            $entityManager = $this->managerRegistry->getManager();
            $entityManager->remove($mstTaxOption);
            $entityManager->flush();
        }

        return $this->redirectToRoute('master_tax_option_index');
    }
}
