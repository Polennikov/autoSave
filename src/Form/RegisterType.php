<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
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

class RegisterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class,
                [
                    'label' => 'Имя',
                    'required' => true,
                ])
            ->add('surname', TextType::class,
                [
                    'label' => 'Фамилия',
                    'required' => true,
                ])
            ->add('numberDriver', TextType::class,
                [
                    'label' => 'Номер водительского',
                    'required' => true,
                ])
            ->add('midName', TextType::class,
                [
                    'label' => 'Отчество',
                    'required' => true,
                ])
            ->add('dateDriver', DateType::class,
                [
                    'label' => 'Дата Рождения',
                    'required' => true,
                    'widget' => 'single_text',
                ])
            ->add('adressDriver', TextType::class,
                [
                    'label' => 'Адрес проживания',
                    'required' => true,
                ])
            ->add('expDriver', NumberType::class,
                [
                    'label' => 'Стаж вождения',
                    'required' => true,
                    'invalid_message' => 'Неверный формат поля.',
                ])
            ->add('genderDriver', ChoiceType::class,
                [
                    'label' => 'Пол',
                    'required' => true,
                    'choices'  => array(
                        'Мужской' => true,
                        'Женский' => false,
                    ),
                ])
            ->add('KBM', NumberType::class,
                [
                    'label' => 'Значение КБМ',
                    'required' => true,
                    'invalid_message' => 'Неверный формат поля.',
                ])
            ->add('email', EmailType::class,
                [
                    'label' => 'Email',
                    'required' => true,
                    'constraints' => [
                        new NotBlank([
                            'message' => 'Введите Email.',
                        ]),
                    ],
                ])
            ->add('password',  RepeatedType::class,
                ['label' => 'Пароль',
                    'required' => true,
                    'type' => PasswordType::class,
                    'empty_data' => '',
                    'constraints' => [
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Ваш пароль должен состоять из {{ limit }} символов.',
                        // max length allowed by Symfony for security reasons
                        'max' => 100,
                    ]),
                    new Regex([
                        'pattern' => '/(?=.*[0-9])(?=.*[a-z])(?=.*[A-Z])/',
                        //'pattern' => "/[0-9][a-z][A-Z]/i",
                        'message' => 'Пароль должен содержать цифры, латинские заглавные и строчные буквы',
                    ]),
                ],
                'first_options' => [
                    'label' => 'Пароль',
                ],
                'second_options' => [
                    'label' => 'Повторите пароль',
                ],
                'invalid_message' => 'Пароли не совпадают.',
            ]);
    }
}
