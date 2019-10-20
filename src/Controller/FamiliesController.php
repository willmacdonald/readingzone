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

        $featured_books = array();
        foreach ($highlighted_books as $highlighted_book) {
            foreach ($age_ranges as $age) {
                if (isset($highlighted_book[$age]) && $highlighted_book[$age] === '1') {
                    $featured_books[$age][] = $highlighted_book;
                }
            }
        }

        /**
         * Authors of the month
         */
        $featured_authors = $connection->fetchAll('SELECT 
                                                            book_details.TI as title,
                                                            book_details.FN as authorname,
                                                            book_details.DF2 as summary,
                                                            book_details.ISBN13 as isbn,
                                                            families_authors_month.*
                                                        FROM 
                                                            book_details,
                                                            families_authors_month
                                                        WHERE                                                            
                                                            families_authors_month.isbn = book_details.ISBN13
                                                        ORDER BY 
                                                            added 
                                                        LIMIT 
                                                            20');

        $authors = array();

        foreach ($featured_authors as $featured_author) {
            foreach ($rz_authors_month_range as $rz_authors_month) {
                if (isset($featured_author[$rz_authors_month]) && $featured_author[$rz_authors_month] === '1') {
                    $authors[$rz_authors_month][] = $featured_author;
                }
            }
        }


        /**
         * recent news summary
         */
        $sql = 'SELECT id, headline, body FROM news ORDER BY mark LIMIT 3';
        $stmt = $connection->prepare($sql);
        $stmt->execute();
        $recent_news_summary = $stmt->fetchAll();

        /**
         * Get the latest projects
         */

        $project_name = array(
            'trailer' => 'Book Trailers',
            'podcast' => 'Podcasts',
            'audio' => 'Audio Books',
            'video' => 'Vlogs and Videos',
        );

        $themes = array(
            'trailer' => 'theme-coral',
            'podcast' => 'theme-carrot',
            'audio' => 'theme-lime',
            'video' => '',
        );

        $sql = "SELECT image, video, title, project, intro_text, id 
                FROM projects
                WHERE project = :project
                ORDER BY mark
                LIMIT 1";

        $stmt = $connection->prepare($sql);

        $latest_projects = array();

        foreach ($project_name as $k => $v) {
            $stmt->execute(['project' => $k]);
            $latest_projects[$k] = $stmt->fetch();
        }

        /**
         * Bookshop
         * 'ya','children','schools','teens','families'
         */

        $shop_filters = array(
            'children' => 'Children',
            'teens' => 'Teens',
            'ya' => 'Young Adult',
            'families' => 'Families',
            'schools' => 'Schools & Libraries',
        );

        $sql = "SELECT TI, FN, bookshop.isbn  as isbn, tag
                    FROM book_details, bookshop
                    WHERE bookshop.isbn = book_details.ISBN13
                    AND bookshop.popular = 1
                    ORDER BY mark";

        $stmt = $connection->prepare($sql);
        $stmt->execute();
        $popular_books = $stmt->fetchAll();

        $sql = "SELECT book_details.TI, book_details.FN, bookshop.isbn as isbn, bookshop_categories.name, tag
                FROM book_details, bookshop, bookshop_categories
                WHERE bookshop_categories.live = 1
                AND bookshop_categories.id = bookshop.category
                AND bookshop.isbn = book_details.ISBN13";

        $stmt = $connection->prepare($sql);
        $stmt->execute();
        $bookshop_1 = $stmt->fetchAll();

        $sql = "SELECT book_details.TI, book_details.FN, bookshop.isbn as isbn, bookshop_categories.name, tag
                FROM book_details, bookshop, bookshop_categories
                WHERE bookshop_categories.live = 2
                AND bookshop_categories.id = bookshop.category
                AND bookshop.isbn = book_details.ISBN13";

        $stmt = $connection->prepare($sql);
        $stmt->execute();
        $bookshop_2 = $stmt->fetchAll();

        return $this->render('index-families.html.twig', [
            'books' => $featured_books,
            'age_ranges' => $age_ranges,
            'authors' => $authors,
            'author_range' => $featured_authors,
            'recent_news_summary' => $recent_news_summary,
            'latest_projects' => $latest_projects,
            'project_types' => $project_name,
            'themes' => $themes,
            'shop_filters' => $shop_filters,
            'popular_books' => $popular_books,
            'bookshop_1' => $bookshop_1,
            'bookshop_2' => $bookshop_2,
        ]);
    }
}
