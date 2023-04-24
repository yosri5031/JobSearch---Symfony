<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Entreprise;


class EntrepriseController extends AbstractController
{
    /**
     * @Route("/add", name="ajouter")
     */
    public function ajouter(): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        
        $entreprise = new Entreprise();
        $entreprise->setTitre('titre1');
        $entreprise->setEmail('test@gmail.com');
        $entreprise->setSpecialite('developement');
        $entreprise->setCreditAt(new \DateTimeImmutable());

        $entityManager->persist($entreprise);
        $entityManager->flush();

        return $this->render('entreprise/index.html.twig', [
            'id' => $entreprise->getId(),
        ]);
    }
 
/** 
 *  @Route("/edit/{id}", name="entreprise_show")
 */

public function show($id) 
{
$entreprise = $this->getDoctrine()
->getRepository(Entreprise::class)
->find($id);

return $this->render('entreprise/show.html.twig', [
'entreprise' =>$entreprise
]);
}}