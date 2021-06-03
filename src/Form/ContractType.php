<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Component\Validator\Constraints\NotNull;

class ContractType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('date_start', DateType::class,
                [
                    'label'       => 'Дата начала',
                    'required'    => true,
                    'mapped'      => false,
                    'widget'      => 'single_text',
                    'input'       => 'datetime',
                    'html5'       => 'false',
                    'constraints' => [
                        new NotBlank(['message' => 'Pick a date!']),
                        new NotNull(['message' => 'Pick a date!']),
                    ],
                ])
            ->add('date_end', DateType::class,
                [
                    'label'       => 'Дата окончания',
                    'required'    => true,
                    'mapped'      => false,
                    'widget'      => 'single_text',
                    'input'       => 'datetime',
                    'html5'       => 'false',
                    'constraints' => [
                        new NotBlank(['message' => 'Pick a date!']),
                        new NotNull(['message' => 'Pick a date!']),
                    ],
                ])
            ->add('purpose', ChoiceType::class,
                [
                    'label'    => ' Цель использования ТС',
                    'required' => true,
                    'choices'  => array(
                        'личное'       => 'личное',
                        'коммерческое' => 'коммерческое',
                    ),
                ])
            ->add('amount', TextType::class,
                [
                    'label'    => 'Сумма',
                    'required' => false,
                   /* 'disabled' => true,*/
                ])
            ->add('diagnostic_card', TextType::class,
                [
                    'label'    => 'Номер Диагностической карты',
                    'required' => true,
                ])
            ->add('non_limited', CheckboxType::class,
                [
                    'label'    => 'Ограничения страховки',
                    'required' => false,
                ])
            /*->add('status', NumberType::class,
                [
                    'label' => 'Статус',
                    'required' => true,
                ])*/
            /*->add('autoVin', ChoiceType::class,
                [
                    'label' => 'Пол',
                    'required' => true,
                    'choices'  => array(
                        'Мужской' => true,
                        'Женский' => false,
                    ),
                ])*/
            /* ->add('agent_id', NumberType::class,
                 [
                     'label' => 'Значение КБМ',
                     'required' => true,
                 ])*/
            ->add('driver_one', TextType::class,
                [
                    'label'    => 'Первый водитель',
                    'required' => true,
                ])
            ->add('driver_two', TextType::class,
                [
                    'label'    => 'Второй водитель',
                    'required' => false,
                ])
            ->add('driver_three', TextType::class,
                [
                    'label'    => 'Третий водитель',
                    'required' => false,
                ])
            ->add('driver_four', TextType::class,
                [
                    'label'    => 'Четвертый водитель',
                    'required' => false,
                ])


            ->add('date_start_one', DateType::class,
                [
                    'label'       => 'Период начала 1',
                    'required'    => true,
                    'mapped'      => false,
                    'widget'      => 'single_text',
                    'input'       => 'datetime',
                    'html5'       => 'false',
                    'constraints' => [
                        new NotBlank(['message' => 'Pick a date!']),
                        new NotNull(['message' => 'Pick a date!']),
                    ],
                ])
            ->add('date_end_one', DateType::class,
                [
                    'label'       => 'Период окончания 1',
                    'required'    => true,
                    'mapped'      => false,
                    'widget'      => 'single_text',
                    'input'       => 'datetime',
                    'html5'       => 'false',
                    'constraints' => [
                        new NotBlank(['message' => 'Pick a date!']),
                        new NotNull(['message' => 'Pick a date!']),
                    ],
                ])

           /* ->add('period_two', CheckboxType::class,
                [
                    'label'    => 'Период 2',
                    'required' => false,
                ])*/
            ->add('date_start_two', DateType::class,
                [
                    'required'   => false,
                    'mapped'      => false,
                    'label'      => 'Период начала 2',
                    'years'      => range(date('Y'), date('Y') + 1),
                    'months'     => range(date('m'), date('m') + 12),
                    'days'       => range(date('d'), date('d') + 31),
                    'widget'     => 'single_text',
                    'empty_data' => '',
                ])
            ->add('date_end_two', DateType::class,
                [
                    'label'       => 'Период окончания 2',
                    'required'   => false,
                    'mapped'      => false,
                    'empty_data' => '',
                    'label'      => 'Период начала 2',
                    'years'      => range(date('Y'), date('Y') + 2),
                    'months'     => range(date('m'), date('m') + 12),
                    'days'       => range(date('d'), date('d') + 31),
                    'widget'     => 'single_text',

                ])


           /* ->add('period_three', CheckboxType::class,
                [
                    'label'    => 'Период 3',
                    'required' => false,
                ])*/
            ->add('date_start_three', DateType::class,
                [
                    'label'       => 'Период начала 3',
                    'required'   => false,
                    'mapped'      => false,
                    'label'      => 'Период начала 2',
                    'years'      => range(date('Y'), date('Y') + 2),
                    'months'     => range(date('m'), date('m') + 12),
                    'days'       => range(date('d'), date('d') + 31),
                    'widget'     => 'single_text',
                    'empty_data' => '',
                ])
            ->add('date_end_three', DateType::class,
                [
                    'label'       => 'Период окончания 3',
                    'required'   => false,
                    'mapped'      => false,
                    'label'      => 'Период начала 2',
                    'widget'     => 'single_text',
                    'empty_data' => '',
                ]);

    }
}
