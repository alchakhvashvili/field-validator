<?php

declare(strict_types=1);

namespace Alchakhvashvili\FieldValidator;

use ArrayIterator;
use IteratorAggregate;

class FieldCasesGenerator implements IteratorAggregate
{
    private array $requestBodyTemplate;

    /**
     *  $fieldRules = [
     *      [dataKey] => 'empty',
     *      [dataKey] => 'string:12',
     *      [dataKey] => 'float',
     *      [dataKey] => 'val:abc',
     *      [dataKey] => 'intVal:12',
     *      [dataKey] => 'emptyArray',
     *      [dataKey] => 'arrayKeyHasEmptyValue:key',
     *      [dataKey] => 'empty|string|val:abcd',
     *      [dataKey] => 'empty|string:25|intVal:12',
     * ],
     */
    private array $fieldRules;

    private array $generatedDataSet;

    /**
     * @param array $fieldRules
     * @param array $requestBodyTemplate
     */
    public function __construct(array $fieldRules, array $requestBodyTemplate)
    {
        $this->fieldRules = $fieldRules;
        $this->requestBodyTemplate = $requestBodyTemplate;
        $this->generatedDataSet = [];
    }

    public function getIterator(): \Traversable
    {
        return new ArrayIterator(
            $this->generate()
        );
    }

    private function generate()
    {
        foreach ($this->fieldRules as $fieldKey => $field) {
            $generatorCase = explode('|', $field);
            foreach ($generatorCase as $caseKey => $case) {
                $value = $this->getValueByCase($case);
                $this->pushInvalidDataToDataset($fieldKey, $value, $case);
            }
        }
        return $this->generatedDataSet;
    }

    private function pushInvalidDataToDataset(string $key, $value, $case)
    {
        $this->generatedDataSet[$key . ':' . $case] = [
            array_merge($this->requestBodyTemplate, [$key => $value])
        ];
    }

    /**
     * Get value by case, adding new case is possible if needed.
     *
     * @param $case
     * @return array|float|int|string
     * @throws \Exception
     */
    private function getValueByCase($case): array | float | int | string
    {
        $splittedCase = explode(':', $case);
        $caseMethod = $splittedCase[0];
        if (count($splittedCase) > 1) {
            $caseValue = substr($case, strlen($caseMethod) + 1);
        }
        return match ($caseMethod) {
            'empty' => '',
            'string' => $this->getRandomString((int)$caseValue),
            'float' => $this->getFloatNumber(),
            'val' => $caseValue,
            'intVal' => (int)$caseValue,
            'emptyArray' => [],
            'arrayKeyHasEmptyValue' => [$caseValue => ''],
            default => throw new \Exception($caseMethod . ' is an unpredictable case!'),
        };
    }

    private function getRandomString($length = 10): string
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randomString = '';

        for ($i = 0; $i < $length; $i++) {
            $index = rand(0, strlen($characters) - 1);
            $randomString .= $characters[$index];
        }

        return $randomString;
    }

    private function getFloatNumber(): float
    {
        $left = rand(10, 100);
        $right = rand(10, 100);
        return (float) ($left . '.' . $right);
    }
}
