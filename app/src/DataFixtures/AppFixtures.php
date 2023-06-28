<?php
/**
 * App Fixtures.
 */

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

/**
 * Class App Fixtures.
 */
class AppFixtures extends Fixture
{
    /**
     * Load function.
     *
     * @param ObjectManager $manager Object Manager
     */
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);

        $manager->flush();
    }
}
