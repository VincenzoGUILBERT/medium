<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Post;
use App\Entity\User;
use App\Entity\Comment;
use App\Entity\PostLike;
use App\Entity\CommentLike;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    public function __construct(private UserPasswordHasherInterface $hasher) {}

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        $usersArray = [];
        $usersNumber = 10;

        // new users
        for ($i = 0; $i < $usersNumber; $i++) {
            $user = new User();
            $hashedPassword = $this->hasher->hashPassword($user, 'password');
            $user->setUsername($faker->userName())
                ->setEmail($faker->email())
                ->setPassword($hashedPassword)
                ->setCreatedAt(\DateTimeImmutable::createFromMutable($faker->dateTimeBetween('-1 year', 'now')))
            ;

            array_push($usersArray, $user);
            $manager->persist($user);
        }

        // new posts
        for ($j = 0; $j < 50; $j++) {
            $post = new Post();
            $post->setTitle($faker->sentence())
                ->setContent($faker->paragraph(10, true))
                ->setAuthor($usersArray[mt_rand(0, 9)])
                ->setCreatedAt(\DateTimeImmutable::createFromMutable($faker->dateTimeBetween('-1 year', 'now')))
            ;

            $manager->persist($post);
            // new likes
            for ($n = 0; $n < mt_rand(0, $usersNumber); $n++) {
                $like = new PostLike();
                $like->setUser($usersArray[$n])
                    ->setPost($post)
                ;

                $manager->persist($like);
            }

            // New comments
            for ($k = 0; $k < mt_rand(3, 5); $k++) {
                $comment = new Comment();
                $comment->setContent($faker->paragraph(mt_rand(1, 5)))
                    ->setCreatedAt(\DateTimeImmutable::createFromMutable($faker->dateTimeBetween('-1 year', 'now')))
                    ->setAuthor($usersArray[mt_rand(0, 9)])
                    ->setPost($post)
                ;

                $manager->persist($comment);
                // new likes
                for ($n = 0; $n < mt_rand(0, $usersNumber); $n++) {
                    $like = new CommentLike();
                    $like->setUser($usersArray[$n])
                        ->setComment($comment)
                    ;

                    $manager->persist($like);
                }
            }
        }

        $manager->flush();
    }
}
