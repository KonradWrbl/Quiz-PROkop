<?php

namespace App\Service\Manager\Quiz;

use App\Entity\Quiz;
use App\Entity\Question;
use App\Entity\Answer;
use App\Model\Quiz\QuizModel;
use App\Model\Question\QuestionModel;
use App\Model\Answer\AnswerModel;
use App\Service\Manager\Manager;
use App\Service\ModelHydration;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class QuizManager extends Manager
{
    public function __construct(EntityManagerInterface $entityManager, ValidatorInterface $validator)
    {
        parent::__construct($entityManager, $validator);
    }

    /**
     * @param QuizModel $model
     * @return Quiz|ConstraintViolationListInterface
     * @throws Exception
     */
    public function createQuiz(QuizModel $model)
    {
        $errors = $this->getValidator()->validate($model);

        if (count($errors) > 0) {
            return $errors;
        }

        $quiz = new Quiz();
        $quiz->setTitle($model->getTitle());
        $this->saveEntity($quiz);

        return $quiz;
    }

    /**
     * @param QuestionModel $model
     * @param Quiz $quiz
     * @return Question|ConstraintViolationListInterface
     * @throws Exception
     */
    public function createQuestion(QuestionModel $model, Quiz $quiz)
    {
        $errors = $this->getValidator()->validate($model);

        if (count($errors) > 0) {
            return $errors;
        }

        $question = new Question();
        $question->setContent($model->getContent());
        $question->setQuiz($quiz);
        $this->saveEntity($question);

        return $question;
    }

    /**
     * @param QuizModel $model
     * @param Question $question
     * @return Quiz|ConstraintViolationListInterface
     * @throws Exception
     */
    public function createAnswer(AnswerModel $model, Question $question)
    {
        $errors = $this->getValidator()->validate($model);

        if (count($errors) > 0) {
            return $errors;
        }

        $answer = new Answer();
        $answer->setDescription($model->getDescription());
        $answer->setIsCorrect($model->getIsCorrect());
        $answer->setQuestion($question);

        $this->saveEntity($answer);

        return $answer;
    }
}
