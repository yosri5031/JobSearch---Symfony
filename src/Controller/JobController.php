<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Job;


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
        $job->setDescriptioj('Data Analyse');
        $job->setExpiresAt(new \DateTimeImmutable());
        $job->setEmail('salem.yosri2@gmail.com');
        $entityManager->persist($job);
        $entityManager->flush();

        return $this->render('job/index.html.twig', [
            'id' =>$job->getId(),
        ]);
    }
    /**
* @Route("/job/{id}", name="job_show")
*/
public function show($id)
{
 $job = $this->getDoctrine()
 ->getRepository(Job::class)
 ->find($id);
 if (!$job) {
 throw $this->createNotFoundException(
 'No job found for id '.$id
 );
 }
 return $this->render('job/show.html.twig', [
 'job' =>$job
 ]);
}
}
