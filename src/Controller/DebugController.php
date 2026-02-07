<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;

class DebugController extends AbstractController
{
    #[Route('/debug/session', name: 'debug_session')]
    public function index(Request $request, CsrfTokenManagerInterface $csrfTokenManager): Response
    {
        // 1. Get Session ID
        $session = $request->getSession();
        $session->start();
        $sessionId = $session->getId();

        // 2. Generate CSRF Token
        $tokenId = 'authenticate';
        $token = $csrfTokenManager->getToken($tokenId)->getValue();
        $managerClass = get_class($csrfTokenManager);

        // 3. Check if submitted and valid
        $message = '';
        if ($request->isMethod('POST')) {
            $submittedToken = $request->request->get('_csrf_token');
            if ($csrfTokenManager->isTokenValid(new CsrfToken($tokenId, $submittedToken))) {
                $message = '✅ SUCCESS: CSRF Token is VALID!';
            } else {
                $message = '❌ ERROR: CSRF Token is INVALID!';
            }
        }

        return new Response(<<<HTML
<html>
<body>
    <h1>Session & CSRF Debugger</h1>
    <p><strong>Session ID:</strong> $sessionId</p>
    <p><em>Reload this page. If the Session ID changes, your session is not persisting.</em></p>
    
    <hr>
    
    <h2>Test CSRF</h2>
    <p><strong>Manager Class:</strong> $managerClass</p>
    <p><strong>Generated Token:</strong> $token</p>
    
    <h3>Validation Result:</h3>
    <p style="font-size: 1.2em; font-weight: bold;">$message</p>

    <form method="post">
        <input type="hidden" name="_csrf_token" value="$token">
        <button type="submit">Test CSRF Validation</button>
    </form>
</body>
</html>
HTML
        );
    }
}
