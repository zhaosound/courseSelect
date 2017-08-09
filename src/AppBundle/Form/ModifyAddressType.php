<?php

namespace AppBundle\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;

class ModifyAddressType extends AbstractType
{
    function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('addresses', CollectionType::class, [
                'entry_type'   => AddressType::class,
                'allow_delete' => true,
                'allow_add'    => true,
                'prototype'    => true,
                'by_reference' => false,
            ]);
    }
}