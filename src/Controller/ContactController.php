<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Entity\Destinataire;
use App\Form\ContactMessageType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use \Symfony\Component\Translation\Formatter\MessageFormatter;
use Symfony\Component\Messenger\Bridge\Doctrine\Transport\DoctrineTransport;



class ContactController extends AbstractController
{
    private EntityManagerInterface $entityManager;





    public function __construct(EntityManagerInterface $entityManager,MailerInterface $mailer)
    {
        $this->entityManager=$entityManager;




    }


    #[Route('/contact', name: 'app_contact')]
    public function index(MailerInterface $mailer,SessionInterface $session,TranslatorInterface $translator): Response
    {
        $request = Request::createFromGlobals();
        $infocontact = new Contact();
        $destinataire=$this->entityManager->getRepository(Destinataire::class)->findBy(['fonctions' => 'RH']);
        $form=$this->createForm(ContactMessageType::class);

        $form->handleRequest($request);

        if($form->isSubmitted()&& $form->isValid()) {

            $infocontact=$form->getData();
            $infocontact->setDateDenvoi(new \DateTime());
            $creatAt=$infocontact->getDateDenvoi()->format( 'Y-m-d H:i:s' );

            $infocontact->setIpDeClient($request->getClientIp());
            $ip=$infocontact->getIpDeClient();
            $civilite=$infocontact->getCivilite();
            $nom=$infocontact->getNom();
            $prenom=$infocontact->getPrenom();
            $email=$infocontact->getEmail();
            if($dateDeNaissance=$infocontact->getDateDeNaissance()){
                $dateDeNaissance=$infocontact->getDateDeNaissance()->format( 'Y-m-d' );
            }else{
                $dateDeNaissance='';
            }


            $telephone=$infocontact->getTelephone();
            $address=$infocontact->getAdresse();
            $codePostal=$infocontact->getCodePostal();
            $ville=$infocontact->getVille();
            $pays=$infocontact->getPays();
            $societe=$infocontact->getSociete();
            $destinataire=$infocontact->getDestinataire();
            $message=$infocontact->getmessage();
            //$recevoireNewsletter=$infocontact->isRecevoireNewsletter();

            $message_contact = (new Email())
                ->from($email)
                ->to('admin@admin.com')
                ->subject("Formulaire de contact > $nom")
                ->html(body: "Message :<br>
«  Un visiteur du site nom_site, vous a envoyé le message suivant via le formulaire de contact :<br>
<br><br><br>


Civilité    : $civilite<br>
Prénom      : $prenom<br>
Nom         : $nom<br>
Email       : $email<br>
Date de naissance : $dateDeNaissance<br>
Message     : $message<br>
Date        : $creatAt<br>
Adresse IP  : $ip »


                 ")
            ;



                $mailer?->send($message_contact);




            $this->entityManager->persist($infocontact);
            $this->entityManager->flush();
            $session->getFlashBag()->add('success', 'Nous vous remercions pour votre intérêt, votre message a été envoyé.');

            return $this->redirectToRoute(route: 'app_contact');


        }


        return $this->render('contact/index.html.twig', [
            'form' =>$form->createView(),
            'destinataires'=>$destinataire
        ]);
    }

}
