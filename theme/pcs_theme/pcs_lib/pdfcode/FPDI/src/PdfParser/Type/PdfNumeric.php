<?php

/**
 * This file is part of FPDI
 *
 * @package   setasign\Fpdi
 * @copyright Copyright (c) 2020 Setasign GmbH & Co. KG (https://www.setasign.com)
 * @license   http://opensource.org/licenses/mit-license The MIT License
 */

namespace setasign\Fpdi\PdfParser\Type;

/**
 * Class representing a numeric PDF object
 */
class PdfNumeric extends PdfType
{
    /**
     * Helper method to create an instance.
     *
     * @param int|float $value
     */
    public static function create($value): self
    {
        $v = new self();
        $v->value = $value + 0;

        return $v;
    }

    /**
     * Ensures that the passed value is a PdfNumeric instance.
     *
     * @param mixed $value
     * @return self
     * @throws PdfTypeException
     */
    public static function ensure($value)
    {
        return PdfType::ensureType(self::class, $value, 'Numeric value expected.');
    }
}
