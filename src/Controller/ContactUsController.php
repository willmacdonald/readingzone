<?php declare(strict_types=1);

namespace App\Controller;

use http\Env\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\DBAL\Driver\Connection;

class ContactUsController extends AbstractController
{
    /**
     * @Route("/contact_us", name="contact_us")
     * @param Connection $connection
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(Connection $connection)
    {

        $sql = 'SELECT main_text FROM meta_data where page = "contact_us" LIMIT 1';
        $stmt = $connection->prepare($sql);
        $stmt->execute();
        $data = $stmt->fetch();
        return $this->render('contact-us.html.twig', ['data' => $data]);
    }
}