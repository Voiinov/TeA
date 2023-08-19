<?php

namespace App\Controllers;

use Core\Services\Mods;
use Core\Views;

class DashboardController extends Views
{

    private $data;

    public function __construct(string $module = "dashboard")
    {
        $this->data = Mods::loadMod($module);
    }

    public function index(): void
    {
        Views::render("index", $this->data);
    }

    public function about(array $data = []): void
    {
        // Створення об'єкту View та виклик методу render()
        views::render('home', $data);
    }
}
