<?php

namespace App\Form;

use App\Entity\Empresa;
use App\Entity\PersonaContacto;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PersonaContactoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('puesto')
            ->add('telefono')
            ->add('email')
            ->add('visible')
            ->add('empresa', EntityType::class, [
                'class' => Empresa::class,
                'choice_label' => 'id',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => PersonaContacto::class,
        ]);
    }
}
