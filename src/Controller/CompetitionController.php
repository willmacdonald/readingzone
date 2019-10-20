<?php declare(strict_types=1);

namespace App\Controller;

use Doctrine\DBAL\Connection;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;


class CompetitionController extends AbstractController
{
    /**
     * @Route("/competition/{comp_id}", name="competition", requirements={"comp_id"="\d+"})
     * @param Connection $controller
     * @param int $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(Connection $controller, int $id)
    {
        $sql = "SELECT * FROM projects WHERE id = :id LIMIT 1";

        return $this->render('competition/index.html.twig', [

        ]);
    }
}
