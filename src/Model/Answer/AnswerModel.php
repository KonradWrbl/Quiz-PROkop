<?php

namespace App\Model\Answer;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;

class AnswerModel
{
    /**
     * @var string
     *
     * @Assert\NotNull()
     * @Assert\NotBlank()
     *
     * @Groups({"default"})
     */
    private $description;

    /**
     * @var boolean
     *
     * @Assert\NotNull()
     * @Assert\NotBlank()
     *
     * @Groups({"default"})
     */
    private $isCorrect;

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getIsCorrect(): ?bool
    {
        return $this->isCorrect;
    }

    public function setIsCorrect(bool $isCorrect): self
    {
        $this->isCorrect = $isCorrect;

        return $this;
    }
}
