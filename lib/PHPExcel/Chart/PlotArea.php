<?php

/**
 * PHPExcel_Chart_PlotArea
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
 * @category    PHPExcel
 * @package        PHPExcel_Chart
 * @copyright    Copyright (c) 2006 - 2015 PHPExcel (http://www.codeplex.com/PHPExcel)
 * @license        http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt    LGPL
 * @version        ##VERSION##, ##DATE##
 */
class PHPExcel_Chart_PlotArea
{
    /**
     * PlotArea Layout
     */
    private ?\PHPExcel_Chart_Layout $layout;

    /**
     * Plot Series
     *
     * @var array of PHPExcel_Chart_DataSeries
     */
    private $plotSeries = array();

    /**
     * Create a new PHPExcel_Chart_PlotArea
     */
    public function __construct(PHPExcel_Chart_Layout $layout = null, $plotSeries = array())
    {
        $this->layout = $layout;
        $this->plotSeries = $plotSeries;
    }

    /**
     * Get Layout
     *
     * @return PHPExcel_Chart_Layout
     */
    public function getLayout()
    {
        return $this->layout;
    }

    /**
     * Get Number of Plot Groups
     *
     * @return array of PHPExcel_Chart_DataSeries
     */
    public function getPlotGroupCount(): int
    {
        return count($this->plotSeries);
    }

    /**
     * Get Number of Plot Series
     *
     * @return integer
     */
    public function getPlotSeriesCount(): int|float
    {
        $seriesCount = 0;
        foreach ($this->plotSeries as $plot) {
            $seriesCount += $plot->getPlotSeriesCount();
        }
        return $seriesCount;
    }

    /**
     * Get Plot Series
     *
     * @return array of PHPExcel_Chart_DataSeries
     */
    public function getPlotGroup()
    {
        return $this->plotSeries;
    }

    /**
     * Get Plot Series by Index
     *
     * @return PHPExcel_Chart_DataSeries
     */
    public function getPlotGroupByIndex($index)
    {
        return $this->plotSeries[$index];
    }

    /**
     * Set Plot Series
     *
     * @param [PHPExcel_Chart_DataSeries]
     */
    public function setPlotSeries($plotSeries = array()): static
    {
        $this->plotSeries = $plotSeries;
        
        return $this;
    }

    public function refresh(PHPExcel_Worksheet $worksheet): void
    {
        foreach ($this->plotSeries as $plotSeries) {
            $plotSeries->refresh($worksheet);
        }
    }
}
