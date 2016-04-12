<?php

namespace App;

use App\Exceptions\InvalidUserDataException;

class User implements \JsonSerializable
{
    /**
     * @var string
     */
    private $username;

    /**
     * @var string
     */
    private $encryptedPassword;

    /**
     * User constructor.
     *
     * @param string $username
     * @param string $password
     * @param bool   $encrypted
     */
    public function __construct($username, $password, $encrypted = false)
    {
        $this->assertString($username);
        $this->assertString($password);

        $this->username = $username;
        if ((bool)$encrypted) {
            $this->encryptedPassword = $password;
        } else {
            $this->setPassword($password);
        }
    }

    /**
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param string $username
     */
    public function setUsername($username)
    {
        $this->assertString($username);
        $this->username = $username;
    }

    /**
     * @return string
     */
    public function getEncryptedPassword()
    {
        return $this->encryptedPassword;
    }

    /**
     * @param string $encryptedPassword
     */
    public function setEncryptedPassword($encryptedPassword)
    {
        $this->assertString($encryptedPassword);
        $this->encryptedPassword = $encryptedPassword;
    }

    /**
     * @param string $clearTextPassword
     */
    public function setPassword($clearTextPassword)
    {
        $this->assertString($clearTextPassword);
        $this->encryptedPassword = crypt($clearTextPassword, base64_encode($clearTextPassword));
    }

    /**
     * @return \Exception
     */
    public function validate()
    {
        if (!$this->username) {
            throw new InvalidUserDataException('Username not defined');
        }
        if (!ctype_alnum($this->username)) {
            throw new InvalidUserDataException('Username contains invalid data');
        }
        if (!$this->encryptedPassword) {
            throw new InvalidUserDataException('Password not defined');
        }
    }

    private function assertString($input)
    {
        if (!is_string($input)) {
            throw new \InvalidArgumentException(
                sprintf(
                    'Expected argument to be of type string %s given',
                    is_object($input) ? get_class($input) : gettype($input)
                )
            );
        }
    }

    function jsonSerialize()
    {
        return [
            'username' => $this->getUsername(),
            'password' => $this->getEncryptedPassword(),
        ];
    }
}
