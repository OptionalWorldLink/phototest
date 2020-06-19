<?php
namespace App\Controller;

use App\Repository\PictureRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @var PictureRepository
     */
    private $pictureRepository;

    /**
     * HomeController constructor.
     * @param PictureRepository $pictureRepository
     */
    public function __construct(PictureRepository $pictureRepository)
    {
        $this->pictureRepository = $pictureRepository;
    }

    /**
     * @Route("/", name="home")
     */
    public function index(): Response
    {
        return $this->render("index.html.twig", [
            'pictures' => $this->pictureRepository->findAll()
        ]);
    }
}
