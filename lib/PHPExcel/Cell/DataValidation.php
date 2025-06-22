<?php

/**
 * PHPExcel_Cell_DataValidation
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
 * @package    PHPExcel_Cell
 * @copyright  Copyright (c) 2006 - 2015 PHPExcel (http://www.codeplex.com/PHPExcel)
 * @license    http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt    LGPL
 * @version    ##VERSION##, ##DATE##
 */
class PHPExcel_Cell_DataValidation
{
    /* Data validation types */
    const TYPE_NONE        = 'none';
    const TYPE_CUSTOM      = 'custom';
    const TYPE_DATE        = 'date';
    const TYPE_DECIMAL     = 'decimal';
    const TYPE_LIST        = 'list';
    const TYPE_TEXTLENGTH  = 'textLength';
    const TYPE_TIME        = 'time';
    const TYPE_WHOLE       = 'whole';

    /* Data validation error styles */
    const STYLE_STOP         = 'stop';
    const STYLE_WARNING      = 'warning';
    const STYLE_INFORMATION  = 'information';

    /* Data validation operators */
    const OPERATOR_BETWEEN             = 'between';
    const OPERATOR_EQUAL               = 'equal';
    const OPERATOR_GREATERTHAN         = 'greaterThan';
    const OPERATOR_GREATERTHANOREQUAL  = 'greaterThanOrEqual';
    const OPERATOR_LESSTHAN            = 'lessThan';
    const OPERATOR_LESSTHANOREQUAL     = 'lessThanOrEqual';
    const OPERATOR_NOTBETWEEN          = 'notBetween';
    const OPERATOR_NOTEQUAL            = 'notEqual';

    /**
     * Formula 1
     */
    private string $formula1;

    /**
     * Formula 2
     */
    private string $formula2;

    /**
     * Type
     */
    private string $type;

    /**
     * Error style
     */
    private string $errorStyle;

    /**
     * Operator
     */
    private string $operator;

    /**
     * Allow Blank
     */
    private bool $allowBlank;

    /**
     * Show DropDown
     */
    private bool $showDropDown;

    /**
     * Show InputMessage
     */
    private bool $showInputMessage;

    /**
     * Show ErrorMessage
     */
    private bool $showErrorMessage;

    /**
     * Error title
     */
    private string $errorTitle;

    /**
     * Error
     */
    private string $error;

    /**
     * Prompt title
     */
    private string $promptTitle;

    /**
     * Prompt
     */
    private string $prompt;

    /**
     * Create a new PHPExcel_Cell_DataValidation
     */
    public function __construct()
    {
        // Initialise member variables
        $this->formula1          = '';
        $this->formula2          = '';
        $this->type              = PHPExcel_Cell_DataValidation::TYPE_NONE;
        $this->errorStyle        = PHPExcel_Cell_DataValidation::STYLE_STOP;
        $this->operator          = '';
        $this->allowBlank        = false;
        $this->showDropDown      = false;
        $this->showInputMessage  = false;
        $this->showErrorMessage  = false;
        $this->errorTitle        = '';
        $this->error             = '';
        $this->promptTitle       = '';
        $this->prompt            = '';
    }

    /**
     * Get Formula 1
     *
     * @return string
     */
    public function getFormula1()
    {
        return $this->formula1;
    }

    /**
     * Set Formula 1
     *
     * @param  string    $value
     */
    public function setFormula1($value = ''): static
    {
        $this->formula1 = $value;
        return $this;
    }

    /**
     * Get Formula 2
     *
     * @return string
     */
    public function getFormula2()
    {
        return $this->formula2;
    }

    /**
     * Set Formula 2
     *
     * @param  string    $value
     */
    public function setFormula2($value = ''): static
    {
        $this->formula2 = $value;
        return $this;
    }

    /**
     * Get Type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set Type
     *
     * @param  string    $value
     */
    public function setType($value = PHPExcel_Cell_DataValidation::TYPE_NONE): static
    {
        $this->type = $value;
        return $this;
    }

