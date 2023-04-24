<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Image;
use App\Entity\Job;
use App\Entity\Candidature;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\HttpFoundation\Request;
use App\Form\JobType;
use Symfony\Component\HttpFoundation\Session\Session;

class JobController extends AbstractController
{
    /**
     * @Route("/job", name="app_job")
     */
    public function index(): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $job = new Job();
        $job->settype('Datascientist');
        $job->setCompany('Esprims');
        $job->setDescription('Data Analyse');
        $job->setExpiresAt(new \DateTimeImmutable());
        $job->setEmail('salem.yosri2@gmail.com');
        $image = new Image();
        $image->setUrl('https://cdn.pixabay.com/photo/2015/10/30/10/03/gold-1013618_960_720.jpg');
        $image->setAlt('job de reve ');
        $job->setImage($image);
        
        // ajout des conidats
        $candidature1=new Candidature();
        $candidature1->setCandidat("ben salem");
        $candidature1->setContenu("formation INFO");
        $candidature1->setDatec(new \Datetime());
        $candidature2=new Candidature();
        $candidature2->setCandidat("saleh");
        $candidature2->setContenu("formation Symfony");
        $candidature2->setDatec(new \Datetime());
        $candidature1->setJob($job);
        $candidature2->setJob($job);
        $entityManager->persist($job);
        $entityManager->persist($candidature1);
        $entityManager->persist($candidature2);
        $entityManager->flush();
        return $this->render('job/index.html.twig',[
            'id' => $job->getId()
        ]);

        
    }
    /**
* @Route("/job/{id}", name="job_show")
*/
public function show($id,Request $request)
{
    /* le doctrine est le modèle et le controlleur est un intermidiaire */

 $job = $this->getDoctrine()
 ->getRepository(Job::class)
 ->find($id);
 
 $em =$this->getDoctrine()->getManager();
 $listCandidatures=$em->getRepository(Candidature::class)
 ->findby(['Job'=>$job]);
 $publicPath = $request->getScheme().'://'.$request->getHttpHost().$request->getBasePath().'/uploads/jobs/';
 /* select * from job where id =... */
 if (!$job) {
 throw $this->createNotFoundException(
 'No job found for id '.$id
 );
 }

 return $this->render('job/show.html.twig', [
    'publicPath' =>$publicPath,
    'listCandidatures' =>  $listCandidatures,
 'job' =>$job
 ]);
}

/**
 * @Route ("/",name="home")
 */
public function home(Request $request){
    $form =$this->createFormBuilder()
    ->add("criteria",TextType::class)
    ->add('Valider',SubmitType::class)
    ->getForm();

    $form->handleRequest($request);
    $em = $this->getDoctrine()->getManager();
    $repo = $em->getRepository(Candidature::class);
    $lesCandidats = $repo->findAll();

    //lancer la recherche quand on clique sur le bouton
    if ($form->isSubmitted())
    {
        $data = $form->getData();
        $lesCandidats = $repo->recherche($data['criteria']);

    }
    return $this->render('job/home.html.twig',
    ['lesCandidats' => $lesCandidats , 'form'=>$form->createView()]);
    
    
}

/**
 * @Route ("/Ajouter,name="ajouter")
 */
 public function Ajouter( Request $request){
    $candidat = new Candidature();
    $fb = $this-> createFormBuilder($candidat)
    ->add('candidat',TextType::class)
    ->add('contenu',TextType::class,array("label"=>"Contenu"))
    
    
    ->add('datec',DateType::class)
    // entitytype = select remplie a partir d'une entité
    ->add('job',EntityType::class,[
        'class'=>Job::class,
        'choice_label'=>'type'
    ])
    ->add('Valider',SubmitType::class);

$form = $fb->getForm();
$form->handleRequest($request);
if ($form->isSubmitted()) {
$em = $this->getDoctrine()->getManager();
$em->persist($candidat);
$em->flush();
// redirection vers la page d'accueil ('name')
return $this->redirectToRoute('Acceuil');
}
    return $this->render('job/ajouter.html.twig',
    ['f' => $form->createView() ]);
    }
/**
 * @Route ("/add",name="ajout_job")
 */
 public function ajouter2(Request $request)
 {
    $publicPath= "uploads/jobs/";
    $job = new Job();
    $form = $this->createForm("App\Form\JobType", $job);
    $form->handleRequest($request);
    if ($form->isSubmitted())
    {
        $image = $form->get('image')->getData();
        if($image){
            $imageName = $job->getDescription().'.'.$image->guessExtension();
            $image->move($publicPath,$imageName);
            $job->setImage($imageName);
        }
        $em = $this->getDoctrine()->getManager();
        $em->persist($job);
        $em->flush();
        return $this->redirectToRoute('Acceuil');
    }
    return $this->render('job/ajouter.html.twig',
    ['f' => $form->createView() ]);
 }
