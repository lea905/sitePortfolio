<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ParcoursController extends AbstractController
{
    #[Route('/parcours', name: 'app_parcours')]
    public function index(): Response
    {
        $experiences = [
            [
                'title' => 'Développeuse web',
                'company' => 'E-Conception',
                'date' => '2025- Present',
                'description' => 'Develppement Wordpress, SEO',
                'type' => 'Alternance'
            ],
            [
                'title' => 'Développeuse web',
                'company' => 'E-Conception',
                'date' => '2025',
                'description' => "Développement projet plaine de l'ain",
                'type' => 'Stage'
            ],
            [
                'title' => 'Agent de maintenance',
                'company' => 'Camping La Plaine Tonique',
                'date' => '2024',
                'description' => 'Nettoyer les sanitaires du camping',
                'type' => '1 mois'
            ],
            [
                'title' => 'Puéricultrice',
                'company' => 'Crèches des Minis',
                'date' => '2019',
                'description' => "J'ai aidé les puéricultrices dans leur travail pendant mon stage",
                'type' => 'Stage'
            ]
        ];

        return $this->render('parcours/index.html.twig', [
            'experiences' => $experiences,
        ]);
    }
}
