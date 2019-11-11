<?php

namespace App\Service;

use App\Entity\Book;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class NewYorkTimes
{

    /**
     * @var HttpClientInterface
     */
    private $httpClient;

    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var int
     */
    private $startPage = 1;

    /**
     * @var int
     */
    private $endPage;

    /**
     * @var integer
     */
    private $numberOfImportedBooks = 0;

    /**
     * NewYorkTimes constructor.
     * @param HttpClientInterface $httpClient
     * @param EntityManagerInterface $em
     * @param ContainerInterface $container
     */
    public function __construct(
        HttpClientInterface $httpClient,
        EntityManagerInterface $em,
        ContainerInterface $container
    )
    {
        $this->httpClient = $httpClient;
        $this->em = $em;
        $this->container = $container;
    }

    /**
     * Save NYT Bestsellers Books
     * @param int/null $startPage
     * @param int/null $endPage
     */
    public function importBestseller($startPage = null, $endPage = null)
    {
        if ($startPage) {
            $this->startPage = $startPage;
        }
        $apiUrl = $this->container
            ->getParameter('nytApiUrl');
        $apiUrlOffset = $this->container
            ->getParameter('nytApiUrlOffset');
        $apiKey = $this->container
            ->getParameter('nytApiKey');
        $bookRepository = $this->em
            ->getRepository(Book::class);
        $response = $this->httpClient->request(
            'GET',
            $apiUrl . $apiKey
        );
        $data = $response->toArray();
        $this->setEndPage($endPage, $data['num_results']);
        for ($i = $startPage; $i <= $this->endPage; $i++) {
            $response = $this->httpClient->request(
                'GET',
                $apiUrl . $apiKey . $apiUrlOffset . ($i * 20)
            );
            $data = $response->toArray();
            if (is_array($data['results'])) {
                $newBooksPerPage = 0;
                foreach ($data['results'] as $key => $row) {
                    if ($bookRepository->isExist(
                        $row['title'],
                        $row['author'])
                    ) {
                        continue;
                    }
                    $reviews = $this->getNumberReviews($row['reviews']);
                    $book = new Book();
                    $book->setTitle($row['title']);
                    $book->setDescription($row['description']);
                    $book->setAuthor($row['author']);
                    $book->setNumberReviews($reviews);
                    $this->em->persist($book);
                    $this->numberOfImportedBooks++;
                    $newBooksPerPage++;
                }
            }
            print 'Page ' . $i . ' was imported. ('
                . $newBooksPerPage . ' new books)' . PHP_EOL;
            sleep(5);
        }
        return $this->em->flush();
    }

    /**
     * Get number of imported books
     * @return int
     */
    public function getNumberOfImportedBooks()
    {
        return $this->numberOfImportedBooks;
    }

    /**
     * Get number of reviews
     * @param array $data Reviews
     * @return int Number of reviews
     */
    private function getNumberReviews($data)
    {
        $numberOfReviews = 0;
        if (is_array($data)) {
            foreach (array_pop($data) as $keyReview => $review) {
                if ($review) {
                    $numberOfReviews++;
                }
            }
        }
        return $numberOfReviews;
    }

    /**
     * Set end page
     * @param int $endPage End page
     * @param int $numberResults Number of all results
     */
    private function setEndPage($endPage, $numberResults)
    {
        $this->endPage = ceil($numberResults / Book::NUMBER_OF_ITEMS_FOR_PAGE);
        if ($endPage && $endPage < $this->endPage) {
            $this->endPage = $endPage;
        }
    }

}