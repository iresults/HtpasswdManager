<?php

namespace App\Http\Controllers;

use App\Exceptions\InvalidUserDataException;
use App\Repository\UserRepository;
use App\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * Create a new controller instance.
     *
     * @param UserRepository $userProvider
     */
    public function __construct(UserRepository $userProvider)
    {
        $this->userRepository = $userProvider;
    }

    public function listAll()
    {
        return view('list', ['users' => $this->userRepository->getUsers()]);
    }

    public function show($username)
    {
        return view('show', ['user' => $this->userRepository->getUser($username)]);
    }

    public function edit($username)
    {
        return view('edit', ['user' => $this->userRepository->getUser($username)]);
    }

    public function update(Request $request, $username)
    {
        if ($request->get('username') !== (string)$username) {
            throw new InvalidUserDataException('Username mismatch in request');
        }
        list($username, $password) = $this->getUsernameAndPasswordFromRequest($request);

        $user = new User($username, $password);
        $user->validate();

        $this->userRepository
            ->update($user)
            ->persist();

        return redirect()->route('users');
    }

    public function new()
    {
        return view('new');
    }

    public function create(Request $request)
    {
        list($username, $password) = $this->getUsernameAndPasswordFromRequest($request);

        $user = new User($username, $password);
        $user->validate();

        $this->userRepository
            ->add($user)
            ->persist();

        return redirect()->route('users');
    }

    /**
     * @param Request $request
     * @return array
     */
    private function getUsernameAndPasswordFromRequest(Request $request)
    {
        $username = trim($request->get('username'));
        if (!$username) {
            throw new InvalidUserDataException('Username mismatch in request');
        }
        $password = trim($request->get('password'));
        if (!$password) {
            throw new InvalidUserDataException('Password not set');
        }

        return array($username, $password);
    }
}
