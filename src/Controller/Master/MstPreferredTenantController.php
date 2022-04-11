<?php

namespace App\Controller\Master;

use Exception;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use App\Entity\Master\MstPreferredTenant;
use App\Form\Master\MstPreferredTenantType;
use App\Repository\Master\MstPreferredTenantRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Ramsey\Uuid\Uuid;

/**
 * @Route("/core/master/general/tenants", name="master_preferred_tenant_")
 * @IsGranted("ROLE_SYS_CONTENT_USER")
 */
class MstPreferredTenantController extends AbstractController
{
    /**
     * @Route("/", name="index", methods={"GET"})
     * @param MstPreferredTenantRepository $mstPreferredTenantRepository
     * @param Request $request
     * @return Response
     */
    public function index(MstPreferredTenantRepository $mstPreferredTenantRepository, Request $request): Response
    {
        $preferred_tenant = $mstPreferredTenantRepository->findAll();
        return $this->render('master/mst_preferred_tenant/index.html.twig', [
            'mst_preferred_tenants' => $preferred_tenant,
            'path_index' => 'master_preferred_tenant_index',
            'path_add' => 'master_preferred_tenant_add',
            'path_edit' => 'master_preferred_tenant_edit',
            'path_show' => 'master_preferred_tenant_show',
            'label_title' => 'label.preferred_tenant',
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
        $mstPreferredTenant = new MstPreferredTenant();
        $form = $this->createForm(MstPreferredTenantType::class, $mstPreferredTenant);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $mstPreferredTenant->setRowId(Uuid::uuid4()->toString());
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($mstPreferredTenant);
            $entityManager->flush();
            $this->addFlash('success', 'form.created_successfully');
            return $this->redirectToRoute('master_preferred_tenant_index');
        }

        return $this->render('form/form.html.twig', [
            'master_preferred_tenant' => $mstPreferredTenant,
            'form' => $form->createView(),
            'index_path' => 'master_preferred_tenant_index',
            'label_title' => 'label.preferred_tenant',
            'label_button' => 'label.create',
            'mode' => 'add'
        ]);
    }

    /**
     * @Route("/edit/{id}", name="edit", methods={"GET","POST"})
     * @param Request $request
     * @param MstPreferredTenant $mstPreferredTenant
     * @return Response
     */
    public function edit(Request $request, MstPreferredTenant $mstPreferredTenant): Response
    {
        $form = $this->createForm(MstPreferredTenantType::class, $mstPreferredTenant);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', 'form.updated_successfully');
            return $this->redirectToRoute('master_preferred_tenant_index');
        }

        return $this->render('form/form.html.twig', [
            'master_preferred_tenant' => $mstPreferredTenant,
            'form' => $form->createView(),
            'index_path' => 'master_preferred_tenant_index',
            'label_title' => 'label.preferred_tenant',
            'label_button' => 'label.update',
            'mode' => 'edit'
        ]);
    }

    /**
     * @Route("/{id}", name="delete", methods={"DELETE"})
     * @param Request $request
     * @param MstPreferredTenant $mstPreferredTenant
     * @return Response
     */
    public function delete(Request $request, MstPreferredTenant $mstPreferredTenant): Response
    {
        if ($this->isCsrfTokenValid('delete'.$mstPreferredTenant->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($mstPreferredTenant);
            $entityManager->flush();
        }

        return $this->redirectToRoute('master_preferred_tenant_index');
    }
}
