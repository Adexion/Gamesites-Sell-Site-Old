<?php

namespace App\Form;

use App\Enum\PaymentTypeEnum;
use App\Repository\ConfigurationRepository;
use App\Repository\PaymentRepository;
use Symfony\Component\Form\Extension\Core\Type\BaseType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Type;

class ItemType extends BaseType
{
    private ?array $payments;
    private const PSC = 'paySafeCard';

    public function __construct(PaymentRepository $paymentRepository, ConfigurationRepository $configurationRepository)
    {
        foreach ($paymentRepository->findBy(['isActive' => true]) as $payment) {
            $enum = PaymentTypeEnum::from($payment->getType());
            $this->payments[$enum->getValue()] = $enum->getValue();
        }

        if ($configurationRepository->findOneBy([])->getSimplePaySafeCard()) {
            $this->payments['psc'] = self::PSC;
        }
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class)
            ->add('username', TextType::class, [
                'constraints' => [
                    new Length(['max' => 15, 'maxMessage' => 'Nazwa użytkownika może mieć maksimum 15 znaków']),
                ],
            ])
            ->add('payment', ChoiceType::class, [
                'choices' => $this->payments,
                'choice_translation_domain' => 'messages',
            ])
            ->add('code', TextType::class, [
                'required' => false,
                'constraints' => [
                    new Type(['type' => 'numeric', 'message' => 'Błędny PaySafeCard']),
                    new Length(['min' => 16, 'max' => 16, 'exactMessage' => 'PaySafeCard powinien mieć dokładnie 16 znaków']),
                ],
            ])
            ->add('check', CheckboxType::class, [])
            ->add('uri', HiddenType::class)
            ->add('locale', HiddenType::class)
            ->addEventListener(FormEvents::POST_SUBMIT, [$this, 'preSubmit']);
    }

    public function preSubmit(FormEvent $event)
    {
        if ($event->getData()['payment'] === self::PSC && empty($event->getData()['code'])) {
            $event->getForm()->get('code')->addError(new FormError('PaySafeCard powinien mieć dokładnie 16 znaków'));
        }
    }

    public function getBlockPrefix(): string
    {
        return '';
    }
}