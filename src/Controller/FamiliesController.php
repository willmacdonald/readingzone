<?php declare(strict_types=1);

namespace App\Controller;

use http\Env\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\DBAL\Driver\Connection;

class FamiliesController extends AbstractController
{
    /**
     * @Route("/families", name="families_homepage")
     * @param Connection $connection
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(Connection $connection)
    {
        return $this->render('index-families.html.twig');
    }
}
