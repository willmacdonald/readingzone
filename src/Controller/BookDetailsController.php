<?php declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\DBAL\Driver\Connection;

/**
 * @param array $array
 * @return Array
 */

function array_flatten(Array $array = null): Array
{
    $result = array();

    if (!is_array($array)) {
        $array = func_get_args();
    }

    foreach ($array as $key => $value) {
        if (is_array($value)) {
            $result = array_merge($result, \App\Controller\array_flatten($value));
        } else {
            $result = array_merge($result, array($key => $value));
        }
    }

    return $result;
}

class BookDetailsController extends AbstractController
{
    /**
     * @Route("/book/{isbn}", name="book_details", requirements={"isbn"="\d{13}"})
     * @param Connection $connection
     * @param string $isbn
     * @return Response
     */
    public function index(Connection $connection, string $isbn) {

        /*
         * Main details
         */
        $sql = 'SELECT TI, FN, DF2, PU2, PUBPD, NBDREV, FMT, ISBN13 
                    FROM book_details 
                    WHERE ISBN13 = :isbn 
                    LIMIT 1';
        $stmt = $connection->prepare($sql);
        $stmt->execute(['isbn' => $isbn]);
        $books_details = $stmt->fetch();

        /**
         * related title
         */
        $sql = "SELECT TI, FN, ISBN13 
                FROM book_details 
                WHERE 
                    FN LIKE :FN 
                    AND ISBN != :isbn
                    AND FMT != 'Electronic book text'
                LIMIT 4 ";

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
        $sql = 'SELECT id, headline 
                FROM news 
                WHERE isbn = :isbn 
                    OR headline like :FN 
                    OR body like :FN 
                    OR headline like :TI
                    OR body like :TI 
                ORDER BY mark 
                LIMIT 6';
        $stmt = $connection->prepare($sql);
        $stmt->execute(
            [   'isbn' => $isbn,
                'FN' => '%' . $books_details['FN'] . '%',
                'TI' => '%' . $books_details['TI'] . '%',
            ]);
        $related_articles = $stmt->fetchAll();
        /**
        * if (count($related_titles) > 1) {
            * foreach ($related_titles as $related_title) {
                * $stmt->execute(
                    * [   'isbn' => $related_title['ISBN13'],
         * 'FN' => '%'.$related_title['FN'].'%',
         * 'TI' => '%'.$related_title['TI'].'%',
         * ]);
                * $related_articles[] = $stmt->fetchAll();
         * }
         * }
         */

        /**
         * See if there is an extract to download
         */
        $sql = 'SELECT id as extract_id, size, extract_text, type 
                FROM extracts 
                WHERE isbn = :isbn';
        $stmt = $connection->prepare($sql);
        $stmt->execute(
            ['isbn' => $isbn,
            ]);
        $book_extracts = $stmt->fetchAll();

        foreach ($book_extracts as $book_extract) {
            $books_details['extract_found'][$book_extract['extract_id']] = $book_extract['size'] ?
                substr($book_extract['type'], strpos($book_extract['type'], '/') + 1) . ' (' . number_format($book_extract['size'] / 1048576, 2) . 'M) ' :
                false;

            $books_details['extract_text'] = strlen($book_extract['extract_text']) > 0 ?
                $book_extract['extract_text'] :
                false;
        }

        return $this->render('book-details.html.twig', [
            'book_details' => $books_details,
            'related_titles' => $related_titles,
            'related_articles' => $related_articles,
        ]);

    }
}