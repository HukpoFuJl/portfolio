<?php

namespace Engine\BlogBundle\Controller;

use Doctrine\ORM\EntityManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\BrowserKit\Response;
use Symfony\Component\Finder\SplFileInfo;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/blog")
 */
class BlogController extends Controller
{
    /**
     * @Route("/", name="blog_index", defaults={"title":"Blog"})
     */
    public function indexAction()
    {

    }

    /**
     * @Route("/all", name="blog_all", defaults={"title":"Blog"})
     */
    public function allBlogAction()
    {

    }

    /**
     * @Route("/{id}", name="news_view", defaults={"title":"Single News"})
     * @param int|null $id
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function itemAction($id = null)
    {

    }

}