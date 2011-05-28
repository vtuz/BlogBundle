<?php

namespace Application\PortfolioBundle\Tests\Controller;

use Liip\FunctionalTestBundle\Test\WebTestCase;

class PostControllerTest extends WebTestCase
{
    public function testEmptyPostsList()
    {
        $this->loadFixtures(array());
        $crawler = $this->fetchCrawler($this->getUrl('blog_post_index', array()), 'GET', true, true);

        // check display notice
        $this->assertEquals(1, $crawler->filter('html:contains("List of posts is empty")')->count());
        // check don't display categories
        $this->assertEquals(0, $crawler->filter('ul li:contains("My first post")')->count());
    }

    public function testCreateNewPost()
    {
        $this->loadFixtures(array());
        $client = $this->makeClient(true);
        $crawler = $client->request('GET', $this->getUrl('blog_post_create', array()));

        $form = $crawler->selectButton('Send')->form();

        $form['post[title]'] = 'Post title';
        $form['post[slug]'] = 'post-slug';
        $form['post[text]'] = 'Post text';
        $crawler = $client->submit($form);

        // check redirect to list of post
        $this->assertTrue($client->getResponse()->isRedirect());
        $this->assertTrue($client->getResponse()->isRedirected($this->getUrl('blog_post_index', array())));

        $crawler = $client->followRedirect();

        // check responce
        $this->assertTrue($client->getResponse()->isSuccessful());
        $this->assertFalse($client->getResponse()->isRedirect());

        // check display new category in list
        $this->assertEquals(1, $crawler->filter('ul li:contains("Post title")')->count());
    }

    public function testPostList()
    {
        $this->loadFixtures(array('Stfalcon\Bundle\BlogBundle\DataFixtures\ORM\LoadPostData'));
        $crawler = $this->fetchCrawler($this->getUrl('blog_post_index', array()), 'GET', true, true);

        // check display posts list
        $this->assertEquals(1, $crawler->filter('ul li:contains("My first post")')->count());
    }

    public function testViewPost()
    {
        $this->loadFixtures(array('Stfalcon\Bundle\BlogBundle\DataFixtures\ORM\LoadPostData'));
        $crawler = $this->fetchCrawler($this->getUrl('blog_post_view', array('slug' => 'my-first-post')), 'GET', true, true);

        // check display post
        $this->assertEquals(1, $crawler->filter('h1:contains("My first post")')->count());
        $this->assertEquals(1, $crawler->filter('p:contains("In work we use Symfony2.")')->count());
    }

    public function testEditPost()
    {
        $this->loadFixtures(array('Stfalcon\Bundle\BlogBundle\DataFixtures\ORM\LoadPostData'));
        $client = $this->makeClient(true);
        $crawler = $client->request('GET', $this->getUrl('blog_post_edit', array('slug' => 'my-first-post')));

        $form = $crawler->selectButton('Save')->form();

        $form['post[title]'] = 'New post title';
        $form['post[slug]'] = 'new-post-slug';
        $form['post[text]'] = 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua..';
        $crawler = $client->submit($form);

        // check redirect to list of categories
        $this->assertTrue($client->getResponse()->isRedirect());
        $this->assertTrue($client->getResponse()->isRedirected($this->getUrl('blog_post_index', array())));

        $crawler = $client->followRedirect();

        // check responce
        $this->assertTrue($client->getResponse()->isSuccessful());
        $this->assertFalse($client->getResponse()->isRedirect());

        $this->assertEquals(1, $crawler->filter('ul li:contains("New post title")')->count());

    }
}