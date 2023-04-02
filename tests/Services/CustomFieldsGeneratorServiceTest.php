<?php

namespace DMT\Test\Laposta\Api\Services;

use DateTime;
use DMT\Laposta\Api\Clients\Fields;
use DMT\Laposta\Api\Config;
use DMT\Laposta\Api\Entity\Field;
use DMT\Laposta\Api\Entity\FieldCollection;
use DMT\Laposta\Api\Services\CustomFieldsGeneratorService;
use PHPUnit\Framework\TestCase;

/**
 * @version Sun, 02 Apr 2023 15:26:28 GMT
 */
class CustomFieldsGeneratorServiceTest extends TestCase
{
    /**
     * @dataProvider isUpToDateEntityProvider
     */
    public function testEntityIsUpToDate(string $listId, FieldCollection $collection): void
    {
        $this->assertTrue($this->getCustomFieldsService($collection)->checkEntity($listId));
    }

    public function isUpToDateEntityProvider(): iterable
    {
        $createdCollection = new FieldCollection();
        $field = new Field();
        $field->created = new DateTime('2023-02-28');
        $createdCollection->fields[] = $field;

        $modifiedCollection = new FieldCollection();
        $field = new Field();
        $field->created = new DateTime('2023-02-28');
        $field->modified = new DateTime('2023-03-21');
        $modifiedCollection->fields[] = $field;

        return [
            'no custom fields' => ['BaImMu3JZA', new FieldCollection()],
            'unmodified field' => ['BaImMu3JZA', $createdCollection],
            'modified field' => ['BaImMu3JZA', $modifiedCollection],
        ];
    }

    /**
     * @dataProvider entityNeedsToBeRenderedProvider
     */
    public function testCheckEntityNeedsToBeRendered(string $listId, FieldCollection $collection): void
    {
        $this->assertFalse($this->getCustomFieldsService($collection)->checkEntity($listId));
    }

    public function entityNeedsToBeRenderedProvider(): iterable
    {
        $createdCollection = new FieldCollection();
        $field = new Field();
        $field->created = new DateTime('2023-06-28');
        $createdCollection->fields[] = $field;

        $modifiedCollection = new FieldCollection();
        $field = new Field();
        $field->created = new DateTime('2023-02-28');
        $field->modified = new DateTime('2023-06-21');
        $modifiedCollection->fields[] = $field;

        return [
            'no entity' => ['ImBaMuZa3J', new FieldCollection()],
            'not configured list' => ['IaMuZmBa3J', new FieldCollection()],
            'not versioned entity' => ['AZJ3uMmIaB', new FieldCollection()],
            'field added' => ['BaImMu3JZA', $createdCollection],
            'modified field' => ['BaImMu3JZA', $modifiedCollection],
        ];
    }

    private function getCustomFieldsService(FieldCollection $collection): CustomFieldsGeneratorService
    {
        $config = new Config();
        $config->customFieldsClasses = [
            'BaImMu3JZA' => self::class,
            'AZJ3uMmIaB' => parent::class,
            'ImBaMuZa3J' => '_Unknown_class_',
        ];

        $client = $this->getMockBuilder(Fields::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['all'])
            ->getMock();
        $client->expects($this->any())->method('all')->willReturn($collection);

        return new CustomFieldsGeneratorService($config, $client);
    }
}
