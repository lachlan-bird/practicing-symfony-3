<?php

namespace App\Controller;

use App\Entity\Genus;
use App\Entity\GenusNote;
use App\Entity\GenusScientist;
use App\Entity\SubFamily;
use App\Entity\User;
use App\Repository\GenusNoteRepository;
use App\Repository\GenusRepository;
use App\Repository\GenusScientistRepository;
use App\Repository\SubFamilyRepository;
use App\Repository\UserRepository;
use App\Service\MarkdownTransformer;
use Psr\Log\LoggerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class GenusController extends Controller
{
    /**
     * @Route("/genus/new")
     */
    public function newAction(SubFamilyRepository $subFamilyRepository, UserRepository $userRepository)
    {
        $em = $this->getDoctrine()->getManager();

        $subFamily = $subFamilyRepository
            ->findOneBy([]);

        $genus = new Genus();
        $genus->setName('Octopus'.rand(1, 100));
        $genus->setSubFamily($subFamily);
        $genus->setFirstDiscoveredAt(new \DateTime());
        $genus->setSpeciesCount(rand(100, 99999));

        $genusNote = new GenusNote();
        $genusNote->setUsername('AquaWeaver');
        $genusNote->setUserAvatarFilename('ryan.jpeg');
        $genusNote->setNote('I counted 8 legs... as they wrapped around me');
        $genusNote->setCreatedAt(new \DateTime('-1 month'));
        $genusNote->setGenus($genus);

        $user = $userRepository
            ->findOneBy(['email' => 'aquanaut1@example.org']);

        $genusScientist = new GenusScientist();
        $genusScientist->setGenus($genus);
        $genusScientist->setUser($user);
        $genusScientist->setYearsStudied(10);

        $em = $this->getDoctrine()->getManager();
        $em->persist($genus);
        $em->persist($genusNote);
        $em->persist($genusScientist);
        $em->flush();

        return new Response('<html><body>Genus created!</body></html>');
    }

    /**
     * @Route("/genus")
     */
    public function listAction(GenusRepository $repository)
    {
        $genuses = $repository->findAllPublishedOrderedByRecentlyActive();

        return $this->render('genus/list.html.twig', [
            'genuses' => $genuses
        ]);
    }

    /**
     * @Route("/genus/{slug}", name="genus_show")
     */
    public function showAction(Genus $genus,
                               MarkdownTransformer $markdownTransformer,
                               LoggerInterface $logger,
                               GenusNoteRepository $genusNoteRepository
    )
    {
        $funFact = $markdownTransformer->parse($genus->getFunFact());

        $logger->info('Showing genus: '.$genus->getName());

        $recentNotes = $genusNoteRepository->findAllRecentNotesForGenus($genus);

        return $this->render('genus/show.html.twig', array(
            'genus' => $genus,
            'funFact' => $funFact,
            'recentNoteCount' => count($recentNotes)
        ));
    }

    /**
     * @Route("/genus/{slug}/notes", name="genus_show_notes")
     * @Method("GET")
     */
    public function getNotesAction(Genus $genus)
    {
        $notes = [];

        foreach ($genus->getNotes() as $note) {
            $notes[] = [
                'id' => $note->getId(),
                'username' => $note->getUsername(),
                'avatarUri' => '/images/'.$note->getUserAvatarFilename(),
                'note' => $note->getNote(),
                'date' => $note->getCreatedAt()->format('M d, Y')
            ];
        }

        $data = [
            'notes' => $notes
        ];

        return new JsonResponse($data);
    }

    /**
     * @Route("/genus/{genusId}/scientist/{userId}", name="genus_scientist_remove")
     * @Method("DELETE")
     */
    public function removeGenusScientistAction($genusId, $userId, GenusScientistRepository $genusScientistRepository)
    {
        $em = $this->getDoctrine()->getManager();

        /** @var Genus $genus */
        $genusScientist = $genusScientistRepository
            ->findOneBy([
                'user' => $userId,
                'genus' => $genusId
            ]);

        if(!$genusScientist)
            throw $this->createNotFoundException('Genus Scientist not found');


        $em->remove($genusScientist);
        $em->flush();

        return new Response(null, 204);

    }
}
