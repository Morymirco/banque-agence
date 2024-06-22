<?php
namespace App\Form;

use App\Entity\Agence;
use App\Entity\Client;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ClientType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('numeroClient', TextType::class, [
                'attr' => ['class' => 'form-control col-md-6'], // col-md-6 sets the input to half the width
                'label' => 'Numéro de Client',
                'label_attr' => ['class' => 'form-label']
            ])
            ->add('nomClient', TextType::class, [
                'attr' => ['class' => 'form-control col-md-6'],
                'label' => 'Nom',
                'label_attr' => ['class' => 'form-label']
            ])
            ->add('prenomClient', TextType::class, [
                'attr' => ['class' => 'form-control col-md-6'],
                'label' => 'Prénom',
                'label_attr' => ['class' => 'form-label']
            ])
            ->add('sexeClient', ChoiceType::class, [
                'choices'  => [
                    'Homme' => 'Homme',
                    'Femme' => 'Femme',
                ],
                'attr' => ['class' => 'form-control col-md-6'],
                'label' => 'Sexe',
                'label_attr' => ['class' => 'form-label']
            ])
            ->add('telephoneClient', TextType::class, [
                'attr' => ['class' => 'form-control col-md-6'],
                'label' => 'Téléphone',
                'label_attr' => ['class' => 'form-label']
            ])
          ->add('Agence')
            ->add('save', SubmitType::class, [
                'label' => 'Enregistrer',
                'attr' => ['class' => 'btn btn-primary mt-5'], 
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Client::class,
        ]);
    }
}
