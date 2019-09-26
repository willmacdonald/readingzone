<?php

namespace App\Controller;

use http\Env\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\DBAL\Driver\Connection;

class BookDetailsController extends AbstractController
{
    /**
     * @Route("/book/{isbn}", name="book_details", requirements={"isbn"="\d{13}"})
     * @param Connection $connection
     * @param string $isbn
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(Connection $connection, string $isbn) {

        /*
         * Main details
         */
        $sql = 'SELECT TI, FN, DF2, PU2, PUBPD, FMT, ISBN13 FROM book_details WHERE ISBN13 = :isbn LIMIT 1';
        $stmt = $connection->prepare($sql);
        $stmt->execute(['isbn' => $isbn]);
        $books_details = $stmt->fetch();

        /**
         * related title
         */

        $sql = 'SELECT TI, FN, ISBN13 FROM book_details WHERE FN LIKE :FN AND ISBN != :isbn LIMIT 4 ';
        $stmt = $connection->prepare($sql);
        $stmt->execute(
            ['isbn' => $isbn,
            'FN' => $books_details['FN'],
            ]);
        $related_titles = $stmt->fetchAll();

        /**
         * related articles
         *
         */


        $sql = 'SELECT id, headline FROM news WHERE isbn = :isbn OR headline like :FN OR body like :FN OR 
                                    headline like :TI OR body like :TI ORDER BY mark LIMIT 6';
        $stmt = $connection->prepare($sql);
        $stmt->execute(
            [   'isbn' => $isbn,
                'FN' => $books_details['FN'],
                'TI' => $books_details['TI'],
            ]);
        $related_articles[] = $stmt->fetchAll();
        if (count($related_titles) > 1) {
            foreach ($related_titles as $related_title) {
                $stmt->execute(
                    [   'isbn' => $related_title['ISBN13'],
                        'FN' => $related_title['FN'],
                        'TI' => $related_title['TI'],
                    ]);
                $related_articles[] = $stmt->fetchAll();
            }
        }

        return $this->render('book-details.html.twig', [
            'book_details' => $books_details,
            'related_titles' => $related_titles,
            'related_articles' => array_flatten($related_articles),
        ]);

    }
}