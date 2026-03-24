<?php

namespace App\Form;

use App\Entity\ProjectImage;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProjectImageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('file', \Symfony\Component\Form\Extension\Core\Type\FileType::class, [
                'label' => 'Sélectionner une image',
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
                        mimeTypesMessage: 'Veuillez uploader une image valide'
                    )
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ProjectImage::class,
        ]);
    }
}