/**
 * @Route("/supp/{id}",name="cand_delete")
 */
public function delete(Request $request, $id) : Response
{
    
    $c = $this->getDoctrine()
    ->getRepository(Candidature::class)
    ->find($id);
    if (!$c) {
        throw $this->createNotFoundException(
        'No candidat found for id '.$id
        );
        }
        $em = $this->getDoctrine()->getManager();
        $em->remove($c);
        $em->flush();
        return $this->redirectToRoute('Acceuil');
}
/**
* @Route("/editU/{id}", name="edit_user")
* Method({"GET","POST"})
*/
public function edit(Request $request, $id)
{ $candidat = new Candidature();
$candidat = $this->getDoctrine()
->getRepository(Candidature::class)
->find($id);
if (!$candidat) {
throw $this->createNotFoundException(
'No candidat found for id '.$id
);
}
$fb = $this->createFormBuilder($candidat)
->add('candidat', TextType::class)
->add('contenu', TextType::class, array("label" => "Contenu"))
->add('datec', DateType::class)
->add('job', EntityType::class, [
'class' => Job::class,
'choice_label' => 'type',
])
->add('Valider', SubmitType::class);
// générer le formulaire à partir du FormBuilder
$form = $fb->getForm();
$form->handleRequest($request);
if ($form->isSubmitted()) {
$entityManager = $this->getDoctrine()->getManager();
$entityManager->flush();
return $this->redirectToRoute('home');
}
return $this->render('job/ajouter.html.twig',
['f' => $form->createView()] );
}

/** 
 * @Route("/listejob", name="affiche_job")
 */
public function afficheJob(Request $request) {
    $form =$this->createFormBuilder()
    ->add("criteria",TextType::class)
    ->add('Valider',SubmitType::class)
    ->getForm();
    $em = $this->getDoctrine()->getManager();
    $repo = $em->getRepository(Job::class);
   
   $form->handleRequest($request);
    $lesJobs = $repo->findAll();
    //lancer la recherche quand on clique sur le bouton
    if ($form->isSubmitted())
    {
        $data = $form->getData();
        $lesJobs = $repo->recherche($data['criteria']);

    }
    return $this->render('job/listejob.html.twig',
    ['lesJobs' => $lesJobs,'form'=>$form->createView()]);
}
/**
 * @Route ("/Ajouter_Job",name="ajouter_job")
 */
public function AjoutJ( Request $request){
    $newjob= new Job();
    $fb = $this-> createFormBuilder($newjob)
    ->add('Type',TextType::class)
    ->add('Company',TextType::class)
    ->add('Description',TextType::class,array("label"=>"Description"))
    ->add('Valider',SubmitType::class);

$form = $fb->getForm();
$form->handleRequest($request);
if ($form->isSubmitted()) {
$em = $this->getDoctrine()->getManager();
$em->persist($newjob);
$em->flush();
// redirection vers la page d'accueil ('name')
return $this->redirectToRoute('Acceuil');
}
    return $this->render('job/ajouter.html.twig',
    ['f' => $form->createView() ]);
    }
 /**
 * @Route("/suppj/{id}",name="job_delete")
 */
public function deletej(Request $request, $id) : Response
{
    
    $c = $this->getDoctrine()
    ->getRepository(Job::class)
    ->find($id);
    if (!$c) {
        throw $this->createNotFoundException(
        'No job found for id '.$id
        );
        }
        $em = $this->getDoctrine()->getManager();
        $em->remove($c);
        $em->flush();
        return $this->redirectToRoute('Acceuil');
}
/**
* @Route("/editJ/{id}", name="edit_job")
* Method({"GET","POST"})
*/
public function editj(Request $request, $id)
{ $job = new Job();
$job = $this->getDoctrine()
->getRepository(Job::class)
->find($id);
if (!$job) {
throw $this->createNotFoundException(
'No Job found for id '.$id
);
}
$fb = $this->createFormBuilder($job)
->add('Type',TextType::class)
->add('Company',TextType::class)
->add('Description',TextType::class,array("label"=>"Description"))
->add('Valider',SubmitType::class);
// générer le formulaire à partir du FormBuilder
$form = $fb->getForm();
$form->handleRequest($request);
if ($form->isSubmitted()) {
$entityManager = $this->getDoctrine()->getManager();
$entityManager->flush();
return $this->redirectToRoute('affiche_job');
}
return $this->render('job/ajouter.html.twig',
['f' => $form->createView()] );
}

}