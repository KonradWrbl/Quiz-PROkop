<?php

namespace App\Service\Manager;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class Manager
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var ValidatorInterface $validator
     */
    protected $validator;

    public function __construct(EntityManagerInterface $entityManager, ValidatorInterface $validator)
    {
        $this->setEntityManager($entityManager);
        $this->setValidator($validator);
    }

    /**
     * @param Object $entity
     * @param bool $flush
     */
    public function saveEntity($entity, $flush = true)
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->entityManager->flush();
        }
    }

    /**
     * @return EntityManagerInterface
     */
    public function getEntityManager(): EntityManagerInterface
    {
        return $this->entityManager;
    }

    /**
     * @param EntityManagerInterface $entityManager
     * @return Manager
     */
    public function setEntityManager(EntityManagerInterface $entityManager): Manager
    {
        $this->entityManager = $entityManager;
        return $this;
    }

    /**
     * @return ValidatorInterface
     */
    protected function getValidator(): ValidatorInterface
    {
        return $this->validator;
    }

    /**
     * @param ValidatorInterface $validator
     */
    protected function setValidator(ValidatorInterface $validator): void
    {
        $this->validator = $validator;
    }
}