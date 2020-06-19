<?php

namespace App\Form;

use App\Entity\Branch;
use App\Entity\Picture;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PictureType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom'
            ])
            ->add('branches', EntityType::class, [
                'class' => Branch::class,
                'choice_label' => 'name',
                'multiple' => true
            ])
            ->add('keywords', TextType::class, [
                'label' => 'Mots clés',
                'required' => false
            ])
            ->add('copyright', TextType::class, [
                'label' => 'Copyright',
                'required' => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Picture::class,
        ]);
    }
}
