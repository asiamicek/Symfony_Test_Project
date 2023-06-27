<?php

namespace App\Tests;

use App\Entity\Category;
use App\Entity\Comment;
use App\Entity\Post;
use App\Entity\User;
use App\Repository\CategoryRepository;
use App\Repository\CommentRepository;
use App\Repository\PostRepository;
use App\Repository\UserRepository;
use DateTime;
use Monolog\DateTimeImmutable;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

abstract class BaseTest extends WebTestCase
{

    /**
     * Test client.
     */
    protected KernelBrowser $httpClient;

    /**
     * Create user.
     *
     * @param array $roles User roles
     *
     * @return User User entity
     */
    protected function createUser(array $roles, string $email, string $nickname): User
    {
        $passwordHasher = static::getContainer()->get('security.password_hasher');
        $user = new User();
        $user->setEmail($email);
        $user->setRoles($roles);
        $user->setNickname($nickname);
        $user->setPassword(
            $passwordHasher->hashPassword(
                $user,
                'p@55w0rd'
            )
        );
        $userRepository = static::getContainer()->get(UserRepository::class);
        $userRepository->save($user, true);

        return $user;
    }

    /**
     * Simulate user log in.
     *
     * @param User $user User entity
     */
    protected function logIn(User $user): void
    {
        $session = self::getContainer()->get('session');

        $firewallName = 'main';
        $firewallContext = 'main';

        $token = new UsernamePasswordToken($user, null, $firewallName, $user->getRoles());
        $session->set('_security_'.$firewallContext, serialize($token));
        $session->save();

        $cookie = new Cookie($session->getName(), $session->getId());
        $this->httpClient->getCookieJar()->set($cookie);
    }

    /**
     * Remove user
     */
    protected function removeUser(): void
    {
        $userRepository = static::getContainer()->get(UserRepository::class);
        $entity = $userRepository->findOneBy(array('email' => 'test2@example.com'));


        if ($entity !== null) {
            $userRepository->remove($entity);
        }
    }

    /**
     * Create category.
     */
    protected function createCategory(string $title): Category
    {
        $category = new Category();
        $category->setTitle($title);
        $categoryRepository = self::getContainer()->get(CategoryRepository::class);
        $categoryRepository->save($category, true);

        return $category;
    }

    /**
     * Create post.
     */
    protected function createPost(User $user, Category $category): Post
    {
        $post = new Post();
        $post->setTitle('PName');
        $post->setContent('PContent');
        $post->setAuthor($user);
        $post->setCategory($category);
        $post->setUpdatedAt(DateTimeImmutable::createFromMutable(new \DateTime('@'.strtotime('now'))));
        $post->setCreatedAt(DateTimeImmutable::createFromMutable(new \DateTime('@'.strtotime('now'))));
        $postRepository = self::getContainer()->get(PostRepository::class);
        $postRepository->save($post, true);

        return $post;
    }

    /**
     * Create comment
     */
    protected function createComment(Post $post, User $user): Comment
    {
        $comment = new Comment();
        $comment->setContent('CText');
        $comment->setAuthor($user);
        $comment->setPost($post);

        $commentRepository = self::getContainer()->get(CommentRepository::class);
        $commentRepository->save($comment, true);

        return $comment;
    }
}
