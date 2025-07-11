<?php

/**
 * This file is part of FPDI
 *
 * @package   setasign\Fpdi
 * @copyright Copyright (c) 2020 Setasign GmbH & Co. KG (https://www.setasign.com)
 * @license   http://opensource.org/licenses/mit-license The MIT License
 */

namespace setasign\Fpdi\PdfParser;

use setasign\Fpdi\PdfParser\CrossReference\CrossReference;
use setasign\Fpdi\PdfParser\CrossReference\CrossReferenceException;
use setasign\Fpdi\PdfParser\Type\PdfArray;
use setasign\Fpdi\PdfParser\Type\PdfBoolean;
use setasign\Fpdi\PdfParser\Type\PdfDictionary;
use setasign\Fpdi\PdfParser\Type\PdfHexString;
use setasign\Fpdi\PdfParser\Type\PdfIndirectObject;
use setasign\Fpdi\PdfParser\Type\PdfIndirectObjectReference;
use setasign\Fpdi\PdfParser\Type\PdfName;
use setasign\Fpdi\PdfParser\Type\PdfNull;
use setasign\Fpdi\PdfParser\Type\PdfNumeric;
use setasign\Fpdi\PdfParser\Type\PdfStream;
use setasign\Fpdi\PdfParser\Type\PdfString;
use setasign\Fpdi\PdfParser\Type\PdfToken;
use setasign\Fpdi\PdfParser\Type\PdfType;

/**
 * A PDF parser class
 */
class PdfParser
{
    protected \setasign\Fpdi\PdfParser\StreamReader $streamReader;

    protected \setasign\Fpdi\PdfParser\Tokenizer $tokenizer;

    /**
     * The file header.
     *
     * @var string
     */
    protected $fileHeader;

    /**
     * The offset to the file header.
     *
     * @var int
     */
    protected $fileHeaderOffset;

    /**
     * @var CrossReference|null
     */
    protected $xref;

    /**
     * All read objects.
     *
     * @var array
     */
    protected $objects = [];

    /**
     * PdfParser constructor.
     */
    public function __construct(StreamReader $streamReader)
    {
        $this->streamReader = $streamReader;
        $this->tokenizer = new Tokenizer($streamReader);
    }

    /**
     * Removes cycled references.
     *
     * @internal
     */
    public function cleanUp(): void
    {
        $this->xref = null;
    }

    /**
     * Get the stream reader instance.
     */
    public function getStreamReader(): \setasign\Fpdi\PdfParser\StreamReader
    {
        return $this->streamReader;
    }

    /**
     * Get the tokenizer instance.
     */
    public function getTokenizer(): \setasign\Fpdi\PdfParser\Tokenizer
    {
        return $this->tokenizer;
    }

    /**
     * Get the PDF version.
     *
     * @return int[] An array of major and minor version.
     * @throws PdfParserException
     */
    public function getPdfVersion(): array
    {
        $this->resolveFileHeader();

        if (\preg_match('/%PDF-(\d)\.(\d)/', $this->fileHeader, $result) === 0) {
            throw new PdfParserException(
                'Unable to extract PDF version from file header.',
                PdfParserException::PDF_VERSION_NOT_FOUND
            );
        }
        list(, $major, $minor) = $result;

        $catalog = $this->getCatalog();
        if (isset($catalog->value['Version'])) {
            $versionParts = \explode(
                '.',
                PdfName::unescape(PdfType::resolve($catalog->value['Version'], $this)->value)
            );
            if (count($versionParts) === 2) {
                list($major, $minor) = $versionParts;
            }
        }

        return [(int)$major, (int)$minor];
    }

    /**
     * Resolves the file header.
     *
     * @return int
     * @throws PdfParserException
     */
    protected function resolveFileHeader()
    {
        if ($this->fileHeader) {
            return $this->fileHeaderOffset;
        }

        $this->streamReader->reset(0);
        $maxIterations = 1000;
        while (true) {
            $buffer = $this->streamReader->getBuffer(false);
            $offset = \strpos($buffer, '%PDF-');
            if ($offset === false) {
                if (!$this->streamReader->increaseLength(100) || (--$maxIterations === 0)) {
                    throw new PdfParserException(
                        'Unable to find PDF file header.',
                        PdfParserException::FILE_HEADER_NOT_FOUND
                    );
                }
                continue;
            }
            break;
        }

        $this->fileHeaderOffset = $offset;
        $this->streamReader->setOffset($offset);

        $this->fileHeader = \trim($this->streamReader->readLine());
        return $this->fileHeaderOffset;
    }

    /**
     * Get the catalog dictionary.
     *
     * @return PdfDictionary
     * @throws Type\PdfTypeException
     * @throws CrossReferenceException
     * @throws PdfParserException
     */
    public function getCatalog()
    {
        $trailer = $this->getCrossReference()->getTrailer();

        $catalog = PdfType::resolve(PdfDictionary::get($trailer, 'Root'), $this);

        return PdfDictionary::ensure($catalog);
    }

    /**
     * Get the cross reference instance.
     *
     * @return CrossReference
     * @throws CrossReferenceException
     * @throws PdfParserException
     */
    public function getCrossReference()
    {
        if ($this->xref === null) {
            $this->xref = new CrossReference($this, $this->resolveFileHeader());
        }

        return $this->xref;
    }

