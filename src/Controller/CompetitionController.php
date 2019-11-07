<?php declare(strict_types=1);

namespace App\Controller;

use Doctrine\DBAL\Connection;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;


class CompetitionController extends AbstractController
{
    /**
     * @Route("/competition/{id}", name="competition")
     * @param Connection $connection
     * @param int $id
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Doctrine\DBAL\DBALException
     */
    public function index(Connection $connection, int $id)
    {

        $project_types = array(
            'trailer' => 'Book Trailer',
            'podcast' => 'Podcast',
            'audio' => 'Audio Book',
            'video' => 'Vlog and Video',
        );

        /**
         * Main competition details
         */
        $sql = "SELECT * FROM projects WHERE id = :id LIMIT 1";

        $stmt = $connection->prepare($sql);
        $stmt->execute(['id' => $id]);
        $competition = $stmt->fetch();


        /**
         * Get Creative section
         */

        $gc_primary = $connection->fetchAll("SELECT * 
                                                    FROM projects
                                                    WHERE gc_primary = 1
                                                    LIMIT 1");

        $gc_secondary = $connection->fetchAll("SELECT * 
                                                    FROM projects
                                                    WHERE gc_secondary = 1
                                                    LIMIT 1");

        $gc_3rd_col = $connection->fetchAll("SELECT * 
                                                    FROM projects
                                                    WHERE gc_3rd_col = 1
                                                    LIMIT 1");

        /**
         * Previous Entries
         */

        $pe_primary = $connection->fetchAll("SELECT * 
                                                    FROM projects
                                                    WHERE pe_primary = 1
                                                    LIMIT 1");

        $pe_secondary = $connection->fetchAll("SELECT * 
                                                    FROM projects
                                                    WHERE pe_secondary = 1
                                                    LIMIT 1");

        $pe_3rd_col = $connection->fetchAll("SELECT * 
                                                    FROM projects
                                                    WHERE pe_3rd_col = 1
                                                    LIMIT 1");


        return $this->render('competition/index.html.twig', [
            'competition' => $competition,
            'gc_primary' => $gc_primary[0],
            'gc_secondary' => $gc_secondary[0],
            'gc_3rd_col' => $gc_3rd_col[0],
            'gc_3rd_col_title' => $project_types[$gc_3rd_col[0]['project']],
            'pe_primary' => $pe_primary[0],
            'pe_secondary' => $pe_secondary[0],
            'pe_3rd_col' => $pe_3rd_col[0],
            'pe_3rd_col_title' => $project_types[$pe_3rd_col[0]['project']],
        ]);
    }
}
