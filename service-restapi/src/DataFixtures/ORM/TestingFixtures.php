<?php

namespace App\DataFixtures\ORM;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;

use App\Entity\User;

class TestingFixtures extends Fixture implements FixtureGroupInterface
{
    public static function getGroups(): array
    {
        return ['testing'];
    }

    public function load(ObjectManager $manager)
    {
        $sql = file_get_contents(__DIR__ . '/../SQL/testing_dump.sql');
        $manager->getConnection()->exec($sql);
        $manager->flush();
    }
}
