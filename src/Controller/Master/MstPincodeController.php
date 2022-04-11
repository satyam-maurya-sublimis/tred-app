<?php

namespace App\Controller\Master;

use Exception;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use App\Entity\Master\MstPincode;
use App\Form\Master\MstPincodeType;
use App\Repository\Master\MstPincodeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Ramsey\Uuid\Uuid;

/**
 * @Route("/core/master/place/pincode", name="master_pincode_")
 * @IsGranted("ROLE_SYS_CONTENT_USER")
 */
class MstPincodeController extends AbstractController
{
    /**
     * @Route("/", name="index", methods={"GET"})
     * @param MstPincodeRepository $mstPincodeRepository
     * @param Request $request
     * @return Response
     */
    public function index(MstPincodeRepository $mstPincodeRepository, Request $request, PaginatorInterface $paginator): Response
    {
        $queryBuilder = $mstPincodeRepository->findAll();
        $pagination = $paginator->paginate(
            $queryBuilder,
            $request->query->getInt('page', 1),
            10
        );
        return $this->render('master/mst_pincode/index.html.twig', [
            'mst_pincodes' => $pagination,
            'path_index' => 'master_pincode_index',
            'path_add' => 'master_pincode_add',
            'path_edit' => 'master_pincode_edit',
            'path_show' => 'master_pincode_show',
            'label_title' => 'label.pincode',
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
        $mstPincode = new MstPincode();
        $form = $this->createForm(MstPincodeType::class, $mstPincode);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $mstPincode->setRowId(Uuid::uuid4()->toString());
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($mstPincode);
            $entityManager->flush();
            $this->addFlash('success', 'form.created_successfully');
            return $this->redirectToRoute('master_pincode_index');
        }

        return $this->render('form/form.html.twig', [
            'master_pincode' => $mstPincode,
            'form' => $form->createView(),
            'index_path' => 'master_pincode_index',
            'label_title' => 'label.pincode',
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
        $mstPincode = $request->query->get('pincodeSearch');
        $mstPincode = $this->getDoctrine()->getRepository(MstPincode::class)->findBy(["pincode"=>$mstPincode,'delivery'=>'Delivery']);
        return $this->render('master/mst_pincode/_ajax_listing.html.twig', [
            'mst_pincodes' => $mstPincode,
            'path_add' => 'master_pincode_add',
            'path_edit' => 'master_pincode_edit',
            'path_show' => 'master_pincode_show',
            'label_title' => 'label.pincode',
            'mode' => 'add'
        ]);
    }

    /**
     * @Route("/edit/{id}", name="edit", methods={"GET","POST"})
     * @param Request $request
     * @param MstPincode $mstPincode
     * @return Response
     */
    public function edit(Request $request, MstPincode $mstPincode): Response
    {
        $form = $this->createForm(MstPincodeType::class, $mstPincode);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', 'form.updated_successfully');
            return $this->redirectToRoute('master_pincode_index');
        }

        return $this->render('form/form.html.twig', [
            'master_pincode' => $mstPincode,
            'form' => $form->createView(),
            'index_path' => 'master_pincode_index',
            'label_title' => 'label.pincode',
            'label_button' => 'label.update',
            'mode' => 'edit'
        ]);
    }

    /**
     * @Route("/{id}", name="delete", methods={"DELETE"})
     * @param Request $request
     * @param MstPincode $mstPincode
     * @return Response
     */
    public function delete(Request $request, MstPincode $mstPincode): Response
    {
        if ($this->isCsrfTokenValid('delete'.$mstPincode->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($mstPincode);
            $entityManager->flush();
        }

        return $this->redirectToRoute('master_pincode_index');
    }
}
