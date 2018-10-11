<?php

namespace App\Form\Financial;

use App\Entity\Financial\Account;
use App\Entity\User;
use App\Form\EntityManagerType;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Constraints\Choice;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * Class AccountShareType
 * @package App\Form\Financial
 */
class AccountShareType extends EntityManagerType
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
        $accountRepo = $this->entityManager->getRepository(Account::class);

        /** @var Account $account */
        $account = $options['data'];

        $unallowedChoices = $accountRepo->getUnallowedUsers($account);
        $allowedChoices = $accountRepo->getAllowedUsers($account, false);

        $unallowedChoicesAttr = [];
        $allowedChoicesAttr = [];
        $allChoices = [];
        /** @var User $user */
        foreach ($allowedChoices as $user) {
            $allChoices[] = $user;
            $unallowedChoicesAttr[] = ['share' => 'allowed'];
        }
        /** @var User $user */
        foreach ($unallowedChoices as $user) {
            $allChoices[] = $user;
            $allowedChoicesAttr[] = ['share' => 'unallowed'];
        }

        dump($unallowedChoices);
        dump($allowedChoices);

        dump($unallowedChoicesAttr);
        dump($allowedChoicesAttr);

        $builder
            ->add('unallowed', ChoiceType::class, [
                'choices' => $allChoices,
                'choice_value' => 'id',
                'choice_label' => 'displayableName',
                'choice_attr' => $unallowedChoicesAttr,
                'multiple' => true,
                'required' => false,
                'mapped' => false,
                'choice_translation_domain' => false,
                'constraints' => new Choice([
                    'multiple' => true,
                    'choices' => $allChoices,
                ])
            ])
            ->add('allowed', ChoiceType::class, [
                'choices' => $allChoices,
                'choice_value' => 'id',
                'choice_label' => 'displayableName',
                'choice_attr' => $allowedChoicesAttr,
                'multiple' => true,
                'required' => false,
                'mapped' => false,
                'choice_translation_domain' => false,
                'constraints' => new Choice([
                    'multiple' => true,
                    'choices' => $allChoices,
                ])
            ])
            ->add('back', ButtonType::class)
            ->add('save', SubmitType::class);
    }
}
