<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class HideButtonsType
 * @package App\Form
 */
class HideButtonsType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefined('hide_back_button');
        $resolver->setDefined('hide_save_button');
        $resolver->addAllowedTypes('hide_back_button', 'boolean');
        $resolver->addAllowedTypes('hide_save_button', 'boolean');
        $resolver->addAllowedValues('hide_back_button', true);
        $resolver->addAllowedValues('hide_save_button', true);
    }
}
