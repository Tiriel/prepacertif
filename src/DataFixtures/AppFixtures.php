<?php

namespace App\DataFixtures;

use App\Entity\Post;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $admin = $this->loadAdmin($manager);
        $this->loadPosts($manager, $admin);

        $manager->flush();
    }

    private function loadAdmin(ObjectManager $manager): User
    {
        $admin = (new User())
            ->setEmail('admin@admin.com')
            ->setIsVerified(true)
            ->setUsername('admin')
            ->setRoles(['ROLE_ADMIN'])
            ->setPassword('$2y$13$LmAVmFWzimDI0O72LTXEi.iLp3ugJmre.9XDemnt9V9qfAe3yCMX2');
        $manager->persist($admin);

        return $admin;
    }

    private function loadPosts(ObjectManager $manager, User $admin)
    {
        $post = (new Post())
            ->setTitle("Where are you going? Well, I'm not going that way. It's much too rocky.")
            ->setText(<<<EOT
Where are you going? Well, I'm not going that way. It's much too rocky. This way is much easier. What makes you think there are settlements over there? Don't get technical with me.\n
All flight trooper, man your stations. All flight troops, man your stations. So...you got your reward and you're just leaving then? That's right, yeah! I got some old debts I've got to pay off with this stuff. Even if I didn't, you don't think I'd be fool enough to stick around here, do you? Why don't you come with us? You're pretty good in a fight.\n
Oh, he's not dead, not...not yet. You know him! Well of course, of course I know him. He's me! I haven't gone by the name Obi-Wan since oh, before you were born. Then the droid does belong to you.\n
There's nothing here for me now. I want to learn the ways of the Force and become a Jedi like my father. Mos Eisley Spaceport. You will never find a more wretched hive of scum and villainy. We must be cautious. How long have you had these droids? About three or four seasons.\n
EOT
            )
            ->setCreatedAt(new \DateTimeImmutable())
            ->setPublicationDate(new \DateTimeImmutable())
            ->setStatus('published')
            ->setCreatedBy($admin);
        $manager->persist($post);

        $post = (new Post())
            ->setTitle("Hold your fire. There are no life forms. It must have been short-circuited.")
            ->setText(<<<EOT
Hold your fire. There are no life forms. It must have been short-circuited. That's funny, the damage doesn't look as bad from out here. Are you sure this things safe? Lord Vader, I should have known.\n
If they identify us, we're in big trouble. Not if I can help it. Chewie...jam it's transmissions. It'd be as well to let it go.\n
There aren't any bases around here. Where did it come from? It sure is leaving in a big hurry. If they identify us, we're in big trouble. Not if I can help it. Chewie...jam it's transmissions. It'd be as well to let it go. It's too far out of range.\n
Ninety-four. Looks like somebody's beginning to take an interest in your handiwork. All right, we'll check it out. Seventeen thousand! Those guys must really be desperate. This could really save my neck.\n
EOT
            )
            ->setCreatedAt(new \DateTimeImmutable())
            ->setPublicationDate(new \DateTimeImmutable())
            ->setStatus('published')
            ->setCreatedBy($admin);
        $manager->persist($post);
    }
}