    /**
     * Get Error style
     *
     * @return string
     */
    public function getErrorStyle()
    {
        return $this->errorStyle;
    }

    /**
     * Set Error style
     *
     * @param  string    $value
     */
    public function setErrorStyle($value = PHPExcel_Cell_DataValidation::STYLE_STOP): static
    {
        $this->errorStyle = $value;
        return $this;
    }

    /**
     * Get Operator
     *
     * @return string
     */
    public function getOperator()
    {
        return $this->operator;
    }

    /**
     * Set Operator
     *
     * @param  string    $value
     */
    public function setOperator($value = ''): static
    {
        $this->operator = $value;
        return $this;
    }

    /**
     * Get Allow Blank
     *
     * @return boolean
     */
    public function getAllowBlank()
    {
        return $this->allowBlank;
    }

    /**
     * Set Allow Blank
     *
     * @param  boolean    $value
     */
    public function setAllowBlank($value = false): static
    {
        $this->allowBlank = $value;
        return $this;
    }

    /**
     * Get Show DropDown
     *
     * @return boolean
     */
    public function getShowDropDown()
    {
        return $this->showDropDown;
    }

    /**
     * Set Show DropDown
     *
     * @param  boolean    $value
     */
    public function setShowDropDown($value = false): static
    {
        $this->showDropDown = $value;
        return $this;
    }

    /**
     * Get Show InputMessage
     *
     * @return boolean
     */
    public function getShowInputMessage()
    {
        return $this->showInputMessage;
    }

    /**
     * Set Show InputMessage
     *
     * @param  boolean    $value
     */
    public function setShowInputMessage($value = false): static
    {
        $this->showInputMessage = $value;
        return $this;
    }

    /**
     * Get Show ErrorMessage
     *
     * @return boolean
     */
    public function getShowErrorMessage()
    {
        return $this->showErrorMessage;
    }

    /**
     * Set Show ErrorMessage
     *
     * @param  boolean    $value
     */
    public function setShowErrorMessage($value = false): static
    {
        $this->showErrorMessage = $value;
        return $this;
    }

    /**
     * Get Error title
     *
     * @return string
     */
    public function getErrorTitle()
    {
        return $this->errorTitle;
    }

    /**
     * Set Error title
     *
     * @param  string    $value
     */
    public function setErrorTitle($value = ''): static
    {
        $this->errorTitle = $value;
        return $this;
    }

    /**
     * Get Error
     *
     * @return string
     */
    public function getError()
    {
        return $this->error;
    }

    /**
     * Set Error
     *
     * @param  string    $value
     */
    public function setError($value = ''): static
    {
        $this->error = $value;
        return $this;
    }

    /**
     * Get Prompt title
     *
     * @return string
     */
    public function getPromptTitle()
    {
        return $this->promptTitle;
    }

    /**
     * Set Prompt title
     *
     * @param  string    $value
     */
    public function setPromptTitle($value = ''): static
    {
        $this->promptTitle = $value;
        return $this;
    }

    /**
     * Get Prompt
     *
     * @return string
     */
    public function getPrompt()
    {
        return $this->prompt;
    }

    /**
     * Set Prompt
     *
     * @param  string    $value
     */
    public function setPrompt($value = ''): static
    {
        $this->prompt = $value;
        return $this;
    }

    /**
     * Get hash code
     *
     * @return string    Hash code
     */
    public function getHashCode(): string
    {
        return md5(
            $this->formula1 .
            $this->formula2 .
            $this->type = PHPExcel_Cell_DataValidation::TYPE_NONE .
            $this->errorStyle = PHPExcel_Cell_DataValidation::STYLE_STOP .
            $this->operator .
            ($this->allowBlank ? 't' : 'f') .
            ($this->showDropDown ? 't' : 'f') .
            ($this->showInputMessage ? 't' : 'f') .
            ($this->showErrorMessage ? 't' : 'f') .
            $this->errorTitle .
            $this->error .
            $this->promptTitle .
            $this->prompt .
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
