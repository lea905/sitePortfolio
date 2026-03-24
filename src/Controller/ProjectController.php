<?php

namespace App\Controller;

use App\Entity\Project;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\ProjectRepository;


final class ProjectController extends AbstractController
{
    #[Route('/project', name: 'app_project')]
    public function index(ProjectRepository $projectRepository): Response
    {
        return $this->render('project/index.html.twig', [
            'projects' => $projectRepository->findBy([], ['id' => 'DESC']),
        ]);
    }

    #[Route('/project/show/{id}', name: 'app_project_show')]
    public function show(Project $project): Response
    {
        return $this->render('project/show.html.twig', [
            'project' => $project,
        ]);
    }
}
