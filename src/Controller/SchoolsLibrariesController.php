<?php

namespace App\Controller;

use http\Env\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\DBAL\Driver\Connection;

class SchoolsLibrariesController extends AbstractController
{
    /**
     * @Route("/schools_libraries", name="schools_libraries_homepage")
     * @param Connection $connection
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(Connection $connection) {
        return $this->render('index-schools_libraries.html.twig');
    }
}