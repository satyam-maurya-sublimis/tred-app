<?php


namespace App\Twig\Cms;

use App\Entity\Cms\CmsBanner;
use App\Entity\Cms\CmsPage;
use Doctrine\ORM\EntityManagerInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class CmsPageExtension  extends AbstractExtension
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
            new TwigFunction('get_cms_page_content_by_page', [$this, 'getCmsPageContentByPage']),
            new TwigFunction('get_cms_page_content_by_slugname', [$this, 'getCmsPageContentBySlugName']),
            new TwigFunction('get_cms_page_banner', [$this, 'getCmsPageBanner']),
        ];
    }

    public function getCmsPageContentByPage($page)
    {
        return $this->em->getRepository(CmsPage::class)->getContentByPage($page);
    }

    public function getCmsPageContentBySlugName($page)
    {
        return $this->em->getRepository(CmsPage::class)->getContentBySlugName($page);
    }

    public function getCmsPageBanner($page_id)
    {
        return $this->em->getRepository(CmsBanner::class)->getBanner($page_id);
    }

}