<?php

namespace App\Form;

use App\Entity\CompteCourant;
use Doctrine\DBAL\Types\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType as TypeTextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CompteCourantType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('code', TypeTextType::class, [
                'attr' => ['class' => 'form-control col-md-6'], // col-md-6 sets the input to half the width
                'label' => 'Code',
                'label_attr' => ['class' => 'form-label']
            ])
            ->add('solde',TypeTextType::class, [
                'attr' => ['class' => 'form-control col-md-6'], // col-md-6 sets the input to half the width
                'label' => 'Solde',
                'label_attr' => ['class' => 'form-label']
            ])
            ->add('Client')
             ->add('save', SubmitType::class, [
                'label' => 'Enregistrer',
                'attr' => ['class' => 'btn btn-primary mt-4'], 
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CompteCourant::class,
        ]);
    }
}
