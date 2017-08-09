<?php

namespace AppBundle\Form;

use AppBundle\Entity\Student;
use AppBundle\Entity\Subject;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichFileType;
class SelectSubjectType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('subjects', EntityType::class, [
                'class'        => Subject::class,
                'choice_label' => function($subject){
                    return $subject->getName()."|".$subject->getSummary();
                },
                'expanded'     => true,
                'multiple'     => true,
                'required'     => false,
    ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Student::class,
        ]);
    }
}