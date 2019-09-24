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

        /*
         * related title
         */

        $sql = 'SELECT ISBN13 FROM related_titles WHERE ISBN13 = :isbn LIMIT 1';
        $stmt = $connection->prepare($sql);
        $stmt->execute(['isbn' => $isbn]);
        $related_titles = $stmt->fetchAll();

        /*
         * related articles
         */

        $sql = 'SELECT ISBN13 FROM related_articles WHERE ISBN13 = :isbn LIMIT 1';
        $stmt = $connection->prepare($sql);
        $stmt->execute(['isbn' => $isbn]);
        $related_articles = $stmt->fetchAll();

        return $this->render('book-details.html.twig', [
            'book_details' => $books_details,
            'related_titles' => $related_titles,
            'related_articles' => $related_articles,
        ]);
    }
}