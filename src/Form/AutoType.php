<?php

namespace App\Form;

use App\Entity\Auto;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class AutoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('vin', TextType::class, [
                'label'       => 'Vin номер',
                'required' => true,
                'constraints' => [
                    new Length([
                        'max' => 150,
                    ]),
                ],
            ])
            ->add('marka', TextType::class, [
                'label'       => 'Марка',
                'required' => true,
                'constraints' => [
                    new Length([
                        'max' => 100,
                    ]),
                ],
            ])
            ->add('model', TextType::class, [
                'label'       => 'Модель',
                'required' => true,
                'constraints' => [
                    new Length([
                        'max' => 100,
                    ]),
                ],
            ])
            ->add('number', TextType::class, [
                'label'       => 'Гос номер',
                'required' => true,
                'constraints' => [
                    new Length([
                        'max' => 10,
                    ]),
                ],
            ])
            ->add('color', TextType::class, [
                'label'       => 'Цвет',
                'required' => true,
                'constraints' => [
                    new Length([
                        'max' => 100,
                    ]),
                ],
            ])
            ->add('number_sts', TextType::class, [
                'label'       => 'Номер СТС',
                'required' => true,
                'constraints' => [
                    new Length([
                        'max' => 100,
                    ]),
                ],
            ])
            ->add('year', NumberType::class, [
                'label' => 'Год выпуска',
                'required' => true,
                'invalid_message' => 'Неверный формат поля.',

            ])
            ->add('power', NumberType::class, [
                'label' => 'Мощность',
                'required' => true,
                'invalid_message' => 'Неверный формат поля.',
            ])
            ->add('mileage', NumberType::class, [
                'label' => 'Пробег (км)',
                'required' => true,
                'invalid_message' => 'Неверный формат поля.',
            ])
            ->add('category', ChoiceType::class, [
                'label'    => 'Категория ТС',
                'required' => true,
                'choices'  => array(
                    'A' => 'A',
                    'B' => 'B',
                    'C' => 'C',
                    'D' => 'D',
                    'E' => 'E',
                ),
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Auto::class,
        ]);
    }
}
