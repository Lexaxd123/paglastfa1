<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

/**
 * TFA4: blocks every route it is attached to unless somebody is logged in.
 *
 * It runs BEFORE the controller, so a logged-out visitor never reaches
 * Customers or Users code at all. The filter is registered under the
 * alias "auth" in Config/Filters.php and attached to the Customer and
 * User routes in Config/Routes.php.
 */
class AuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();

        if ($session->get('isLoggedIn') === true && $session->get('user_id')) {
            return null; // let the request continue to the controller
        }

        // Remember where the visitor was heading so login can send them
        // back there. Only GET pages are remembered (a POST cannot be replayed).
        if ($request->is('get')) {
            $session->set('intended_url', uri_string());
        }

        return redirect()->to('/login')
            ->with('error', 'Please log in to continue.');
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Protected pages must not be cached, otherwise the Back button
        // could show a private page again after logging out.
        return $response->setHeader('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0')
            ->setHeader('Pragma', 'no-cache');
    }
}
