<?php

namespace App\Controller\Cms;

use App\Entity\SystemApp\AppUser;
use App\Service\CommonHelper;
use App\Service\FileUploaderHelper;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;

use Exception;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use App\Entity\Cms\CmsArticle;
use App\Form\Cms\CmsArticleType;
use App\Repository\Cms\CmsArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Ramsey\Uuid\Uuid;

/**
 * @Route("/core/cms/article", name="cms_article_")
 * @IsGranted("ROLE_SYS_CONTENT_USER")
 */
class CmsArticleController extends AbstractController
{
    /**
     * @Route("/", name="index", methods={"GET"})
     * @param CmsArticleRepository $cmsArticleRepository
     * @return Response
     */
    public function index(CmsArticleRepository $cmsArticleRepository): Response
    {
        $articles = $cmsArticleRepository->findAll();
        return $this->render('cms/cms_article/index.html.twig', [
            'cms_articles' => $articles,
            'path_index' => 'cms_article_index',
            'path_add' => 'cms_article_add',
            'path_edit' => 'cms_article_edit',
            'path_comment' => 'cms_article_comment_index',
            'path_show' => 'cms_article_show',
            'label_title' => 'label.article',
        ]);
    }

    /**
     * @Route("/add", name="add", methods={"GET","POST"})
     * @param Request $request
     * @param CommonHelper $commonHelper
     * @param FileUploaderHelper $fileUploaderHelper
     * @return Response
     */
    public function new(Request $request, CommonHelper $commonHelper, FileUploaderHelper $fileUploaderHelper): Response
    {
        $cmsArticle = new CmsArticle();
        $option = array('image_required' => false);
        $form = $this->createForm(CmsArticleType::class, $cmsArticle, $option);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $cmsArticle->setArticleSlugName($commonHelper->slugify($form->get('articleTitle')->getData()));
            $cmsArticle->setRowId(Uuid::uuid4()->toString());
            $cmsArticle->setArticleCreateDateTime(new DateTime());
            $cmsArticle->setArticleCreatedBy($this->getDoctrine()->getRepository(AppUser::class)->find($this->getUser()));

            $cmsArticle->setArticleSlugName($commonHelper->slugify($form->get('articleTitle')->getData()));

            // Upload the OG Image for SEO
            $ogImageFile = $form['ogImage']->getData();
            if ($ogImageFile) {
                $newFilename = $fileUploaderHelper->uploadPublicFile($ogImageFile, $form['ogImage']->getData(),null);
                $cmsArticle->setOgImage($newFilename);
                $cmsArticle->setOgImagePath($this->getParameter('public_file_folder'));
            }

            // Set up Intro media
            $introMediaType = $form['introMediaType']->getData();

            // If Media Type is image
            if ($introMediaType == 'image') {
                // Upload the intro image for Article
                $articleIntroFile = $form['articleIntroImage']->getData();
                if ($articleIntroFile) {
                    $newFilename = $fileUploaderHelper->uploadPublicFile($articleIntroFile, $form['articleIntroImageSetName']->getData(), $existingIntroImage = null);
                    $cmsArticle->setArticleIntroImage($newFilename);
                    $cmsArticle->setArticleIntroImageSetName($form->get('articleIntroImageSetName')->getData());
                    $cmsArticle->setArticleIntroImagePath($this->getParameter('public_file_folder'));
                }
            }

            // If media type is video
            if ($introMediaType == 'video') {
                $cmsArticle->setArticleIntroVideo($form->get('articleIntroVideo')->getData());
                $cmsArticle->setArticleIntroVideoPath($form->get('articleIntroVideoPath')->getData());
            }

            foreach ($form->get('cmsArticleContent') as $key=>$content) {
                $cmsArticleContent = $cmsArticle->getCmsArticleContent()[$key];
                $cmsArticleContent->setArticleContent($content['articleContent']->getData());
                $cmsArticleContent->setMediaType('image');
                $mediaType = $cmsArticleContent->getMediaType();

                if ($mediaType == 'image' ){
                    $articleContentFile = $content['articleContentImage']->getData();
                    if ($articleContentFile) {
                        $newFilename = $fileUploaderHelper->uploadPublicFile($articleContentFile, $content['articleContentImageSetName']->getData(),$existingArticleContentFile = null);
                        $cmsArticleContent->setArticleContentImage($newFilename);
                        $cmsArticleContent->setArticleContentImageSetName($content['articleContentImageSetName']->getData());
                        $cmsArticleContent->setArticleContentImageAlt($content['articleContentImageAlt']->getData());
                        $cmsArticleContent->setArticleContentImageTitle($content['articleContentImageTitle']->getData());
                        $cmsArticleContent->setArticleContentImagePath($this->getParameter('public_file_folder'));
                    }
                }
                if ($mediaType == 'video' ){
                    $video = $content['articleContentVideo']->getData();
                    if ($video) {
                        $cmsArticleContent->setArticleContentVideo($content['articleContentVideo']->getData());
                        $cmsArticleContent->setArticleContentVideoPath($content['articleContentVideoPath']->getData());
                    }
                }
            }

            $entityManager->persist($cmsArticle);
            $entityManager->flush();
            $this->addFlash('success', 'form.created_successfully');
            return $this->redirectToRoute('cms_article_index');
        }
        return $this->render('cms/cms_article/form.html.twig', [
                'cms_article' => $cmsArticle,
                'form' => $form->createView(),
                'index_path' => 'cms_article_index',
                'label_title' => 'label.article',
                'label_button' => 'label.create',
                'mode' => 'add'
        ]);

    }

    /**
     * @Route("/{id}", name="show", methods={"GET"})
     * @param CmsArticle $cmsArticle
     * @return Response
     */
    public function show(CmsArticle $cmsArticle): Response
    {
        return $this->render('cms/cms_article/show.html.twig', [
            'data' => $cmsArticle,
            'index_path' => 'cms_article_delete',
            'label_button' => 'label.delete',
            'path_index' => 'cms_article_index',
            'path_edit' => 'cms_article_edit',
            'path_delete' => 'cms_article_delete',
            'label_title' => 'label.article'
        ]);
    }

    /**
     * @Route("/edit/{id}", name="edit", methods={"GET","POST"})
     * @param Request $request
     * @param CmsArticle $cmsArticle
     * @param CommonHelper $commonHelper
     * @param FileUploaderHelper $fileUploaderHelper
     * @return Response
     * @throws Exception|Exception
     */
    public function edit(Request $request, CmsArticle $cmsArticle, CommonHelper $commonHelper, FileUploaderHelper $fileUploaderHelper): Response
    {
        $existingOgImageFile = $cmsArticle->getOgImage();
        $existingIntroImage = $cmsArticle->getArticleIntroImage();
        $option = array('image_required' => false);
        $form = $this->createForm(CmsArticleType::class, $cmsArticle, $option);
        $form->handleRequest($request);

        // Get the existing Data
        $originalContent = new ArrayCollection();
        foreach ($cmsArticle->getCmsArticleContent() as $content)
        {
            $originalContent->add($content);
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            foreach($originalContent as $cntnt){
                if($cmsArticle->getCmsArticleContent()->contains($cntnt)==false){
                    $this->getDoctrine()->getManager()->remove($cntnt);
                }
            }
            $cmsArticle->setArticleSlugName($commonHelper->slugify($form->get('articleTitle')->getData()));

            // Upload the OG Image for SEO
            $ogImageFile = $form['ogImage']->getData();
            if ($ogImageFile) {
                if ($existingOgImageFile != '')
                {
                    $newFilename = $fileUploaderHelper->uploadPublicFile($ogImageFile, $form['ogImage']->getData(),$existingOgImageFile);
                } else {
                    $newFilename = $fileUploaderHelper->uploadPublicFile($ogImageFile, $form['ogImage']->getData(),null);
                }

                $cmsArticle->setOgImage($newFilename);
                $cmsArticle->setOgImagePath($this->getParameter('public_file_folder'));
            }

            // Set up Intro media
            $introMediaType = $form['introMediaType']->getData();
            if(!empty($_POST['cms_article_change_maker']['removeIntroImage']))
            {
                // Remove the image from the system
                $fileUploaderHelper->removeFile($cmsArticle->getArticleIntroImage());
                $cmsArticle->setIntroMediaType('');
                $cmsArticle->setArticleIntroImage('');
                $cmsArticle->setArticleIntroImageSetName('');
                $cmsArticle->setArticleIntroImageAlt('');
                $cmsArticle->setArticleIntroImageTitle('');
                $cmsArticle->setArticleIntroImagePath('');
            } else {
                // If Media Type is image
                if ($introMediaType == 'image') {
                    // Upload the intro image for Article
                    $articleIntroFile = $form['articleIntroImage']->getData();
                    $articleIntroFileOld = $cmsArticle->getArticleIntroImage();
                    if ($articleIntroFile) {
                        $newFilename = $fileUploaderHelper->uploadPublicFile($articleIntroFile, $form['articleIntroImageSetName']->getData(),$articleIntroFileOld);
                        $cmsArticle->setArticleIntroImage($newFilename);
                        $cmsArticle->setArticleIntroImageSetName($form->get('articleIntroImageSetName')->getData());
                        $cmsArticle->setArticleIntroImagePath($this->getParameter('public_file_folder'));
                    }
                }

            }
            // If media type is video
            if ($introMediaType == 'video') {
                $cmsArticle->setArticleIntroVideo($form->get('articleIntroVideo')->getData());
                $cmsArticle->setArticleIntroVideoPath($form->get('articleIntroVideoPath')->getData());
            }


            foreach ($form->get('cmsArticleContent') as $key=>$content) {

                $cmsArticleContent = $cmsArticle->getCmsArticleContent()[$key];
                $cmsArticleContent->setArticleContent($content['articleContent']->getData());
                $mediaType = $cmsArticleContent->getMediaType();
                $existingArticleContentFile = $cmsArticleContent->getArticleContentImage();
                if(!empty($_POST['cms_article_change_maker']['cmsArticleContent'][$key]['removeContentImage']))
                {
                    // Remove the image from the system
                    $fileUploaderHelper->removeFile($cmsArticleContent->getArticleContentImage());
                    $cmsArticleContent->setMediaType('');
                    $cmsArticleContent->setArticleContentImage('');
                    $cmsArticleContent->setArticleContentImageSetName('');
                    $cmsArticleContent->setArticleContentImageAlt('');
                    $cmsArticleContent->setArticleContentImageTitle('');
                    $cmsArticleContent->setArticleContentImagePath('');
                } else {

                    if ($mediaType == 'image' ){
                        $articleContentFile = $content['articleContentImage']->getData();
                        $articleContentFileOld = $cmsArticleContent->getArticleContentImage();
                        if ($articleContentFile) {
                            $newFilename = $fileUploaderHelper->uploadPublicFile($articleContentFile, $content['articleContentImageSetName']->getData(),$articleContentFileOld );
                            $cmsArticleContent->setArticleContentImage($newFilename);
                            $cmsArticleContent->setArticleContentImageSetName($content['articleContentImageSetName']->getData());
                            $cmsArticleContent->setArticleContentImageAlt($content['articleContentImageAlt']->getData());
                            $cmsArticleContent->setArticleContentImageTitle($content['articleContentImageTitle']->getData());
                            $cmsArticleContent->setArticleContentImagePath($this->getParameter('public_file_folder'));
                            $cmsArticleContent->setArticleContentVideo('');
                            $cmsArticleContent->setArticleContentVideoPath('');
                        }
                    }

                }
                if ($mediaType == 'video') {
                    $video = $content['articleContentVideo']->getData();
                    if ($video) {
                        $fileUploaderHelper->removeFile($existingArticleContentFile);
                        $cmsArticleContent->setArticleContentImage('');
                        $cmsArticleContent->setArticleContentImageSetName('');
                        $cmsArticleContent->setArticleContentImageAlt('');
                        $cmsArticleContent->setArticleContentImageTitle('');
                        $cmsArticleContent->setArticleContentImagePath('');
                        $cmsArticleContent->setArticleContentVideo($content['articleContentVideo']->getData());
                        $cmsArticleContent->setArticleContentVideoPath($content['articleContentVideoPath']->getData());
                    }
                }

            }
            $cmsArticle->setArticleUpdateDateTime(new DateTime());
            $cmsArticle->setArticleUpdatedBy($this->getDoctrine()->getRepository(AppUser::class)->find($this->getUser()));
            $entityManager->persist($cmsArticle);
            $entityManager->flush();

            $this->addFlash('success', 'form.updated_successfully');
            return $this->redirectToRoute('cms_article_index');
        }

        return $this->render('cms/cms_article/form.html.twig', [
            'cms_article' => $cmsArticle,
            'form' => $form->createView(),
            'index_path' => 'cms_blog_index',
            'label_title' => 'label.blog',
            'label_button' => 'label.update',
            'mode' => 'edit'
        ]);
    }
}
