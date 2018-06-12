<?php

namespace TR\PlatformBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;


class PlatformController extends Controller
{
    public function indexAction(Request $request)
    {
        return $this->render('TRPlatformBundle::index.html.twig');
    }
}