<?php

// src/Form/TransferType.php

namespace App\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Entity\Compte;

class TransferType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('source', EntityType::class, [
                'class' => Compte::class,
                'choice_label' => 'id', // Choisissez une étiquette appropriée pour identifier le compte
                'label' => 'Compte Source'
            ])
            ->add('destination', EntityType::class, [
                'class' => Compte::class,
                'choice_label' => 'id', // Choisissez une étiquette appropriée pour identifier le compte
                'label' => 'Compte Destination'
            ])
            ->add('amount', MoneyType::class, [
                'currency' => 'GNF',
                'label' => 'Montant'
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Transférer'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configurez les options du formulaire ici
        ]);
    }
}