    /**
     * Get an indirect object by its object number.
     *
     * @param int $objectNumber
     * @param bool $cache
     * @return PdfIndirectObject
     * @throws CrossReferenceException
     * @throws PdfParserException
     */
    public function getIndirectObject($objectNumber, $cache = false)
    {
        $objectNumber = (int)$objectNumber;
        if (isset($this->objects[$objectNumber])) {
            return $this->objects[$objectNumber];
        }

        $object = $this->getCrossReference()->getIndirectObject($objectNumber);

        if ($cache) {
            $this->objects[$objectNumber] = $object;
        }

        return $object;
    }

    /**
     * Read a PDF value.
     *
     * @param null|bool|string $token
     * @param null|string $expectedType
     * @return false|PdfArray|PdfBoolean|PdfDictionary|PdfHexString|PdfIndirectObject|PdfIndirectObjectReference|PdfName|PdfNull|PdfNumeric|PdfStream|PdfString|PdfToken
     * @throws Type\PdfTypeException
     */
    public function readValue($token = null, $expectedType = null): false|\setasign\Fpdi\PdfParser\Type\PdfString|\setasign\Fpdi\PdfParser\Type\PdfDictionary|\setasign\Fpdi\PdfParser\Type\PdfHexString|\setasign\Fpdi\PdfParser\Type\PdfName|\setasign\Fpdi\PdfParser\Type\PdfArray|\setasign\Fpdi\PdfParser\Type\PdfIndirectObject|\setasign\Fpdi\PdfParser\Type\PdfIndirectObjectReference|\setasign\Fpdi\PdfParser\Type\PdfNumeric|\setasign\Fpdi\PdfParser\Type\PdfBoolean|\setasign\Fpdi\PdfParser\Type\PdfNull|\setasign\Fpdi\PdfParser\Type\PdfToken
    {
        if ($token === null) {
            $token = $this->tokenizer->getNextToken();
        }

        if ($token === false) {
            if ($expectedType !== null) {
                throw new Type\PdfTypeException('Got unexpected token type.', Type\PdfTypeException::INVALID_DATA_TYPE);
            }
            return false;
        }

        switch ($token) {
            case '(':
                $this->ensureExpectedType($token, $expectedType);
                return PdfString::parse($this->streamReader);

            case '<':
                if ($this->streamReader->getByte() === '<') {
                    $this->ensureExpectedType('<<', $expectedType);
                    $this->streamReader->addOffset(1);
                    return PdfDictionary::parse($this->tokenizer, $this->streamReader, $this);
                }

                $this->ensureExpectedType($token, $expectedType);
                return PdfHexString::parse($this->streamReader);

            case '/':
                $this->ensureExpectedType($token, $expectedType);
                return PdfName::parse($this->tokenizer, $this->streamReader);

            case '[':
                $this->ensureExpectedType($token, $expectedType);
                return PdfArray::parse($this->tokenizer, $this);

            default:
                if (\is_numeric($token)) {
                    if (($token2 = $this->tokenizer->getNextToken()) !== false) {
                        if (\is_numeric($token2) && ($token3 = $this->tokenizer->getNextToken()) !== false) {
                            switch ($token3) {
                                case 'obj':
                                    if ($expectedType !== null && $expectedType !== PdfIndirectObject::class) {
                                        throw new Type\PdfTypeException(
                                            'Got unexpected token type.',
                                            Type\PdfTypeException::INVALID_DATA_TYPE
                                        );
                                    }

                                    return PdfIndirectObject::parse(
                                        (int)$token,
                                        (int)$token2,
                                        $this,
                                        $this->tokenizer,
                                        $this->streamReader
                                    );
                                case 'R':
                                    if (
                                        $expectedType !== null &&
                                        $expectedType !== PdfIndirectObjectReference::class
                                    ) {
                                        throw new Type\PdfTypeException(
                                            'Got unexpected token type.',
                                            Type\PdfTypeException::INVALID_DATA_TYPE
                                        );
                                    }

                                    return PdfIndirectObjectReference::create((int)$token, (int)$token2);
                            }

                            $this->tokenizer->pushStack($token3);
                        }

                        $this->tokenizer->pushStack($token2);
                    }

                    if ($expectedType !== null && $expectedType !== PdfNumeric::class) {
                        throw new Type\PdfTypeException(
                            'Got unexpected token type.',
                            Type\PdfTypeException::INVALID_DATA_TYPE
                        );
                    }
                    return PdfNumeric::create($token + 0);
                }

                if ($token === 'true' || $token === 'false') {
                    $this->ensureExpectedType($token, $expectedType);
                    return PdfBoolean::create($token === 'true');
                }

                if ($token === 'null') {
                    $this->ensureExpectedType($token, $expectedType);
                    return new PdfNull();
                }

                if ($expectedType !== null && $expectedType !== PdfToken::class) {
                    throw new Type\PdfTypeException(
                        'Got unexpected token type.',
                        Type\PdfTypeException::INVALID_DATA_TYPE
                    );
                }

                $v = new PdfToken();
                $v->value = $token;

                return $v;
        }
    }

    /**
     * Ensures that the token will evaluate to an expected object type (or not).
     *
     * @param string $token
     * @param string|null $expectedType
     * @throws Type\PdfTypeException
     */
    private function ensureExpectedType(string|bool $token, $expectedType): bool
    {
        static $mapping = [
            '(' => PdfString::class,
            '<' => PdfHexString::class,
            '<<' => PdfDictionary::class,
            '/' => PdfName::class,
            '[' => PdfArray::class,
            'true' => PdfBoolean::class,
            'false' => PdfBoolean::class,
            'null' => PdfNull::class
        ];

        if ($expectedType === null || $mapping[$token] === $expectedType) {
            return true;
        }

        throw new Type\PdfTypeException('Got unexpected token type.', Type\PdfTypeException::INVALID_DATA_TYPE);
    }
}
