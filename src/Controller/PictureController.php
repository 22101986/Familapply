<?php

namespace App\Controller;

use App\Entity\Person;
use App\Entity\Picture;
use App\Entity\Categories;
use App\Controller\PictureController;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PictureController extends AbstractController
{

    #[Route('/picture/listing/{id}', name: 'picture_listing')]
    public function listing(ManagerRegistry $doctrine, int $id = null): Response
    {  
        if($id == null) {
            $pictures = $doctrine->getRepository(Picture::class)->findAll();

        }else {
            $person = $doctrine->getRepository(Person::class)->find($id);
            $pictures = $person->getPictures();
        }
        
         $persons = $doctrine->getRepository(Person::class)->findAll();

        // return dd($pictures, $persons);
        return $this->render('picture/listing.html.twig', [
            'picture' => $pictures,
            'persons' => $persons
        ]);
    }

    #[Route('/picture/delete/{id}', name: 'picture_delete')]
    public function remove(ManagerRegistry $doctrine, int $id): Response
    {
        $entityManager = $doctrine->getManager();

        $picture = $entityManager->getRepository(Picture::class)->find($id);
        
        $entityManager->remove($picture);
        $entityManager->flush();

        $this->addFlash('danger', 'Photo effacée avec succes');

        return $this->redirectToRoute('picture_listing');
    }


    #[Route('/picture/add', name: 'picture_add')]
    public function create(Request $request, ManagerRegistry $doctrine, SluggerInterface $slugger): Response
    {
        
        $entityManager = $doctrine->getManager();
        
        $picture = new Picture();
        $form = $this->createFormBuilder($picture)
        
         ->add('photo', FileType::class, [
            'label' => 'Votre photo :',
            'required' => true,
            'constraints' => [
                new File([
                    'maxSize' => '10512k',
                    'mimeTypes' => [
                        'image/jpg',
                        'image/jpeg',
                        'image/svg',
                        'image/png',
                    ],
                    'mimeTypesMessage' => 'Merci de choisir une photo valide ".jpg ,.jpeg ,.svg ,.png" et inférieur à 10512Ko.',
                ])
            ],
        ])
        ->add('name', TextType::class, [
            'label' => 'Nom de la photo :',
            'attr' => [
                'class' => 'form-control mb-4'
            ]
         ])
        ->add('person_id', EntityType::class, [
            'label' => 'nom',
            'class' => Person::class,
            'choice_label' => 'nom',
            'multiple' => true,
            'expanded' => false,
            'attr' => [
                'class' => 'form-control mb-4'
            ]
        ])

        ->add('save', SubmitType::class, [
            'label' => 'ajouter la photo',
            'attr' => [
                'class' => 'btn btn-primary'
                ]])
        ->getForm();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            
            $picture = $form->getData();
            

            $pictureFile = $form->get('photo')->getData();
            if ($pictureFile) {
                $originalFilename = pathinfo($pictureFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $pictureFile->guessExtension();
                $pictureFile->move(
                    $this->getParameter('uploads_directory'),
                    $newFilename
                    
                );
                $picture->setPhoto($newFilename);
            }
            $entityManager->persist($picture);
            $entityManager->flush();
            $this->addFlash('success', 'Photo ajoutée avec succes');

            return $this->redirectToRoute('picture_listing');
        }

        return $this->renderForm('picture/add_pict.html.twig', [
        'form' => $form,
    ]);
    }
}

