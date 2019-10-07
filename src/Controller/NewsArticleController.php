<?php declare(strict_types=1);

namespace App\Controller;

use http\Env\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\DBAL\Driver\Connection;

class NewsArticleController extends AbstractController
{
    /**
     * @Route("/news_article", name="news_article")
     * @param Connection $connection
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(Connection $connection)
    {

        $sql = 'SELECT main_text FROM meta_data where page = "about_us" LIMIT 1';
        $stmt = $connection->prepare($sql);
        $stmt->execute();
        $data = $stmt->fetch();
        return $this->render('about-us.html.twig', ['data' => $data]);
    }
}