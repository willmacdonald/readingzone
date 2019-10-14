<?php

namespace App\Controller;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DBALException;
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

        $lists = array(
            'home' => array('highlighted_books_homepage', ''),
            'children' => array('highlighted_books_children', 'Children'),
            'families' => array('highlighted_books_families', 'Families'),
            'teens' => array('highlighted_books_teen', 'Teenagers'),
            'young-adult' => array('highlighted_books_ya', 'Young Adult'),
            'schools_libraries' => array('highlighted_books_schools', 'Schools & Libraries'),
        );

        $table = $lists[$list][0];


        $sql = "SELECT 
                    book_details.TI,
                    book_details.FN,
                    book_details.PU,
                    book_details.PUBPD,
                    book_details.DF2,
                    book_details.ISBN13 as isbn
                FROM " . $table . ", book_details
                WHERE book_details.ISBN13 = " . $table . ".isbn 
                ORDER BY added 
                LIMIT 20";

        $stmt = $connection->prepare($sql);
        $stmt->execute();
        $books = $stmt->fetchAll();

        return $this->render('book_list/index.html.twig', [
            'books' => $books,
            'booklist' => $lists[$list][1],
        ]);
    }
}
