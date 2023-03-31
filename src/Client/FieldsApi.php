<?php

namespace DMT\Laposta\Api\Client;

use DMT\Laposta\Api\Commands\CreateField;
use DMT\Laposta\Api\Commands\DeleteField;
use DMT\Laposta\Api\Commands\GetField;
use DMT\Laposta\Api\Commands\GetFields;
use DMT\Laposta\Api\Commands\UpdateField;
use DMT\Laposta\Api\Entity\Field;
use DMT\Laposta\Api\Entity\Fields;
use League\Tactician\CommandBus;

class FieldsApi
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
     * @return \DMT\Laposta\Api\Entity\Fields
     */
    public function all(string $listId): Fields
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
     *
     * @return \DMT\Laposta\Api\Entity\Field The newly created field
     */
    public function create(Field $field): Field
    {
        return $this->commandBus->handle(new CreateField($field));
    }

    /**
     * Update an existing  field.
     *
     * @param \DMT\Laposta\Api\Entity\Field $field The field with modifications
     *
     * @return \DMT\Laposta\Api\Entity\Field The modified field
     */
    public function update(Field $field): Field
    {
        return $this->commandBus->handle(new UpdateField($field));
    }

    /**
     * Delete a field from a list.
     *
     * @param string $listid The ID of the list
     * @param string $fieldId The ID of a field
     *
     * @return \DMT\Laposta\Api\Entity\Field A reference to the deleted field
     */
    public function delete(string $listid, string $fieldId): Field
    {
        return $this->commandBus->handle(new DeleteField($listid, $fieldId));
    }
}
