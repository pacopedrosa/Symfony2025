<?php

namespace App\Form;

use App\Entity\Objetos;
use App\Entity\Registro;
use App\Entity\RegistroSalida;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ObjetosType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('descripcion')
            ->add('cantidad')
            ->add('relacionRegistros', EntityType::class, [
                'class' => Registro::class,
                'choice_label' => 'id',
            ])
            ->add('relacionRegistroSalida', EntityType::class, [
                'class' => RegistroSalida::class,
                'choice_label' => 'id',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Objetos::class,
        ]);
    }
}
