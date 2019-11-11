<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\Api;

class ApiController extends AbstractController
{
    /**
     * Get list of unique authors
     * @param Request $request
     * @param Api $apiService
     * @return Response
     * @Route("/api/author/", methods={"GET"}, name="app_api_author")
     */
    public function authorAction(
        Request $request,
        Api $apiService)
    {
        $data = $apiService->getAuthors($request);
        $response = new Response();
        $response->setContent(json_encode(array(
            'result' => $data
        )));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    /**
     * Get list of books filter by the criteria
     * @param Request $request
     * @param Api $apiService
     * @return Response
     * @Route("/api/book/", methods={"GET"}, name="app_api_book")
     */
    public function bookAction(
        Request $request,
        Api $apiService
    )
    {
        $data = $apiService->getBooks($request);
        $response = new Response();
        $response->setContent(json_encode(array(
            'result' => $data
        )));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

}