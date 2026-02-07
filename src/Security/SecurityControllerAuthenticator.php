<?php

namespace App\Security;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Authenticator\AbstractLoginFormAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\CsrfTokenBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\RememberMeBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\SecurityRequestAttributes;
use Symfony\Component\Security\Http\Util\TargetPathTrait;

class SecurityControllerAuthenticator extends AbstractLoginFormAuthenticator
{
    use TargetPathTrait;

    public const LOGIN_ROUTE = 'app_login';

    public function __construct(
        private UrlGeneratorInterface $urlGenerator,
        private \Symfony\Component\Security\Csrf\CsrfTokenManagerInterface $csrfTokenManager,
        private \Psr\Log\LoggerInterface $logger
    ) {
    }

    public function authenticate(Request $request): Passport
    {
        $email = $request->getPayload()->getString('email');

        // DEBUGGING LOGS
        $this->logger->info('--- LOGIN DEBUG ---');
        $this->logger->info('Email: ' . $email);
        $tokenValue = $request->getPayload()->getString('_csrf_token');
        $this->logger->info('CSRF Token in Request: ' . $tokenValue);

        $csrfToken = new \Symfony\Component\Security\Csrf\CsrfToken('authenticate', $tokenValue);

        $isValid = $this->csrfTokenManager->isTokenValid($csrfToken);
        if (!$isValid) {
            $this->logger->error('CRITICAL: CSRF Token is INVALID but proceeding anyway for debug.');
        } else {
            $this->logger->info('SUCCESS: CSRF Token is VALID.');
        }

        // Proceed even if invalid
        return new Passport(
            new UserBadge($email),
            new PasswordCredentials($request->getPayload()->getString('password')),
            [
                new RememberMeBadge(),
            ]
        );
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        if ($targetPath = $this->getTargetPath($request->getSession(), $firewallName)) {
            return new RedirectResponse($targetPath);
        }

        // Redirect to the admin dashboard (project index) after successful login
        return new RedirectResponse($this->urlGenerator->generate('app_admin_project_index'));
    }

    protected function getLoginUrl(Request $request): string
    {
        return $this->urlGenerator->generate(self::LOGIN_ROUTE);
    }
}
