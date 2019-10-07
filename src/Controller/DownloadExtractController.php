<?php declare(strict_types=1);

namespace App\Controller;

use http\Env\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\DBAL\Driver\Connection;

class DownloadExtractController extends AbstractController
{
    /**
     * @Route("/download_extract/{isbn}/{id}", name="download_extract", requirements={"isbn"="\d{13}","id"="\d+"})
     * @param $connection
     * @param string $isbn
     * @param string $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(Connection $connection, string $isbn, string $id)
    {

        /*
         * Main details
         */
        $sql = 'SELECT id, name, type  
                    FROM extracts 
                    WHERE isbn = :isbn
                    AND id = :id
                    LIMIT 1';
        $stmt = $connection->prepare($sql);
        $stmt->execute(['isbn' => $isbn, 'id' => $id]);
        $extract_details = $stmt->fetch();

        if ($extract_details) {
            $response = new Symfony\Component\HttpFoundation\BinaryFileResponse('extract/' . $extract_details['id']);
            $response->headers->set('Content-Type', $extract_details['type']);
            return $response->setContentDisposition(ResponseHeaderBag::DISPOSITION_ATTACHMENT, $extract_details['name']);
        } else {
            throw $this->createNotFoundException('Book extract not found');
        }
    }
}