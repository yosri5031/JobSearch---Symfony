<?php // src/Controller/inscriptionController.php
namespace App\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class InscriptionController extends AbstractController
{
    // name utiliser dans href or redurect
 /**
 *@Route("/Accueil", name="Acceuil")
 */
 public function number()
 {
 $number = random_int(0, 100);
 return $this->render('Inscription/accueil.html.twig', [
    'number' => $number,
 ]);
 }
}


?>