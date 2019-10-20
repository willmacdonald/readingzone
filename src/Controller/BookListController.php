<?php

namespace App\Controller;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DBALException;
use Doctrine\DBAL\FetchMode;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BookListController extends AbstractController
{
    /**
     * @Route("/booklist/{list}", name="book_list", requirements={"list"="\w+"})
     * @param Connection $connection
     * @param string $list
     * @return Response
     * @throws DBALException
     */
    public function index(Connection $connection, string $list = "home")
    {


        $age_ranges = ['zero', 'three', 'five', 'seven', 'nine', 'eleven', 'fourteen'];
        $rz_authors_month_range = ['picture_book', 'children', 'teenager', 'young_adult', 'debut', 'featured_authors'];

        $lists = array(
            'home' => array('highlighted_books_homepage', ''),
            'children' => array('highlighted_books_children', 'Children'),
            'families' => array('highlighted_books_families', 'Families'),
            'teens' => array('highlighted_books_teen', 'Teenagers'),
            'young-adult' => array('highlighted_books_ya', 'Young Adult'),
            'schools_libraries' => array('highlighted_books_schools', 'Schools & Libraries'),
        );

        $sql = "";

        if (array_key_exists($list, $lists)) {
            $table = $lists[$list][0];

            $sql = "SELECT 
                    'isbn'
                FROM  $table /** @var string $table */                 
                ORDER BY 'added' 
                LIMIT 20";
        } elseif (in_array($list, $rz_authors_month_range)) {
            $sql = "SELECT 
                    isbn
                FROM highlighted_books_homepage                 
                ORDER BY added 
                LIMIT 20";
        }

        $stmt = $connection->prepare($sql);
        $stmt->execute();
        $books = $stmt->fetchAll(FetchMode::NUMERIC);

        $isbn_list = "";
        foreach ($books as $book) {
            $isbn_list .= "'" . $book[0] . "', ";
        }

        $isbn_list = rtrim($isbn_list, ', ');

        $sql = "SELECT isbn, TI, FN, PUBPD, PU, ISBN13 as isbn, DF2 
        FROM book_details
        WHERE isbn13 in (" . $isbn_list . ")";

        $stmt = $connection->prepare($sql);
        $stmt->execute();
        $books_details = $stmt->fetchAll();

        return $this->render('book_list/index.html.twig', [
            'books' => $books,
            //'booklist' => $lists[$list][1],
            'books_details' => $books_details,
        ]);
    }
}
