<?php
/**
 * Tag fixtures.
 */

namespace App\DataFixtures;

use App\Entity\Tag;
use App\Entity\User;

/**
 * Class TagFixtures.
 *
 * @psalm-suppress MissingConstructor
 */
class TagFixtures extends AbstractBaseFixtures
{
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

        $this->createMany(15, 'tags', function () {
            $tag = new Tag();
            $tag->setTitle($this->faker->word);

            /** @var User $author */
            $author = $this->getRandomReference('users');
            $tag->setAuthor($author);


            return $tag;
        });



        $this->manager->flush();
    }

    /**
     * This method must return an array of fixtures classes
     * on which the implementing class depends on.
     *
     * @return string[] of dependencies
     *
     * @psalm-return array{0: UserFixtures::class}
     */
    public function getDependencies(): array
    {
        return [ UserFixtures::class];
    }
}