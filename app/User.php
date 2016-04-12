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
    public function __construct(string $username, string $password, bool $encrypted = false)
    {
        $this->username = $username;
        if ($encrypted) {
            $this->encryptedPassword = $password;
        } else {
            $this->setPassword($password);
        }
    }

    /**
     * @return string
     */
    public function getUsername():string
    {
        return $this->username;
    }

    /**
     * @param string $username
     */
    public function setUsername(string $username)
    {
        $this->username = $username;
    }

    /**
     * @return string
     */
    public function getEncryptedPassword():string
    {
        return $this->encryptedPassword;
    }

    /**
     * @param string $encryptedPassword
     */
    public function setEncryptedPassword(string $encryptedPassword)
    {
        $this->encryptedPassword = $encryptedPassword;
    }

    /**
     * @param string $clearTextPassword
     */
    public function setPassword(string $clearTextPassword)
    {
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
        if (!ctype_alnum($this->username) ){
            throw new InvalidUserDataException('Username contains invalid data');
        }
        if (!$this->encryptedPassword) {
            throw new InvalidUserDataException('Password not defined');
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
