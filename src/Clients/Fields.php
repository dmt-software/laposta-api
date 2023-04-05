<?php

namespace DMT\Laposta\Api\Clients;

use DMT\Laposta\Api\Commands\Fields\CreateField;
use DMT\Laposta\Api\Commands\Fields\DeleteField;
use DMT\Laposta\Api\Commands\Fields\GetField;
use DMT\Laposta\Api\Commands\Fields\GetFields;
use DMT\Laposta\Api\Commands\Fields\UpdateField;
use DMT\Laposta\Api\Entity\Field;
use DMT\Laposta\Api\Entity\FieldCollection;
use League\Tactician\CommandBus;

class Fields
{
    private CommandBus $commandBus;

    public function __construct(CommandBus $commandBus)
    {
        $this->commandBus = $commandBus;
    }

    /**
     * Get all the fields for a list.
     *
     * @param string $listId The ID of the list
     *
     * @return \DMT\Laposta\Api\Entity\FieldCollection
     */
    public function all(string $listId): FieldCollection
    {
        return $this->commandBus->handle(new GetFields($listId));
    }

    /**
     * Get a single field for a list.
     *
     * @param string $listid The ID of the list
     * @param string $fieldId The ID of the field
     *
     * @return \DMT\Laposta\Api\Entity\Field
     */
    public function get(string $listid, string $fieldId): Field
    {
        return $this->commandBus->handle(new GetField($listid, $fieldId));
    }

    /**
     * Add a new field to a list.
     *
     * @param \DMT\Laposta\Api\Entity\Field $field The field to add
     */
    public function create(Field $field): void
    {
        $this->commandBus->handle(new CreateField($field));
    }

    /**
     * Update an existing field.
     *
     * @param \DMT\Laposta\Api\Entity\Field $field The field with modifications
     */
    public function update(Field $field): void
    {
        $this->commandBus->handle(new UpdateField($field));
    }

    /**
     * Delete a field from a list.
     *
     * @param string $listId The ID of the list
     * @param string $fieldId The ID of a field
     */
    public function delete(string $listId, string $fieldId): void
    {
        $this->commandBus->handle(new DeleteField($listId, $fieldId));
    }
}
