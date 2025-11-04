<?php
/**
 * Template to render an entity
 *
 * @var array $variables [class => Binding, listId => '...', version => '...']
 */
extract($variables);
?>
<?= '<?' ?>php

namespace <?= $class->namespace ?>;

<?php if (array_filter($class->properties, fn($property) => $property->type == \DateTime::class)): ?>
use DateTime;
<?php endif ?>
use DMT\Laposta\Api\Entity\BaseCustomFields;
use JMS\Serializer\Annotation as JMS;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * GeneratedClass: <?= $class->name ?>.
 *
 * This contains the custom fields of the member entity for list `<?= $listId ?? null ?>`.
 *
 * @version <?= $version . PHP_EOL ?>
 */
class <?= $class->name ?> extends BaseCustomFields
{<?php foreach($class->properties as $property): ?>

    #[JMS\Type("<?= is_a($property->type, \DateTimeInterface::class, true) ? "DateTime<'Y-m-d', '', 'Y-m-d H:i:s'>" : $property->type ?>")]
<?php if ($property->required): ?>
     #[Assert\<?= $property->type == 'string' ? 'NotBlank' : 'NotNull' ?>()]
<?php endif ?>
    public ?<?= $property->type ?> $<?= $property->name ?> = <?= $property->default ?? 'null' ?>;
<?php endforeach ?>
}
