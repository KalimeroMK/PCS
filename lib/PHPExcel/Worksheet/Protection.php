<?php
/**
 * PHPExcel
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
 * @package    PHPExcel_Worksheet
 * @copyright  Copyright (c) 2006 - 2015 PHPExcel (http://www.codeplex.com/PHPExcel)
 * @license    http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt    LGPL
 * @version    ##VERSION##, ##DATE##
 */


/**
 * PHPExcel_Worksheet_Protection
 *
 * @category   PHPExcel
 * @package    PHPExcel_Worksheet
 * @copyright  Copyright (c) 2006 - 2015 PHPExcel (http://www.codeplex.com/PHPExcel)
 */
class PHPExcel_Worksheet_Protection
{
    /**
     * Sheet
     *
     * @var boolean
     */
    private $sheet                    = false;

    /**
     * Objects
     *
     * @var boolean
     */
    private $objects                = false;

    /**
     * Scenarios
     *
     * @var boolean
     */
    private $scenarios                = false;

    /**
     * Format cells
     *
     * @var boolean
     */
    private $formatCells            = false;

    /**
     * Format columns
     *
     * @var boolean
     */
    private $formatColumns            = false;

    /**
     * Format rows
     *
     * @var boolean
     */
    private $formatRows            = false;

    /**
     * Insert columns
     *
     * @var boolean
     */
    private $insertColumns            = false;

    /**
     * Insert rows
     *
     * @var boolean
     */
    private $insertRows            = false;

    /**
     * Insert hyperlinks
     *
     * @var boolean
     */
    private $insertHyperlinks        = false;

    /**
     * Delete columns
     *
     * @var boolean
     */
    private $deleteColumns            = false;

    /**
     * Delete rows
     *
     * @var boolean
     */
    private $deleteRows            = false;

    /**
     * Select locked cells
     *
     * @var boolean
     */
    private $selectLockedCells        = false;

    /**
     * Sort
     *
     * @var boolean
     */
    private $sort                    = false;

    /**
     * AutoFilter
     *
     * @var boolean
     */
    private $autoFilter            = false;

    /**
     * Pivot tables
     *
     * @var boolean
     */
    private $pivotTables            = false;

    /**
     * Select unlocked cells
     *
     * @var boolean
     */
    private $selectUnlockedCells    = false;

    /**
     * Password
     *
     * @var string
     */
    private $password                = '';

    /**
     * Is some sort of protection enabled?
     */
    public function isProtectionEnabled(): bool
    {
        return $this->sheet ||
            $this->objects ||
            $this->scenarios ||
            $this->formatCells ||
            $this->formatColumns ||
            $this->formatRows ||
            $this->insertColumns ||
            $this->insertRows ||
            $this->insertHyperlinks ||
            $this->deleteColumns ||
            $this->deleteRows ||
            $this->selectLockedCells ||
            $this->sort ||
            $this->autoFilter ||
            $this->pivotTables ||
            $this->selectUnlockedCells;
    }

    /**
     * Get Sheet
     *
     * @return boolean
     */
    public function getSheet()
    {
        return $this->sheet;
    }

    /**
     * Set Sheet
     *
     * @param boolean $pValue
     */
    public function setSheet($pValue = false): static
    {
        $this->sheet = $pValue;
        return $this;
    }

    /**
     * Get Objects
     *
     * @return boolean
     */
    public function getObjects()
    {
        return $this->objects;
    }

    /**
     * Set Objects
     *
     * @param boolean $pValue
     */
    public function setObjects($pValue = false): static
    {
        $this->objects = $pValue;
        return $this;
    }

    /**
     * Get Scenarios
     *
     * @return boolean
     */
    public function getScenarios()
    {
        return $this->scenarios;
    }

    /**
     * Set Scenarios
     *
     * @param boolean $pValue
     */
    public function setScenarios($pValue = false): static
    {
        $this->scenarios = $pValue;
        return $this;
    }

    /**
     * Get FormatCells
     *
     * @return boolean
     */
    public function getFormatCells()
    {
        return $this->formatCells;
    }

    /**
     * Set FormatCells
     *
     * @param boolean $pValue
     */
    public function setFormatCells($pValue = false): static
    {
        $this->formatCells = $pValue;
        return $this;
    }

    /**
     * Get FormatColumns
     *
     * @return boolean
     */
    public function getFormatColumns()
    {
        return $this->formatColumns;
    }

