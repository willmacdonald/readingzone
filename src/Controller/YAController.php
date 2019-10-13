<?php declare(strict_types=1);

namespace App\Controller;

use http\Env\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\DBAL\Driver\Connection;

class YAController extends AbstractController
{
    /**
     * @Route("/young-adult", name="young-adult_homepage")
     * @param Connection $connection
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(Connection $connection) {
        return $this->render('index-young_adult.html.twig');
    }
}