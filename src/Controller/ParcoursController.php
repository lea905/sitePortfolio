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
                'title' => 'Développeuse web (stage et alternance)',
                'company' => 'E-Conception',
                'date' => 'Avril 2025 - Février 2026',
                'description' => "Développement Fullstack pour la Plaine de l'Ain : Conception de l'architecture Back-end (Symfony, Doctrine), intégration d'interfaces responsive (Twig, Bootstrap) et gestion de projets sous l'écosystème WordPress",
                'type' => 'Alternance'
            ],
            [
                'title' => 'Agent de maintenance',
                'company' => 'Camping La Plaine Tonique',
                'date' => '2024',
                'description' => "Assurer l'entretien complet et la désinfection des blocs sanitaires (douches, WC, lavabos)",
                'type' => '1 mois'
            ],
            [
                'title' => 'Auxiliaire de puériculture',
                'company' => 'Crèches des Minis',
                'date' => '2019',
                'description' => "Accompagnement quotidien des enfants : gestion des repas, de l'hygiène et du coucher",
                'type' => 'Stage'
            ]
        ];

        return $this->render('parcours/index.html.twig', [
            'experiences' => $experiences,
        ]);
    }
}
