<?php declare(strict_types=1);

namespace App\Controller;

use Doctrine\DBAL\Driver\Connection;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ChildrensController extends AbstractController
{
    /**
     * @Route("/children", name="children_homepage")
     * @param Connection $connection
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(Connection $connection)
    {

        /**
         * Authors
         */

        $top_section = $connection->fetchAll("SELECT * FROM children_homepage_top_section
                                                    ORDER BY mark 
                                                    LIMIT 1");

        /**
         * latest books & extracts
         */
        $latest_books_extracts = $connection->fetchAll("SELECT isbn FROM childrens_homepage_bookextracts ORDER BY mark LIMIT 20");

        /**
         * featured authors
         */

        $featured_authors = $connection->fetchAll(
            "SELECT authorid, text FROM children_homepage_authors_month ORDER BY mark LIMIT 20");

        /**
         * Your reviews
         */

        var_dump($featured_authors);

        return $this->render('index-children.html.twig', [
            'author_month_id' => $top_section[0]['author_month'],
            'author_month_title' => $top_section[0]['author_month_title'],
            'author_month_text' => $top_section[0]['author_month_text'],
            'writers_shed_id' => $top_section[0]['writers_shed'],
            'writers_shed_title' => $top_section[0]['author_month_title'],
            'writers_shed_text' => $top_section[0]['writers_shed_text'],
            'career_id' => $top_section[0]['career'],
            'career_title' => $top_section[0]['career_title'],
            'career_text' => $top_section[0]['career_text'],
            'latest_bookextracts' => $latest_books_extracts,
            'featured_authors' => $featured_authors,
        ]);
    }
}