<?php

namespace App\Form;

use App\Entity\Questions;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class QuestionsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('askname')
            ->add('askphoto')
            ->add('answername')
            ->add('answerphoto')
            ->add('question')
            ->add('answer')
            ->add('status')
            ->add('rid')
            ->add('ruid')
            ->add('rname')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Questions::class,
            'csrf_protection' => false,
        ]);
    }
}
