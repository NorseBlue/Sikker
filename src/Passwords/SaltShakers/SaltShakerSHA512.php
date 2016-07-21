<?php
/**
 * Sikker is a PHP 7.0+ Security package that contains security related implementations.
 *
 * @package    NorseBlue\Sikker
 * @version    0.1
 * @author     NorseBlue
 * @license    MIT License
 * @copyright  2016 NorseBlue
 * @link       https://github.com/NorseBlue/Sikker
 */
declare(strict_types = 1);

namespace NorseBlue\Sikker\Passwords\SaltShakers;

use NorseBlue\Sikker\Passwords\SaltShaker;
use NorseBlue\Sikker\Tokens\TokenFactory;

/**
 * Class SaltShakerSHA512
 *
 * @package NorseBlue\Sikker\Passwords\SaltShakers
 * @uses NorseBlue\Sikker\Tokens\TokenFactory
 * @since 0.1
 */
class SaltShakerSHA512 implements SaltShaker
{
    /**
     * @var string SHA512 salt prefix.
     */
    const PREFIX = '$6$';

    /**
     * @var string SHA512 salt rounds opening.
     */
    const ROUNDS_OPEN = 'rounds=';

    /**
     * @var string SHA512 salt rounds closing.
     */
    const ROUNDS_CLOSE = '$';

    /**
     * @var string SHA512 salt postfix.
     */
    const POSTFIX = '$';

    /**
     * @var int The maximum salt length (not counting prefix and postfix).
     */
    const MAX_LENGTH = 16;

    /**
     * @var int The minimum supported number of rounds.
     */
    const MIN_ROUNDS = 1000;

    /**
     * @var int The default number of rounds.
     */
    const DEFAULT_ROUNDS = 5000;

    /**
     * @var int The maximum supported number of rounds.
     */
    const MAX_ROUNDS = 999999999;

    /**
     * @var int The number of rounds to use for hash.
     */
    protected $rounds;

    /**
     * SaltShakerSH256 constructor.
     *
     * @param int $rounds The number of rounds to use. The default is 5000.
     * @since 0.1
     */
    public function __construct(int $rounds = self::DEFAULT_ROUNDS)
    {
        $this->setRounds($rounds);
    }

    /**
     * Gets the number of rounds.
     *
     * @return int Returns the number of rounds.
     * @since 0.1
     */
    public function getRounds(): int
    {
        return $this->rounds;
    }

    /**
     * Sets the number of rounds.
     *
     * @param int $rounds The new number of rounds.
     * @return SaltShakerSHA512 Returns this instance for fluent interface.
     * @since 0.1
     */
    public function setRounds(int $rounds = null): SaltShakerSHA512
    {
        $this->rounds = $rounds ?? self::DEFAULT_ROUNDS;
        $this->rounds = max(self::MIN_ROUNDS, min(self::MAX_ROUNDS, $this->rounds));
        return $this;
    }

    /**
     * Encodes the given salt in SHA512 format. If no salt is given a random token with max length is generated as the salt.
     *
     * @see http://php.net/manual/es/function.crypt.php PHP crypt function reference.
     * @param string|null $salt The salt to encode (up to 8 chars). The salt will also be truncated at the first $ found.
     * @return string Returns the encoded salt in SHA512 format according to {@link http://php.net/manual/es/function.crypt.php PHP crypt function reference.}
     * @since 0.1
     */
    public function encode(string $salt = null) : string
    {
        if ($salt === null) {
            $tokenFactory = new TokenFactory(self::MAX_LENGTH);
            $encoded = $tokenFactory->forgeToken();
        } else {
            $encoded = substr($salt, 0, min(self::MAX_LENGTH, strlen($salt)));
        }

        if (($dollar = strpos($encoded, '$')) !== false) {
            $encoded = substr($encoded, 0, $dollar);
        }

        $rounds = self::ROUNDS_OPEN . $this->rounds . self::ROUNDS_CLOSE;

        return self::PREFIX . $rounds . $encoded . self::POSTFIX;
    }
}