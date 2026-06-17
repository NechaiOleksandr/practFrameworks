<?php

namespace App\Controller;

use App\Entity\Question;
use App\Repository\QuestionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/questions', name: 'api_')]
class QuestionController extends AbstractController
{
    //GET
    #[Route('', name: 'questions_index', methods: ['GET'])]
    public function index(QuestionRepository $repository): JsonResponse
    {
        $questions = $repository->findAll();
        $data = [];

        foreach ($questions as $q) {
            $data[] = [
                'id' => $q->getId(),
                'text' => $q->getText(),
                'isActive' => $q->isActive(),
            ];
        }

        return new JsonResponse($data, Response::HTTP_OK);
    }

    //POST
    #[Route('', name: 'questions_create', methods: ['POST'])]
    public function create(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (empty($data['text'])) {
            return new JsonResponse(['error' => 'Missing text parameter'], Response::HTTP_BAD_REQUEST);
        }

        $question = new Question();
        $question->setText($data['text']);
        $question->setIsActive($data['isActive'] ?? true);

        $em->persist($question);
        $em->flush();

        return new JsonResponse([
            'id' => $question->getId(),
            'text' => $question->getText(),
            'isActive' => $question->isActive()
        ], Response::HTTP_CREATED);
    }

    //GET
    #[Route('/{id}', name: 'questions_show', methods: ['GET'])]
    public function show(Question $question): JsonResponse
    {
        return new JsonResponse([
            'id' => $question->getId(),
            'text' => $question->getText(),
            'isActive' => $question->isActive()
        ], Response::HTTP_OK);
    }

    //PATCH
    #[Route('/{id}', name: 'questions_update', methods: ['PATCH'])]
    public function update(Request $request, Question $question, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (isset($data['text'])) {
            $question->setText($data['text']);
        }
        if (isset($data['isActive'])) {
            $question->setIsActive($data['isActive']);
        }

        $em->flush();

        return new JsonResponse([
            'id' => $question->getId(),
            'text' => $question->getText(),
            'isActive' => $question->isActive()
        ], Response::HTTP_OK);
    }

    //DELETE
    #[Route('/{id}', name: 'questions_delete', methods: ['DELETE'])]
    public function delete(Question $question, EntityManagerInterface $em): JsonResponse
    {
        $em->remove($question);
        $em->flush();

        return new JsonResponse(['message' => 'Question deleted successfully'], Response::HTTP_OK);
    }
}
