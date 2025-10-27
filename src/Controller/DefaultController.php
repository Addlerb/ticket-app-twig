<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    #[Route('/', name: 'landing')]
    public function landing(): Response
    {
        return $this->render('landing/index.html.twig');
    }

    #[Route('/auth/login', name: 'login')]
    public function login(Request $request): Response
    {
        $validEmail = 'addlerb@hng.com';
        $validPassword = 'password123';

        if ($request->isMethod('POST')) {
            $email = $request->request->get('email');
            $password = $request->request->get('password');

            if ($email === $validEmail && $password === $validPassword) {
                return $this->redirectToRoute('dashboard');
            } else {
                return $this->render('auth/login.html.twig', [
                    'error' => 'Invalid email or password.',
                ]);
            }
        }

        return $this->render('auth/login.html.twig');
    }

    #[Route('/auth/signup', name: 'signup')]
    public function signup(Request $request): Response
    {
        if ($request->isMethod('POST')) {
            $email = $request->request->get('email');
            $password = $request->request->get('password');

            if (!empty($email) && !empty($password)) {
                return $this->redirectToRoute('login');
            } else {
                return $this->render('auth/signup.html.twig', [
                    'error' => 'Email and password are required.',
                ]);
            }
        }

        return $this->render('auth/signup.html.twig');
    }

    #[Route('/dashboard', name: 'dashboard')]
    public function dashboard(Request $request, SessionInterface $session): Response
    {
        // Initialize or retrieve tickets from session
        $tickets = $session->get('tickets', [
            ['id' => 1, 'title' => 'Server Down', 'description' => 'Server crashed at 2 AM', 'status' => 'Open'],
            ['id' => 2, 'title' => 'UI Bug', 'description' => 'Button not clickable', 'status' => 'In Progress'],
        ]);

        if ($request->isMethod('POST')) {
            $title = $request->request->get('title');
            $description = $request->request->get('description');

            if (!empty($title) && !empty($description)) {
                $newTicket = [
                    'id' => count($tickets) + 1,
                    'title' => $title,
                    'description' => $description,
                    'status' => 'Open',
                ];
                $tickets[] = $newTicket;
                $session->set('tickets', $tickets);
            }
        }

        return $this->render('dashboard/index.html.twig', [
            'message' => 'Welcome to your Ticket Master Dashboard!',
            'tickets' => $tickets,
        ]);
    }

    #[Route('/logout', name: 'logout')]
    public function logout(SessionInterface $session): Response
    {
        $session->invalidate();
        return $this->redirectToRoute('landing');
    }
}
