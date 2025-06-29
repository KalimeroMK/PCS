<?php

/**
 * Created by PhpStorm.
 * User: Wiktor Trzonkowski
 * Date: 6/17/14
 * Time: 12:11 PM
 */

class PHPExcel_Chart_Axis extends PHPExcel_Chart_Properties
{
    /**
     * @var string
     */
    public $orientation;
    /**
     * Axis Number
     *
     * @var  array of mixed
     */
    private array $axisNumber = array(
        'format' => self::FORMAT_CODE_GENERAL,
        'source_linked' => 1
    );

    /**
     * Axis Options
     *
     * @var  array of mixed
     */
    private array $axisOptions = array(
        'minimum' => null,
        'maximum' => null,
        'major_unit' => null,
        'minor_unit' => null,
        'orientation' => self::ORIENTATION_NORMAL,
        'minor_tick_mark' => self::TICK_MARK_NONE,
        'major_tick_mark' => self::TICK_MARK_NONE,
        'axis_labels' => self::AXIS_LABELS_NEXT_TO,
        'horizontal_crosses' => self::HORIZONTAL_CROSSES_AUTOZERO,
        'horizontal_crosses_value' => null
    );

    /**
     * Fill Properties
     *
     * @var  array of mixed
     */
    private $fillProperties = array(
        'type' => self::EXCEL_COLOR_TYPE_ARGB,
        'value' => null,
        'alpha' => 0
    );

    /**
     * Line Properties
     *
     * @var  array of mixed
     */
    private $lineProperties = array(
        'type' => self::EXCEL_COLOR_TYPE_ARGB,
        'value' => null,
        'alpha' => 0
    );

    /**
     * Line Style Properties
     *
     * @var  array of mixed
     */
    private array $lineStyleProperties = array(
        'width' => '9525',
        'compound' => self::LINE_STYLE_COMPOUND_SIMPLE,
        'dash' => self::LINE_STYLE_DASH_SOLID,
        'cap' => self::LINE_STYLE_CAP_FLAT,
        'join' => self::LINE_STYLE_JOIN_BEVEL,
        'arrow' => array(
            'head' => array(
                'type' => self::LINE_STYLE_ARROW_TYPE_NOARROW,
                'size' => self::LINE_STYLE_ARROW_SIZE_5
            ),
            'end' => array(
                'type' => self::LINE_STYLE_ARROW_TYPE_NOARROW,
                'size' => self::LINE_STYLE_ARROW_SIZE_8
            ),
        )
    );

    /**
     * Shadow Properties
     *
     * @var  array of mixed
     */
    private $shadowProperties = array(
        'presets' => self::SHADOW_PRESETS_NOSHADOW,
        'effect' => null,
        'color' => array(
            'type' => self::EXCEL_COLOR_TYPE_STANDARD,
            'value' => 'black',
            'alpha' => 40,
        ),
        'size' => array(
            'sx' => null,
            'sy' => null,
            'kx' => null
        ),
        'blur' => null,
        'direction' => null,
        'distance' => null,
        'algn' => null,
        'rotWithShape' => null
    );

    /**
     * Glow Properties
     *
     * @var  array of mixed
     */
    private array $glowProperties = array(
        'size' => null,
        'color' => array(
            'type' => self::EXCEL_COLOR_TYPE_STANDARD,
            'value' => 'black',
            'alpha' => 40
        )
    );

    /**
     * Soft Edge Properties
     *
     * @var  array of mixed
     */
    private array $softEdges = array(
        'size' => null
    );

    /**
     * Get Series Data Type
     */
    public function setAxisNumberProperties($format_code): void
    {
        $this->axisNumber['format'] = (string) $format_code;
        $this->axisNumber['source_linked'] = 0;
    }

    /**
     * Get Axis Number Format Data Type
     *
     * @return  string
     */
    public function getAxisNumberFormat()
    {
        return $this->axisNumber['format'];
    }

    /**
     * Get Axis Number Source Linked
     */
    public function getAxisNumberSourceLinked(): string
    {
        return (string) $this->axisNumber['source_linked'];
    }

    /**
     * Set Axis Options Properties
     *
     * @param string $axis_labels
     * @param string $horizontal_crosses_value
     * @param string $horizontal_crosses
     * @param string $axis_orientation
     * @param string $major_tmt
     * @param string $minor_tmt
     * @param string $minimum
     * @param string $maximum
     * @param string $major_unit
     * @param string $minor_unit
     *
     */
    public function setAxisOptionsProperties($axis_labels, $horizontal_crosses_value = null, $horizontal_crosses = null, $axis_orientation = null, $major_tmt = null, $minor_tmt = null, $minimum = null, $maximum = null, $major_unit = null, $minor_unit = null): void
    {
        $this->axisOptions['axis_labels'] = (string) $axis_labels;
        ($horizontal_crosses_value !== null) ? $this->axisOptions['horizontal_crosses_value'] = (string) $horizontal_crosses_value : null;
        ($horizontal_crosses !== null) ? $this->axisOptions['horizontal_crosses'] = (string) $horizontal_crosses : null;
        ($axis_orientation !== null) ? $this->axisOptions['orientation'] = (string) $axis_orientation : null;
        ($major_tmt !== null) ? $this->axisOptions['major_tick_mark'] = (string) $major_tmt : null;
        ($minor_tmt !== null) ? $this->axisOptions['minor_tick_mark'] = (string) $minor_tmt : null;
        ($minor_tmt !== null) ? $this->axisOptions['minor_tick_mark'] = (string) $minor_tmt : null;
        ($minimum !== null) ? $this->axisOptions['minimum'] = (string) $minimum : null;
        ($maximum !== null) ? $this->axisOptions['maximum'] = (string) $maximum : null;
        ($major_unit !== null) ? $this->axisOptions['major_unit'] = (string) $major_unit : null;
        ($minor_unit !== null) ? $this->axisOptions['minor_unit'] = (string) $minor_unit : null;
    }

