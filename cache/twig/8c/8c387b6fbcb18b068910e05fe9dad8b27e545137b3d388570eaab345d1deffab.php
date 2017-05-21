<?php

/* layout.html.twig */
class __TwigTemplate_8e64a8628893e76ace2a0746dac188d9c26a2f549d8bb22f686d35c223133c05 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = false;

        $this->blocks = array(
            'content' => array($this, 'block_content'),
        );
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        // line 1
        echo "<!DOCTYPE html>
<html lang=\"en\">
<head>
    <meta charset=\"UTF-8\">
    <meta name=\"author\" content=\"Lucie Eliott Jonathan\">
    <title>Bull-E</title>
    <script src=\"node_modules/jquery/dist/jquery.min.js\"></script>
    <script src=\"web/JS/whole.js\"></script>
    <link rel=\"stylesheet\" href=\"web/style/style.css\">
</head>
<body>

";
        // line 13
        if ((($context["loggedIn"] ?? null) == false)) {
            // line 14
            echo "    ";
            $this->loadTemplate("disconnected/header.html.twig", "layout.html.twig", 14)->display($context);
        } elseif ((twig_get_attribute($this->env, $this->getSourceContext(),         // line 15
($context["currentUser"] ?? null), "type", array(), "array") == "victime")) {
            // line 16
            echo "    ";
            $this->loadTemplate("connected/victime/header.html.twig", "layout.html.twig", 16)->display($context);
        } else {
            // line 18
            echo "    ";
            $this->loadTemplate("connected/pro/header.html.twig", "layout.html.twig", 18)->display($context);
        }
        // line 20
        echo "
<div id=\"modalWaiter\"></div>
";
        // line 22
        $this->displayBlock('content', $context, $blocks);
        // line 23
        echo "
<footer>
    <div class=\"footerContainer\">
        <div class=\"links\">
            <ul class=linksList>
                <li><a href=\"?action=home\">index</a></li>
                <li><a href=\"?action=about\">à propos</a></li>
                <li><a href=\"?action=forum\">forum</a></li>
                <li><a href=\"?action=proList\">liste des professionnels</a></li>
                <li><a href=\"#\">nous soutenir</a></li>
                <li><a href=\"?action=legalNotice\">mentions légales</a></li>
                <li><a href=\"?action=documentation&article=what\">qu'est-ce que le cyber-bullying</a></li>
                <li><a href=\"?action=documentation&article=gesture\">les bons gestes</a></li>
                <li><a href=\"?action=documentation&article=who\">quels recours</a></li>
                <li><a href=\"?action=documentation&article=ressources\">ressources externes</a></li>
            </ul>
        </div>
        <div class=\"logoRights\">
            <div class=\"logo\">
                <a href=\"#\"><img src=\"web/style/img/logoTwitter.png\" alt=\"logo twitter\" class=\"twitter\"></a>
                <a href=\"#\"><img src=\"web/style/img/logoFb.png\" alt=\"logo facebook\" class=\"facebook\"></a>
            </div>
            <span class=\"copyrights\">@ Bull-E 2017 / Tous droits réservés</span>
        </div>
    </div>
</footer>

<!--
<div id=\"debug\">
    <pre>
        ";
        // line 54
        echo "    </pre>
</div>
-->

";
        // line 58
        if ((($context["loggedIn"] ?? null) == false)) {
            // line 59
            echo "    <script src=\"web/JS/disconnected/whole.js\"></script>
";
        } elseif ((twig_get_attribute($this->env, $this->getSourceContext(),         // line 60
($context["currentUser"] ?? null), "type", array(), "array") == "victime")) {
            // line 61
            echo "    <script src=\"web/JS/connected/victime.js\"></script>
    <script src=\"web/JS/connected/whole.js\"></script>
";
        } else {
            // line 64
            echo "    <script src=\"web/JS/connected/pro.js\"></script>
    <script src=\"web/JS/connected/whole.js\"></script>
";
        }
        // line 67
        echo "</body>
</html>
";
    }

    // line 22
    public function block_content($context, array $blocks = array())
    {
        echo " ";
    }

    public function getTemplateName()
    {
        return "layout.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  116 => 22,  110 => 67,  105 => 64,  100 => 61,  98 => 60,  95 => 59,  93 => 58,  87 => 54,  55 => 23,  53 => 22,  49 => 20,  45 => 18,  41 => 16,  39 => 15,  36 => 14,  34 => 13,  20 => 1,);
    }

    public function getSourceContext()
    {
        return new Twig_Source("", "layout.html.twig", "C:\\wamp\\www\\projetTransversal\\projetTransversal\\views\\layout.html.twig");
    }
}
