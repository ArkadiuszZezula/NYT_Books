<?php

namespace App\Tests\Controller;

use PHPUnit\Framework\TestCase;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ApiTest extends WebTestCase
{
    public function testGetAuthorWithoutParameter()
    {
        $client = static::createClient();
        // Check status
        $client->request('GET', '/api/author/');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $data = json_decode($client->getResponse()->getContent());
        // Check contain data - authors
        $this->assertObjectHasAttribute('authors', $data->result);
        // Check without error
        $this->assertObjectNotHasAttribute('error', $data->result);
        // Check total Result
        $this->assertObjectHasAttribute('totalResult', $data->result);
        // Check total Pages
        $this->assertObjectHasAttribute('totalResult', $data->result);}

    public function testGetAuthorWithCorrectParameterPage()
    {
        $client = static::createClient();
        $client->request('GET', '/api/author/?page=1');
        // Check status
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $data = json_decode($client->getResponse()->getContent());
        // Check contain data - authors
        $this->assertObjectHasAttribute('authors', $data->result);
        // Check without error
        $this->assertObjectNotHasAttribute('error', $data->result);
        // Check total Result
        $this->assertObjectHasAttribute('totalResult', $data->result);
        // Check total Pages
        $this->assertObjectHasAttribute('totalResult', $data->result);}

    public function testGetAuthorWithIncorrectParameterPage()
    {
        $client = static::createClient();
        $client->request('GET', '/api/author/?page=asd');
        // Check status
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $data = json_decode($client->getResponse()->getContent());
        // Check contain data - authors
        $this->assertObjectNotHasAttribute('authors', $data->result);
        // Check without error
        $this->assertObjectHasAttribute('error', $data->result);
        // Check total Result
        $this->assertObjectNotHasAttribute('totalResult', $data->result);
        // Check total Pages
        $this->assertObjectNotHasAttribute('totalResult', $data->result); }

    public function testGetBooksWithoutParameter()
    {
        $client = static::createClient();
        // Check status
        $client->request('GET', '/api/book/');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $data = json_decode($client->getResponse()->getContent());
        // Check contain data - books
        $this->assertObjectHasAttribute('books', $data->result);
        // Check without error
        $this->assertObjectNotHasAttribute('error', $data->result);
        // Check total Result
        $this->assertObjectHasAttribute('totalResult', $data->result);
        // Check total Pages
        $this->assertObjectHasAttribute('totalResult', $data->result);}

    public function testGetBooksWithCorrectParameterPage()
    {
        $client = static::createClient();
        $client->request('GET', '/api/book/?page=1');
        // Check status
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $data = json_decode($client->getResponse()->getContent());
        // Check contain data - books
        $this->assertObjectHasAttribute('books', $data->result);
        // Check without error
        $this->assertObjectNotHasAttribute('error', $data->result);
        // Check total Result
        $this->assertObjectHasAttribute('totalResult', $data->result);
        // Check total Pages
        $this->assertObjectHasAttribute('totalResult', $data->result); }

    public function testGetBooksWithIncorrectParameterPage()
    {
        $client = static::createClient();
        $client->request('GET', '/api/book/?page=qwe');
        // Check status
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $data = json_decode($client->getResponse()->getContent());
        // Check contain data - books
        $this->assertObjectNotHasAttribute('books', $data->result);
        // Check without error
        $this->assertObjectHasAttribute('error', $data->result);
        // Check total Result
        $this->assertObjectNotHasAttribute('totalResult', $data->result);
        // Check total Pages
        $this->assertObjectNotHasAttribute('totalResult', $data->result);}


    public function testGetBooksWithCorrectParameterQuery()
    {
        $client = static::createClient();
        $client->request('GET', '/api/book/?page=1&query=Tom');
        // Check status
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $data = json_decode($client->getResponse()->getContent());
        // Check contain data - books
        $this->assertObjectHasAttribute('books', $data->result);
        // Check without error
        $this->assertObjectNotHasAttribute('error', $data->result);
        // Check total Result
        $this->assertObjectHasAttribute('totalResult', $data->result);
        // Check total Pages
        $this->assertObjectHasAttribute('totalResult', $data->result);
    }

    public function testGetBooksWithCorrectParameterAuthor()
    {
        $client = static::createClient();
        $client->request('GET', '/api/book/?page=1&author=Dick');
        // Check status
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $data = json_decode($client->getResponse()->getContent());
        // Check contain data - books
        $this->assertObjectHasAttribute('books', $data->result);
        // Check without error
        $this->assertObjectNotHasAttribute('error', $data->result);
        // Check total Result
        $this->assertObjectHasAttribute('totalResult', $data->result);
        // Check total Pages
        $this->assertObjectHasAttribute('totalResult', $data->result);
    }

    public function testGetBooksWithCorrectParameterReview()
    {
        $client = static::createClient();
        $client->request('GET', '/api/book/?page=1&review=1');
        // Check status
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $data = json_decode($client->getResponse()->getContent());
        // Check contain data - books
        $this->assertObjectHasAttribute('books', $data->result);
        // Check without error
        $this->assertObjectNotHasAttribute('error', $data->result);
        // Check total Result
        $this->assertObjectHasAttribute('totalResult', $data->result);
        // Check total Pages
        $this->assertObjectHasAttribute('totalResult', $data->result);
    }

    public function testGetBooksWithIncorrectParameterReview()
    {
        $client = static::createClient();
        $client->request('GET', '/api/book/?review=asd');
        // Check status
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $data = json_decode($client->getResponse()->getContent());
        // Check contain data - books
        $this->assertObjectNotHasAttribute('books', $data->result);
        // Check without error
        $this->assertObjectHasAttribute('error', $data->result);
        // Check total Result
        $this->assertObjectNotHasAttribute('totalResult', $data->result);
        // Check total Pages
        $this->assertObjectNotHasAttribute('totalResult', $data->result);
    }


//    public function testGetBooksWithAllCorrectParameters()
//    {
//        $client = static::createClient();
//        $client->request('GET', '/api/book/?page=1&query=Tom&review=1');
//         Check status
//        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        // Check contain data - books
//        $this->assertContains('books', $client->getResponse()->getContent());
        // Check without error
//        $this->assertNotContains('error', $client->getResponse()->getContent());
//        // Check total Result
//        $this->assertContains('totalResult', $client->getResponse()->getContent());
//        // Check total Pages
//        $this->assertContains('totalPages', $client->getResponse()->getContent());
//    }



}