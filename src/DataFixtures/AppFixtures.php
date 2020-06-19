<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Nelmio\Alice\Loader\NativeLoader;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class AppFixtures extends Fixture implements ContainerAwareInterface
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * @var Factory
     */
    private $faker;

    /**
     * EmployeeFixtures constructor.
     */
    public function __construct()
    {
        $this->faker = Factory::create('fr_FR');
    }

    /**
     * @param ContainerInterface|null $container
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $loader = new NativeLoader();
        $env = $this->container->getParameter('kernel.environment');

        if($env === 'dev')
        {
            $objectSet = $loader->loadFile(__DIR__.'/fixtures.yml')->getObjects();
            foreach($objectSet as $object) {
                $manager->persist($object);
            }
        }

        $manager->flush();
    }
}
