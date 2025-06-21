<?php

/**
 * This file is part of FPDI
 *
 * @package   setasign\Fpdi
 * @copyright Copyright (c) 2020 Setasign GmbH & Co. KG (https://www.setasign.com)
 * @license   http://opensource.org/licenses/mit-license The MIT License
 */

namespace setasign\Fpdi\PdfReader\DataStructure;

use setasign\Fpdi\PdfParser\CrossReference\CrossReferenceException;
use setasign\Fpdi\PdfParser\PdfParser;
use setasign\Fpdi\PdfParser\PdfParserException;
use setasign\Fpdi\PdfParser\Type\PdfArray;
use setasign\Fpdi\PdfParser\Type\PdfNumeric;
use setasign\Fpdi\PdfParser\Type\PdfType;
use setasign\Fpdi\PdfParser\Type\PdfTypeException;

/**
 * Class representing a rectangle
 */
class Rectangle
{
    protected float|int $llx;

    protected float|int $lly;

    protected float|int $urx;

    protected float|int $ury;

    /**
     * Create a rectangle instance by a PdfArray.
     *
     * @param PdfArray|mixed $array
     * @throws PdfTypeException
     * @throws CrossReferenceException
     * @throws PdfParserException
     */
    public static function byPdfArray(array $array, PdfParser $parser): self
    {
        $array = PdfArray::ensure(PdfType::resolve($array, $parser), 4)->value;
        $ax = PdfNumeric::ensure(PdfType::resolve($array[0], $parser))->value;
        $ay = PdfNumeric::ensure(PdfType::resolve($array[1], $parser))->value;
        $bx = PdfNumeric::ensure(PdfType::resolve($array[2], $parser))->value;
        $by = PdfNumeric::ensure(PdfType::resolve($array[3], $parser))->value;

        return new self($ax, $ay, $bx, $by);
    }

    /**
     * Rectangle constructor.
     *
     * @param float|int $ax
     * @param float|int $ay
     * @param float|int $bx
     * @param float|int $by
     */
    public function __construct($ax, $ay, $bx, $by)
    {
        $this->llx = \min($ax, $bx);
        $this->lly = \min($ay, $by);
        $this->urx = \max($ax, $bx);
        $this->ury = \max($ay, $by);
    }

    /**
     * Get the width of the rectangle.
     */
    public function getWidth(): int|float
    {
        return $this->urx - $this->llx;
    }

    /**
     * Get the height of the rectangle.
     */
    public function getHeight(): int|float
    {
        return $this->ury - $this->lly;
    }

    /**
     * Get the lower left abscissa.
     *
     * @return float|int
     */
    public function getLlx()
    {
        return $this->llx;
    }

    /**
     * Get the lower left ordinate.
     *
     * @return float|int
     */
    public function getLly()
    {
        return $this->lly;
    }

    /**
     * Get the upper right abscissa.
     *
     * @return float|int
     */
    public function getUrx()
    {
        return $this->urx;
    }

    /**
     * Get the upper right ordinate.
     *
     * @return float|int
     */
    public function getUry()
    {
        return $this->ury;
    }

    /**
     * Get the rectangle as an array.
     */
    public function toArray(): array
    {
        return [
            $this->llx,
            $this->lly,
            $this->urx,
            $this->ury
        ];
    }

    /**
     * Get the rectangle as a PdfArray.
     */
    public function toPdfArray(): \setasign\Fpdi\PdfParser\Type\PdfArray
    {
        $array = new PdfArray();
        $array->value[] = PdfNumeric::create($this->llx);
        $array->value[] = PdfNumeric::create($this->lly);
        $array->value[] = PdfNumeric::create($this->urx);
        $array->value[] = PdfNumeric::create($this->ury);

        return $array;
    }
}
