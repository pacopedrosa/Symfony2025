<?php

namespace App\Form;

use App\Entity\Comunicacion;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class ComunicacionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('anotacion')
            ->add('nombreContacto', TextType::class, [
                'label' => 'Nombre del Contacto',  // Etiqueta para el campo
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Comunicacion::class,
        ]);
    }
}
