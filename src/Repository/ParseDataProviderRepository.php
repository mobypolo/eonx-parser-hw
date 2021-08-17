<?php

namespace App\Repository;

use App\Entity\ParseDataProvider;
use App\Exceptions\AppMainException;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use App\Interfaces\IParseDataProviderRepository;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * ParseDataProviderRepository - is repo for DataProvider with persist method logic
 */
class ParseDataProviderRepository extends ServiceEntityRepository implements IParseDataProviderRepository
{

    public string $url;
    public int $elementsForParse;
    public string $rootIteratorElement;
    public ?ParseDataProvider $model;

    public function __construct(
        private ManagerRegistry $registry,
        private EntityManagerInterface $em,
        public $modelClass = ParseDataProvider::class,
    ) {
        parent::__construct($registry, $this->modelClass);
    }

    /**
     * updateOrCreate - checking if entity exist - update it, overwise create new
     *
     * @param  mixed $params
     * @return ParseDataProvider
     */
    public function updateOrCreate(array $params): ParseDataProvider
    {
        return $this->setParams($params)->checkup()->checkIfExist()->persist()->getModel();
    }

    /**
     * checkup - checking supplied data for fitting to requements
     *
     * @return static
     */
    private function checkup(): static
    {
        if (
            strlen((string) $this->url) < 1
            || (int) $this->elementsForParse < 1
            || strlen((string) $this->rootIteratorElement) < 1
        )
            throw new AppMainException("Invalid data specified in " . __METHOD__);
        return $this;
    }

    /**
     * setParams - simple factory method for class
     *
     * @param  mixed $params
     * @return static
     */
    private function setParams(array $params): static
    {
        [
            'url' => $url,
            'elementsForParse' => $elementsForParse,
            'rootIteratorElement' => $rootIteratorElement,
        ] = $params;

        $this->url = $url;
        $this->elementsForParse = $elementsForParse;
        $this->rootIteratorElement = $rootIteratorElement;
        return $this;
    }

    /**
     * checkIfExist - checking if entity exist in db
     *
     * @return static
     */
    private function checkIfExist(): static
    {
        $this->model = $this->createQueryBuilder('p')
            ->andWhere('p.url = :val')
            ->setParameter('val', $this->url)
            ->getQuery()
            ->getOneOrNullResult();
        return $this;
    }

    /**
     * persist - store data to db if model is not defined
     *
     * @return static
     */
    private function persist(): static
    {
        if ($this->model == null) {
            $this->model = new $this->modelClass();
            $this->model->setUrl($this->url);
            $this->model->setElementsForParse($this->elementsForParse);
            $this->model->setRootIteratorElement($this->rootIteratorElement);
            $this->save();
        }
        return $this;
    }

    /**
     * getModel - getter for receive model
     *
     * @return ParseDataProvider
     */
    private function getModel(): ParseDataProvider
    {
        return $this->model;
    }

    /**
     * updateSubmission - update sudmission for exist model, normally called after successfully ended submission tour
     *
     * @param  mixed $array
     * @return ParseDataProvider
     */
    public function updateSubmission(array $array): ParseDataProvider
    {
        $this->model->setSubmission($array);
        $this->save();
        return $this->model;
    }

    /**
     * save - simple save to db
     *
     * @return void
     */
    public function save(): void
    {
        $this->em->persist($this->model);
        $this->em->flush();
    }
}
