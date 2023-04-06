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
     * @dataProvider fieldForCollectionProvider
     */
    public function testGenerateEntity(Field $field, string $expected): void
    {
        $this->expectOutputRegex($expected);

        $fieldsCollection = new FieldCollection([$field]);

        $service = $this->getCustomFieldsService($fieldsCollection);
        $service->generateEntity('BaImMu3JZA', 'My\\Personal\\Space\\EntityClass', 'php://output');
    }

    public function fieldForCollectionProvider(): iterable
    {
        $field = new Field();
        $field->customName = 'text';

        yield 'text field' => [$field, '~JMS\\\\Type\("string"\)(.*)public \?string \$text = null;~ms'];

        $field = new Field();
        $field->customName = 'required';
        $field->required = true;

        yield 'required field' => [$field, '~Assert\\\\NotBlank\(\)(.*)public \?string \$required = null;~ms'];

        $field = new Field();
        $field->customName = 'required';
        $field->datatype = 'numeric';
        $field->required = true;

        yield 'required numeric field' => [$field, '~Assert\\\\NotNull\(\)(.*)public \?float \$required = null;~ms'];

        $field = new Field();
        $field->customName = 'date';
        $field->datatype = 'date';

        yield 'date field' => [$field, '~JMS\\\\Type\("DateTime<\'Y-m-d\', \'\', \'Y-m-d H:i:s\'>"\)(.*)public \?DateTime \$date = null;~ms'];

        $field = new Field();
        $field->customName = 'number';
        $field->datatype = 'numeric';
        $field->defaultvalue = '14';

        yield 'integer field' => [$field, '~JMS\\\\Type\("int"\)(.*)public \?int \$number = 14;~ms'];

        $field = new Field();
        $field->customName = 'number';
        $field->datatype = 'numeric';
        $field->defaultvalue = '0';

        yield 'integer field default 0' => [$field, '~JMS\\\\Type\("int"\)(.*)public \?int \$number = 0;~ms'];

        $field = new Field();
        $field->customName = 'number';
        $field->datatype = 'numeric';

        yield 'float field' => [$field, '~JMS\\\\Type\("float"\)(.*)public \?float \$number = null;~ms'];

        $field = new Field();
        $field->customName = 'number';
        $field->datatype = 'numeric';
        $field->defaultvalue = '0.0';

        yield 'float field with default' => [$field, '~JMS\\\\Type\("float"\)(.*)public \?float \$number = 0.0;~ms'];

        $field = new Field();
        $field->customName = 'choose';
        $field->datatype = 'select_single';
        $field->options = ['one', 'two', 'three'];

        yield 'select no default' => [$field, '~JMS\\\\Type\("string"\)(.*)public \?string \$choose = null;~ms'];

        $field = new Field();
        $field->customName = 'choose';
        $field->datatype = 'select_single';
        $field->options = ['one', 'two', 'three'];
        $field->defaultvalue = 'two';

        yield 'select with default' => [$field, '~JMS\\\\Type\("string"\)(.*)public \?string \$choose = \'two\';~ms'];

        $field = new Field();
        $field->customName = 'choose';
        $field->datatype = 'select_single';
        $field->options = ['one', 'two', 'three'];
        $field->defaultvalue = 'five';

        yield 'select default missing' => [$field, '~JMS\\\\Type\("string"\)(.*)public \?string \$choose = null;~ms'];

        $field = new Field();
        $field->customName = 'choose';
        $field->datatype = 'select_multiple';
        $field->options = ['one', 'two', 'three'];

        yield 'multi no default' => [$field, '~JMS\\\\Type\("array"\)(.*)public \?array \$choose = null;~ms'];

        $field = new Field();
        $field->customName = 'choose';
        $field->datatype = 'select_multiple';
        $field->options = ['one', 'two', 'three'];
        $field->defaultvalue = 'two';

        yield 'multi with default' => [$field, '~JMS\\\\Type\("array"\)(.*)public \?array \$choose = \[\'two\'\];~ms'];

        $field = new Field();
        $field->customName = 'choose';
        $field->datatype = 'select_multiple';
        $field->options = ['one', 'two', 'three'];
        $field->defaultvalue = 'five';

        yield 'multi default missing' => [$field, '~JMS\\\\Type\("array"\)(.*)public \?array \$choose = null;~ms'];

    }

    /**
     * @dataProvider isUpToDateEntityProvider
     */
    public function testEntityIsUpToDate(string $listId, FieldCollection $collection): void
    {
        $this->assertTrue($this->getCustomFieldsService($collection)->checkEntity($listId));
    }

    public function isUpToDateEntityProvider(): iterable
    {
        $field = new Field();
        $field->created = new DateTime('2023-02-28');
        $createdCollection = new FieldCollection([$field]);

        $field = new Field();
        $field->created = new DateTime('2023-02-28');
        $field->modified = new DateTime('2023-03-21');
        $modifiedCollection = new FieldCollection([$field]);

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
        $field = new Field();
        $field->created = new DateTime('2023-06-28');
        $createdCollection = new FieldCollection([$field]);

        $field = new Field();
        $field->created = new DateTime('2023-02-28');
        $field->modified = new DateTime('2023-06-21');
        $modifiedCollection = new FieldCollection([$field]);

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
