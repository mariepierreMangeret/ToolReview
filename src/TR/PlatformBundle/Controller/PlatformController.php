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

    public function vocabularyAction(Request $request)
    {
        $form = $this->createFormBuilder()
            ->add('date1', TextType::class)
            ->add('date2', TextType::class)
            ->add('validate', SubmitType::class, array('label' => 'Valider'))
            ->getForm();

        if ($form->handleRequest($request)->isValid()) {
            $encoders = array(new XmlEncoder(), new JsonEncoder());
            $normalizers = array(new ObjectNormalizer());
            $serializer = new Serializer($normalizers, $encoders);

            $words = $this
                ->getDoctrine()
                ->getManager()
                ->getRepository('TRPlatformBundle:Vocabulary')
                ->findSearchByDate($form["date1"]->getData()." 00:00:00", $form["date2"]->getData()." 24:59:59");

            $dates = $this->getDoctrine()
                ->getManager()
                ->getRepository('TRPlatformBundle:Vocabulary')->createQueryBuilder('v')
                ->select('v.dateCreation')
                ->groupBy('v.dateCreation')
                ->getQuery()
                ->getArrayResult();

            $datesJson = $serializer->serialize($dates, 'json');

            return $this->render('TRPlatformBundle:vocabulary:french.html.twig', array (
                'words' => $words,
                'form'  => $form->createView(),
                'dates' => $datesJson
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

        $dates = $this->getDoctrine()
            ->getManager()
            ->getRepository('TRPlatformBundle:Vocabulary')->createQueryBuilder('v')
            ->select('v.dateCreation')
            ->groupBy('v.dateCreation')
            ->getQuery()
            ->getArrayResult();
            
        $datesJson = $serializer->serialize($dates, 'json');

        return $this->render('TRPlatformBundle:vocabulary:french.html.twig', array (
            'navbarTop' => 'vocabulary',
            'words'     => $words,
            'form'      => $form->createView(),
            'dates'     => $datesJson
        ));
    }

    public function irregularVerbsAction(Request $request)
    {
        return $this->render('TRPlatformBundle:exercices:irregular_verbs.html.twig', array (
                'navbarTop'    => 'irregular verbs'
            ));
    }

    public function exerciceAAction(Request $request)
    {
        $form = $this->createFormBuilder()
            ->add('date1', TextType::class)
            ->add('date2', TextType::class)
            ->add('validate', SubmitType::class, array('label' => 'Valider'))
            ->getForm();

        if ($form->handleRequest($request)->isValid()) {
            $encoders = array(new XmlEncoder(), new JsonEncoder());
            $normalizers = array(new ObjectNormalizer());
            $serializer = new Serializer($normalizers, $encoders);

            $words = $this
                ->getDoctrine()
                ->getManager()
                ->getRepository('TRPlatformBundle:Vocabulary')
                ->findSearchByDate($form["date1"]->getData()." 00:00:00", $form["date2"]->getData()." 24:59:59");

            $dates = $this->getDoctrine()
                ->getManager()
                ->getRepository('TRPlatformBundle:Vocabulary')->createQueryBuilder('v')
                ->select('v.dateCreation')
                ->groupBy('v.dateCreation')
                ->getQuery()
                ->getArrayResult();

            $wordsJson = $serializer->serialize($words, 'json');
            $datesJson = $serializer->serialize($dates, 'json');

            return $this->render('TRPlatformBundle:exercices:exercice_a.html.twig', array (
                'navbarTop'         => 'exercice 1',
                'filterExercice'    => 'date',
                'words'             => $wordsJson,
                'form'              => $form->createView(),
                'dates'             => $datesJson
            ));
        }

        $encoders = array(new XmlEncoder(), new JsonEncoder());
        $normalizers = array(new ObjectNormalizer());
        $serializer = new Serializer($normalizers, $encoders);

        $words = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository('TRPlatformBundle:Vocabulary')
            ->findAll();

        $dates = $this->getDoctrine()
            ->getManager()
            ->getRepository('TRPlatformBundle:Vocabulary')->createQueryBuilder('v')
            ->select('v.dateCreation')
            ->groupBy('v.dateCreation')
            ->getQuery()
            ->getArrayResult();


        $wordsJson = $serializer->serialize($words, 'json');
        $datesJson = $serializer->serialize($dates, 'json');

        return $this->render('TRPlatformBundle:exercices:exercice_a.html.twig', array (
            'navbarTop' => 'exercice 1',
            'words'     => $wordsJson,
            'form'      => $form->createView(),
            'dates'     => $datesJson
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
            $encoders = array(new XmlEncoder(), new JsonEncoder());
            $normalizers = array(new ObjectNormalizer());
            $serializer = new Serializer($normalizers, $encoders);

            $words = $this
                ->getDoctrine()
                ->getManager()
                ->getRepository('TRPlatformBundle:Vocabulary')
                ->findSearchByDate($form["date1"]->getData()." 00:00:00", $form["date2"]->getData()." 24:59:59");

            $dates = $this->getDoctrine()
                ->getManager()
                ->getRepository('TRPlatformBundle:Vocabulary')->createQueryBuilder('v')
                ->select('v.dateCreation')
                ->groupBy('v.dateCreation')
                ->getQuery()
                ->getArrayResult();

            $wordsJson = $serializer->serialize($words, 'json');
            $datesJson = $serializer->serialize($dates, 'json');

            return $this->render('TRPlatformBundle:exercices:exercice_a.html.twig', array (
                'navbarTop'         => 'exercice 1',
                'filterExercice'    => 'date',
                'words'             => $wordsJson,
                'form'              => $form->createView(),
                'dates'             => $datesJson
            ));
        }

        $encoders = array(new XmlEncoder(), new JsonEncoder());
        $normalizers = array(new ObjectNormalizer());
        $serializer = new Serializer($normalizers, $encoders);

        $words = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository('TRPlatformBundle:Vocabulary')
            ->findByFavorite(true);

        $dates = $this->getDoctrine()
            ->getManager()
            ->getRepository('TRPlatformBundle:Vocabulary')->createQueryBuilder('v')
            ->select('v.dateCreation')
            ->groupBy('v.dateCreation')
            ->getQuery()
            ->getArrayResult();

        $wordsJson = $serializer->serialize($words, 'json');
        $datesJson = $serializer->serialize($dates, 'json');

        return $this->render('TRPlatformBundle:exercices:exercice_a.html.twig', array (
            'navbarTop'         => 'exercice 1',
            'filterExercice'    => 'favorite',
            'words'             => $wordsJson,
            'form'              => $form->createView(),
            'dates'             => $datesJson
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