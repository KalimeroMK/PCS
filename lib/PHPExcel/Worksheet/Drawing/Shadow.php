<?php

/**
 * PHPExcel_Worksheet_Drawing_Shadow
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
 * @package    PHPExcel_Worksheet_Drawing
 * @copyright  Copyright (c) 2006 - 2015 PHPExcel (http://www.codeplex.com/PHPExcel)
 * @license    http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt    LGPL
 * @version    ##VERSION##, ##DATE##
 */
class PHPExcel_Worksheet_Drawing_Shadow implements PHPExcel_IComparable
{
    /* Shadow alignment */
    const SHADOW_BOTTOM       = 'b';
    const SHADOW_BOTTOM_LEFT  = 'bl';
    const SHADOW_BOTTOM_RIGHT = 'br';
    const SHADOW_CENTER       = 'ctr';
    const SHADOW_LEFT         = 'l';
    const SHADOW_TOP          = 't';
    const SHADOW_TOP_LEFT     = 'tl';
    const SHADOW_TOP_RIGHT    = 'tr';

    /**
     * Visible
     */
    private bool $visible;

    /**
     * Blur radius
     *
     * Defaults to 6
     */
    private int $blurRadius;

    /**
     * Shadow distance
     *
     * Defaults to 2
     */
    private int $distance;

    /**
     * Shadow direction (in degrees)
     */
    private int $direction;

    /**
     * Shadow alignment
     */
    private string $alignment;

    /**
     * Color
     */
    private \PHPExcel_Style_Color $color;

    /**
     * Alpha
     */
    private int $alpha;

    /**
     * Create a new PHPExcel_Worksheet_Drawing_Shadow
     */
    public function __construct()
    {
        // Initialise values
        $this->visible     = false;
        $this->blurRadius  = 6;
        $this->distance    = 2;
        $this->direction   = 0;
        $this->alignment   = PHPExcel_Worksheet_Drawing_Shadow::SHADOW_BOTTOM_RIGHT;
        $this->color       = new PHPExcel_Style_Color(PHPExcel_Style_Color::COLOR_BLACK);
        $this->alpha       = 50;
    }

    /**
     * Get Visible
     *
     * @return boolean
     */
    public function getVisible()
    {
        return $this->visible;
    }

    /**
     * Set Visible
     *
     * @param boolean $pValue
     */
    public function setVisible($pValue = false): static
    {
        $this->visible = $pValue;
        return $this;
    }

    /**
     * Get Blur radius
     *
     * @return int
     */
    public function getBlurRadius()
    {
        return $this->blurRadius;
    }

    /**
     * Set Blur radius
     *
     * @param int $pValue
     */
    public function setBlurRadius($pValue = 6): static
    {
        $this->blurRadius = $pValue;
        return $this;
    }

    /**
     * Get Shadow distance
     *
     * @return int
     */
    public function getDistance()
    {
        return $this->distance;
    }

    /**
     * Set Shadow distance
     *
     * @param int $pValue
     */
    public function setDistance($pValue = 2): static
    {
        $this->distance = $pValue;
        return $this;
    }

    /**
     * Get Shadow direction (in degrees)
     *
     * @return int
     */
    public function getDirection()
    {
        return $this->direction;
    }

    /**
     * Set Shadow direction (in degrees)
     *
     * @param int $pValue
     */
    public function setDirection($pValue = 0): static
    {
        $this->direction = $pValue;
        return $this;
    }

   /**
     * Get Shadow alignment
     *
     * @return int
     */
    public function getAlignment()
    {
        return $this->alignment;
    }

    /**
     * Set Shadow alignment
     *
     * @param int $pValue
     */
    public function setAlignment($pValue = 0): static
    {
        $this->alignment = $pValue;
        return $this;
    }

   /**
     * Get Color
     *
     * @return PHPExcel_Style_Color
     */
    public function getColor()
    {
        return $this->color;
    }

    /**
     * Set Color
     *
     * @throws     PHPExcel_Exception
     */
    public function setColor(PHPExcel_Style_Color $pValue = null): static
    {
           $this->color = $pValue;
           return $this;
    }

   /**
     * Get Alpha
     *
     * @return int
     */
    public function getAlpha()
    {
        return $this->alpha;
    }

    /**
     * Set Alpha
     *
     * @param int $pValue
     */
    public function setAlpha($pValue = 0): static
    {
        $this->alpha = $pValue;
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
            ($this->visible ? 't' : 'f') .
            $this->blurRadius .
            $this->distance .
            $this->direction .
            $this->alignment .
            $this->color->getHashCode() .
            $this->alpha .
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
