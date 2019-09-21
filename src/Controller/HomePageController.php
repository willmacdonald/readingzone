<?php

namespace App\Controller;

use http\Env\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\DBAL\Driver\Connection;

class HomePageController extends AbstractController {
    /**
     * @Route("/", name="homepage")
     * @param Connection $connection
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index (Connection $connection) {

        $highlighted_books = $connection->fetchAll('SELECT * FROM highlighted_books_homepage');

        return $this->render('_footer.html.twig');
    }

    /**
     * @Route("/families", name="families_homepage")
     */
    public function families () {
        return $this->render('index-families.html.twig');
    }

    /**
     * @Route("/children", name="children_homepage")
     */
    public function children () {
        return $this->render('index-children.html.twig');
    }

    /**
     * @Route("/teens", name="teenagers_homepage")
     */
    public function teens () {
        return $this->render('index-teens.html.twig');
    }

    /**
     * @Route("/young-adult", name="young-adult_homepage")
     */
    public function young_adult () {
        return $this->render('index-young_adult.html.twig');
    }

    /**
     * @Route("/schools_libraries", name="schools_libraries_homepage")
     */
    public function schools_libraries () {
        return $this->render('index-schools_libraries.html.twig');
    }
}
