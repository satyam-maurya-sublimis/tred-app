<?php

namespace App\Form\Cms;

use App\Entity\Cms\CmsPage;
use App\Entity\Cms\CmsPageContent;
use App\Entity\Product\PrdProduct;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class CmsPageType extends AbstractType
{
    private $em;
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('pageName', TextType::class,[
                'label' => 'label.name',
                'required' => true,
            ])
            ->add('pageTitle', TextType::class,[
                'label' => 'label.title',
                'required' => true,
            ])
            ->add('pageSlugName', TextType::class,[
                'label' => 'label.slug_name',
                'required' => true,
            ])
            ->add('pageCanonicalUrl', TextType::class,[
                'label' => 'label.canonical_url',
                'required' => false,
            ])
            ->add('cmsPageContent', CollectionType::class,[
                'label'=>false,
                'entry_type' => CmsPageContentType::class,
                'entry_options' => [
                    'label' => false,
                ],
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference'=>false,

            ])
            ->add('pageRoute', TextType::class,[
                'label' => 'Page Routing',
                'required' => false,
                'help' => 'Be careful while edit / updating this field, as it will effect the page url',
            ])
            ->add('parentId', ChoiceType::class,[
                'label' => 'label.parent_page',
                'choices' => $this->em->getRepository(CmsPage::class)->getParentPage(),
                'required' => false,
                'placeholder' => 'Parent Page (If applicable)',
            ])
            ->add('prdProduct', EntityType::class,[
                'label' => 'label.product',
                'class' => PrdProduct::class,
                'help' => 'Select if you want to link this page to a specific product',
                'required' => false,
                'placeholder' => 'Product (If applicable)',
            ])

            ->add('metaTitle', TextType::class,[
                'label' => 'label.meta_title',
                'required'=> false,
            ])

            ->add('seoSchema', TextareaType::class,[
                'label' => 'label.schema',
                'required'=> false,
            ])

            ->add('metaDescription', TextareaType::class,[
                'label' => 'label.meta_description',
                'required'=> false,
            ])

            ->add('metaKeyword', TextareaType::class,[
                'label' => 'label.meta_keyword',
                'required'=> false,
            ])

            ->add('focusKeyPhrase', TextareaType::class,[
                'label' => 'label.focus_key_phrase',
                'required'=> false,
            ])

            ->add('keyPhraseSynonyms', TextareaType::class,[
                'label' => 'label.key_phrase_synonyms',
                'required'=> false,
            ])

            ->add('ogTitle', TextType::class,[
                'label' => 'label.social_title',
                'required'=> false,
            ])
            ->add('ogDescription', TextareaType::class,[
                'label' => 'label.social_description',
                'required'=> false,
            ])
            ->add('ogType', TextType::class,[
                'label' => 'label.social_type',
                'data' => 'website',
                'required'=> false,
                'attr' => [
                    'readonly' => 'readonly'
                ]
            ])
            ->add('ogImage', FileType::class,[
                'mapped' => false,
                'label' => 'Upload Image File',
                'required' => false,
                'constraints' => [
                    new File([
                            'maxSize' => '3000k',
                            'maxSizeMessage' => 'The maximum file upload size is 3 mb.',
                            'mimeTypes' => [
                                'image/jpg',
                                'image/png',
                                'image/jpeg'
                            ],
                            'mimeTypesMessage' => 'Please upload a valid jpg or png file.',
                        ]
                    )
                ],
                'attr' => [
                    'placeholder' => 'Upload a file..',
                ]
            ])
            ->add('ogImageWidth', TextType::class,[
                'label' => 'label.og_width',
                'required'=> false,
            ])

            ->add('isActive', CheckboxType::class,[
                'required'=> false,
                'label' => 'label.is_active',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => CmsPage::class,
        ]);
    }
}
