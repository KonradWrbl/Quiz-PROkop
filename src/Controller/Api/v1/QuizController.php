<?php

namespace App\Controller\Api\v1;

use App\Controller\BaseController;
use App\Entity\Quiz;
use App\Entity\Question;
use App\Entity\Answer;
use App\Model\Quiz\QuizModel;
use App\Model\Question\QuestionModel;
use App\Model\Answer\AnswerModel;
use App\Repository\QuizRepository;
use App\Service\Manager\Quiz\QuizManager;
use Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Swagger\Annotations as SWG;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;

/**
 * @Route("/api/v1/")
 */
class QuizController extends BaseController
{
    /**
     * Creates a quiz.
     *
     * @Route("quiz/", methods={"POST"})
     *
     * @SWG\Tag(name="Quiz")
     *
     * @SWG\Parameter(
     *     name="body",
     *     in="body",
     *     @Model(type=QuizModel::class)
     * )
     *
     * @SWG\Response(
     *      response=Response::HTTP_OK,
     *      description="Quiz added",
     *      @Model(type=Quiz::class, groups={"default"})
     * )
     *
     * @param Request $request
     * @param QuizManager $quizManager
     * @return Response
     * @throws Exception
     */
    public function postQuiz(Request $request, QuizManager $quizManager)
    {
        /** @var QuizModel $quizModel */
        $quizModel = $this->jsonDeserialize($request->getContent(), QuizModel::class, ['groups' => ['default']]);
        $result = $quizManager->createQuiz($quizModel);

        return $this->jsonResponse(['result' => $result], Response::HTTP_OK, ['groups' => ['default']]);
    }

    /**
     * Creates a question.
     *
     * @Route("question/{quiz}", methods={"POST"})
     *
     * @SWG\Tag(name="Question")
     *
     * @SWG\Parameter(
     *     name="body",
     *     in="body",
     *     @Model(type=QuestionModel::class)
     * )
     *
     * @SWG\Response(
     *      response=Response::HTTP_OK,
     *      description="Question added",
     *      @Model(type=Question::class, groups={"default"})
     * )
     *
     * @param Request $request
     * @param QuizManager $quizManager
     * @return Response
     * @throws Exception
     */
    public function postQuestion(Request $request, QuizManager $quizManager, Quiz $quiz)
    {
        /** @var QuestionModel $questionModel */
        $questionModel = $this->jsonDeserialize($request->getContent(), QuestionModel::class, ['groups' => ['default']]);
        $result = $quizManager->createQuestion($questionModel, $quiz);

        return $this->jsonResponse(['result' => $result], Response::HTTP_OK, ['groups' => ['default']]);
    }

    /**
     * Get quizzes.
     *
     * @Route("quiz/", methods={"GET"})
     *
     * @Security(name="Bearer")
     * @SWG\Tag(name="Quiz")
     *
     * @SWG\Response(
     *      response=Response::HTTP_OK,
     *      description="List of all quizzes for user",
     *     @SWG\Schema(
     *         type="array",
     *         @SWG\Items(ref= @Model(type=Quiz::class, groups={"default"}))
     *      )
     * )
     *
     * @SWG\Response(
     *      response=Response::HTTP_FORBIDDEN,
     *      description="Access denied",
     * )
     *
     * @return Response
     */
    public function getQuizzes()
    {
        /** @var Quiz[] $quizzes */
        $quizzes = $this->getDoctrine()
            ->getRepository(Quiz::class)
            ->findAll();

        return $this->jsonResponse(['quizzes' => $quizzes], Response::HTTP_OK, ['groups' => ['default']]);
    }


    /**
    * Creates a answer.
    *
    * @Route("answer/{question}", methods={"POST"})
    *
    * @Security(name="Bearer")
    * @SWG\Tag(name="Answer")
    *
    * @SWG\Parameter(
    *     name="body",
    *     in="body",
    *     @Model(type=AnswerModel::class)
    * )
    *
    * @SWG\Response(
    *      response=Response::HTTP_OK,
    *      description="Answer added",
    *      @Model(type=Answer::class, groups={"default"})
    * )
    *
    * @SWG\Response(
    *      response=Response::HTTP_FORBIDDEN,
    *      description="Access denied",
    * )
    *
    * @param Request $request
    * @param QuizManager $quizManager
    * @param Question $question
    * @return Response
    * @throws Exception
    */
   public function postAnswer(Request $request, QuizManager $quizManager, Question $question)
   {
       /** @var AnswerModel $quizModel */
       $answerModel = $this->jsonDeserialize($request->getContent(), AnswerModel::class, ['groups' => ['default']]);
       $result = $quizManager->createAnswer($answerModel, $question);

       return $this->jsonResponse(['response' => $result], Response::HTTP_OK, ['groups' => ['default']]);
   }

    /**
     * Get quiz.
     *
     * @Route("quiz/{quiz}/", methods={"GET"})
     *
     * @Security(name="Bearer")
     * @SWG\Tag(name="Quiz")
     *
     * @SWG\Response(
     *      response=Response::HTTP_OK,
     *      description="All information about quiz",
     *      @Model(type=Quiz::class, groups={"default"})
     * )
     *
     * @SWG\Response(
     *      response=Response::HTTP_FORBIDDEN,
     *      description="Access denied",
     * )
     *
     * @param Quiz $quiz
     * @return Response
     */
    public function getQuiz(Quiz $quiz)
    {
        return $this->jsonResponse(['quiz' => $quiz], Response::HTTP_OK, ['groups' => ['default']]);
    }
}