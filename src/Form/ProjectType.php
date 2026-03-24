<?php

namespace App\Form;

use App\Entity\Project;
use App\Form\ProjectImageType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProjectType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title')
            ->add('description')
            ->add('contexte')
            ->add('imageFile', \Symfony\Component\Form\Extension\Core\Type\FileType::class, [
                'label' => 'Image principale (fichier)',
                'required' => false,
                'constraints' => [
                    new \Symfony\Component\Validator\Constraints\File(
                        maxSize: '5M',
                        mimeTypes: [
                            'image/jpeg',
                            'image/png',
                            'image/gif',
                            'image/webp'
                        ],
                        mimeTypesMessage: 'Veuillez uploader une image valide (JPEG, PNG, GIF, WEBP)'
                    )
                ],
            ])
            ->add('link')
            ->add('technologies', null, [
                'label' => 'Technologies (séparées par des virgules)',
                'attr' => ['placeholder' => 'ex: Symfony, React, Docker']
            ])
            ->add('duration')
            ->add('images', CollectionType::class, [
                'entry_type' => ProjectImageType::class,
                'entry_options' => ['label' => false],
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'label' => 'Images supplémentaires (Galerie)',
                'prototype' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Project::class,
        ]);
    }
}
