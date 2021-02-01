<?php

namespace App\Model\Quiz;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;

class QuizModel
{
    /**
     * @var string
     *
     * @Assert\NotNull()
     * @Assert\NotBlank()
     *
     * @Groups({"default"})
     */
    private $title;

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }
}
