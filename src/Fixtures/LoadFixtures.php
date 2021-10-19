<?php 

namespace App\Fixtures;

use App\Entity\Client;
use App\Entity\Products;
use App\Entity\Users;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class LoadFixtures extends Fixture
{

    private UserPasswordEncoderInterface $password;

    public function __construct(UserPasswordEncoderInterface $password) {
        $this->password = $password;
    }

    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');

        $client = (new Client)
            ->setEmail('test@gmail.com');
        $client->setPassword($this->password->encodePassword($client, 'test'));
        $manager->persist($client);


        for ($i = 0; $i < 20; $i++) {
            $brand = $faker->company();

            $product = (new Products())
                            ->setName($brand. " " .rand(1, 20))
                            ->setStorage(rand(64, 1000))
                            ->setBrand($brand)
                            ->setSize(rand(4, 12))
                            ->setPrice(rand(800, 2684))
                            ;
            
            $manager->persist($product);

        }

        for ($i = 0; $i < 10; $i++) {

            $firstname = $faker->firstName();
            $lastname = $faker->lastName();
            $email = "$firstname.$lastname@gmail.com";

            $user = (new Users())
                            ->setClient($client)
                            ->setFirstname($firstname)            
                            ->setLastname($lastname)
                            ->setEmail($email)
                            ->setPhoneNumber($faker->phoneNumber());

            $manager->persist($user);

        }

        $manager->flush();
    }
}