<?php

namespace App\Service;

use App\Entity\Book;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;

class Api
{

    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var PaginatorInterface
     */
    private $paginator;

    /**
     * @var string/null
     */
    private $apiError = null;

    /**
     * Api constructor.
     * @param EntityManagerInterface $em
     * @param PaginatorInterface $paginator
     */
    public function __construct(
        EntityManagerInterface $em,
        PaginatorInterface $paginator
    )
    {
        $this->em = $em;
        $this->paginator = $paginator;
        $this->repositoryBook = $this->em
            ->getRepository(Book::class);
    }

    /**
     * @param Request $request
     * @return array Authors
     */
    public function getAuthors(Request $request)
    {
        $result = [];
        if (!$this->isValid($request)) {
            $result['error'] = $this->apiError;
            return $result;
        }
        try {
            $page = $request->get('page') ?: 1;
            $search = $this->repositoryBook->getAuthors();
            $authors = $this->paginator->paginate(
                $search, $page, Book::NUMBER_OF_ITEMS_FOR_PAGE
            );
            foreach ($authors->getItems() as $key => $row) {
                $result['authors'][] = [
                    $this->getPaginateKey($page, $key) => array_pop($row)
                ];
                $result['totalResult'] = $authors->getTotalItemCount();
                $result['totalPages'] = $result['totalResult'] > 0 ? ceil($result['totalResult'] / Book::NUMBER_OF_ITEMS_FOR_PAGE) : '';

            }
        } catch (\Exception $e) {
            $result['error'] = 'Failed to retrieve data.';
        }
        return $result;
    }

    /**
     * @param Request $request
     * @return array
     */
    public function getBooks(Request $request)
    {
        $result = [];
        if (!$this->isValid($request)) {
            $result['error'] = $this->apiError;
            return $result;
        }
        try {
            $page = $request->get('page') ?: 1;
            $query = $request->get('query');
            $author = $request->get('author');
            $review = $request->get('review');
            $search = $this->repositoryBook->getBooks(
                $query,
                $author,
                $review
            );
            $books = $this->paginator->paginate(
                $search, $page, Book::NUMBER_OF_ITEMS_FOR_PAGE
            );
            foreach ($books->getItems() as $key => $row) {
                $result['books'][] = [
                    $this->getPaginateKey($page, $key) => $row
                ];
            }
            $result['totalResult'] = $books->getTotalItemCount();
            $result['totalPages'] = $result['totalResult'] > 0 ? ceil($result['totalResult'] / Book::NUMBER_OF_ITEMS_FOR_PAGE) : '';
        } catch (\Exception $e) {
            $result['error'] = 'Failed to retrieve data.';
        }
        return $result;
    }

    /**
     * @param Request $request
     * @return bool
     */
    private function isValid(Request $request)
    {
        $valid = true;
        if ($request->get('page')
            && !is_numeric($request->get('page'))
        ) {
            $this->apiError = 'Wrong type of argument: page';
        }
        if ($request->get('review')
            && !is_numeric($request->get('review'))
        ) {
            $this->apiError = 'Wrong type of argument: review';
        }
        if ($this->apiError) {
            $valid = false;
        }
        return $valid;
    }

    /**
     * Get paginate key
     * @param int $page
     * @param int $key
     * @return float|int
     */
    private function getPaginateKey($page, $key)
    {
        return ((($page - 1) * Book::NUMBER_OF_ITEMS_FOR_PAGE) + $key + 1);
    }
}