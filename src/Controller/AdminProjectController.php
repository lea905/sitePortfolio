<?php

namespace App\Controller;

use App\Entity\Project;
use App\Form\ProjectType;
use App\Repository\ProjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/admin/project')]
#[IsGranted('ROLE_USER')]
final class AdminProjectController extends AbstractController
{
    #[Route(name: 'app_admin_project_index', methods: ['GET'])]
    public function index(ProjectRepository $projectRepository): Response
    {
        return $this->render('admin_project/index.html.twig', [
            'projects' => $projectRepository->findAll(),
        ]);
    }

    private function handleProjectUploads(Project $project, \App\Service\CloudinaryService $cloudinaryService): void
    {
        // On vérifie s'il y a une image principale
        $mainImageFile = $project->getImageFile();
        if ($mainImageFile) {
            $uploadedUrl = $cloudinaryService->uploadImage($mainImageFile);
            $project->setImage($uploadedUrl);
        }
        
        // On gère les images de la galerie
        foreach ($project->getImages() as $projectImage) {
            $file = $projectImage->getFile();
            if ($file) {
                $uploadedUrl = $cloudinaryService->uploadImage($file);
                $projectImage->setImageName($uploadedUrl);
            } elseif (!$projectImage->getId() && !$projectImage->getImageName()) {
                $project->removeImage($projectImage);
            }
        }
    }

    #[Route('/new', name: 'app_admin_project_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, \App\Service\CloudinaryService $cloudinaryService): Response
    {
        $project = new Project();
        $form = $this->createForm(ProjectType::class, $project);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->handleProjectUploads($project, $cloudinaryService);
            $entityManager->persist($project);
            $entityManager->flush();

            return $this->redirectToRoute('app_admin_project_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin_project/new.html.twig', [
            'project' => $project,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_admin_project_show', methods: ['GET'])]
    public function show(Project $project): Response
    {
        return $this->render('admin_project/show.html.twig', [
            'project' => $project,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_admin_project_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Project $project, EntityManagerInterface $entityManager, \App\Service\CloudinaryService $cloudinaryService): Response
    {
        $form = $this->createForm(ProjectType::class, $project);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->handleProjectUploads($project, $cloudinaryService);
            $entityManager->flush();

            return $this->redirectToRoute('app_admin_project_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin_project/edit.html.twig', [
            'project' => $project,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_admin_project_delete', methods: ['POST'])]
    public function delete(Request $request, Project $project, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $project->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($project);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_admin_project_index', [], Response::HTTP_SEE_OTHER);
    }
}
