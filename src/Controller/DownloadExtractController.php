<?php declare(strict_types=1);

namespace App\Controller;

use http\Env\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\DBAL\Driver\Connection;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use Symfony\Component\Filesystem\Filesystem;

class DownloadExtractController extends AbstractController
{
    /**
     * @Route("/download_extract/{isbn}", name="download_extract", requirements={"isbn"="\d{13}"})
     * @param string $isbn
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(string $isbn)
    {

        /*
         * Main details
         */

        $filesystem = new Filesystem();

        if ($filesystem->exists('extract/' . $isbn . '.pdf')) {

            $response = new Symfony\Component\HttpFoundation\BinaryFileResponse('extract/' . $isbn . '.pdf');
            $response->headers->set('Content-Type', 'text/plain');
            return $response->setContentDisposition(ResponseHeaderBag::DISPOSITION_ATTACHMENT, 'extract/' . $isbn . '.pdf');
        } else {
            throw $this->createNotFoundException('Book extract not found');
        }
    }
}