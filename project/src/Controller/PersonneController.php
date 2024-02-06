<?php

namespace App\Controller;

use ApiPlatform\Metadata\Post;
use App\Entity\Emploi;
use App\Entity\PersonneEmploi;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Personne;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use App\Repository\PersonneEmploiRepository;


class PersonneController extends AbstractController
{
    #[Route('/api/personne/{id}', name: 'get_personne_by_id', methods: ['Get'])]
    public function getPersonne(EntityManagerInterface $entityManager, SerializerInterface $serializer, $id): JsonResponse
    {
        $personne = $entityManager->getRepository(Personne::class)->find($id);

        if (!$personne) {
            return $this->json(['message' => 'Personne not found'], Response::HTTP_NOT_FOUND);
        }

        $data = $serializer->serialize($personne, 'json');

        return JsonResponse::fromJsonString($data);
    }

    #[Route('/api/personne', name: 'add_personne', methods: ['POST'])]
    public function addPersonne(Request $request, SerializerInterface $serializer, ValidatorInterface $validator, EntityManagerInterface $entityManager): Response
    {
        $personne = $serializer->deserialize($request->getContent(), Personne::class, 'json');

        // Vérification de l'âge
        $today = new DateTime();
        $age = $today->diff($personne->getDateNaissance())->y;

        if ($age > 150) {
            return $this->json(['message' => 'L\'âge de la personne ne peut pas dépasser 150 ans'], Response::HTTP_BAD_REQUEST);
        }

        $errors = $validator->validate($personne);
        if (count($errors) > 0) {
            return $this->json($errors, Response::HTTP_BAD_REQUEST);
        }

        $entityManager->persist($personne);
        $entityManager->flush();

        return $this->json($personne, Response::HTTP_CREATED);
    }

    #[Route('/api/personnes', name: 'personnes_filter_nom', methods: ['Get'])]
    public function getPersonnesOrdered(EntityManagerInterface $entityManager, $id = null): JsonResponse
    {
        $personnes = $entityManager->getRepository(Personne::class)->findBy([], ['nom' => 'ASC']);
        $data = [];

        foreach ($personnes as $personne) {
            $age = $personne->getDateNaissance() ? $personne->getDateNaissance()->diff(new \DateTime())->y : null;

            $emploisActuels = $personne->getEmplois()->filter(function($personneEmploi) {
                $now = new \DateTime();
                return $personneEmploi->getDateDebut() <= $now &&
                    ($personneEmploi->getDateFin() === null || $personneEmploi->getDateFin() >= $now);
            });

            $emploisData = [];
            foreach ($emploisActuels as $emploiActuel) {
                $emploiData = [
                    'nomEntreprise' => $emploiActuel->getEmploi()->getNomEntreprise(),
                    'posteOccupe' => $emploiActuel->getPosteOccupe(),
                    'dateDebut' => $emploiActuel->getDateDebut()->format('Y-m-d'),
                    'dateFin' => $emploiActuel->getDateFin() ? $emploiActuel->getDateFin()->format('Y-m-d') : null,
                ];
                $emploisData[] = $emploiData;
            }

            $data[] = [
                'nom' => $personne->getNom(),
                'prenom' => $personne->getPrenom(),
                'age' => $age,
                'emplois' => $emploisData,
            ];
        }

        return new JsonResponse($data);
    }

    #[Route('/api/personne_emplois', name: 'get_personnes_by_entreprise', methods: ['GET', 'PUT'])]
    public function getPersonnesByEntreprise(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {

        $nomEntreprise = $request->query->get('nomEntreprise', null);
        if (!$nomEntreprise) {
            return $this->json(['message' => 'Le nom de l\'entreprise est requis'], Response::HTTP_BAD_REQUEST);
        }

        $personneEmploiRepo = $entityManager->getRepository(PersonneEmploi::class);
        $personneEmplois = $personneEmploiRepo->findByNomEntreprise($nomEntreprise);

        $personnesData = [];
        foreach ($personneEmplois as $personneEmploi) {
            $personne = $personneEmploi->getPersonne();
            $personnesData[] = [
                'id' => $personne->getId(),
                'nom' => $personne->getNom(),
                'prenom' => $personne->getPrenom(),
                'dateNaissance' => $personne->getDateNaissance() ? $personne->getDateNaissance()->format('Y-m-d') : null
            ];
        }

        return new JsonResponse($personnesData);
    }

    #[Route('/api/personne_emplois/by_personne/{id}/dates', name: 'get_emplois_by_personne_dates', methods: ['GET'])]
    public function getEmploisByPersonneAndDates(Request $request, EntityManagerInterface $entityManager, int $id): JsonResponse
    {
        $dateDebut = new \DateTime($request->query->get('dateDebut'));
        $dateFin = new \DateTime($request->query->get('dateFin'));

        $personneEmploiRepo = $entityManager->getRepository(PersonneEmploi::class);
        $emplois = $personneEmploiRepo->findEmploisByPersonneAndDateRange($id, $dateDebut, $dateFin);

        $emploisData = [];
        foreach ($emplois as $emploi) {
            $emploisData[] = [
                'idEmploi' => $emploi->getEmploi()->getId(),
                'nomEntreprise' => $emploi->getEmploi()->getNomEntreprise(),
                'posteOccupe' => $emploi->getPosteOccupe(),
                'dateDebut' => $emploi->getDateDebut()->format('Y-m-d'),
                'dateFin' => $emploi->getDateFin() ? $emploi->getDateFin()->format('Y-m-d') : null,
            ];
        }

        return new JsonResponse($emploisData);
    }
}
