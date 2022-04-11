<?php

namespace App\Controller\Master;

use Exception;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use App\Entity\Master\MstUserMemberType;
use App\Form\Master\MstUserMemberTypeType;
use App\Repository\Master\MstUserMemberTypeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Ramsey\Uuid\Uuid;

/**
 * @Route("/core/master/general/user_member_type", name="master_user_member_type_")
 * @IsGranted("ROLE_SYS_CONTENT_USER")
 */
class MstUserMemberTypeController extends AbstractController
{
    /**
     * @Route("/", name="index", methods={"GET"})
     * @param MstUserMemberTypeRepository $mstUserMemberTypeRepository
     * @param Request $request
     * @return Response
     */
    public function index(MstUserMemberTypeRepository $mstUserMemberTypeRepository, Request $request): Response
    {
        $user_member_types = $mstUserMemberTypeRepository->findAll();
        return $this->render('master/mst_user_member_type/index.html.twig', [
            'user_member_types' => $user_member_types,
            'path_index' => 'master_user_member_type_index',
            'path_add' => 'master_user_member_type_add',
            'path_edit' => 'master_user_member_type_edit',
            'path_show' => 'master_user_member_type_show',
            'label_title' => 'label.user_member_type',
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
        $mstUserMemberType = new MstUserMemberType();
        $form = $this->createForm(MstUserMemberTypeType::class, $mstUserMemberType);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $mstUserMemberType->setRowId(Uuid::uuid4()->toString());
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($mstUserMemberType);
            $entityManager->flush();
            $this->addFlash('success', 'form.created_successfully');
            return $this->redirectToRoute('master_user_member_type_index');
        }

        return $this->render('form/form.html.twig', [
            'master_user_member_type' => $mstUserMemberType,
            'form' => $form->createView(),
            'index_path' => 'master_user_member_type_index',
            'label_title' => 'label.user_member_type',
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
        $user_member_type = ucwords($request->query->get('user_member_typeSearch'));

        $mstUserMemberType = $this->getDoctrine()->getRepository(MstUserMemberType::class)->getCityListByCountryId($user_member_type, $countryId);
        return $this->render('master/mst_user_member_type/_ajax_listing.html.twig', [
            'mst_cities' => $mstUserMemberType,
            'country_id' => $countryId,
            'path_add' => 'master_user_member_type_add',
            'path_edit' => 'master_user_member_type_edit',
            'path_show' => 'master_user_member_type_show',
            'label_title' => 'label.user_member_type',
            'mode' => 'add'
        ]);
    }

    /**
     * @Route("/edit/{id}", name="edit", methods={"GET","POST"})
     * @param Request $request
     * @param MstUserMemberType $mstUserMemberType
     * @return Response
     */
    public function edit(Request $request, MstUserMemberType $mstUserMemberType): Response
    {
        $form = $this->createForm(MstUserMemberTypeType::class, $mstUserMemberType);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', 'form.updated_successfully');
            return $this->redirectToRoute('master_user_member_type_index');
        }

        return $this->render('form/form.html.twig', [
            'master_user_member_type' => $mstUserMemberType,
            'form' => $form->createView(),
            'index_path' => 'master_user_member_type_index',
            'label_title' => 'label.user_member_type',
            'label_button' => 'label.update',
            'mode' => 'edit'
        ]);
    }

    /**
     * @Route("/{id}", name="delete", methods={"DELETE"})
     * @param Request $request
     * @param MstUserMemberType $mstUserMemberType
     * @return Response
     */
    public function delete(Request $request, MstUserMemberType $mstUserMemberType): Response
    {
        if ($this->isCsrfTokenValid('delete'.$mstUserMemberType->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($mstUserMemberType);
            $entityManager->flush();
        }

        return $this->redirectToRoute('master_user_member_type_index');
    }
}
