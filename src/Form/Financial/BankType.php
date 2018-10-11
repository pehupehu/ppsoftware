<?php

namespace App\Form\Financial;

use App\Entity\Financial\Bank;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class BankType
 * @package App\Form\Financial
 */
class BankType extends AbstractType
{
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Bank::class,
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
                'constraints' => [
                    new UniqueEntity(['fields' => ['surname']]),
                ]
            ])
            ->add('file', FileType::class, [
                'required' => false,
                'mapped' => false,
            ])
            ->add('back', ButtonType::class)
            ->add('save', SubmitType::class);
    }
}
