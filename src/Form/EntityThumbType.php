<?php

namespace App\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\ChoiceList\View\ChoiceView;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EntityThumbType extends EntityType
{
    private $thumbs;

    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);
        $resolver->setDefined('choice_thumb');

        $resolver->setAllowedTypes('choice_thumb', array('null', 'callable', 'string', 'Symfony\Component\PropertyAccess\PropertyPath'));
    }

    public function getBlockPrefix()
    {
        return 'entitythumb';
    }

    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        parent::buildView($view, $form, $options);

        if (!empty($options['choice_thumb']) && !empty($options['choice_value'])) {
            $methodValue = 'get' . ucfirst(strtolower($options['choice_value']));
            $methodThumb = 'get' . ucfirst(strtolower($options['choice_thumb']));

            /** @var ChoiceView $choiceView */
            foreach ($view->vars['choices'] as $choiceView) {
                $this->thumbs[$choiceView->data->$methodValue()] = $choiceView->data->$methodThumb();
            }
        }
    }

    public function finishView(FormView $view, FormInterface $form, array $options)
    {
        foreach ($view as $childView) {
            $childView->vars['thumb'] = $this->thumbs[$childView->vars['value']] ?? '';
        }
    }
}