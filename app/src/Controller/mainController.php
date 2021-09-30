<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class mainController extends AbstractController
{
    public function __construct()
    {
    }

    #[Route('/main', name: 'main', methods: ['GET', 'POST'])]
    public function main()
    {
        dd('main controller here...');
    }

}