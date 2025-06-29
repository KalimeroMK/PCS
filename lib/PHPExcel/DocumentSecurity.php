<?php

/**
 * PHPExcel_DocumentSecurity
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
 * @package    PHPExcel
 * @copyright  Copyright (c) 2006 - 2015 PHPExcel (http://www.codeplex.com/PHPExcel)
 * @license    http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt    LGPL
 * @version    ##VERSION##, ##DATE##
 */
class PHPExcel_DocumentSecurity
{
    /**
     * LockRevision
     */
    private bool $lockRevision;

    /**
     * LockStructure
     */
    private bool $lockStructure;

    /**
     * LockWindows
     */
    private bool $lockWindows;

    /**
     * RevisionsPassword
     */
    private string $revisionsPassword;

    /**
     * WorkbookPassword
     */
    private string $workbookPassword;

    /**
     * Create a new PHPExcel_DocumentSecurity
     */
    public function __construct()
    {
        // Initialise values
        $this->lockRevision      = false;
        $this->lockStructure     = false;
        $this->lockWindows       = false;
        $this->revisionsPassword = '';
        $this->workbookPassword  = '';
    }

    /**
     * Is some sort of document security enabled?
     */
    public function isSecurityEnabled(): bool
    {
        return  $this->lockRevision ||
                $this->lockStructure ||
                $this->lockWindows;
    }

    /**
     * Get LockRevision
     *
     * @return boolean
     */
    public function getLockRevision()
    {
        return $this->lockRevision;
    }

    /**
     * Set LockRevision
     *
     * @param boolean $pValue
     */
    public function setLockRevision($pValue = false): static
    {
        $this->lockRevision = $pValue;
        return $this;
    }

    /**
     * Get LockStructure
     *
     * @return boolean
     */
    public function getLockStructure()
    {
        return $this->lockStructure;
    }

    /**
     * Set LockStructure
     *
     * @param boolean $pValue
     */
    public function setLockStructure($pValue = false): static
    {
        $this->lockStructure = $pValue;
        return $this;
    }

    /**
     * Get LockWindows
     *
     * @return boolean
     */
    public function getLockWindows()
    {
        return $this->lockWindows;
    }

    /**
     * Set LockWindows
     *
     * @param boolean $pValue
     */
    public function setLockWindows($pValue = false): static
    {
        $this->lockWindows = $pValue;
        return $this;
    }

    /**
     * Get RevisionsPassword (hashed)
     *
     * @return string
     */
    public function getRevisionsPassword()
    {
        return $this->revisionsPassword;
    }

    /**
     * Set RevisionsPassword
     *
     * @param string     $pValue
     * @param boolean     $pAlreadyHashed If the password has already been hashed, set this to true
     */
    public function setRevisionsPassword($pValue = '', $pAlreadyHashed = false): static
    {
        if (!$pAlreadyHashed) {
            $pValue = PHPExcel_Shared_PasswordHasher::hashPassword($pValue);
        }
        $this->revisionsPassword = $pValue;
        return $this;
    }

    /**
     * Get WorkbookPassword (hashed)
     *
     * @return string
     */
    public function getWorkbookPassword()
    {
        return $this->workbookPassword;
    }

    /**
     * Set WorkbookPassword
     *
     * @param string     $pValue
     * @param boolean     $pAlreadyHashed If the password has already been hashed, set this to true
     */
    public function setWorkbookPassword($pValue = '', $pAlreadyHashed = false): static
    {
        if (!$pAlreadyHashed) {
            $pValue = PHPExcel_Shared_PasswordHasher::hashPassword($pValue);
        }
        $this->workbookPassword = $pValue;
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
