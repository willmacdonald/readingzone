<?php declare(strict_types=1);

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
        $rz_authors_month_range = ['picture_book', 'children', 'teenager', 'young_adult', 'debut', 'featured_authors'];

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
                    $featured_books[$age][] = $highlighted_book;
                }
            }
        }

        $featured_authors = $connection->fetchAll('SELECT 
                                                            book_details.TI as title,
                                                            book_details.FN as authorname,
                                                            book_details.DF2 as summary,
                                                            book_details.ISBN13 as isbn,
                                                            homepage_authors_month.*
                                                        FROM 
                                                            book_details,
                                                            homepage_authors_month
                                                        WHERE                                                            
                                                            homepage_authors_month.isbn = book_details.ISBN13
                                                        ORDER BY 
                                                            added 
                                                        LIMIT 
                                                            20');


        foreach ($featured_authors as $featured_author) {
            foreach ($rz_authors_month_range as $rz_authors_month) {
                if (isset($featured_author[$rz_authors_month]) && $featured_author[$rz_authors_month] === '1') {
                    $authors[$rz_authors_month][] = $featured_author;
                }
            }
        }


        return $this->render('index.html.twig',[
            'books' => $featured_books,
            'age_ranges' => $age_ranges,
            'authors' => $authors,
            'author_range' => $rz_authors_month_range,
            ]);
    }
}
