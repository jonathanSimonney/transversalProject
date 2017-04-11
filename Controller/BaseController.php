<?php

namespace Controller;

class BaseController
{
    private $twig;

    public function __construct(\Twig_Environment $twig)
    {
        $this->twig = $twig;
    }
    
    protected function getTwig()
    {
        return $this->twig;
    }

    protected function renderView($template, array $data = []){
        $template = $this->getTwig()->load($template);
        return $template->render($data);
    }

    protected function redirect($route){
        header('Location: ?action='.$route);
        exit();
    }
}
