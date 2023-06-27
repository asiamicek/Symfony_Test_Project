<?php
//
///**
// *
// */
//
//use App\Entity\User;
//use App\Security\LoginFormAuthenticator;
//use PHPUnit\Framework\TestCase;
//use Symfony\Component\HttpFoundation\RedirectResponse;
//use Symfony\Component\HttpFoundation\Request;
//use Symfony\Component\HttpFoundation\Session\SessionInterface;
//use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
//use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
//use Symfony\Component\Security\Core\User\UserInterface;
//use Symfony\Component\Security\Http\Authenticator\Passport\Badge\BadgeInterface;
//use Symfony\Component\Security\Http\Authenticator\Passport\Badge\CsrfTokenBadge;
//use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
//use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;
//use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
//use Symfony\Component\Security\Http\Util\TargetPathTrait;
//
//class LoginFormAuthenticatorTest extends TestCase
//{
//    public function testSupports(): void
//    {
//        $urlGenerator = $this->createMock(UrlGeneratorInterface::class);
//        $authenticator = new LoginFormAuthenticator($urlGenerator);
//
//        $request = Request::create('/login', 'POST');
//        $request->attributes->set('_route', 'app_login');
//
//        $this->assertTrue($authenticator->supports($request));
//    }
//
//    public function testSupportsReturnsFalseForInvalidRoute(): void
//    {
//        $urlGenerator = $this->createMock(UrlGeneratorInterface::class);
//        $authenticator = new LoginFormAuthenticator($urlGenerator);
//
//        $request = Request::create('/login', 'POST');
//        $request->attributes->set('_route', 'invalid_route');
//
//        $this->assertFalse($authenticator->supports($request));
//    }
//
//    public function testSupportsReturnsFalseForNonPostMethod(): void
//    {
//        $urlGenerator = $this->createMock(UrlGeneratorInterface::class);
//        $authenticator = new LoginFormAuthenticator($urlGenerator);
//
//        $request = Request::create('/login', 'GET');
//        $request->attributes->set('_route', 'app_login');
//
//        $this->assertFalse($authenticator->supports($request));
//    }
//
//    public function testAuthenticate(): void
//    {
//        $urlGenerator = $this->createMock(UrlGeneratorInterface::class);
//        $authenticator = new LoginFormAuthenticator($urlGenerator);
//
//        $request = Request::create('/login', 'POST');
//        $request->setSession($this->createMock(SessionInterface::class));
//        $request->request->set('email', 'test@example.com');
//        $request->request->set('password', 'password');
//        $request->request->set('_csrf_token', 'csrf_token');
//
//        $user = $this->createMock(UserInterface::class);
//
//        $authenticator->getUser = function($userIdentifier) use ($user) {
//            return $user;
//        };
//
//        $passport = $authenticator->authenticate($request);
//
//        $badges = $passport->getBadges();
//        $this->assertNotEmpty($badges);
//
//        $userBadge = $badges[0] ?? null;
//        $this->assertInstanceOf(UserBadge::class, $userBadge);
//        $this->assertSame($user, $userBadge->getUser());
//
//        $credentials = $passport->getCredentials();
//        $this->assertInstanceOf(PasswordCredentials::class, $credentials);
//        $this->assertSame('password', $credentials->getPassword());
//
//        $csrfTokenBadge = $badges[1] ?? null;
//        $this->assertInstanceOf(CsrfTokenBadge::class, $csrfTokenBadge);
//        $this->assertSame('authenticate', $csrfTokenBadge->getId());
//        $this->assertSame('csrf_token', $csrfTokenBadge->getToken());
//    }
//
//
//
//
//
//    public function testOnAuthenticationSuccessWithTargetPath(): void
//    {
//        $urlGenerator = $this->createMock(UrlGeneratorInterface::class);
//        $urlGenerator->method('generate')->willReturn('/default');
//        $authenticator = new LoginFormAuthenticator($urlGenerator);
//
//        $request = Request::create('/login');
//        $request->setSession($this->createMock(SessionInterface::class));
//        $request->getSession()->expects($this->once())->method('get')->willReturn('/target');
//
//        $token = $this->createMock(TokenInterface::class);
//
//        $response = $authenticator->onAuthenticationSuccess($request, $token, 'main');
//
//        $this->assertInstanceOf(RedirectResponse::class, $response);
//        $this->assertSame('/target', $response->getTargetUrl());
//    }
//
//    public function testOnAuthenticationSuccessWithoutTargetPath(): void
//    {
//        $urlGenerator = $this->createMock(UrlGeneratorInterface::class);
//        $urlGenerator->method('generate')->willReturn('/default');
//        $authenticator = new LoginFormAuthenticator($urlGenerator);
//
//        $request = Request::create('/login');
//        $request->setSession($this->createMock(SessionInterface::class));
//        $request->getSession()->expects($this->once())->method('get')->willReturn(null);
//
//        $token = $this->createMock(TokenInterface::class);
//
//        $response = $authenticator->onAuthenticationSuccess($request, $token, 'main');
//
//        $this->assertInstanceOf(RedirectResponse::class, $response);
//        $this->assertSame('/default', $response->getTargetUrl());
//    }
//
//    public function testGetLoginUrl(): void
//    {
//        $urlGenerator = $this->createMock(UrlGeneratorInterface::class);
//        $urlGenerator->method('generate')->willReturn('/login');
//        $authenticator = new LoginFormAuthenticator($urlGenerator);
//
//        $request = Request::create('/login');
//
//        $reflection = new ReflectionClass(LoginFormAuthenticator::class);
//        $method = $reflection->getMethod('getLoginUrl');
//        $method->setAccessible(true);
//        $loginUrl = $method->invoke($authenticator, $request);
//
//        $this->assertSame('/login', $loginUrl);
//    }
//}
//
