<?php

/* disconnected/modal/signUp.html.twig */
class __TwigTemplate_bc3e0e589cc474832e9f429aaf94328b71c25c1e73aab9b10ccb0481dc8078d9 extends Twig_Template
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
        echo "<div class=\"opacityBg\">
    <div class=\"choosePro\">
        <div id=\"modal\">
            <div class=\"allPopUp\">
                <div class=\"PopUpSignUp\">
                    <div class=\"imgCross\">
                        <a id=\"close\"><img alt=\"cross\" src=\"http://www27.atpages.jp/tasuku/work/paint_tool/img/batu.png\"></a>
                    </div>
                    <p>vous n'êtes pas encore un des nôtres</p>
                </div>
                <div class=\"formChoosePro\">
                    <div>
                        <input type=\"submit\" value=\"s'inscrire en tant qu'utilisateur\" class=\"pwdUn\" id=\"victimSignUp\">
                    </div>
                    <div>
                        <input type=\"submit\" value=\"s'inscrire en tant que professionnel\" class=\"pwdTwo\" id=\"proSignUp\">
                    </div>
                </div>
            </div>
            <div class=\"memberSignIn\">
                <p>Déjà membre ? <a href=\"signIn.html\" id=\"signInModal\"><span>Connectez vous.</span></a></p>
            </div>
        </div>
    </div>
</div>";
    }

    public function getTemplateName()
    {
        return "disconnected/modal/signUp.html.twig";
    }

    public function getDebugInfo()
    {
        return array (  19 => 1,);
    }

    public function getSourceContext()
    {
        return new Twig_Source("", "disconnected/modal/signUp.html.twig", "C:\\wamp\\www\\projetTransversal\\projetTransversal\\views\\disconnected\\modal\\signUp.html.twig");
    }
}
