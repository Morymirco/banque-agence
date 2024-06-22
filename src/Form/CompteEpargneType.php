<?php

namespace App\Form;

use App\Entity\CompteEpargne;
use Doctrine\DBAL\Types\FloatType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CompteEpargneType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('code', IntegerType::class, [
                'attr' => ['class' => 'form-control col-md-6'], // col-md-6 sets the input to half the width
                'label' => 'Code',
                'label_attr' => ['class' => 'form-label']
            ])
            ->add('solde',TextType::class, [
                'attr' => ['class' => 'form-control col-md-6'], // col-md-6 sets the input to half the width
                'label' => 'Solde',
                'label_attr' => ['class' => 'form-label']
            ])
            ->add('tauxInteret', TextType::class, [
                'attr' => ['class' => 'form-control col-md-6'], // col-md-6 sets the input to half the width
                'label' => 'Taux Interet',
                'label_attr' => ['class' => 'form-label']
            ])
            
            ->add('Client')
              ->add('save', SubmitType::class, [
                'label' => 'Enregistrer',
                'attr' => ['class' => 'btn btn-primary mt-5'], 
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CompteEpargne::class,
        ]);
    }
}
