<?php

namespace App\Controller\Admin\Field;

use EasyCorp\Bundle\EasyAdminBundle\Contracts\Field\FieldInterface;
use EasyCorp\Bundle\EasyAdminBundle\Field\FieldTrait;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

final class EntityField implements FieldInterface
{
    use FieldTrait;

    public const OPTION_CLASS = 'class';
    public const OPTION_CHOICE_LABEL = 'choice_label';
    private ?string $choiceValue = null;
    private string $filteredBy;
    private string $filteredValue;

    public static function new(string $propertyName, ?string $label = null): self
    {
        return (new self())
            ->setProperty($propertyName)
            ->setLabel($label)
            ->setTemplatePath('admin/field/entity.html.twig')
            ->setFormType(EntityType::class)
            ->setRequired(true);
    }

    public function setClass($entityClass, string $choiceLabel): self
    {
        $choiceValue = $this->choiceValue;

        $this
            ->setCustomOption(self::OPTION_CHOICE_LABEL, $choiceLabel)
            ->setFormTypeOptions([
                self::OPTION_CLASS => $entityClass,
                self::OPTION_CHOICE_LABEL => $choiceLabel,
            ])
            ->formatValue(function ($value) use ($choiceLabel) {
                if (!$value) {
                    return null;
                }

                if (is_string($value)) {
                    return $value;
                }

                if (isset($this->filteredBy) && call_user_func([$value, 'get' . ucfirst($this->filteredBy)]
                    ) == $this->filteredValue) {
                    return null;
                }

                return call_user_func([$value, 'get' . ucfirst($choiceLabel)]);
            })
            ->setFormTypeOption('choice_value', function($entity) use ($choiceValue) {
                return $choiceValue ? call_user_func([$entity, 'get' . ucfirst($choiceValue)]) : $entity;
            });

        return $this;
    }

    public function setFilteredBy(string $filteredBy, string $value): self
    {
        $this->filteredBy = $filteredBy;
        $this->filteredValue = $value;

        return $this;
    }

    public function setChoiceValue($choiceValue): self
    {
        $this->choiceValue = $choiceValue;

        return $this;
    }
}
