<?php

namespace App\Http\Controllers;

use App\Exceptions\InvalidUserDataException;
use App\Http\Redirector;
use App\Repository\UserRepository;
use App\User;
use Illuminate\Http\Request;
use Laravel\Lumen\Application;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

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

    public function listAction(Request $request)
    {
        $this->checkAccess($request);

        return view('list', ['users' => $this->userRepository->getUsers()]);
    }

    public function showAction(Request $request, $username)
    {
        $this->checkAccess($request);

        return view('show', ['user' => $this->userRepository->getUser($username)]);
    }

    public function editAction(Request $request, $username)
    {
        $this->checkAccess($request);

        return view('edit', ['user' => $this->userRepository->getUser($username)]);
    }

    public function deleteAction(Request $request, $username)
    {
        $this->checkAccess($request);
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
        $this->checkAccess($request);
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

    public function newAction(Request $request)
    {
        $this->checkAccess($request);

        return view('new');
    }

    public function createAction(Request $request)
    {
        $this->checkAccess($request);
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
        $rd = new Redirector(app());

        return $rd->route('users');
    }

    private function checkAccess(Request $request)
    {
        /** @var Application $app */
        $app = app();
        if (!$app->environment('local')) {

            $currentUsername = $request->getUser();
            if (!$currentUsername) {
                throw new AccessDeniedHttpException();
            }

            if (!in_array($currentUsername, $this->getAdminUsers())) {
                throw new AccessDeniedHttpException();
            }
        }
    }

    private function getAdminUsers()
    {
        return array_filter(explode(',', env('ADMIN_USERS')));
    }
}
