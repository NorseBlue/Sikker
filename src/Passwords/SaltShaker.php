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

namespace NorseBlue\Sikker\Passwords;

/**
 * Interface SaltShaker
 *
 * @package NorseBlue\Sikker\Passwords
 * @uses NorseBlue\Sikker\Tokens\TokenFactory
 * @see http://php.net/manual/es/function.crypt.php PHP crypt function reference.
 * @see http://php.net/manual/es/function.password-hash.php PHP password_hash function reference.
 * @see http://php.net/manual/es/function.password-verify.php PHP password_verify function reference.
 * @since 0.1
 */
interface SaltShaker
{
    /**
     * Encodes the given salt. If no salt is given a random token is generated as the salt.
     *
     * @see http://php.net/manual/es/function.crypt.php PHP crypt function reference.
     * @param string|null $salt The salt to encode.
     * @return string Returns the encoded salt.
     */
    public function encode(string $salt = null) : string;
}