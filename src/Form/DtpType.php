<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class DtpType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('date_dtp', DateType::class, [
                'label'    => 'Дата ДТП',
                'required' => true,
                'widget'   => 'single_text',
            ])
            ->add('description', TextType::class, [
                'label'       => 'Описание',
                'required'    => true,
                'constraints' => [
                    new Length([
                        'max' => 150,
                    ]),
                ],
            ])
            ->add('adress_dtp', TextType::class, [
                'label'       => 'Адрес происшествия',
                'required'    => true,
                'constraints' => [
                    new Length([
                        'max' => 100,
                    ]),
                ],
            ])
            ->add('degree', ChoiceType::class, [
                'label'    => 'Категория ТС',
                'required' => true,
                'choices'  => array(
                    'Легкое'  => 'Легкое',
                    'Среднее' => 'Среднее',
                    'Тяжелое' => 'Тяжелое',
                ),
            ])
            ->add('initiator', ChoiceType::class, [
                'label'    => 'Виновность в ДТП',
                'required' => true,
                'choices'  => array(
                    'Инициатор'  => true,
                    'Пострадавший' => false,
                ),
            ])
            ->add('users', TextType::class, [
                'label'       => 'Водитель',
                'required'    => true,
                'constraints' => [
                    new Length([
                        'max' => 100,
                    ]),
                ],
            ])
            ->add('autos', TextType::class, [
                'label'       => 'Автомобиль',
                'required'    => true,
                'constraints' => [
                    new Length([
                        'max' => 100,
                    ]),
                ],
            ]);
    }

}
