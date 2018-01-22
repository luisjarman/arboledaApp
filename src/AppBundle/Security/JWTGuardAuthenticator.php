<?php
namespace AppBundle\Security;
use KnpU\Guard\Exception\CustomAuthenticationException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use AppBundle\Exception\InvalidJWTException;
use AppBundle\Service\JWTCoder;
/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
final class JWTGuardAuthenticator extends GuardAuthenticator
{
    private $jwtCoder;
    public function __construct(JWTCoder $jwtCoder)
    {
        $this->jwtCoder = $jwtCoder;
    }
    /**
     * {@inheritdoc}
     */
    public function getCredentials(Request $request)
    {
        if (!$request->headers->has('Authorization')) {
            throw CustomAuthenticationException::createWithSafeMessage('Missing Authorization Header');
        }
        $headerParts = explode(' ', $request->headers->get('Authorization'));
        if (!(count($headerParts) === 2 && $headerParts[0] === 'Bearer')) {
            throw CustomAuthenticationException::createWithSafeMessage('Malformed Authorization Header');
        }
        return $headerParts[1];
    }
    /**
     * {@inheritdoc}
     */
    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        try {
            $payload = $this->jwtCoder->decode($credentials);
        } catch (InvalidJWTException $e) {
            throw CustomAuthenticationException::createWithSafeMessage($e->getMessage());
        } catch (\Exception $e) {
            throw CustomAuthenticationException::createWithSafeMessage('Malformed JWT');
        }
        if (!isset($payload['username'])) {
            throw CustomAuthenticationException::createWithSafeMessage('Invalid JWT');
        }
        return $userProvider->loadUserByUsername($payload['username']);
    }
    /**
     * {@inheritdoc}
     */
    public function checkCredentials($credentials, UserInterface $user)
    {
        // noop
    }
}