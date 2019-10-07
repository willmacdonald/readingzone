<?php declare(strict_types=1);

namespace App\Controller;

use http\Env\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\DBAL\Driver\Connection;

class NewsletterSignUpController extends AbstractController
{
    /**
     * @Route("/newsletter_signup", name="newsletter_signup")
     * @param Connection $connection
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(Connection $connection)
    {

        $sql = 'SELECT main_text FROM meta_data where page = "newsletter_signup" LIMIT 1';
        $stmt = $connection->prepare($sql);
        $stmt->execute();
        $data = $stmt->fetch();
        return $this->render('newsletter-signup.html.twig', ['data' => $data]);
    }
}