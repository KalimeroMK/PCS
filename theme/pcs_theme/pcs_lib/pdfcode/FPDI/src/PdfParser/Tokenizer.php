<?php

/**
 * This file is part of FPDI
 *
 * @package   setasign\Fpdi
 * @copyright Copyright (c) 2020 Setasign GmbH & Co. KG (https://www.setasign.com)
 * @license   http://opensource.org/licenses/mit-license The MIT License
 */

namespace setasign\Fpdi\PdfParser;

/**
 * A tokenizer class.
 */
class Tokenizer
{
    protected \setasign\Fpdi\PdfParser\StreamReader $streamReader;

    /**
     * A token stack.
     *
     * @var string[]
     */
    protected $stack = [];

    /**
     * Tokenizer constructor.
     */
    public function __construct(StreamReader $streamReader)
    {
        $this->streamReader = $streamReader;
    }

    /**
     * Get the stream reader instance.
     */
    public function getStreamReader(): \setasign\Fpdi\PdfParser\StreamReader
    {
        return $this->streamReader;
    }

    /**
     * Clear the token stack.
     */
    public function clearStack(): void
    {
        $this->stack = [];
    }

    /**
     * Push a token onto the stack.
     *
     * @param string $token
     */
    public function pushStack($token): void
    {
        $this->stack[] = $token;
    }

    /**
     * Get next token.
     *
     * @return bool|string
     */
    public function getNextToken()
    {
        $token = \array_pop($this->stack);
        if ($token !== null) {
            return $token;
        }

        if (($byte = $this->streamReader->readByte()) === false) {
            return false;
        }

        if (\in_array($byte, ["\x20", "\x0A", "\x0D", "\x0C", "\x09", "\x00"], true)) {
            if ($this->leapWhiteSpaces() === false) {
                return false;
            }
            $byte = $this->streamReader->readByte();
        }

        switch ($byte) {
            case '/':
            case '[':
            case ']':
            case '(':
            case ')':
            case '{':
            case '}':
            case '<':
            case '>':
                return $byte;
            case '%':
                $this->streamReader->readLine();
                return $this->getNextToken();
        }

        /* This way is faster than checking single bytes.
         */
        $bufferOffset = $this->streamReader->getOffset();
        do {
            $lastBuffer = $this->streamReader->getBuffer(false);
            $pos = \strcspn(
                $lastBuffer,
                "\x00\x09\x0A\x0C\x0D\x20()<>[]{}/%",
                $bufferOffset
            );
        } while (
            // Break the loop if a delimiter or white space char is matched
            // in the current buffer or increase the buffers length
            $lastBuffer !== false &&
            (
                $bufferOffset + $pos === \strlen($lastBuffer) &&
                $this->streamReader->increaseLength()
            )
        );

        $result = \substr($lastBuffer, $bufferOffset - 1, $pos + 1);
        $this->streamReader->setOffset($bufferOffset + $pos);

        return $result;
    }

    /**
     * Leap white spaces.
     */
    public function leapWhiteSpaces(): bool
    {
        do {
            if (!$this->streamReader->ensureContent()) {
                return false;
            }

            $buffer = $this->streamReader->getBuffer(false);
            $matches = \strspn($buffer, "\x20\x0A\x0C\x0D\x09\x00", $this->streamReader->getOffset());
            if ($matches > 0) {
                $this->streamReader->addOffset($matches);
            }
        } while ($this->streamReader->getOffset() >= $this->streamReader->getBufferLength());

        return true;
    }
}
