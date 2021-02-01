<?php

namespace App\Controller;

use App\Service\Utils\ISerializer;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\ConstraintViolationInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class BaseController extends AbstractController
{
    /**
     * @var ISerializer
     */
    private $serializer;

    /**
     * @var ValidatorInterface
     */
    private $validator;

    /**
     * @var LoggerInterface
     */
    private $logger;

    public function __construct(ISerializer $serializer, ValidatorInterface $validator, LoggerInterface $logger)
    {
        $this->setSerializer($serializer);
        $this->setValidator($validator);
        $this->setLogger($logger);
    }

    protected function jsonSerialize($object, $groups = ['groups' => 'default']): string
    {
        return $this->getSerializer()->serialize($object, 'json', $groups);
    }

    protected function jsonDeserialize($data, string $class, $groups = ['groups' => 'default']): object
    {
        return $this->getSerializer()->deserialize($data, $class, 'json', $groups);
    }

    protected function jsonResponse($data = null, $statusCode = Response::HTTP_OK, $serializationGroups = ['groups' => 'default'], $headers = []): Response
    {
        $headers = array_merge(
            ['Content-Type' => 'application/json'],
            $headers
        );
        $headers = array_unique($headers);

        if (empty($data['status'])) {
            $data['status'] = $statusCode;
        }

        $data = $this->jsonSerialize($data, $serializationGroups);

        return new JsonResponse($data, $statusCode, $headers, true);
    }

    /**
     * @param string $path
     * @return BinaryFileResponse
     */
    protected function fileResponse($path)
    {
        return new BinaryFileResponse($path);
    }

    protected function validateModel($model): ConstraintViolationListInterface
    {
        return $this->getValidator()->validate($model);
    }

    protected function failedValidationResponse(ConstraintViolationListInterface $violationList): Response
    {
        $response = [
            'validationErrors' => []
        ];

        /** @var ConstraintViolationInterface $violation */
        foreach ($violationList as $violation) {
            $response['validationErrors'][$violation->getPropertyPath()][] = $violation->getMessage();
        }

        return $this->jsonResponse($response, Response::HTTP_BAD_REQUEST);
    }

    /**
     * @return ISerializer
     */
    public function getSerializer(): ISerializer
    {
        return $this->serializer;
    }

    /**
     * @param ISerializer $serializer
     * @return BaseController
     */
    public function setSerializer(ISerializer $serializer): BaseController
    {
        $this->serializer = $serializer;
        return $this;
    }

    /**
     * @return ValidatorInterface
     */
    public function getValidator(): ValidatorInterface
    {
        return $this->validator;
    }

    /**
     * @param ValidatorInterface $validator
     * @return BaseController
     */
    public function setValidator(ValidatorInterface $validator): BaseController
    {
        $this->validator = $validator;
        return $this;
    }

    /**
     * @return LoggerInterface
     */
    public function getLogger(): LoggerInterface
    {
        return $this->logger;
    }

    /**
     * @param LoggerInterface $logger
     * @return BaseController
     */
    public function setLogger(LoggerInterface $logger): BaseController
    {
        $this->logger = $logger;
        return $this;
    }
}