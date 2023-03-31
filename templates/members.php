<?= '<?php' . PHP_EOL ?><?php /** @var \DMT\Laposta\Api\Generators\Entity\ClassBinding $class */ ?>
<?= $class->namespace ? PHP_EOL . 'namespace ' . $class->namespace . ';' . PHP_EOL : null ?>

use DMT\Laposta\Api\Entity\Member as BaseMember;
use JMS\Serializer\Annotation as JMS;

/**
 * GeneratedClass: <?= $class->className  . PHP_EOL ?>
 *
 * This contains the actual implementation of the member entity for list `<?= $class->listId ?>`.
 *
 * @version <?= members . phpdate(DateTime::RFC7231, strtotime('now')) . PHP_EOL ?>
 */
class <?= $class->className ?> extends BaseMember
{<?php foreach ($class->properties as $binding): ?>

    /**
     * @JMS\Type("<?= is_a($binding->type, \DateTimeInterface::class, true) ? "DateTime<'Y-m-d'>" : $binding->type ?>")
     */
    public <?= $binding->required || $binding->default !== null ? null : '?' ?><?= $binding->type ?> $<?= $binding->name ?><?= $binding->defaults() ?>;<?= PHP_EOL ?>
<?php endforeach; ?>
}
