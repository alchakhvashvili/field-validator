<?php

declare(strict_types=1);

use Alchakhvashvili\FieldValidator\FieldCasesGenerator;
use Codeception\Test\Unit;

abstract class OCPIValidatorTest extends Unit
{
    abstract public function validDataProvider();

    abstract public function invalidDataProvider(): FieldCasesGenerator;

    abstract protected function getValidator();

    /**
     * @param array $values
     *
     * @dataProvider invalidDataProvider
     */
    public function testValidatorWithInvalidData(array $values): void
    {
//        $this->expectException(ValidationException::class);
        $validator = $this->getValidator();
        (new $validator($values))->validate();
    }

    /**
     * @param array $values
     *
     * @dataProvider validDataProvider
     */
    public function testValidatorWithValidValues(array $values): void
    {
        $this->expectNotToPerformAssertions();
        $validator = $this->getValidator();
        (new $validator($values))->validate();
    }
}
