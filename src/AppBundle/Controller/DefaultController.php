<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

use AppBundle\Form\ClientType;
use AppBundle\Entity\Client;

use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class DefaultController extends Controller
{
    /**
     * /////////////////////////////
     * 
     * indexAction                  (homepage)
     * chargerClientAction          (charger_client)
     * 
     * /////////////////////////////
     */

    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        // DATA
            $em         = $this->getDoctrine()->getManager();
            $client     = new Client();
            $form       = $this->createForm(ClientType::class, $client);
            $form->add('valider', SubmitType::class, array('label' => 'Enregistrer', 'attr' => ['class' => 'btn btn-success']));

        // HYDRATATION DU FORMULAIRE
            $form->handleRequest($request);
        // SOUMISSION
            if($form->isSubmitted()){
                if($form->isValid()){
                    $em->persist($client);
                    $em->flush();

                    $this->addFlash('success', 'Client enregistré avec success');
                    return $this->redirectToRoute('homepage');
                }
            }

        // replace this example code with whatever you need
            return $this->render('@App/default/index.html.twig', [
                'form'      => $form->createView()
            ]);
    }

    /**
     *  @Route("/charger-client", name="charger_client", options={"expose"=true})
     */
    public function chargerClientAction(Request $request){
        // DATA
            $em = $this->getDoctrine()->getManager();
            $router = $this->get('router');

            $client = $em->getRepository('AppBundle:Client')->findAll();
            // Colonne de la table Client servant à l'ordonnement des colonnes dans datatable avec "orderable"
                $colonnes = array(
                     0 => 'c.id'        , // id
                     1 => 'c.nom'       , // Nom
                     2 => 'c.prenom'    , // Prénom
                     3 => 'c.age'       , // Age
                     4 => 'c.email'     , // Email
                     5 => 'c.flagMajeur', // FlagMajeur
                );
            $index_a_trier  = $request->request->get('order')[0]['column'];            // Index de la colonne à trier
            $order          = $request->request->get('order')[0]['dir'];               // Ordre d'affichage : ASC ou DESC
            $windowWidth    = $request->query->get('windowWidth');                     // Récupération taille page
            $nbItem         = $request->request->get('length');                        // Nombre de ligne à afficher
            $page           = $request->request->get('start');                         // Page à afficher -> click sur 2 dans la pagination
            
            // Si aucune recherche ou aucun filtre
            $clients        = $this->getDoctrine() ->getRepository('AppBundle:Client')->findAllClient($colonnes[$index_a_trier], $order, $nbItem, $page);

            $datatable = array();

            foreach($clients as $key => $client){
                $datatable[$key][0] = $client->getId();                 // Id du client
                $datatable[$key][1] = $client->getNom();                // Nom du client
                $datatable[$key][2] = $client->getPrenom();             // Prénom du client
                $datatable[$key][3] = $client->getAge();                // Age du client
                $datatable[$key][4] = $client->getEmail();              // Email du client
                $datatable[$key][5] = $client->getFlagMajeur();         // FlagMajeur du client

            }
            $tab = array();
            $tab['data'] = $datatable;
        return $this->json($tab);
    }
}