    /**
     * Set FormatColumns
     *
     * @param boolean $pValue
     */
    public function setFormatColumns($pValue = false): static
    {
        $this->formatColumns = $pValue;
        return $this;
    }

    /**
     * Get FormatRows
     *
     * @return boolean
     */
    public function getFormatRows()
    {
        return $this->formatRows;
    }

    /**
     * Set FormatRows
     *
     * @param boolean $pValue
     */
    public function setFormatRows($pValue = false): static
    {
        $this->formatRows = $pValue;
        return $this;
    }

    /**
     * Get InsertColumns
     *
     * @return boolean
     */
    public function getInsertColumns()
    {
        return $this->insertColumns;
    }

    /**
     * Set InsertColumns
     *
     * @param boolean $pValue
     */
    public function setInsertColumns($pValue = false): static
    {
        $this->insertColumns = $pValue;
        return $this;
    }

    /**
     * Get InsertRows
     *
     * @return boolean
     */
    public function getInsertRows()
    {
        return $this->insertRows;
    }

    /**
     * Set InsertRows
     *
     * @param boolean $pValue
     */
    public function setInsertRows($pValue = false): static
    {
        $this->insertRows = $pValue;
        return $this;
    }

    /**
     * Get InsertHyperlinks
     *
     * @return boolean
     */
    public function getInsertHyperlinks()
    {
        return $this->insertHyperlinks;
    }

    /**
     * Set InsertHyperlinks
     *
     * @param boolean $pValue
     */
    public function setInsertHyperlinks($pValue = false): static
    {
        $this->insertHyperlinks = $pValue;
        return $this;
    }

    /**
     * Get DeleteColumns
     *
     * @return boolean
     */
    public function getDeleteColumns()
    {
        return $this->deleteColumns;
    }

    /**
     * Set DeleteColumns
     *
     * @param boolean $pValue
     */
    public function setDeleteColumns($pValue = false): static
    {
        $this->deleteColumns = $pValue;
        return $this;
    }

    /**
     * Get DeleteRows
     *
     * @return boolean
     */
    public function getDeleteRows()
    {
        return $this->deleteRows;
    }

    /**
     * Set DeleteRows
     *
     * @param boolean $pValue
     */
    public function setDeleteRows($pValue = false): static
    {
        $this->deleteRows = $pValue;
        return $this;
    }

    /**
     * Get SelectLockedCells
     *
     * @return boolean
     */
    public function getSelectLockedCells()
    {
        return $this->selectLockedCells;
    }

    /**
     * Set SelectLockedCells
     *
     * @param boolean $pValue
     */
    public function setSelectLockedCells($pValue = false): static
    {
        $this->selectLockedCells = $pValue;
        return $this;
    }

    /**
     * Get Sort
     *
     * @return boolean
     */
    public function getSort()
    {
        return $this->sort;
    }

    /**
     * Set Sort
     *
     * @param boolean $pValue
     */
    public function setSort($pValue = false): static
    {
        $this->sort = $pValue;
        return $this;
    }

    /**
     * Get AutoFilter
     *
     * @return boolean
     */
    public function getAutoFilter()
    {
        return $this->autoFilter;
    }

    /**
     * Set AutoFilter
     *
     * @param boolean $pValue
     */
    public function setAutoFilter($pValue = false): static
    {
        $this->autoFilter = $pValue;
        return $this;
    }

    /**
     * Get PivotTables
     *
     * @return boolean
     */
    public function getPivotTables()
    {
        return $this->pivotTables;
    }

    /**
     * Set PivotTables
     *
     * @param boolean $pValue
     */
    public function setPivotTables($pValue = false): static
    {
        $this->pivotTables = $pValue;
        return $this;
    }

    /**
     * Get SelectUnlockedCells
     *
     * @return boolean
     */
    public function getSelectUnlockedCells()
    {
        return $this->selectUnlockedCells;
    }

    /**
     * Set SelectUnlockedCells
     *
     * @param boolean $pValue
     */
    public function setSelectUnlockedCells($pValue = false): static
    {
        $this->selectUnlockedCells = $pValue;
        return $this;
    }

    /**
     * Get Password (hashed)
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set Password
     *
     * @param string     $pValue
     * @param boolean     $pAlreadyHashed If the password has already been hashed, set this to true
     */
    public function setPassword($pValue = '', $pAlreadyHashed = false): static
    {
        if (!$pAlreadyHashed) {
            $pValue = PHPExcel_Shared_PasswordHasher::hashPassword($pValue);
        }
        $this->password = $pValue;
        return $this;
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
