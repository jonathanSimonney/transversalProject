<?php

/* disconnected/header.html.twig */
class __TwigTemplate_d05702b0c48b95c39194633b6a766fab4436301bde68881a0b15b9eab515c57d extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = false;

        $this->blocks = array(
        );
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        // line 1
        echo "<header>
    <div class=\"topContainer\">
        <div class=\"topHeader\">
            <div class=\"logoTitle\">
                <div><img src=\"web/style/img/logoBullE.png\" alt=\"logo Bull-E\"></div>
                <a href=\"?action=home\"><h1>Bull-E</h1></a>
            </div>
            <div class=\"supportUsBtn\">
                <input type=\"submit\" value=\"nous soutenir\" class=\"suppBtn\">
            </div>
            <div class=\"signInUp\">
                <form action=\"\" class=\"formLogIn\">
                    <input type=\"submit\" value=\"s'inscrire\" class=\"signUp\" id=\"signUp\">
                    <input type=\"submit\" value=\"se connecter\" class=\"signIn\" id=\"signIn\">
                </form>
            </div>
        </div>
    </div>
    <nav class=\"navigation\">
        <ul class=\"menu\">
            <li><a href=\"?action=about\">à propos</a></li>
            <li><a href=\"?action=contact\">contact</a></li>
            <li><a href=\"?action=forum\">forum</a></li>
            <li class=\"documentation\">
                <a href=\"?action=documentation\">documentation</a>
                <ul>
                    <li><a href=\"?action=documentation&article=what\">Qu'est-ce que le cyber-bullying ?</a></li>
                    <li><a href=\"?action=documentation&article=gesture\">Les bons gestes</a></li>
                    <li><a href=\"?action=documentation&article=who\">Quels recours</a></li>
                    <li><a href=\"?action=documentation&article=ressources\">Ressources externes</a></li>
                </ul>
            </li>
        </ul>
    </nav>
</header>
";
    }

    public function getTemplateName()
    {
        return "disconnected/header.html.twig";
    }

    public function getDebugInfo()
    {
        return array (  19 => 1,);
    }

    public function getSourceContext()
    {
        return new Twig_Source("", "disconnected/header.html.twig", "C:\\wamp\\www\\projetTransversal\\projetTransversal\\views\\disconnected\\header.html.twig");
    }
}
