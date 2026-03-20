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
            ->add('imageName', TextType::class, [
                'label' => 'Nom de l\'image (ex: img/projet1.jpg)',
                'attr' => ['placeholder' => 'screenshot1.png']
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
