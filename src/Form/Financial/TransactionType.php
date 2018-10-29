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
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\OptionsResolver\OptionsResolver;

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
            ->add('name', null, [
                'required' => true,
            ])
            ->add('date', DateType::class, [
                'required' => true,
                'format' => 'dd/MM/yyyy',
                'widget' => 'single_text',
            ])
            ->add('amount', MoneyType::class, [
                'required' => true,
                'currency' => $transaction->getAccount()->getBalanceCurrency(),
            ])
            ->add('typeOfTransaction', EntityThumbType::class, [
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
            ])
            ->add('category', EntityType::class, [
                'class' => Category::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('c')
                        ->orderBy('c.name', 'ASC');
                },
                'choice_value' => 'id',
                'choice_label' => 'name',
            ]);

        if (empty($options['hide_back_button'])) {
            $builder->add('back', ButtonType::class);
        }
        if (empty($options['hide_save_button'])) {
            $builder->add('save', SubmitType::class);
        }
    }
}
