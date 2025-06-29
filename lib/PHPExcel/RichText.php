<?php

/**
 * PHPExcel_RichText
 *
 * Copyright (c) 2006 - 2015 PHPExcel
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 2.1 of the License, or (at your option) any later version.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this library; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301  USA
 *
 * @category   PHPExcel
 * @package    PHPExcel_RichText
 * @copyright  Copyright (c) 2006 - 2015 PHPExcel (http://www.codeplex.com/PHPExcel)
 * @license    http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt    LGPL
 * @version    ##VERSION##, ##DATE##
 */
class PHPExcel_RichText implements PHPExcel_IComparable
{
    /**
     * Rich text elements
     *
     * @var PHPExcel_RichText_ITextElement[]
     */
    private array $richTextElements;

    /**
     * Create a new PHPExcel_RichText instance
     *
     * @throws PHPExcel_Exception
     */
    public function __construct(PHPExcel_Cell $pCell = null)
    {
        // Initialise variables
        $this->richTextElements = array();

        // Rich-Text string attached to cell?
        if ($pCell !== null) {
            // Add cell text and style
            if ($pCell->getValue() != "") {
                $objRun = new PHPExcel_RichText_Run($pCell->getValue());
                $objRun->setFont(clone $pCell->getParent()->getStyle($pCell->getCoordinate())->getFont());
                $this->addText($objRun);
            }

            // Set parent value
            $pCell->setValueExplicit($this, PHPExcel_Cell_DataType::TYPE_STRING);
        }
    }

    /**
     * Add text
     *
     * @param PHPExcel_RichText_ITextElement $pText Rich text element
     * @throws PHPExcel_Exception
     */
    public function addText(PHPExcel_RichText_ITextElement $pText = null): static
    {
        $this->richTextElements[] = $pText;
        return $this;
    }

    /**
     * Create text
     *
     * @param string $pText Text
     * @throws PHPExcel_Exception
     */
    public function createText($pText = ''): \PHPExcel_RichText_TextElement
    {
        $objText = new PHPExcel_RichText_TextElement($pText);
        $this->addText($objText);
        return $objText;
    }

    /**
     * Create text run
     *
     * @param string $pText Text
     * @throws PHPExcel_Exception
     */
    public function createTextRun($pText = ''): \PHPExcel_RichText_Run
    {
        $objText = new PHPExcel_RichText_Run($pText);
        $this->addText($objText);
        return $objText;
    }

    /**
     * Get plain text
     */
    public function getPlainText(): string
    {
        // Return value
        $returnValue = '';

        // Loop through all PHPExcel_RichText_ITextElement
        foreach ($this->richTextElements as $text) {
            $returnValue .= $text->getText();
        }

        // Return
        return $returnValue;
    }

    /**
     * Convert to string
     */
    public function __toString(): string
    {
        return $this->getPlainText();
    }

    /**
     * Get Rich Text elements
     *
     * @return PHPExcel_RichText_ITextElement[]
     */
    public function getRichTextElements()
    {
        return $this->richTextElements;
    }

    /**
     * Set Rich Text elements
     *
     * @param PHPExcel_RichText_ITextElement[] $pElements Array of elements
     * @throws PHPExcel_Exception
     */
    public function setRichTextElements($pElements = null): static
    {
        if (is_array($pElements)) {
            $this->richTextElements = $pElements;
        } else {
            throw new PHPExcel_Exception("Invalid PHPExcel_RichText_ITextElement[] array passed.");
        }
        return $this;
    }

    /**
     * Get hash code
     *
     * @return string    Hash code
     */
    public function getHashCode(): string
    {
        $hashElements = '';
        foreach ($this->richTextElements as $element) {
            $hashElements .= $element->getHashCode();
        }

        return md5(
            $hashElements .
            __CLASS__
        );
    }

    /**
     * Implement PHP __clone to create a deep clone, not just a shallow copy.
     */
    public function __clone()
    {
        $vars = get_object_vars($this);
        foreach ($vars as $key => $value) {
            $this->$key = is_object($value) ? clone $value : $value;
        }
    }
}
