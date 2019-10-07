<?php declare(strict_types=1);

namespace App\Controller;

use http\Env\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\DBAL\Driver\Connection;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use Symfony\Component\Filesystem\Filesystem;

/**
 * @param null $array
 * @return array
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
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(Connection $connection, string $isbn) {

        /*
         * Main details
         */
        $sql = 'SELECT TI, FN, DF2, PU2, PUBPD, NBDREV, FMT, ISBN13 FROM book_details WHERE ISBN13 = :isbn LIMIT 1';
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

        //$filesystem = new Filesystem();

        $sql = 'SELECT id, size 
                FROM extracts 
                WHERE isbn = :isbn 
                LIMIT 1';
        $stmt = $connection->prepare($sql);
        $stmt->execute(
            ['isbn' => $isbn,
            ]);
        $book_extract = $stmt->fetch();

        $books_details['extract_found'] = number_format($book_extract['size']/1048576, 2);

        return $this->render('book-details.html.twig', [
            'book_details' => $books_details,
            'related_titles' => $related_titles,
            'related_articles' => $related_articles,
        ]);

    }
}