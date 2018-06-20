<?php

namespace TR\PlatformBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Google_Client;
use Google_Service_Sheets;
use TR\PlatformBundle\Entity\Vocabulary;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;

use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;


class PlatformController extends Controller
{
    public function indexAction(Request $request)
    {
        return $this->render('TRPlatformBundle::index.html.twig');
    }

    public function irregularVerbsAction(Request $request)
    {
        return $this->render('TRPlatformBundle:exercices:irregular_verbs.html.twig');
    }

    public function exerciceAAction(Request $request)
    {
        $form = $this->createFormBuilder()
            ->add('date1', TextType::class)
            ->add('date2', TextType::class)
            ->add('validate', SubmitType::class, array('label' => 'Valider'))
            ->getForm();

        if ($form->handleRequest($request)->isValid()) {
            $words = $this
                ->getDoctrine()
                ->getManager()
                ->getRepository('TRPlatformBundle:Vocabulary')
                ->findSearchByDate($form["date1"]->getData()." 00:00:00", $form["date2"]->getData()." 24:59:59");

            $encoders = array(new XmlEncoder(), new JsonEncoder());
            $normalizers = array(new ObjectNormalizer());
            $serializer = new Serializer($normalizers, $encoders);
            $wordsJson = $serializer->serialize($words, 'json');


            return $this->render('TRPlatformBundle:exercices:exercice_a.html.twig', array (
                'filterExercice'    => 'date',
                'words'             => $wordsJson,
                'form'              => $form->createView()
            ));
        }

        $words = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository('TRPlatformBundle:Vocabulary')
            ->findAll();

        $encoders = array(new XmlEncoder(), new JsonEncoder());
        $normalizers = array(new ObjectNormalizer());
        $serializer = new Serializer($normalizers, $encoders);
        $wordsJson = $serializer->serialize($words, 'json');

        return $this->render('TRPlatformBundle:exercices:exercice_a.html.twig', array (
            'words' => $wordsJson,
            'form'  => $form->createView()
        ));
    }

    public function exerciceAFavoriteAction(Request $request)
    {
        $form = $this->createFormBuilder()
            ->add('date1', TextType::class)
            ->add('date2', TextType::class)
            ->add('validate', SubmitType::class, array('label' => 'Valider'))
            ->getForm();

        if ($form->handleRequest($request)->isValid()) {
            $words = $this
                ->getDoctrine()
                ->getManager()
                ->getRepository('TRPlatformBundle:Vocabulary')
                ->findSearchByDate($form["date1"]->getData()." 00:00:00", $form["date2"]->getData()." 24:59:59");

            $encoders = array(new XmlEncoder(), new JsonEncoder());
            $normalizers = array(new ObjectNormalizer());
            $serializer = new Serializer($normalizers, $encoders);
            $wordsJson = $serializer->serialize($words, 'json');


            return $this->render('TRPlatformBundle:exercices:exercice_a.html.twig', array (
                'filterExercice'    => 'date',
                'words'             => $wordsJson,
                'form'              => $form->createView()
            ));
        }

        $words = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository('TRPlatformBundle:Vocabulary')
            ->findByFavorite(true);

        $encoders = array(new XmlEncoder(), new JsonEncoder());
        $normalizers = array(new ObjectNormalizer());
        $serializer = new Serializer($normalizers, $encoders);
        $wordsJson = $serializer->serialize($words, 'json');

        return $this->render('TRPlatformBundle:exercices:exercice_a.html.twig', array (
            'filterExercice'    => 'favorite',
            'words'             => $wordsJson,
            'form'              => $form->createView()
        ));
    }

    public function ajaxfavoriteAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $id = $request->request->get('id');

        $repository = $em->getRepository("TRPlatformBundle:Vocabulary");
        $vocabulary = $repository->findOneById($id);
        $vocabulary->setFavorite(!$vocabulary->getFavorite());
        $em->persist($vocabulary);
        $em->flush();

        $response = new Response();
        $response->setContent(json_encode(array("success"=>true, "favorite"=>$vocabulary->getFavorite())));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }
}