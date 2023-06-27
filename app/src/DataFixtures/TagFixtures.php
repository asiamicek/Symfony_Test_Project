<?php
/**
 * Tag fixtures.
 */

namespace App\DataFixtures;

use App\Entity\Enum\UserRole;
use App\Entity\Tag;
use App\Entity\User;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

/**
 * Class TagFixtures.
 *
 * @psalm-suppress MissingConstructor
 */
class TagFixtures extends AbstractBaseFixtures
{
    /**
     * Password hasher.
     */
    private UserPasswordHasherInterface $passwordHasher;

    /**
     * @param UserPasswordHasherInterface $passwordHasher Password hasher
     */
    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    /**
     * Load data.
     *
     * @psalm-suppress PossiblyNullReference
     * @psalm-suppress UnusedClosureParam
     */
    public function loadData(): void
    {
        if (null === $this->manager || null === $this->faker) {
            return;
        }

//        $user = new User();
//        $user->setEmail(sprintf('user@example.com'));
//        $user->setRoles([UserRole::ROLE_USER->value, UserRole::ROLE_ADMIN->value]);
//        $user->setNickname($this->faker->unique()->word);
//        $user->setPassword(
//            $this->passwordHasher->hashPassword(
//                $user,
//                'admin1234'
//            )
//        );
//
//        $this->manager->persist($user);
//        $this->manager->flush();

        $this->createMany(15, 'tags', function () {
            $tag = new Tag();
            $tag->setTitle($this->faker->word);


//            $user= $this->manager->getRepository(User::class)->findOneBy(['email' =>'user@example.com']);
//            $tag->setAuthor($user);


//            /** @var User $author */
//            $author = $this->getRandomReference('users');
//            $tag->setAuthor($author);


            return $tag;
        });



        $this->manager->flush();
    }

//    /**
//     * This method must return an array of fixtures classes
//     * on which the implementing class depends on.
//     *
//     * @return string[] of dependencies
//     *
//     * @psalm-return array{0: UserFixtures::class}
//     */
//    public function getDependencies(): array
//    {
//        return [UserFixtures::class];
//    }
}