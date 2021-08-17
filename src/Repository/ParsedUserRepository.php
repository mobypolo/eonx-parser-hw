<?php

namespace App\Repository;

use DateTime;
use App\Entity\ParsedUser;
use Doctrine\ORM\EntityManagerInterface;
use App\Interfaces\IParsedUserRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * ParsedUserRepository - is repo for customerEntity
 */
class ParsedUserRepository extends ServiceEntityRepository implements IParsedUserRepository
{
    public function __construct(
        ManagerRegistry $registry,
        private EntityManagerInterface $em,
        public ?ParsedUser $model,
    ) {
        parent::__construct($registry, ParsedUser::class);
    }

    /**
     * updateOrCreate - checking if entity exist - update it, overwise create new
     *
     * @param  mixed $model
     * @return void
     */
    public function createOrUpdate(ParsedUser $model): void
    {
        $alreadyExist = $this->em->getRepository(ParsedUser::class)->findOneBy(['email' => $model->getEmail()]);

        if ($alreadyExist === null)
            $alreadyExist = new ParsedUser();
        else
            $alreadyExist->setUpdatedAt(new DateTime());

        foreach ($model->fillable as $value) {
            $alreadyExist->{"set" . $value}($model->{"get" . $value}());
        }

        $this->em->persist($alreadyExist);
        $this->em->flush();
    }
}
