<?php declare(strict_types=1);

namespace App\Controller;

use http\Env\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\DBAL\Driver\Connection;

class SignUpController extends AbstractController
{
    /**
     * @Route("/sign_up", name="sign_up")
     * @param Connection $connection
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(Connection $connection)
    {

        $sql = 'SELECT main_text FROM meta_data where page = "sign_up" LIMIT 1';
        $stmt = $connection->prepare($sql);
        $stmt->execute();
        $data = $stmt->fetch();
        return $this->render('sign-up.html.twig', ['data' => $data]);
    }
}