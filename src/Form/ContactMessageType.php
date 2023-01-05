<?php

namespace App\Form;

use App\Entity\Contact;
use App\Entity\Destinataire;
use App\Entity\Pays;
use Doctrine\ORM\EntityManagerInterface;
use Gregwar\CaptchaBundle\Type\CaptchaType;
use phpDocumentor\Reflection\DocBlock\Description;
use phpDocumentor\Reflection\PseudoTypes\List_;
use phpDocumentor\Reflection\Types\Array_;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;



class ContactMessageType extends AbstractType
{    private EntityManagerInterface $entityManager;
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager=$entityManager;
    }
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        $pays=$this->entityManager->getRepository(Pays::class)->findAll();
        $destinataire=$this->entityManager->getRepository(Destinataire::class)->findAll();




        $builder
            ->add('civilite',ChoiceType::class, options: [

                'label' => 'Civilité',

                'required' => false,
                'choices' => ['Monsieur'=>'Monsieur','Madame' => 'Madame', 'Mademoiselle' => 'Mademoiselle'],
                'placeholder' => 'Choisir',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Merci d’indiquer votre Civilite_champ',
                    ]),
                ],


            ])
            ->add('prenom',TextType::class, options: [

                'label' => 'Prénom',
                'required' => false,
                'constraints' => [
                    new Length([
                        'max' => 30,
                        'maxMessage' => 'le nomber de caracter ne doit pas depassé {{ limit }} characters',
                    ]),
                    new NotBlank([
                        'message' => 'Merci d’indiquer votre prénom_champ',
                    ]),
                ],
                ])
            ->add('nom',TextType::class, options: [

                'label' => 'Nom',
                'required' => false,
                'constraints' => [
                    new Length([
                        'max' => 30,
                        'maxMessage' => 'le nomber de caracter ne doit pas depassé {{ limit }} characters',
                    ]),
                    new NotBlank([
                        'message' => 'Merci d’indiquer votre nom_champ',
                    ]),
                ],
            ])
            ->add('email',EmailType::class,[
                'label' => 'Email',
                'required' => false,
                'constraints' => [
                    new Length([
                        'max' => 150,
                        'maxMessage' => 'le nomber de caracter ne doit pas depassé {{ limit }} characters',
                    ]),
                    new NotBlank([
                        'message' => 'Merci d’indiquer votre Email_champ',
                    ]),
                ],


            ])
            ->add('dateDeNaissance',DateType::class, options: [
                'label' => 'date de naissance',
                'required' => false,
                'widget' => 'single_text',
                'html5' => false,
                'format' => 'dd/MM/yyyy',
                'invalid_message' => 'Merci d’indiquer une date de naissance valide, exemple 10/10/1970',

            ])
            ->add('telephone',TelType::class, options: [

                'label' => 'telephone',
                'required' => false,
                'constraints' => [
                    new Length([
                        'max' => 10,
                        'maxMessage' => 'le nomber de caracter ne doit pas depassé {{ limit }} characters',
                    ]),
                ],
            ])
            ->add('adresse',TextType::class, options: [

                'label' => 'adresse',
                'required' => false,
                'constraints' => [
                    new Length([
                        'max' => 150,
                        'maxMessage' => 'The name must not exceed {{ limit }} characters',
                    ]),
                ],
            ])
            ->add('codePostal',TextType::class, options: [

                'label' => 'codePostal',
                'required' => false,
                'constraints' => [
                    new Length([
                        'max' => 150,
                        'maxMessage' => 'The name must not exceed {{ limit }} characters',
                    ]),
                ],
            ])
            ->add('ville',TextType::class, options: [

                'label' => 'ville',
                'required' => false,
                'constraints' => [
                    new Length([
                        'max' => 50,
                        'maxMessage' => 'The name must not exceed {{ limit }} characters',
                    ]),
                ],
            ])
            ->add('pays',EntityType::class, options: [
                'class' => Pays::class,
                'required' => false,
                'choice_label' => fn(Pays $pays) => sprintf('%s', $pays->getPays()),
                'placeholder' => 'Choisir',
            ])

            ->add('societe',TextType::class , options: [

                'label' => 'societe',
                'required' => false,
                'constraints' => [
                    new Length([
                        'max' => 150,
                        'maxMessage' => 'The name must not exceed {{ limit }} characters',
                    ]),
                ],
            ])
            ->add('destinataire',EntityType::class,options: [
                'class' => Destinataire::class,
                'required' => false,

                'choice_label' => fn(Destinataire $destinataire) => sprintf('%s', $destinataire->getId().'/'.$destinataire->getFonctions()),
                'placeholder' => 'Choisir',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Merci d’indiquer votre destinataire_champ',
                    ]),
                ],


            ])
            ->add('cv', FileType::class,options: [
                'label'=>false,
                'required' => false,
                'mapped' => false,
                'attr'=>[
                    'hidden'=> true,
                ]


            ])


            ->add('message', TextareaType::class, options: [

                'label' => 'message',
                'required' => false,
                'constraints' => [
                        new Length([
                        'max' => 500,
                        'maxMessage' => 'The name must not exceed {{ limit }} characters',

                        ]),
                    new NotBlank([
                        'message' => 'Merci d’indiquer votre Message_champ',

                    ]),
                ],
            ])
            ->add('recevoireNewsletter',CheckboxType::class, options: [
                'required' => false,
                'label' => 'recevoire des newsletter',
            ])
            #->add('captcha', CaptchaType::class)
            ->add('submit',SubmitType::class, options: [
                'label'=>'envoyer',
                'attr'=>[
                    'class'=>'btn-block btn-info'
                ]
            ])
            ->getForm()
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Contact::class,
            'attr' => [
                'theme' => 'fields.html.twig',
            ],

        ]);
    }

}
