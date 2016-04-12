<?php
/*
 *  Copyright notice
 *
 *  (c) 2016 Andreas Thurnheer-Meier <tma@iresults.li>, iresults
 *  Daniel Corn <cod@iresults.li>, iresults
 *
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 */

/**
 * @author COD
 * Created 12.04.16 12:09
 */


namespace App\Repository;


use App\User;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\Exception\FileNotFoundException;

class UserRepository
{
    const BASE_PATH = __DIR__.'/../..';

    /**
     * @var User[]
     */
    private $users;


    /**
     * @return User[]
     */
    public function getUsers()
    {
        if ($this->users === null) {
            $this->loadUsers();
        }

        return $this->users;
    }

    /**
     * @param $username
     * @return User|null
     */
    public function getUser($username)
    {
        $users = $this->getUsers();

        return isset($users[$username]) ? $users[$username] : null;
    }

    /**
     * @param User $user
     * @return $this
     */
    public function update(User $user)
    {
        return $this->add($user);
    }

    /**
     * @param User $user
     * @return $this
     */
    public function add(User $user)
    {
        $this->getUsers();
        $this->users[$user->getUsername()] = $user;

        return $this;
    }

    /**
     * @return bool
     */
    public function persist()
    {
        $this->validateFileWriteAccess();
        $fileLines = array_map(
            function (User $user) {
                return $user->getUsername().':'.$user->getEncryptedPassword();
            },
            $this->users
        );

        return 0 < file_put_contents($this->getUsersFilePath(), implode("\n", $fileLines));
    }

    private function getUsersFilePath()
    {
        return self::BASE_PATH.'/'.env('PASSWORD_FILE');
    }

    private function loadUsers()
    {
        $this->validateFileReadAccess();
        $contents = file_get_contents($this->getUsersFilePath());

        if ($contents) {
            $lines = array_map('trim', array_filter(explode("\n", $contents)));

            foreach ($lines as $userLine) {
                list($userName, $password) = explode(':', $userLine, 2);
                $this->users[$userName] = new User($userName, $password, true);
            }
        }
    }

    private function validateFileReadAccess()
    {
        if (!file_exists($this->getUsersFilePath())) {
            throw new FileNotFoundException($this->getUsersFilePath());
        }
    }

    private function validateFileWriteAccess()
    {
        $filePath = $this->getUsersFilePath();
        if (file_exists($filePath)) {
            if (!is_writable($filePath)) {
                throw new FileException(sprintf('No permission to write to file %s', $filePath));
            }
        } elseif (!is_writable(dirname($filePath))) {
            throw new FileException(sprintf('File does %s not exist and folder is not writable', $filePath));
        }
    }
}