<?php

namespace App\Form\Financial;

use App\Entity\Financial\TypeOfTransaction;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class TypeOfTransactionType
 * @package App\Form\Financial
 */
class TypeOfTransactionType extends AbstractType
{
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => TypeOfTransaction::class,
            'constraints' => [
                new UniqueEntity(['fields' => ['surname']]),
            ]
        ]);
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', null, [
                'required' => true,
            ])
            ->add('surname', null, [
                'required' => true,
            ])
            ->add('back', ButtonType::class)
            ->add('save', SubmitType::class);
    }
}
