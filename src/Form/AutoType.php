<?php

namespace App\Form;

use App\Entity\Auto;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;

class AutoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('vin', TextType::class, [
                'label' => 'Vin номер',
                'constraints' => [
                    new Length([
                        'max' => 150,
                    ]),
                ],
            ])
            ->add('marka', TextType::class, [
                'label' => 'Марка',
                'constraints' => [
                    new Length([
                        'max' => 100,
                    ]),
                ],
            ])
            ->add('model', TextType::class, [
                'label' => 'Модель',
                'constraints' => [
                    new Length([
                        'max' => 100,
                    ]),
                ],
            ])
            ->add('number', TextType::class, [
                'label' => 'Гос номер',
                'constraints' => [
                    new Length([
                        'max' => 10,
                    ]),
                ],
            ])
            ->add('color', TextType::class, [
                'label' => 'Цвет',
                'constraints' => [
                    new Length([
                        'max' => 100,
                    ]),
                ],
            ])
            ->add('year', IntegerType::class, [
                'label' => 'Год выпуска',

            ])
            ->add('power', IntegerType::class, [
                'label' => 'Мощность',
            ])
            ->add('mileage', IntegerType::class, [
                'label' => 'Пробег (км)',
            ])
            ->add('category', TextType::class, [
                'label' => 'Категория',
                'constraints' => [
                    new Length([
                        'max' => 5,
                    ]),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Auto::class,
        ]);
    }
}
