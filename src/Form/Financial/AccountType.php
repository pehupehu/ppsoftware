<?php

namespace App\Form\Financial;

use App\Entity\Financial\Account;
use App\Entity\Financial\Bank;
use App\Entity\Financial\TypeOfAccount;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Range;

/**
 * Class AccountType
 * @package App\Form\Financial
 */
class AccountType extends AbstractType
{
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Account::class,
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
            ->add('bank', EntityType::class, [
                'class' => Bank::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('b')
                        ->orderBy('b.name', 'ASC');
                },
                'choice_value' => 'id',
                'choice_label' => 'name',
            ])
            ->add('typeOfAccount', EntityType::class, [
                'class' => TypeOfAccount::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('b')
                        ->orderBy('b.name', 'ASC');
                },
                'choice_value' => 'id',
                'choice_label' => 'name',
            ])
            ->add('name', null, [
                'required' => true,
            ])
            ->add('surname', null, [
                'required' => true,
            ])
            ->add('initialBalance', NumberType::class, [
                'required' => true,
                'constraints' => new Range([
                    'min' => 0,
                ])
            ])
            ->add('back', ButtonType::class)
            ->add('save', SubmitType::class);
    }
}
