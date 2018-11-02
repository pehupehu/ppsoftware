<?php

namespace App\Form\Financial;

use App\Entity\Financial\Account;
use App\Entity\Financial\Transaction;
use App\Entity\Financial\Transfer;
use App\Entity\User;
use App\Form\HideButtonsType;
use App\Repository\Financial\AccountRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Validator\Constraints\GreaterThan;
use Symfony\Component\Validator\Constraints\NotNull;

/**
 * Class TransferType
 * @package App\Form\Financial
 */
class TransferType extends HideButtonsType
{
    /**
     * @var EntityManagerInterface
     */
    protected $entityManager;

    /**
     * @var User
     */
    protected $loggedUser;

    /**
     * TransferType constructor.
     * @param EntityManagerInterface $entityManager
     * @param TokenStorageInterface $tokenStorage
     */
    public function __construct(EntityManagerInterface $entityManager, TokenStorageInterface $tokenStorage)
    {
        $this->entityManager = $entityManager;
        $this->loggedUser = $tokenStorage->getToken()->getUser();
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            'data_class' => Transfer::class,
        ]);
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /** @var Transfer $transfer */
        $transfer = $builder->getData();

        /** @var AccountRepository $accountRepo */
        $accountRepo = $this->entityManager->getRepository(Account::class);
        
        $accountFromChoices = $accountToChoices = [];
        $accountFromChoices[] = $transfer->getAccountFrom();
        $accountsQuery = $accountRepo->getAccountsQueryForOneUser($this->loggedUser);
        foreach ($accountsQuery->execute() as $account) {
            if ($account !== $transfer->getAccountFrom()) {
                $accountToChoices[] = $account;
            }
        }
        
        $builder
            ->add('date', DateType::class, [
                'required' => true,
                'format' => 'dd/MM/yyyy',
                'widget' => 'single_text',
            ])
            ->add('amount', MoneyType::class, [
                'required' => true,
                'currency' => $transfer->getAccountFrom()->getBalanceCurrency(),
                'constraints' => [
                    new GreaterThan(0),
                ],
                'empty_data' => 0,
            ])
            ->add('accountFrom', EntityType::class, [
                'required' => true,
                'class' => Account::class,
                'choices' => $accountFromChoices,
                'group_by' => function (Account $account) {
                    return $account->getTypeOfAccount()->getName();
                },
                'constraints' => [
                    new NotNull(),
                ],
            ])
            ->add('accountTo', EntityType::class, [
                'required' => true,
                'class' => Account::class,
                'choices' => $accountToChoices,
                'group_by' => function (Account $account) {
                    return $account->getTypeOfAccount()->getName();
                },
                'constraints' => [
                    new NotNull(),
                ],
            ])
        ;

        if (empty($options['hide_back_button'])) {
            $builder->add('back', ButtonType::class);
        }
        if (empty($options['hide_save_button'])) {
            $builder->add('save', SubmitType::class);
        }
    }
}
