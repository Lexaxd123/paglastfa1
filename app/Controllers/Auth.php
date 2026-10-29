<?php

namespace App\Controllers;

use App\Models\UserModel;

/**
 * TFA4: login and logout.
 *
 *   GET  /login   -> show the form
 *   POST /login   -> verify username + password, start the session
 *                    (5 failed tries from one IP pause logins for 5 minutes)
 *   POST /logout  -> destroy the session
 */
class Auth extends BaseController
{
    /**
     * A valid bcrypt hash of a throwaway string. When the username does not
     * exist we still run password_verify() against it, so "unknown user" and
     * "wrong password" take about the same time and give the same message.
     */
    private const DUMMY_HASH = '$2y$10$0.5.XIlwhmLRKM7wfNiR5.9qMdQ5H0uO7bFbkUfJ.VudRyJYvNH0u';

    /** After this many FAILED logins from one IP address, logins are paused. */
    private const MAX_FAILURES = 5;

    /** How long (seconds) failed attempts are remembered / the pause lasts. */
    private const LOCK_SECONDS = 300;

    public function __construct()
    {
        helper(['form', 'url']);
    }

    /**
     * GET /login
     */
    public function index()
    {
        if (session()->get('isLoggedIn') === true) {
            return redirect()->to('/customers');
        }

        return view('auth/login', [
            'username'  => '',
            'errors'    => [],
            'loggedOut' => $this->request->getGet('logged_out') === '1',
        ]);
    }

    /**
     * POST /login
     */
    public function attempt()
    {
        $username = trim((string) $this->request->getPost('username'));
        $password = (string) $this->request->getPost('password');

        $rules = [
            'username' => ['label' => 'Username', 'rules' => 'required|max_length[50]'],
            'password' => ['label' => 'Password', 'rules' => 'required|max_length[255]'],
        ];

        if (! $this->validateData(['username' => $username, 'password' => $password], $rules)) {
            return $this->failed($username, $this->validator->getErrors());
        }

        // Slow down password guessing: too many failures from one IP pauses logins.
        $failKey  = 'login_fail_' . md5($this->request->getIPAddress());
        $failures = (int) cache($failKey);

        if ($failures >= self::MAX_FAILURES) {
            return $this->failed($username, ['login' => 'Too many failed login attempts. Please wait a few minutes and try again.']);
        }

        $model = new UserModel();
        $user  = $model->where('username', $username)->first();

        $hash = ($user !== null && ! empty($user['password'])) ? $user['password'] : self::DUMMY_HASH;
        $ok   = password_verify($password, $hash) && $user !== null && ! empty($user['password']);

        if (! $ok) {
            cache()->save($failKey, $failures + 1, self::LOCK_SECONDS);

            // Same message for "no such user" and "wrong password".
            return $this->failed($username, ['login' => 'Invalid username or password.']);
        }

        cache()->delete($failKey); // a good login clears the counter

        // Keep the hash up to date if PHP's default algorithm/cost has changed.
        if (password_needs_rehash($hash, PASSWORD_DEFAULT)) {
            $model->update($user['id'], ['password' => password_hash($password, PASSWORD_DEFAULT)]);
        }

        $session  = session();
        $intended = (string) $session->get('intended_url');

        // New session id after login: stops session fixation.
        $session->regenerate(true);

        // Store only what the app needs. Never the password hash.
        $session->set([
            'isLoggedIn' => true,
            'user_id'    => (int) $user['id'],
            'username'   => $user['username'],
            'full_name'  => $user['full_name'],
        ]);

        // Go back to the page that triggered the login, if it was one of ours.
        $target = $this->safeTarget($intended);

        return redirect()->to($target)
            ->with('success', 'Welcome back, ' . $user['full_name'] . '!');
    }

    /**
     * POST /logout
     * (POST + CSRF token, so another website cannot log you out by linking to it.)
     */
    public function logout()
    {
        // Ends the session completely: all data removed, session file deleted,
        // cookie expired. The old session id is useless afterwards.
        session()->destroy();

        // The session is gone, so it cannot carry a flash message; the login
        // page shows "logged out" when it sees this harmless flag instead.
        return redirect()->to('/login?logged_out=1');
    }

    /**
     * Shows the login form again with an error. The password is never sent back.
     */
    private function failed(string $username, array $errors)
    {
        return view('auth/login', ['username' => $username, 'errors' => $errors, 'loggedOut' => false]);
    }

    /**
     * Accepts only plain paths that belong to this site, to prevent an
     * "open redirect". Anything else falls back to the customer list.
     */
    private function safeTarget(string $path): string
    {
        $path = ltrim($path, '/');

        if ($path !== '' && preg_match('#^[A-Za-z0-9/_-]+$#', $path) && $path !== 'login') {
            return '/' . $path;
        }

        return '/customers';
    }
}
