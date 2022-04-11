<?php

namespace App\Controller\Cms;

use App\Service\CommonHelper;
use App\Service\FileUploaderHelper;
use Doctrine\Common\Collections\ArrayCollection;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use App\Entity\Cms\CmsPage;
use App\Form\Cms\CmsPageType;
use App\Repository\Cms\CmsPageRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Ramsey\Uuid\Uuid;

/**
 * @Route("/core/cms/page", name="cms_page_")
 * @IsGranted("ROLE_SYS_CONTENT_USER")
 */
class CmsPageController extends AbstractController
{
    /**
     * @Route("/", name="index", methods={"GET"})
     * @param CmsPageRepository $cmsPageRepository
     * @return Response
     */
    public function index(CmsPageRepository $cmsPageRepository): Response
    {
        $pages = $cmsPageRepository->findAll();
        return $this->render('cms/cms_page/index.html.twig', [
            'cms_pages' => $pages,
            'path_index' => 'cms_page_index',
            'path_add' => 'cms_page_add',
            'path_edit' => 'cms_page_edit',
            'path_banner' => 'cms_banner_index',
            'path_show' => 'cms_page_show',
            'label_title' => 'label.page',
            'path_advertisement' => 'cms_advertisement_index',
        ]);
    }

    /**
     * @Route("/add", name="add", methods={"GET","POST"})
     * @param Request $request
     * @param CommonHelper $commonHelper
     * @param FileUploaderHelper $fileUploaderHelper
     * @return Response
     * @throws \Exception
     */
    public function new(Request $request, CommonHelper $commonHelper, FileUploaderHelper $fileUploaderHelper): Response
    {
        $cmsPage = new CmsPage();
        $form = $this->createForm(CmsPageType::class, $cmsPage);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $cmsPage->setRowId(Uuid::uuid4()->toString());
            $cmsPage->setPageSlugName($commonHelper->slugify($form->get('pageSlugName')->getData()));
            if ($cmsPage->getParentId() == 'null' || $cmsPage->getParentId() == '') {
                $cmsPage->setParentId('0');
            }

            // Upload the OG Image for SEO
            $ogImageFile = $form['ogImage']->getData();
            if ($ogImageFile) {
                $newFilename = $fileUploaderHelper->uploadPublicFile($ogImageFile, $form['ogImage']->getData(),null);
                $cmsPage->setOgImage($newFilename);
                $cmsPage->setOgImagePath($this->getParameter('public_file_folder'));
            }

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($cmsPage);
            $entityManager->flush();
            $this->addFlash('success', 'form.created_successfully');
            return $this->redirectToRoute('cms_page_index');
        }
        return $this->render('cms/cms_page/form.html.twig', [
            'cms_page' => $cmsPage,
            'form' => $form->createView(),
            'index_path' => 'cms_page_index',
            'label_title' => 'label.page',
            'label_button' => 'label.create',
            'mode' => 'add'
        ]);
    }

    /**
     * @Route("/{id}", name="show", methods={"GET"})
     * @param CmsPage $cmsPage
     * @return Response
     */
    public function show(CmsPage $cmsPage): Response
    {
        return $this->render('cms/cms_page/show.html.twig', [
            'data' => $cmsPage,
            'index_path' => 'cms_page_delete',
            'label_button' => 'label.delete',
            'path_index' => 'cms_page_index',
            'path_edit' => 'cms_page_edit',
            'path_delete' => 'cms_page_delete',
        ]);
    }

    /**
     * @Route("/edit/{id}", name="edit", methods={"GET","POST"})
     * @param Request $request
     * @param CmsPage $cmsPage
     * @param CommonHelper $commonHelper
     * @param FileUploaderHelper $fileUploaderHelper
     * @return Response
     * @throws \Exception
     */
    public function edit(Request $request, CmsPage $cmsPage, CommonHelper $commonHelper, FileUploaderHelper $fileUploaderHelper): Response
    {
        $existingOgImageFile = $cmsPage->getOgImage();
        $form = $this->createForm(CmsPageType::class, $cmsPage);
        $form->handleRequest($request);

        // Get the existing Data
        $originalContent = new ArrayCollection();
        foreach ($cmsPage->getCmsPageContent() as $content)
        {
            $originalContent->add($content);
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $cmsPage->setPageSlugName($commonHelper->slugify($form->get('pageSlugName')->getData()));
            if ($cmsPage->getParentId() == 'null' || $cmsPage->getParentId() == '') {
                $cmsPage->setParentId('0');
            }
            foreach ($originalContent as $content)
            {
                if($cmsPage->getCmsPageContent()->contains($content) == false) {
                    $this->getDoctrine()->getManager()->remove($content);
                }
            }
            // Upload the OG Image for SEO
            $ogImageFile = $form['ogImage']->getData();
            if ($ogImageFile) {
                if ($existingOgImageFile != '')
                {
                    $newFilename = $fileUploaderHelper->uploadPublicFile($ogImageFile, $form['ogImage']->getData(),$existingOgImageFile);
                } else {
                    $newFilename = $fileUploaderHelper->uploadPublicFile($ogImageFile, $form['ogImage']->getData(),null);
                }

                $cmsPage->setOgImage($newFilename);
                $cmsPage->setOgImagePath($this->getParameter('public_file_folder'));
            }

            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', 'form.updated_successfully');
            return $this->redirectToRoute('cms_page_index');
        }

        return $this->render('cms/cms_page/form.html.twig', [
            'cms_page' => $cmsPage,
            'form' => $form->createView(),
            'index_path' => 'cms_page_index',
            'label_title' => 'label.page',
            'label_button' => 'label.update',
            'mode' => 'edit'
        ]);
    }

}
