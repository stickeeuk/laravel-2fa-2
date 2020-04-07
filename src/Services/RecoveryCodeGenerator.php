<?php

namespace Stickee\Laravel2fa\Services;

use Stickee\Laravel2fa\Contracts\RecoveryCodeGenerator as RecoveryCodeGeneratorInterface;

class RecoveryCodeGenerator implements RecoveryCodeGeneratorInterface
{
    /**
     * Lower-case letters alphabet
     *
     * @var string LOWER_ALPHA
     */
    const LOWER_ALPHA = 'abcdefghijklmnopqrstuvwxyz';

    /**
     * Upper-case letters alphabet
     *
     * @var string UPPER_ALPHA
     */
    const UPPER_ALPHA = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';

    /**
     * Numbers alphabet
     *
     * @var string NUMERIC
     */
    const NUMERIC = '0123456789';

    /**
     * Lower-case letters and numbers alphabet
     *
     * @var string LOWER_ALPHA_NUMERIC
     */
    const LOWER_ALPHA_NUMERIC = self::LOWER_ALPHA . self::NUMERIC;

    /**
     * Upper-case letters and numbers alphabet
     *
     * @var string LOWER_ALPHA_NUMERIC
     */
    const UPPER_ALPHA_NUMERIC = self::UPPER_ALPHA . self::NUMERIC;

    /**
     * The total length of the code (excluding separators)
     *
     * @var int $codeLength
     */
    private $codeLength;

    /**
     * The length of each block in the code
     *
     * @var int $blockLength
     */
    private $blockLength;

    /**
     * The alphabet to generate codes from
     *
     * @var string $alphabet
     */
    private $alphabet;

    /**
     * The block separator
     *
     * @var string $blockSeparator
     */
    private $blockSeparator;

    /**
     * Constructor
     *
     * @param int $codeLength The total length of the code (excluding separators)
     * @param int $blockLength The length of each block in the code
     * @param string $alphabet The alphabet to generate codes from
     * @param string $blockSeparator The block separator
     */
    public function __construct(
        int $codeLength = 10,
        int $blockLength = 5,
        string $alphabet = self::LOWER_ALPHA_NUMERIC,
        string $blockSeparator = '-'
    ) {
        $this->codeLength = $codeLength;
        $this->blockLength = $blockLength;
        $this->alphabet = mb_str_split($alphabet);
        $this->blockSeparator = $blockSeparator;
    }

    /**
     * Generate recovery codes
     *
     * @param int $count The number of codes to get
     *
     * @return array
     */
    public function get(int $count): array
    {
        $codes = [];

        for ($i = 0; $i < $count; $i++) {
            $codes[] = $this->getOne();
        }

        return $codes;
    }

    /**
     * Generate a recovery code
     *
     * @return string
     */
    public function getOne(): string
    {
        $code = '';
        $max = count($this->alphabet) - 1;

        for ($i = 0; $i < $this->codeLength; $i++) {
            $code .= $this->alphabet[random_int(0, $max)];

            if ($i && !(($i + 1) % $this->blockLength) && ($i !== $this->codeLength - 1)) {
                $code .= $this->blockSeparator;
            }
        }

        return $code;
    }
}
