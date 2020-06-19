<?php

namespace App\Controller\Admin;


use App\Entity\Branch;
use App\Entity\Picture;
use App\Form\PictureType;
use App\Repository\PictureRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class PictureController
 * @package App\Controller
 * @Route("/admin")
 */
class PictureController extends AbstractController
{
    const CURRENT_MENU = 'admin.pictures';
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    /**
     * @var TranslatorInterface
     */
    private $translator;
    /**
     * @var PictureRepository
     */
    private $pictureRepository;

    /**
     * PictureController constructor.
     * @param EntityManagerInterface $entityManager
     * @param TranslatorInterface $translator
     * @param PictureRepository $pictureRepository
     */
    public function __construct(EntityManagerInterface $entityManager, TranslatorInterface $translator, PictureRepository $pictureRepository)
    {
        $this->entityManager = $entityManager;
        $this->translator = $translator;
        $this->pictureRepository = $pictureRepository;
    }

    /**
     * @Route("/photos", name="admin.pictures.index")
     * @return Response
     */
    public function index(): Response
    {
        return $this->render('picture/index.html.twig', [
            'currentMenu' => self::CURRENT_MENU,
            'pictures' => $this->pictureRepository->findAll()
        ]);
    }

    /**
     * @Route("/photos/create", name="admin.pictures.create", methods="GET")
     * @return Response
     */
    public function create(): Response
    {
        $picture = new Picture();
        $form = $this->createForm(PictureType::class, $picture);

        $layout = $this->render('picture/layouts/_create.html.twig',[
            'form' => $form->createView()
        ])->getContent();

        $response = new Response(json_encode($layout));
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    /**
     * @Route("/photos/store", name="admin.pictures.store", methods="POST")
     * @param Request $request
     * @return Response
     */
    public function store(Request $request): Response
    {
        $picture = new Picture();
        $form = $this->createForm(PictureType::class, $picture, ['method' => 'POST']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($picture);
            $this->entityManager->flush();
            $this->addFlash('success', $this->translator->trans('admin.picture.store'));
            return $this->redirectToRoute('admin.pictures.index');
        }

        $this->addFlash('danger', $this->translator->trans('store.error'));
        return $this->redirectToRoute('admin.pictures.index');
    }

    /**
     * @Route("/photos/{id}/edit", name="admin.pictures.edit", methods="GET")
     * @param Picture $picture
     * @return Response
     */
    public function edit(Picture $picture): Response
    {
        $form = $this->createForm(PictureType::class, $picture);

        $layout = $this->render('picture/layouts/_edit.html.twig',[
            'form' => $form->createView(),
            'picture' => $picture
        ])->getContent();

        $response = new Response(json_encode($layout));
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    /**
     * @Route("/photos/{id}/update", name="admin.pictures.update", methods="PUT")
     * @param Request $request
     * @param Picture $picture
     * @return Response
     */
    public function update(Request $request, Picture $picture): Response
    {
        $form = $this->createForm(PictureType::class, $picture, ['method' => 'PUT']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();
            $this->addFlash('success', $this->translator->trans('admin.picture.update'));
            return $this->redirectToRoute('admin.pictures.index');
        }

        $this->addFlash('danger', $this->translator->trans('store.error'));
        return $this->redirectToRoute('admin.pictures.index');
    }

    /**
     * @Route("/photos/{id}", name="admin.pictures.delete", methods="GET|DELETE")
     * @param Request $request
     * @param Picture $picture
     * @return Response
     */
    public function delete(Request $request, Picture $picture): Response
    {
        if ($request->isMethod('GET')) {
            $layout = $this->render('picture/layouts/_delete.html.twig',[
                'picture' => $picture
            ])->getContent();

            $response = new Response(json_encode($layout));
            $response->headers->set('Content-Type', 'application/json');

            return $response;
        }

        if ($this->isCsrfTokenValid('delete' . $picture->getId(), $request->get('_token'))) {
            $this->entityManager->remove($picture);
            $this->entityManager->flush();
            $this->addFlash('success', $this->translator->trans('admin.picture.delete'));
        }

        return $this->redirectToRoute('admin.pictures.index');
    }
}
