<?php
/**
 * Post fixtures.
 */
namespace App\DataFixtures;

use App\Entity\Post;
use App\Entity\Category;
use App\Entity\Comment;
use DateTimeImmutable;
//use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

//use Doctrine\DBAL\Driver\IBMDB2\Exception\Factory;

/**
 * Class PostFixtures.
 *
 * @psalm-suppress MissingConstructor
 */
class PostFixtures extends AbstractBaseFixtures
{
    /**
     * Load data.
     *
     * @psalm-suppress PossiblyNullPropertyFetch
     * @psalm-suppress PossiblyNullReference
     * @psalm-suppress UnusedClosureParam
     */
    public function loadData(): void
    {
        // $product = new Product();
        // $manager->persist($product);
        if (null === $this->manager || null === $this->faker) {
            return;
        }

        $this->createMany(10, 'posts', function (int $i){
            $post = new Post();
            $post->setTitle($this->faker->sentence);
            $post->setContent($this->faker->sentence);
            $post->setCreatedAt(
                DateTimeImmutable::createFromMutable($this->faker->dateTimeBetween('-100 days', '-1 days'))
            );
            $post->setUpdatedAt(
                DateTimeImmutable::createFromMutable($this->faker->dateTimeBetween('-100 days', '-1 days'))
            );
            /** @var Category $category */
            $category = $this->getRandomReference('categories');
            $post->setCategory($category);

//            $this->manager->persist($post);
            return $post;
        });

        $this->manager->flush();
    }

    /**
     * This method must return an array of fixtures classes
     * on which the implementing class depends on.
     *
     * @return string[] of dependencies
     *
     * @psalm-return array{0: CategoryFixtures::class}
     */
    public function getDependencies(): array
    {
        return [CategoryFixtures::class];
    }
}
