<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Component\Validator\ConstraintViolationListInterface;

use Jawira\CaseConverter\Convert;

class ValidationTransformer
{
    /**
     * @param ConstraintViolationListInterface $violations
     * @return array
     * @throws \Jawira\CaseConverter\CaseConverterException
     */
    public function transform(ConstraintViolationListInterface $violations): array
    {
        $result = [];

        foreach ($violations as $violation) {
            $params = preg_split('/\[|\]|\./', $violation->getPropertyPath(), null, PREG_SPLIT_NO_EMPTY);

            $array = $violation->getMessage();

            for ($i = count($params) - 1; $i >= 0; $i--) {
                $array = [
                    $params[$i] => $array
                ];
            }

            $result = array_replace_recursive($array, $result);
        }

        $result = $this->convertKeysToSnake($result);

        return $result;
    }

    /**
     * @param array $array
     * @return array
     * @throws \Jawira\CaseConverter\CaseConverterException
     */
    private function convertKeysToSnake(array $array): array
    {
        $result = [];

        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $value = $this->convertKeysToSnake($value);
            }

            $key = (new Convert($key))->toSnake();
            $result[$key] = $value;
        }

        return $result;
    }
}
