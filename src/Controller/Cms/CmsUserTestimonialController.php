<?php

namespace App\Controller\Cms;

use App\Entity\Cms\CmsUserTestimonial;
use App\Form\Cms\CmsUserTestimonialType;
use App\Repository\Cms\CmsUserTestimonialRepository;
use App\Service\CommonHelper;
use App\Service\FileUploaderHelper;
use DateTime;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\Validator\Constraints\Timezone;

/**
 * @Route("/core/cms/usertestimonial", name="cms_usertestimonial_")
 * @IsGranted("ROLE_SYS_CONTENT_USER")
 */
class CmsUserTestimonialController extends AbstractController
{
    /**
     * @Route("/", name="index", methods={"GET"})
     * @param CmsUserTestimonialRepository $cmsUserTestimonialRepository
     * @param PaginatorInterface $paginator
     * @param Request $request
     * @return Response
     */
    public function index(CmsUserTestimonialRepository $cmsUserTestimonialRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $queryBuilder = $cmsUserTestimonialRepository->getUserTestimonial();
        $pagination = $paginator->paginate(
            $queryBuilder,
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('cms/cms_user_testimonial/index.html.twig', [
            'cms_user_testimonials' => $pagination,
            'path_index' => 'cms_usertestimonial_index',
            'path_add' => 'cms_usertestimonial_add',
            'path_edit' => 'cms_usertestimonial_edit',
            'path_show' => 'cms_usertestimonial_show',
            'label_title' => 'label.testimonial',
            'label_heading' => 'label.testimonial',
        ]);
    }

    /**
     * @Route("/add", name="add", methods={"GET","POST"})
     * @param Request $request
     * @param FileUploaderHelper $fileUploaderHelper
     * @param CommonHelper $commonHelper
     * @return Response
     * @throws \Exception
     */
    public function add(Request $request, FileUploaderHelper $fileUploaderHelper, CommonHelper $commonHelper): Response
    {
        $cmsUserTestimonial = new CmsUserTestimonial();
        $form = $this->createForm(CmsUserTestimonialType::class, $cmsUserTestimonial);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $mediaFile = $form['userImage']->getData();
            if ($mediaFile)
            {
                $newFilename = $fileUploaderHelper->uploadPublicFile($mediaFile, $commonHelper->slugify($form['userImage']->getData()), $existingMedia = null);
                $cmsUserTestimonial->setUserImage($newFilename);
                $cmsUserTestimonial->setUserImagePath($this->getParameter('public_file_folder'));
            }
            $cmsUserTestimonial->setCreateDateTime(New DateTime('now', new \DateTimeZone('UTC')));
            $entityManager->persist($cmsUserTestimonial);
            $entityManager->flush();
            $this->addFlash('success', 'form.created_successfully');
            return $this->redirectToRoute('cms_usertestimonial_index');
        }

        return $this->render('cms/cms_user_testimonial/form.html.twig', [
            'cms_user_testimonial' => $cmsUserTestimonial,
            'form' => $form->createView(),
            'index_path' => 'cms_usertestimonial_index',
            'label_title' => 'label.testimonial',
            'label_button' => 'label.update',
            'mode' => 'edit'
        ]);
    }

    /**
     * @Route("/edit/{id}", name="edit", methods={"GET","POST"})
     * @param Request $request
     * @param CmsUserTestimonial $cmsUserTestimonial
     * @param FileUploaderHelper $fileUploaderHelper
     * @param CommonHelper $commonHelper
     * @return Response
     * @throws \Exception
     */
    public function edit(Request $request, CmsUserTestimonial $cmsUserTestimonial, FileUploaderHelper $fileUploaderHelper, CommonHelper $commonHelper): Response
    {
        $existingMedia = $cmsUserTestimonial->getUserImage();
        $form = $this->createForm(CmsUserTestimonialType::class, $cmsUserTestimonial);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if(!empty($_POST['cms_user_testimonial']['removeImage']))
            {
                $fileUploaderHelper->removeFile($cmsUserTestimonial->getUserImage());
                $cmsUserTestimonial->setUserImage('');
                $cmsUserTestimonial->setUserImagePath('');

            } else {
                $mediaFile = $form['userImage']->getData();
                if ($mediaFile)
                {
                    if($existingMedia != '')
                    {
                        $newFilename = $fileUploaderHelper->uploadPublicFile($mediaFile, $commonHelper->slugify($form['userImage']->getData()), $existingMedia);
                    } else {
                        $newFilename = $fileUploaderHelper->uploadPublicFile($mediaFile, $commonHelper->slugify($form['userImage']->getData()), $existingMedia = null);
                    }
                    $cmsUserTestimonial->setUserImage($newFilename);
                    $cmsUserTestimonial->setUserImagePath($this->getParameter('public_file_folder'));
                }

            }
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', 'form.updated_successfully');
            return $this->redirectToRoute('cms_usertestimonial_index');
        }

        return $this->render('cms/cms_user_testimonial/form.html.twig', [
            'cms_user_testimonial' => $cmsUserTestimonial,
            'form' => $form->createView(),
            'index_path' => 'cms_usertestimonial_index',
            'label_title' => 'label.testimonial',
            'label_button' => 'label.update',
            'mode' => 'edit'
        ]);
    }

}
