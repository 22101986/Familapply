<?php

namespace App\Controller;

use DateTime;
use App\Entity\Task;
use App\Entity\Categories;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class TachesController extends AbstractController
{
    #[Route('/taches/listing', name: 'taches_listing')]
    public function listing(ManagerRegistry $doctrine): Response
    {  
         $tasks =$doctrine->getRepository(Task::class)->findAll();

        return $this->render('taches/listing.html.twig', [
            'tasks' => $tasks,
        ]);
    }

    
    #[Route('/taches/calendar', name: 'taches_calendar')]
    public function calendar(ManagerRegistry $doctrine): Response
    {  
        $tasks =$doctrine->getRepository(Task::class)->findAll();
        $array = [];
        $id = 0;
        foreach ($tasks as $task) {
           
           if($task->getPriorityTask() == "haute") {
            $taskEvent = [
                "id"=>$id,
                "title"=>$task->getDescriptionTask(),
                "start"=>date_format($task->getDueDateTask(),'Y-m-d'),
                "end"=>date_format($task->getDueDateTask(),'Y-m-d'),
                "backgroundColor"=>"#A81605",
                "allDay"=>true
            ];
        }
            elseif($task->getPriorityTask() == "normale") {
                $taskEvent = [
                    "id"=>$id,
                    "title"=>$task->getDescriptionTask(),
                    "start"=>date_format($task->getDueDateTask(),'Y-m-d'),
                    "end"=>date_format($task->getDueDateTask(),'Y-m-d'),
                    "backgroundColor"=>"#BB9403",
                    "allDay"=>true
                ];

            }
            elseif($task->getPriorityTask() == "basse") {
                $taskEvent = [
                    "id"=>$id,
                    "title"=>$task->getDescriptionTask(),
                    "start"=>date_format($task->getDueDateTask(),'Y-m-d'),
                    "end"=>date_format($task->getDueDateTask(),'Y-m-d'),
                    "backgroundColor"=>"#1E7D02",
                    "allDay"=>true
                ];


            }

            $id++;
            array_push($array, $taskEvent );

        }

        $datas = json_encode($array);
        
        return $this->render('taches/calendar.html.twig', compact('datas'));
    }


    #[Route('/tache/delete/{id}', name: 'tache_delete')]
    public function remove(ManagerRegistry $doctrine, int $id): Response
    {
        $entityManager = $doctrine->getManager();

        $task = $entityManager->getRepository(Task::class)->find($id);

        $entityManager->remove($task);
        $entityManager->flush();

        $this->addFlash('danger', 'Tâche Supprimé avec succes');

        return $this->redirectToRoute('taches_listing');
    }

    #[Route('/tache/add', name: 'tache_add')]
    public function create(Request $request, ManagerRegistry $doctrine): Response
    {
        $entityManager = $doctrine->getManager();

        $task = new Task();
        
        $form = $this->createFormBuilder($task)
        ->add('nameTask', TextType::class, [
            'label' => 'Nom de la tâche :',
            'attr' => [
                'class' => 'form-control mb-4'
            ]
         ])
        ->add('dueDateTask', DateType::class, [
            
            'label' => 'date de la tache',
            'widget' => 'single_text',
            'attr' => [
                'class' => 'form-control mb-4'
            ]
        ])
        // ->add('endDateTask', DateTimeType::class, [
            
        //     'label' => 'date de la tache',
        //     'widget' => 'single_text',
        //     'attr' => [
        //         'class' => 'form-control mb-4'
        //     ]
        // ])
        ->add('priorityTask', ChoiceType::class, [
            'label' => 'priorité',
            'attr' => [
                'class' => 'form-control mb-4'
            ],
            'choices' => [
                'haute' => 'haute', 
                'normale' => 'normale',
                'basse' => 'basse' 
            ],
        ])
        ->add('category', EntityType::class, [
            'label' => 'catégorie',
            'class' => Categories::class,
            'choice_label' => 'libelleCategory',
            'attr' => [
                'class' => 'form-control mb-4'
            ]
        ])
        ->add('descriptionTask', TextareaType::class, [
            'label' => 'description',
            'attr' => [
                'class' => 'form-control mb-4'
            ]
        ])

        ->add('save', SubmitType::class, [
            'label' => 'Créé la tâche',
            'attr' => [
                'class' => 'btn btn-primary'
                ]])
        ->getForm();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $task = $form->getData();
            $task->setCreatedDateTask(new \DateTime('now'));
            $entityManager->persist($task);
            $entityManager->flush();
            $this->addFlash('success', 'Tâche ajoutée avec succes');

            return $this->redirectToRoute('taches_listing');
        }

        return $this->renderForm('taches/add.html.twig', [
        'form' => $form,
    ]);
    }
}