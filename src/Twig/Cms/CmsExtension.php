<?php


namespace App\Twig\Cms;

use App\Entity\Cms\CmsAdvertisement;
use App\Entity\Cms\CmsArticle;
use App\Entity\Cms\CmsArticleComment;
use App\Entity\Cms\CmsBanner;
use App\Entity\Cms\CmsFaq;
use App\Entity\Cms\CmsMedia;
use App\Entity\Cms\CmsPage;
use App\Entity\Cms\CmsUserTestimonial;
use App\Entity\Master\MstProductCategory;
use App\Entity\Master\MstProductSubType;
use App\Entity\Master\MstProductType;
use Doctrine\ORM\EntityManagerInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class CmsExtension  extends AbstractExtension
{
    private $em;
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @return array|TwigFunction[]
     */
    public function getFunctions(): array
    {
        return [
            new TwigFunction('get_cms_articles', [$this, 'getCmsArticle']),
            new TwigFunction('get_cms_articles_limit', [$this, 'getCmsArticleLimit']),
            new TwigFunction('get_cms_article_comments', [$this, 'getCmsArticleComment']),
            new TwigFunction('get_cms_article_comments_count', [$this, 'getCmsArticleCommentCount']),
            new TwigFunction('get_content_by_slugname', [$this, 'getContentBySlugName']),
            new TwigFunction('get_cms_page', [$this, 'getCmsPage']),
            new TwigFunction('get_cms_page_by_route', [$this, 'getCmsPageRoute']),
            new TwigFunction('get_cms_page_banner', [$this, 'getCmsPageBanner']),
            new TwigFunction('get_cms_page_ads', [$this, 'getCmsPageAds']),
            new TwigFunction('get_media', [$this, 'getCmsMedia']),
            new TwigFunction('get_testimonial', [$this, 'getCmsTestimonial']),
            new TwigFunction('get_pagecontent_by_route', [$this, 'getPageContentByRoute']),
            new TwigFunction('get_sub_product_by_route', [$this, 'getSubProductByRoute']),
            new TwigFunction('get_faqs', [$this, 'getFaqs']),
        ];
    }

    public function getCmsArticle($category_id, $company_id)
    {
        return $this->em->getRepository(CmsArticle::class)->findBy(['mstArticleCategory' => $category_id, 'orgCompany' => $company_id, 'isActive' => 1], ['articleCreateDateTime' => 'DESC']);
    }

    public function getCmsArticleLimit($category_id, $company_id)
    {
        return $this->em->getRepository(CmsArticle::class)->findBy(['mstArticleCategory' => $category_id, 'orgCompany' => $company_id, 'isActive' => 1], ['articleCreateDateTime' => 'DESC'], 4, 0);
    }

    public function getContentBySlugName($slugName)
    {
        return $this->em->getRepository(CmsArticle::class)->getContentBySlugName($slugName);
    }

    public function getCmsArticleComment($article_id, $is_approved)
    {
        $cmsArticleComment = $this->em->getRepository(CmsArticleComment::class)->findBy(['cmsArticle' => $article_id, 'isApproved' => $is_approved], ['commentDateTime' => 'DESC']);
        if ($cmsArticleComment) {
            foreach ($cmsArticleComment as $comment) {
                if (!empty($comment->getParentComment())) {
                    $parentId = $comment->getParentComment()->getId();
                    $id = $comment->getId();

                    $comments['reply'][$parentId][$id]['parentId'] = $parentId;
                    $comments['reply'][$parentId][$id]['comment'] = $comment->getArticleComment();
                    $comments['reply'][$parentId][$id]['name'] = $comment->getCommentorName();
                    $comments['reply'][$parentId][$id]['createtime'] = $comment->getCommentDateTime();
                }
                if (empty($comment->getParentComment())) {
                    $id = $comment->getId();
                    $comments['comment'][$id]['id'] = $comment->getId();
                    $comments['comment'][$id]['comment'] = $comment->getArticleComment();
                    $comments['comment'][$id]['name'] = $comment->getCommentorName();
                    $comments['comment'][$id]['createtime'] = $comment->getCommentDateTime();
                }
            }
        } else {
            $comments = [];
        }

        return $comments;
    }

    public function getCmsArticleCommentCount($article_id, $is_approved)
    {
        return $this->em->getRepository(CmsArticleComment::class)->getArticleCommentCount($article_id, $is_approved);
    }

    public function getCmsPage()
    {
        return $this->em->getRepository(CmsPage::class)->getCmsPage();
    }
    public function getCmsPageRoute($route)
    {
        return $this->em->getRepository(CmsPage::class)->findOneBy(['pageRoute'=>$route,'isActive'=>1]);
    }

    public function getCmsPageBanner($page_id)
    {
        return $this->em->getRepository(CmsBanner::class)->getBanner($page_id);
    }

    public function getCmsPageAds($page_id)
    {
        if ($page_id){
            return $this->em->getRepository(CmsAdvertisement::class)->getAdvertisement($page_id);
        }
        return [];
    }

    public function getCmsMedia($mediaType)
    {
        return $this->em->getRepository(CmsMedia::class)->getMedia($mediaType);
    }

    public function getCmsTestimonial()
    {
        return $this->em->getRepository(CmsUserTestimonial::class)->findBy(['isActive' => 1]);
    }

    public function getPageContentByRoute($route){
        return $this->em->getRepository(MstProductCategory::class)->findOneBy(['navRouting'=>$route,'isActive'=>1,'isPortal'=>1]);
    }

    public function getSubProductByRoute($productCategoryId)
    {
        return $this->em->getRepository(MstProductType::class)->findBy(['mstProductCategory'=>$productCategoryId,'isActive'=>1]);
    }

    public function getFaqs($faqId){
        return $this->em->getRepository(CmsFaq::class)->findOneBy(['id'=>$faqId]);
    }

}
