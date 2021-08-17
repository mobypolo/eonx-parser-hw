<?php

namespace App\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use App\Repository\ParseDataProviderRepository;

/**
 * @ORM\Entity(repositoryClass=ParseDataProviderRepository::class)
 * @ORM\Table(name="parse_providers")
 */
class ParseDataProvider
{
    const PREDEFINED_QUESTIONS = [
        'Email',
        'FullName',
        'Country',
        'Username',
        'Gender',
        'City',
        'Phone',
    ];

    public bool $isNew = false;

    public function __construct()
    {
        $this->setCreatedAt(new DateTime());
        $this->isNew = true;
        $this->setSubmission([]);
    }
    /**
     * @ORM\Id()
     * @ORM\Column(type="integer", unique=true)
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     */
    private $url;

    /**
     * @ORM\Column(type="json")
     */
    private $submission;

    /**
     * @ORM\Column(type="integer")
     */
    private $elementsForParse;

    /**
     * @ORM\Column(type="string")
     */
    private $rootIteratorElement;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Gedmo\Timestampable(on="create")
     */
    private $created_at;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Gedmo\Timestampable(on="update")
     */
    private $updated_at;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param mixed $url
     */
    public function setUrl($url): void
    {
        $this->url = $url;
    }

    /**
     * @return mixed
     */
    public function getSubmission()
    {
        return $this->submission;
    }

    /**
     * @param mixed $submission
     */
    public function setSubmission($submission): void
    {
        $this->submission = $submission;
    }

    /**
     * @return mixed
     */
    public function getElementsForParse()
    {
        return $this->elementsForParse;
    }

    /**
     * @param mixed $elementsForParse
     */
    public function setElementsForParse($elementsForParse): void
    {
        $this->elementsForParse = $elementsForParse;
    }

    /**
     * @return mixed
     */
    public function getRootIteratorElement()
    {
        return $this->rootIteratorElement;
    }

    /**
     * @param mixed $rootIteratorElement
     */
    public function setRootIteratorElement($rootIteratorElement): void
    {
        $this->rootIteratorElement = $rootIteratorElement;
    }

    /**
     * @return mixed
     */
    public function getCreatedAt()
    {
        return $this->created_at;
    }

    /**
     * @param mixed $created_at
     */
    public function setCreatedAt($created_at): void
    {
        $this->created_at = $created_at;
    }

    /**
     * @return mixed
     */
    public function getUpdatedAt()
    {
        return $this->updated_at;
    }

    /**
     * @param mixed $updated_at
     */
    public function setUpdatedAt($updated_at): void
    {
        $this->updated_at = $updated_at;
    }
}