    /**
     * Get Axis Options Property
     *
     * @param string $property
     *
     * @return string
     */
    public function getAxisOptionsProperty($property)
    {
        return $this->axisOptions[$property];
    }

    /**
     * Set Axis Orientation Property
     *
     * @param string $orientation
     *
     */
    public function setAxisOrientation($orientation): void
    {
        $this->orientation = (string) $orientation;
    }

    /**
     * Set Fill Property
     *
     * @param string $color
     * @param int $alpha
     * @param string $type
     *
     */
    public function setFillParameters($color, $alpha = 0, $type = self::EXCEL_COLOR_TYPE_ARGB): void
    {
        $this->fillProperties = $this->setColorProperties($color, $alpha, $type);
    }

    /**
     * Set Line Property
     *
     * @param string $color
     * @param int $alpha
     * @param string $type
     *
     */
    public function setLineParameters($color, $alpha = 0, $type = self::EXCEL_COLOR_TYPE_ARGB): void
    {
        $this->lineProperties = $this->setColorProperties($color, $alpha, $type);
    }

    /**
     * Get Fill Property
     *
     * @param string $property
     *
     * @return string
     */
    public function getFillProperty($property)
    {
        return $this->fillProperties[$property];
    }

    /**
     * Get Line Property
     *
     * @param string $property
     *
     * @return string
     */
    public function getLineProperty($property)
    {
        return $this->lineProperties[$property];
    }

    /**
     * Set Line Style Properties
     *
     * @param float $line_width
     * @param string $compound_type
     * @param string $dash_type
     * @param string $cap_type
     * @param string $join_type
     * @param string $head_arrow_type
     * @param string $head_arrow_size
     * @param string $end_arrow_type
     * @param string $end_arrow_size
     *
     */
    public function setLineStyleProperties($line_width = null, $compound_type = null, $dash_type = null, $cap_type = null, $join_type = null, $head_arrow_type = null, $head_arrow_size = null, $end_arrow_type = null, $end_arrow_size = null): void
    {
        (is_null($line_width)) ? null : $this->lineStyleProperties['width'] = $this->getExcelPointsWidth((float) $line_width);
        (is_null($compound_type)) ? null : $this->lineStyleProperties['compound'] = (string) $compound_type;
        (is_null($dash_type)) ? null : $this->lineStyleProperties['dash'] = (string) $dash_type;
        (is_null($cap_type)) ? null : $this->lineStyleProperties['cap'] = (string) $cap_type;
        (is_null($join_type)) ? null : $this->lineStyleProperties['join'] = (string) $join_type;
        (is_null($head_arrow_type)) ? null : $this->lineStyleProperties['arrow']['head']['type'] = (string) $head_arrow_type;
        (is_null($head_arrow_size)) ? null : $this->lineStyleProperties['arrow']['head']['size'] = (string) $head_arrow_size;
        (is_null($end_arrow_type)) ? null : $this->lineStyleProperties['arrow']['end']['type'] = (string) $end_arrow_type;
        (is_null($end_arrow_size)) ? null : $this->lineStyleProperties['arrow']['end']['size'] = (string) $end_arrow_size;
    }

    /**
     * Get Line Style Property
     *
     * @param array|string $elements
     *
     * @return string
     */
    public function getLineStyleProperty($elements)
    {
        return $this->getArrayElementsValue($this->lineStyleProperties, $elements);
    }

    /**
     * Get Line Style Arrow Excel Width
     *
     * @param string $arrow
     *
     * @return string
     */
    public function getLineStyleArrowWidth($arrow)
    {
        return $this->getLineStyleArrowSize($this->lineStyleProperties['arrow'][$arrow]['size'], 'w');
    }

    /**
     * Get Line Style Arrow Excel Length
     *
     * @param string $arrow
     *
     * @return string
     */
    public function getLineStyleArrowLength($arrow)
    {
        return $this->getLineStyleArrowSize($this->lineStyleProperties['arrow'][$arrow]['size'], 'len');
    }

