<?php declare(strict_types=1);

namespace App\Controller;

use http\Env\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\DBAL\Driver\Connection;

class LatestProjectController extends AbstractController
{
    /**
     * @Route("/latest/project", name="latest_project")
     * @param Connection $connection
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(Connection $connection)
    {

        $sql = "SELECT * FROM projects ORDER BY timestamp LIMIT 1";

        $stmt = $connection->prepare($sql);
        $stmt->execute();
        $project = $stmt->fetch();

        return $this->render('latest_project/index.html.twig', [
            'project' => $project
        ]);
    }
}
