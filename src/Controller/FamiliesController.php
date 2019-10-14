<?php declare(strict_types=1);

namespace App\Controller;

use Doctrine\DBAL\Driver\Connection;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class FamiliesController extends AbstractController
{
    /**
     * @Route("/families", name="families_homepage")
     * @param Connection $connection
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(Connection $connection)
    {

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
                    $featured_books[$age][] = $highlighted_book;
                }
            }
        }

        return $this->render('index-families.html.twig', [
            'age_ranges' => $age_ranges,
            'books' => $featured_books,
        ]);
    }
}
