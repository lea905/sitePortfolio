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
        $sessionId = $request->getSession()->getId();

        // 2. Generate CSRF Token
        $tokenId = 'authenticate';

        $token = $csrfTokenManager->getToken($tokenId)->getValue(); // Refresh and get
        $managerClass = get_class($csrfTokenManager);

        // Manual Generator Check
        $manualGenerator = new \Symfony\Component\Security\Csrf\TokenGenerator\UriSafeTokenGenerator();
        $manualToken = $manualGenerator->generateToken();

        $this->addFlash('info', 'Manager: ' . $managerClass);
        $this->addFlash('info', 'Injected Refresh Token: ' . $token);
        $this->addFlash('info', 'Manual Generator Token: ' . $manualToken);

        // Reflect to find the injected generator
        try {
            $reflection = new \ReflectionClass($csrfTokenManager);
            if ($reflection->hasProperty('generator')) {
                $property = $reflection->getProperty('generator');
                $property->setAccessible(true);
                $generator = $property->getValue($csrfTokenManager);
                $generatorClass = is_object($generator) ? get_class($generator) : gettype($generator);
            } else {
                $generatorClass = 'Property "generator" not found';
            }
        } catch (\Exception $e) {
            $generatorClass = 'Reflection Error: ' . $e->getMessage();
        }
        $this->addFlash('info', 'Injected Generator Class: ' . $generatorClass);
        // 3. Check if submitted and valid
        if ($request->isMethod('POST')) {
            $submittedToken = $request->request->get('_csrf_token');
            if ($csrfTokenManager->isTokenValid(new CsrfToken($tokenId, $submittedToken))) {
                $this->addFlash('success', 'CSRF Valid!');
            } else {
                $this->addFlash('error', 'CSRF Invalid!');
            }
        }

        return $this->render('debug/csrf.html.twig', [
            'csrf_token' => $token, // Explicitly pass the token we generated
        ]);
    }
}
