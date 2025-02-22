<?php

namespace App\Controller;

use App\Entity\Task;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CalendarController extends AbstractController
{
   
    #[Route('/calendar', name: 'calendar')]
    public function listing(ManagerRegistry $doctrine): Response
    {  
         $tasks =$doctrine->getRepository(Task::class)->findAll();

        return $this->render('calendar/index.html.twig', [
            'tasks' => $tasks,
        ]);
    }

}
