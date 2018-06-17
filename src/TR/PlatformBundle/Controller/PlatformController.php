<?php

namespace TR\PlatformBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Google_Client;
use Google_Service_Sheets;
use TR\PlatformBundle\Entity\Vocabulary;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;

use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;


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
            'words' => $wordsJson
        ));
    }
}