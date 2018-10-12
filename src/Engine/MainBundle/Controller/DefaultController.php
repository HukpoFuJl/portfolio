<?php
/**
 * Created by PhpStorm.
 * User: abirillo
 * Date: 06.09.16
 * Time: 16:40
 */

namespace Engine\MainBundle\Controller;

use Doctrine\ORM\EntityManager;
use Engine\WorksBundle\Entity\Department;
use Engine\PlansBundle\Entity\Plan;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\BrowserKit\Response;
use Symfony\Component\Finder\SplFileInfo;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Router;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="main_index", defaults={"title":"Homepage"})
     * @Template("@Main/static/index.html.twig")
     */
    public function indexAction()
    {
        return [];
    }

    /**
     * @Route("/about", name="main_about", defaults={"title":"About"})
     */
    public function aboutAction()
    {
        return $this->render('@Main/static/about.html.twig');
    }

    
    /**
     * @Route("/careers", name="main_careers", defaults={"title":"Careers"})
     */
    public function careersAction()
    {
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();

        $slug = $em->getRepository('CareerBundle:Career')
            ->findOneBy([])
            ->getSlug();

        return $this->render('@Main/static/careers.html.twig', [
            'slug' => $slug
        ]);
    }

    /**
     * @Route("/careers/browse/{slug}", name="main_careers_browse")
     */
    public function careersBrowseAction(Request $request, $slug = null)
    {
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();

        $departments = $em->getRepository('CareerBundle:Department')
            ->findAll();

        $allCareers = $em->getRepository('CareerBundle:Career')
            ->findAll();

        $currentCareer = null;

        foreach ($allCareers as $career) {
            if ($career->getSlug() == $slug) {
                $currentCareer = $career;
            }
        }

        $departmentsArray = [];

        /** @var Department $department */
        foreach ($departments as $department) {
            $numberOfCareers = 0;
            foreach ($allCareers as $career) {
                if ($career->getDepartment() == $department) { $numberOfCareers++; }
            }
            $departmentsArray[] = [
                'id' => $department->getId(),
                'title' => $department->getTitle(),
                'careersNumber' => $numberOfCareers
            ];
        }

        return $this->render('@Main/static/careersSearchResults.html.twig', [
            'slug'        => $slug,
            'career'      => $currentCareer,
            'departments' => $departmentsArray,
            'allCareers'  => $allCareers
        ]);
    }

    /**
     * @Route("/careers/print/{id}", name="main_careers_print")
     * @param $id
     */
    public function careersPrint($id = null)
    {

        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();

        $career = $em->getRepository('CareerBundle:Career')
            ->findOneBy([
                'id' => $id
            ]);

        return $this->render('@Main/static/careersPrint.html.twig', [
            'career' => $career
        ]);
    }

    

    /**
     * @Route("/sitemap", name="main_sitemap")
     */
    public function sitemapAction()
    {

        $availableApiRoutes = [];
        
        /**@var Router $router*/
        $router = $this->get('router');
        
        foreach ($router->getRouteCollection()->all() as $name => $route) {
            if( strpos($name, 'main_') === 0) {
                $emptyVars = [];
                $title = $route->getDefault('title');
                if ($title) {
                    $route = $route->compile();
                    foreach ($route->getVariables() as $v) {
                        $emptyVars[$v] = $v;
                    }
                    $url                  = $this->generateUrl($name, $emptyVars);
                    $availableApiRoutes[] = ['title' => $title, 'url' => $url];
                }
            }
        }

        return $this->render('@Main/static/sitemap.html.twig', [
            'data' => $availableApiRoutes
        ]);
    }

}
