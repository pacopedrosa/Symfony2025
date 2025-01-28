<?php

namespace App\Form;

use App\Entity\Card;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class CardType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('number') // Campo de número
        ->add('suit')   // Campo de palo
        ->add('image', FileType::class, [ // Nuevo campo para la imagen
            'label' => 'Subir imagen (JPEG o PNG)',
            'mapped' => false, // No está mapeado a la entidad `Card`
            'required' => false,
            'constraints' => [
                new File([
                    'maxSize' => '2M', // Tamaño máximo permitido
                    'mimeTypes' => [
                        'image/jpeg',
                        'image/png',
                    ],
                    'mimeTypesMessage' => 'Por favor, sube una imagen válida (JPEG o PNG)',
                ]),
            ],
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Card::class,
        ]);
    }
}