    /**
     * Set Shadow Properties
     *
     * @param int $shadow_presets
     * @param string $sh_color_value
     * @param string $sh_color_type
     * @param string $sh_color_alpha
     * @param float $sh_blur
     * @param int $sh_angle
     * @param float $sh_distance
     *
     */
    public function setShadowProperties($sh_presets, $sh_color_value = null, $sh_color_type = null, $sh_color_alpha = null, $sh_blur = null, $sh_angle = null, $sh_distance = null): void
    {
        $this->setShadowPresetsProperties((int) $sh_presets)
            ->setShadowColor(
                is_null($sh_color_value) ? $this->shadowProperties['color']['value'] : $sh_color_value,
                is_null($sh_color_alpha) ? (int) $this->shadowProperties['color']['alpha'] : $sh_color_alpha,
                is_null($sh_color_type) ? $this->shadowProperties['color']['type'] : $sh_color_type
            )
            ->setShadowBlur($sh_blur)
            ->setShadowAngle($sh_angle)
            ->setShadowDistance($sh_distance);
    }

    /**
     * Set Shadow Color
     *
     *
     */
    private function setShadowPresetsProperties(int $shadow_presets): static
    {
        $this->shadowProperties['presets'] = $shadow_presets;
        $this->setShadowProperiesMapValues($this->getShadowPresetsMap($shadow_presets));

        return $this;
    }

    /**
     * Set Shadow Properties from Maped Values
     *
     * @param * $reference
     *
     */
    private function setShadowProperiesMapValues(array $properties_map, &$reference = null): static
    {
        $base_reference = $reference;
        foreach ($properties_map as $property_key => $property_val) {
            if (is_array($property_val)) {
                if ($reference === null) {
                    $reference = & $this->shadowProperties[$property_key];
                } else {
                    $reference = & $reference[$property_key];
                }
                $this->setShadowProperiesMapValues($property_val, $reference);
            } elseif ($base_reference === null) {
                $this->shadowProperties[$property_key] = $property_val;
            } else {
                $reference[$property_key] = $property_val;
            }
        }

        return $this;
    }

    /**
     * Set Shadow Color
     *
     * @param string $color
     * @param int $alpha
     * @param string $type
     */
    private function setShadowColor($color, $alpha, $type): static
    {
        $this->shadowProperties['color'] = $this->setColorProperties($color, $alpha, $type);

        return $this;
    }

    /**
     * Set Shadow Blur
     *
     * @param float $blur
     */
    private function setShadowBlur($blur): static
    {
        if ($blur !== null) {
            $this->shadowProperties['blur'] = (string) $this->getExcelPointsWidth($blur);
        }

        return $this;
    }

    /**
     * Set Shadow Angle
     *
     * @param int $angle
     */
    private function setShadowAngle($angle): static
    {
        if ($angle !== null) {
            $this->shadowProperties['direction'] = (string) $this->getExcelPointsAngle($angle);
        }

        return $this;
    }

    /**
     * Set Shadow Distance
     *
     * @param float $distance
     */
    private function setShadowDistance($distance): static
    {
        if ($distance !== null) {
            $this->shadowProperties['distance'] = (string) $this->getExcelPointsWidth($distance);
        }

        return $this;
    }

    /**
     * Get Glow Property
     *
     * @param float $size
     * @param string $color_value
     * @param int $color_alpha
     * @param string $color_type
     */
    public function getShadowProperty($elements)
    {
        return $this->getArrayElementsValue($this->shadowProperties, $elements);
    }

    /**
     * Set Glow Properties
     *
     * @param float $size
     * @param string $color_value
     * @param int $color_alpha
     * @param string $color_type
     */
    public function setGlowProperties($size, $color_value = null, $color_alpha = null, $color_type = null): void
    {
        $this->setGlowSize($size)
            ->setGlowColor(
                is_null($color_value) ? $this->glowProperties['color']['value'] : $color_value,
                is_null($color_alpha) ? (int) $this->glowProperties['color']['alpha'] : $color_alpha,
                is_null($color_type) ? $this->glowProperties['color']['type'] : $color_type
            );
    }

    /**
     * Get Glow Property
     *
     * @param array|string $property
     *
     * @return string
     */
    public function getGlowProperty($property)
    {
        return $this->getArrayElementsValue($this->glowProperties, $property);
    }

    /**
     * Set Glow Color
     *
     * @param float $size
     */
    private function setGlowSize($size): static
    {
        if (!is_null($size)) {
            $this->glowProperties['size'] = $this->getExcelPointsWidth($size);
        }

        return $this;
    }

    /**
     * Set Glow Color
     *
     * @param string $color
     * @param int $alpha
     * @param string $type
     */
    private function setGlowColor($color, $alpha, $type): static
    {
        $this->glowProperties['color'] = $this->setColorProperties($color, $alpha, $type);

        return $this;
    }

    /**
     * Set Soft Edges Size
     *
     * @param float $size
     */
    public function setSoftEdges($size): void
    {
        if (!is_null($size)) {
            $softEdges['size'] = (string) $this->getExcelPointsWidth($size);
        }
    }

    /**
     * Get Soft Edges Size
     *
     * @return string
     */
    public function getSoftEdgesSize()
    {
        return $this->softEdges['size'];
    }
}
