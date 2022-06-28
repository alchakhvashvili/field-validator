<?php

declare(strict_types=1);

namespace Tests\Unit;

use Alchakhvashvili\FieldValidator\FieldCasesGenerator;
use Codeception\Test\Unit;

class FieldCasesGeneratorTest extends Unit
{
    public function testGenerator(): void
    {
        $invalidDataProvider = [
            'url' => 'empty|val:invalid website url|string:256',
            'thumbnail' => 'val:invalid website url|string:256',
            'category' => 'empty|val:invalid category',
            'type' => 'empty|val:invalid image type',
            'width' => 'val:non number|val:invalid website url|float|intVal:55454545',
            'height' => 'val:non number|float|intVal:54363463463',
        ];
        $object = [
            'url' => 'https://example.com/img/logo.jpg',
            'thumbnail' => 'https://example.com/img/logo_thumb.jpg',
            'category' => 'OPERATOR',
            'type' => 'jpeg',
            'width' => 512,
            'height' => 512
        ];
        $generator = new FieldCasesGenerator($invalidDataProvider, $object);
        foreach ($generator as $item) {
            print_r($item);
        }
        die();
    }
}
