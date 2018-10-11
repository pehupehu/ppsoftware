<?php

namespace App\Form\Financial;

use App\Entity\Financial\Account;
use App\Entity\Financial\Bank;
use App\Entity\Financial\TypeOfAccount;
use App\Form\EntityManagerType;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
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
class AccountType extends EntityManagerType
{
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Account::class,
        ]);
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $repoBank = $this->entityManager->getRepository(Bank::class);
        $repoTypeOfAccount = $this->entityManager->getRepository(TypeOfAccount::class);

        $builder
            ->add('bank', EntityType::class, [
                'class' => Bank::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('b')
                        ->orderBy('b.name', 'ASC');
                },
                'choice_value' => 'id',
                'choice_label' => 'name',
//                'required' => true,
//                'choices' => $repoBank->getBanks(),
//                'choice_translation_domain' => false,
            ])
            ->add('typeOfAccount', EntityType::class, [
                'class' => TypeOfAccount::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('b')
                        ->orderBy('b.name', 'ASC');
                },
                'choice_value' => 'id',
                'choice_label' => 'name',
//                'required' => true,
//                'choices' => $repoTypeOfAccount->getTypes(),
//                'choice_translation_domain' => false,
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
