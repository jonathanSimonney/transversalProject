<?php

/* both/home.html.twig */
class __TwigTemplate_ecb589d0bb308b00b796dff83a615829c90880e2e049890a6c30f3a4b92fbd4e extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        // line 1
        $this->parent = $this->loadTemplate("layout.html.twig", "both/home.html.twig", 1);
        $this->blocks = array(
            'content' => array($this, 'block_content'),
        );
    }

    protected function doGetParent(array $context)
    {
        return "layout.html.twig";
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        $this->parent->display($context, array_merge($this->blocks, $blocks));
    }

    // line 3
    public function block_content($context, array $blocks = array())
    {
        // line 4
        echo "<div class=\"homeBg\">
    <div class=\"homeContainer\">
        <div class=\"home\">
            <div class=\"homeImg\">
                <img src=\"web/style/img/home.png\" alt=\"home image\">
            </div>
            <div class=\"homeContent\">
                <h2>Titre d'accroche</h2>
                <div class=\"homeUnderline\"></div>
                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.
                    Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
            </div>
        </div>
        <div class=\"homeContact\">
            <a href=\"?action=proList\"><input type=\"submit\" value=\"voir la liste des professionnels\" class=\"proBtn\"></a>
            <a href=\"?action=contact\"><input type=\"submit\" value=\"nous contacter\" class=\"contactBtn\"></a>
        </div>
    </div>
</div>

<div class=\"forumBg\">
    <div class=\"homeContainer\">
        <div class=\"forumTitle\">
            <h2>derniers messages du forum</h2>
            <div class=\"forumUnderline\"></div>
        </div>
        <div class=\"forumMain\">
            <div class=\"forumContent\">
                <div class=\"forumMsg\">
                    <h3>Titre du topic test titre long lorem ipsum</h3>
                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip...</p>
                    <span>posté par <span class=\"username\">Michel21</span> à <span class=\"date\">22H10</span> le <span class=\"date\">12/02/2017</span></span>
                </div>
            </div>
            <div class=\"forumContent\">
                <div class=\"forumMsg\">
                    <h3>Titre du topic test titre long lorem ipsum</h3>
                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip...</p>
                    <span>posté par <span class=\"username\">Michel21</span> à <span class=\"date\">22H10</span> le <span class=\"date\">12/02/2017</span></span>
                </div>
            </div>
            <div class=\"forumContent\">
                <div class=\"forumMsg\">
                    <h3>Titre du topic test titre long lorem ipsum</h3>
                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip...</p>
                    <span>posté par <span class=\"username\">Michel21</span> à <span class=\"date\">22H10</span> le <span class=\"date\">12/02/2017</span></span>
                </div>
            </div>
        </div>
    </div>
</div>

<div class=\"supportUsBg\">
    <div class=\"homeContainer\">
        <div class=\"supportUs\">
            <h3>Comment nous aider ?</h3>
            <div class=\"supportUsContent\">
                <div class=\"particular\">
                    <h4>Vous êtes un particulier</h4>
                    <p>Soutenez Bull-E en faisant un don !</p>
                    <input type=\"submit\" value=\"faites un don\" class=\"donationBtn supportBtn\">
                </div>
                <div class=\"horizontalLine\"></div>
                <div class=\"pro\">
                    <h4>Vous êtes une entreprise ou une organisation</h4>
                    <p>Collaborez avec nous !</p>
                    <input type=\"submit\" value=\"devenez partenaire\" class=\"partnerBtn supportBtn\">
                </div>
            </div>
        </div>
    </div>
</div>

<div class=\"ourPartnersBg forumBg\">
    <div class=\"partnersContainer\">
        <div class=\"partnersTitle\">
            <h3>Nos Partenaires</h3>
            <div class=\"partnersUnderline\"></div>
        </div>
        <div class=\"partnersLinks\">
            <div class=\"partners\"></div>
            <div class=\"partners\"></div>
            <div class=\"partners\"></div>
            <div class=\"partners\"></div>
            <div class=\"partners\"></div>
            <div class=\"partners\"></div>
            <div class=\"partners\"></div>
            <div class=\"partners\"></div>
        </div>
    </div>
</div>
";
    }

    public function getTemplateName()
    {
        return "both/home.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  31 => 4,  28 => 3,  11 => 1,);
    }

    public function getSourceContext()
    {
        return new Twig_Source("", "both/home.html.twig", "C:\\wamp\\www\\projetTransversal\\projetTransversal\\views\\both\\home.html.twig");
    }
}
