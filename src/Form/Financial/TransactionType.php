<?php

namespace App\Form\Financial;

use App\Entity\Financial\Category;
use App\Entity\Financial\Transaction;
use App\Entity\Financial\TypeOfTransaction;
use App\Form\HideButtonsType;
use App\Form\EntityThumbType;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\GreaterThan;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;

/**
 * Class TransactionType
 * @package App\Form\Financial
 */
class TransactionType extends HideButtonsType
{
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            'data_class' => Transaction::class,
        ]);
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /** @var Transaction $transaction */
        $transaction = $builder->getData();

        $builder
            ->add('name', TextType::class, [
                'required' => true,
                'constraints' => [
                    new NotBlank(),
                    new Length(['min' => 3]),
                ],
            ])
            ->add('date', DateType::class, [
                'required' => true,
                'format' => 'dd/MM/yyyy',
                'widget' => 'single_text',
            ])
            ->add('amount', MoneyType::class, [
                'required' => true,
                'currency' => $transaction->getAccount()->getBalanceCurrency(),
                'constraints' => [
                    new GreaterThan(0),
                ],
                'empty_data' => 0,
            ])
            ->add('typeOfTransaction', EntityThumbType::class, [
                'required' => true,
                'expanded' => true,
                'class' => TypeOfTransaction::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('t')
                        ->select('t')
                        ->orderBy('t.name', 'ASC');
                },
                'choice_value' => 'id',
                'choice_label' => 'name',
                'choice_thumb' => 'logo',
                'constraints' => [
                    new NotNull(),
                ],
            ])
            ->add('category', EntityType::class, [
                'required' => true,
                'class' => Category::class,
                'query_builder' => function (EntityRepository $er) use ($transaction) {
                    return $er->createQueryBuilder('c')
                        ->where('c.parent IS NOT NULL')
                        ->innerJoin('c.parent', 'p')
                        ->where('c.type = :type')
                        ->orderBy('p.name, c.name', 'ASC')
                        ->setParameters([
                            ':type' => $transaction->getType(),
                        ]);
                },
                'choice_value' => 'id',
                'choice_label' => 'name',
                'group_by' => function (Category $category) {
                    return $category->getParent()->getName();
                },
                'constraints' => [
                    new NotNull(),
                ],
            ]);

        if (empty($options['hide_back_button'])) {
            $builder->add('back', ButtonType::class);
        }
        if (empty($options['hide_save_button'])) {
            $builder->add('save', SubmitType::class);
        }
    }
}
