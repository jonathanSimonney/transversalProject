<?php

/* disconnected/modal/victimSignUp.html.twig */
class __TwigTemplate_d69b72d98f16645069d3fd4a05241d5eb04c27b2335ac01212f5782881f47140 extends Twig_Template
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
    <div class=\"logPage\">
        <div class=\"titleSignUp\" id=\"modal\">
            <div class=\"imgCross\">
                <a id=\"close\"><img alt=\"cross\" src=\"http://www27.atpages.jp/tasuku/work/paint_tool/img/batu.png\"></a>
            </div>
            <!-- TODO leave popUP -->
            <h2>créer un nouveau compte</h2>
            <div class=\"containerSignUp\">
                <form id=\"modalForm\">
                    <div class=\"containerInputSignUp\">
                        <div class=\"divLeftSignUp\">
                            <div class=\"inputTextSignUp\">
                                <label for=\"username\">
                                    <p>nom d'utilisateur*</p>
                                </label>
                                <input type=\"text\" placeholder=\"Entrer votre nom d'utilisateur\" name=\"pseudo\" id=\"username\">
                                <!--TODO-->
                            </div>
                            <div class=\"inputTextSignUp\">
                                <label for=\"password\">
                                    <p>mot de passe*</p>
                                </label>
                                <input type=\"password\" placeholder=\"entrer votre mot de passe\" name=\"password\" id=\"password\">
                                <!--TODO-->
                            </div>

                        </div>
                        <div class=\"divRightSingUp\">
                            <div class=\"inputTextSignUp\">
                                <p>date de naissance*</p>
                                <input type=\"date\" name=\"birthdate\" class=\"listBirthDay\">
                            </div>
                            <div class=\"inputTextSignUp\">
                                <label for=\"confirmPassword\">
                                    <p>confirmation mot de passe</p>
                                </label>
                                <input type=\"password\" placeholder=\"confirmer votre mot de passe\" name=\"confirmationOfPassword\" id=\"confirmPassword\">
                                <!--TODO -->
                            </div>
                        </div>
                    </div>

                    <div class=\"centerDivSignUp\">
                        <div>
                            <label for=\"hintPassword\">
                                <p>indication mot de passe*</p>
                            </label>
                            <input type=\"text\" placeholder=\"écrivez une indication en cas d'oubli de votre mot de passe\" name=\"indic\" id=\"hintPassword\">
                        </div>
                    </div>

                    <div class=\"emailGender\">
                        <div>
                            <label for=\"email\">
                                <p>adresse email*</p>
                            </label>
                            <input type=\"email\" placeholder=\"entrer votre adresse email\" name=\"email\" id=\"email\">
                            <!-- TODO adresse mail-->
                        </div>
                        <div class=\"inputTextSignUp\">
                            <p>genre*</p>
                            <select class=\"listProfession\" name=\"gender\">
                                <option value=\"choose\">choisissez votre genre</option>
                                <!-- TODO -->
                                <option value=\"woman\">femme</option>
                                <option value=\"man\">homme</option>
                            </select>
                            <!--TODO-->
                        </div>
                    </div>

                    <div class=\"postalCodeVictim\">
                        <div class=\"inputTextSignUp\">
                            <label for=\"location\">
                                <p>code postal</p>
                            </label>
                            <input type=\"text\" class=\"postalInput\" placeholder=\"entrez votre code postal\" name=\"location\" id=\"location\">
                            <!--TODO code postal-->
                        </div>
                        <div class=\"buttonBottom\">
                            <input class=\"inputButtonSignUp\" type=\"submit\" value=\"valider et créer mon compte\">
                            <!--TODO valide compte-->
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
";
    }

    public function getTemplateName()
    {
        return "disconnected/modal/victimSignUp.html.twig";
    }

    public function getDebugInfo()
    {
        return array (  19 => 1,);
    }

    public function getSourceContext()
    {
        return new Twig_Source("", "disconnected/modal/victimSignUp.html.twig", "C:\\wamp\\www\\projetTransversal\\projetTransversal\\views\\disconnected\\modal\\victimSignUp.html.twig");
    }
}
