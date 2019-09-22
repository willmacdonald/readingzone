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

        $age_ranges = ['zero', 'three', 'five', 'seven', 'nine', 'eleven', 'fourteen'];
        $highlighted_books = $connection->fetchAll('SELECT 
                                                            book_details.TI as title,
                                                            book_details.FN as authorname,
                                                            book_details.DF2 as summary,
                                                            book_details.ISBN13 as isbn,
                                                            highlighted_books_homepage.*
                                                        FROM 
                                                            book_details,
                                                            highlighted_books_homepage
                                                        WHERE                                                            
                                                            highlighted_books_homepage.isbn = book_details.ISBN13
                                                        ORDER BY 
                                                            added 
                                                        LIMIT 
                                                            20');


        foreach ($highlighted_books as $highlighted_book) {

            foreach ($age_ranges as $age) {
                if (isset($highlighted_book[$age]) && $highlighted_book[$age] === '1') {
                    $send_to_template[$age][] = $highlighted_book;
                }
            }
        }

        return $this->render('index.html.twig',[ 'books' => $send_to_template, 'age_ranges' => $age_ranges]);
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
