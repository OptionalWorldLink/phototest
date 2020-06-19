<?php

namespace App\Controller\Admin;


use App\Entity\Branch;
use App\Form\BranchType;
use App\Repository\BranchRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class BranchController
 * @package App\Controller\Admin
 * @Route("/admin")
 */
class BranchController extends AbstractController
{
    const CURRENT_MENU = 'admin.branches';
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    /**
     * @var TranslatorInterface
     */
    private $translator;
    /**
     * @var BranchRepository
     */
    private $branchRepository;

    /**
     * BranchController constructor.
     * @param EntityManagerInterface $entityManager
     * @param TranslatorInterface $translator
     * @param BranchRepository $branchRepository
     */
    public function __construct(EntityManagerInterface $entityManager, TranslatorInterface $translator, BranchRepository $branchRepository)
    {
        $this->entityManager = $entityManager;
        $this->translator = $translator;
        $this->branchRepository = $branchRepository;
    }

    /**
     * @Route("/branches", name="admin.branches.index")
     * @return Response
     */
    public function index(): Response
    {
        return $this->render('branch/index.html.twig', [
            'currentMenu' => self::CURRENT_MENU,
            'branches' => $this->branchRepository->findAll()
        ]);
    }

    /**
     * @Route("/branches/create", name="admin.branches.create", methods="GET")
     * @return Response
     */
    public function create(): Response
    {
        $branch = new Branch();
        $form = $this->createForm(BranchType::class, $branch);

        $layout = $this->render('branch/layouts/_create.html.twig',[
            'form' => $form->createView()
        ])->getContent();

        $response = new Response(json_encode($layout));
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    /**
     * @Route("/branches/store", name="admin.branches.store", methods="POST")
     * @param Request $request
     * @return Response
     */
    public function store(Request $request): Response
    {
        $branch = new Branch();
        $form = $this->createForm(BranchType::class, $branch, ['method' => 'POST']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($branch);
            $this->entityManager->flush();
            $this->addFlash('success', $this->translator->trans('admin.branch.store'));
            return $this->redirectToRoute('admin.branches.index');
        }

        $this->addFlash('danger', $this->translator->trans('store.error'));
        return $this->redirectToRoute('admin.branches.index');
    }

    /**
     * @Route("/branches/{id}/edit", name="admin.branches.edit", methods="GET")
     * @param Branch $branch
     * @return Response
     */
    public function edit(Branch $branch): Response
    {
        $form = $this->createForm(BranchType::class, $branch);

        $layout = $this->render('branch/layouts/_edit.html.twig',[
            'form' => $form->createView(),
            'branch' => $branch
        ])->getContent();

        $response = new Response(json_encode($layout));
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    /**
     * @Route("/branches/{id}/update", name="admin.branches.update", methods="PUT")
     * @param Request $request
     * @param Branch $branch
     * @return Response
     */
    public function update(Request $request, Branch $branch): Response
    {
        $form = $this->createForm(BranchType::class, $branch, ['method' => 'PUT']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();
            $this->addFlash('success', $this->translator->trans('admin.branch.update'));
            return $this->redirectToRoute('admin.branches.index');
        }

        $this->addFlash('danger', $this->translator->trans('store.error'));
        return $this->redirectToRoute('admin.branches.index');
    }

    /**
     * @Route("/branches/{id}", name="admin.branches.delete", methods="GET|DELETE")
     * @param Request $request
     * @param Branch $branch
     * @return Response
     */
    public function delete(Request $request, Branch $branch): Response
    {
        if ($request->isMethod('GET')) {
            $layout = $this->render('branch/layouts/_delete.html.twig',[
                'branch' => $branch
            ])->getContent();

            $response = new Response(json_encode($layout));
            $response->headers->set('Content-Type', 'application/json');

            return $response;
        }

        if ($this->isCsrfTokenValid('delete' . $branch->getId(), $request->get('_token'))) {
            $this->entityManager->remove($branch);
            $this->entityManager->flush();
            $this->addFlash('success', $this->translator->trans('admin.branch.delete'));
        }

        return $this->redirectToRoute('admin.branches.index');
    }
}
