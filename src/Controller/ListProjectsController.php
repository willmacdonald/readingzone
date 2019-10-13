<?php declare(strict_types=1);

namespace App\Controller;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DBALException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ListProjectsController extends AbstractController
{
    /**
     * 'trailer','podcast','audio','video'
     * @Route("/projects/{project_type}", name="list_projects", requirements={"project_type"="trailer|podcast|audio|video"})
     * @param Connection $connection
     * @param string $project_type
     * @return Response
     * @throws DBALException
     */
    public function index(Connection $connection, string $project_type = "video")
    {
        $project_name = array(
            'trailer' => 'Book Trailers',
            'podcast' => 'Podcasts',
            'audio' => 'Audio Books',
            'video' => 'Vlogs and Videos');

        if (!key_exists($project_type, $project_name)) {
            $project_type = 'video';
        }

        $sql = "SELECT video, title, intro_text FROM projects WHERE project = :project_type ORDER BY timestamp";
        $stmt = $connection->prepare($sql);
        $stmt->execute(['project_type' => $project_type]);
        $projects = $stmt->fetchAll();

        return $this->render('list_projects/index.html.twig', [
            'projects' => $projects,
            'project_type' => $project_name[$project_type],
        ]);
    }
}
