<?php

namespace App\Repository;

use App\Entity\Book;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Book|null find($id, $lockMode = null, $lockVersion = null)
 * @method Book|null findOneBy(array $criteria, array $orderBy = null)
 * @method Book[]    findAll()
 * @method Book[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BookRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Book::class);
    }

    /**
     * Check if book exist
     * @param string $title Title
     * @param string $author Author
     * @return mixed
     */
    public function isExist($title, $author)
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.title = :title')
            ->setParameter('title', $title)
            ->andWhere('b.author = :author')
            ->setParameter('author', $author)
            ->getQuery()
            ->getResult();
    }

    /**
     * Get books search by title or author
     * @param string $query Finding title or author
     * @return mixed
     */
    public function searchByTitleOrAuthor($query)
    {
        return $this->createQueryBuilder('b')
            ->Where('b.title like :title')
            ->setParameter('title', '%' . $query . '%')
            ->orWhere('b.author like :author')
            ->setParameter('author', '%' . $query . '%')
            ->getQuery()
            ->getResult();
    }

    /**
     * Get all unique authors
     * @return mixed
     */
    public function getAuthors()
    {
        return $this->createQueryBuilder('b')
            ->select('distinct (b.author)')
            ->orderBy('b.author', 'ASC')
            ->where('b.author IS NOT NULL')
            ->andWhere("b.author != ''")
            ->getQuery()
            ->getResult();
    }

    /**
     * Get all books, search by the criteria
     * @param string/null $query Title or description
     * @param string/null $author Author
     * @param int/null $review Number of reviews
     * @return mixed
     */
    public function getBooks($query = null, $author = null, $review = null)
    {
        $qb = $this->createQueryBuilder('b')
            ->select('b.title, b.description, b.author, b.numberReviews')
            ->orderBy('b.title', 'ASC');
        if ($query) {
            $qb->andWhere('b.title like :title')
                ->setParameter('title', '%' . $query . '%')
                ->orWhere('b.description like :description')
                ->setParameter('description', '%' . $query . '%');
        }
        if ($author) {
            $qb->andWhere('b.author like :author')
                ->setParameter('author', '%' . $author . '%');
        }
        if ($review) {
            $qb->andWhere('b.numberReviews > :numberReviews')
                ->setParameter('numberReviews', 1);
        }
        return $qb->getQuery()
            ->getResult();
    }
}
