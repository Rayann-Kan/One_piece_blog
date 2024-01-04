<?php

namespace App\DataFixtures;

use App\Entity\Setting;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
;

class SettingFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $setting = $this->createSettingData();
        $manager->persist($setting);

        $manager->flush();
    }

    private function createSettingData(): Setting 
    {
        $setting = new Setting();
        $setting->setEmail('one-blog-convivial@gmail.com');
        $setting->setPhone('06 05 09 06 05');
        
        return $setting;
    }
}
