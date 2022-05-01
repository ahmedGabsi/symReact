<?php

namespace App\DataFixtures;

use App\Entity\Customer;
use App\Entity\Invoice;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Monolog\DateTimeImmutable;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{  
    private $passwordHasher;
    function __construct(UserPasswordHasherInterface $passwordHasher){
     $this->passwordHasher = $passwordHasher;
    }
    public function load(ObjectManager $manager): void
    {
        $faker=Factory::create('fr_FR');
        for($u=0;$u<5;$u++){
            $user=new User();

            $invoiceNumber=1;
            $hash=$this->passwordHasher->hashPassword(
                $user,
                "12345678"
                
            );
            $user->setEmail($faker->email())
                 ->setPassword($hash)
                 ->setFirstName($faker->firstName())
                 ->setLastName($faker->lastName());
                 $manager->persist($user);

        for($c=0;$c<mt_rand(1,20);$c++){
            $customer=new Customer();
            $customer->setFirstName($faker->firstName())
                     ->setLastName($faker->lastName())
                     ->setEmail($faker->email())
                     ->setPhoneNumber($faker->phoneNumber())
                     ->setFaculty($faker->company())
                     ->setUser($user);
                     $manager->persist($customer);
                     for($i=0;$i<mt_rand(1,3);$i++){
                        $invoice= new Invoice();
                         $invoice->setAmount($faker->randomFloat(2,2,10))
                                 ->setSentAt(new DateTimeImmutable('-6 days'))
                                 ->setStatus($faker->randomElement(['PAID','UNPAID']))
                                 ->setCustomer($customer)
                                 ->setInvoiceNumber($invoiceNumber);
                                 $invoiceNumber+=1;
                                 $manager->persist($invoice);


                     }


        }
    }
        // $product = new Product();
        // $manager->persist($product);

        $manager->flush();
    }
}
