<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace EzSystems\EzPlatformContentForms\Form\Type\FieldType;

use eZ\Publish\API\Repository\FieldTypeService;
use eZ\Publish\Core\MVC\ConfigResolverInterface;
use EzSystems\EzPlatformContentForms\FieldType\DataTransformer\FieldValueTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Form Type representing ezurl field type.
 */
class UrlFieldType extends AbstractType
{
    /** @var FieldTypeService */
    protected $fieldTypeService;
    /**
     * @var ConfigResolverInterface
     */
    private $configResolver;

    public function __construct(FieldTypeService $fieldTypeService, ConfigResolverInterface $configResolver)
    {
        $this->fieldTypeService = $fieldTypeService;
        $this->configResolver = $configResolver;
    }

    public function getName()
    {
        return $this->getBlockPrefix();
    }

    public function getBlockPrefix()
    {
        return 'ezplatform_fieldtype_ezurl';
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'link',
                UrlType::class,
                [
                    'label' => /** @Desc("URL") */ 'content.field_type.ezurl.link',
                    'required' => $options['required'],
                    'default_protocol' => $this->configResolver->getParameter('ezurl_default_protocol'),
                ]
            )
            ->add(
                'text',
                TextType::class,
                [
                    'label' => /** @Desc("Text") */ 'content.field_type.ezurl.text',
                    'required' => false,
                ]
            )
            ->addModelTransformer(new FieldValueTransformer($this->fieldTypeService->getFieldType('ezurl')));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'translation_domain' => 'ezplatform_content_forms_fieldtype',
        ]);
    }
}
