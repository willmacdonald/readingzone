<?php

namespace App\Controller;

use Doctrine\DBAL\Connection;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\{Response};
use Symfony\Component\Routing\Annotation\Route;

class AuthorProfileController extends AbstractController
{
    /**
     * @Route("/author/{authorid}/{auth_name}", name="author_profile", requirements={"authorid"="\w{7}"})
     * @param Connection $connection
     * @param $authorid
     * @param $authname
     * @return Response
     */
    public function index(Connection $connection, $authorid, $authname)
    {
        return $this->render('author_profile/index.html.twig', [
            'controller_name' => 'AuthorProfileController',
        ]);
    }
}
