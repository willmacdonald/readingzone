<?php declare(strict_types=1);

namespace App\Controller;

use Doctrine\DBAL\Connection;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\{Response};
use Symfony\Component\Routing\Annotation\Route;

class AuthorProfileController extends AbstractController
{
    /**
     * @Route("/author/{authorid}/{auth_name}", name="author_profile", requirements={"authorid"="\w{7}"})
     * @param Connection $connection
     * @param $authorid
     * @param $auth_name
     * @return Response
     */
    public function index(Connection $connection, $authorid, $auth_name)
    {

        /**
         * Get Author name
         */

        $sql = "SELECT * FROM authorp WHERE authorid like :authorid LIMIT 1";

        $stmt = $connection->prepare($sql);
        $stmt->execute(['authorid' => $authorid . '%']);
        $author_details = $stmt->fetch();

        /**
         * Get recent book ISBN
         */

        if (strpos($author_details['isbns'], ';') !== FALSE) {
            $isbns = explode(';', $author_details['isbns']);

            if (count($isbns) > 0) {
                foreach ($isbns as $isbn) {
                    if (strlen($isbn) == 13) {
                        $recent_books[] = $isbn;
                    }
                }
            }
        } else {
            $recent_books = FALSE;
        }

        /**
         * Get interview
         */

        $sql = "SELECT * FROM czauthor WHERE authorid = :authorid ORDER BY mark LIMIT 1";

        $stmt = $connection->prepare($sql);
        $stmt->execute(['authorid' => $author_details['authorid']]);
        $interview = $stmt->fetch();
        $interview = $interview['interview'];

        $interview = str_ireplace('Q:', '</p><h3>', $interview);
        $interview = str_ireplace('A:', '</h3><p>', $interview);
        $interview = substr($interview, 4);

        $books = array_slice($recent_books, 0, 4);

        /**
         * related articles
         *
         */
        $sql = 'SELECT id, headline 
                FROM news 
                WHERE isbn = :isbn 
                    OR headline like :FN 
                    OR body like :FN                      
                ORDER BY mark 
                LIMIT 6';
        $stmt = $connection->prepare($sql);
        $stmt->execute(
            [
                'isbn' => $recent_books[0],
                'FN' => '%' . $author_details['auth_name'] . '%',
            ]);
        $related_articles = $stmt->fetchAll();

        /**
         * Recent news summary
         */
        $sql = 'SELECT id, headline, body FROM news ORDER BY mark LIMIT 5';
        $stmt = $connection->prepare($sql);
        $stmt->execute();
        $recent_news_summary = $stmt->fetchAll();

        return $this->render('author_profile/article-author-interviews.html.twig', [
            'author' => $author_details,
            'recent_book' => $recent_books[0],
            'interview' => $interview,
            'recent_books' => $books,
            'related_articles' => count($related_articles) > 0 ? $related_articles : NULL,
            'recent_news_summary' => $recent_news_summary,
        ]);
    }
}
