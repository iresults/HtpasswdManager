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

    public function listAction()
    {
        return view('list', ['users' => $this->userRepository->getUsers()]);
    }

    public function showAction($username)
    {
        return view('show', ['user' => $this->userRepository->getUser($username)]);
    }

    public function editAction($username)
    {
        return view('edit', ['user' => $this->userRepository->getUser($username)]);
    }

    public function deleteAction($username)
    {
        $user = $this->userRepository->getUser($username);
        if (!$user) {
            throw new \RuntimeException(sprintf('No user named %s found', $username));
        }
        $this->userRepository
            ->remove($user)
            ->persist();

        return $this->redirectToList();
    }

    public function updateAction(Request $request, $username)
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

        return $this->redirectToList();
    }

    public function newAction()
    {
        return view('new');
    }

    public function createAction(Request $request)
    {
        list($username, $password) = $this->getUsernameAndPasswordFromRequest($request);

        $user = new User($username, $password);
        $user->validate();

        $this->userRepository
            ->add($user)
            ->persist();

        return $this->redirectToList();
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

    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    private function redirectToList()
    {
        /** @var \Laravel\Lumen\Http\Redirector $rd */
        $rd = redirect();
        return $rd->route('users');
    }
}
