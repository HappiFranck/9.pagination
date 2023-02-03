<?php
namespace Framework\Twig;

use Framework\Router;
use Pagerfanta\Pagerfanta;
use Pagerfanta\View\TwitterBootstrap4View;

class PagerFantaExtension extends \Twig_Extension
{

    /**
     * @var Router
     */
    private $router;

    public function __construct(Router $router)
    {
        $this->router = $router;
    }
    /** Renvoit le nombre de fonction
     *
     * @return array \Twig_SimpleFunction :
     *  @param 'paginate' la nom de la fonction qui va etre utiliser dans index.twig
     * @param  [$this, 'paginate'] : représente la fonction de rappel
     * @param  array ['is_safe' => ['html'] : Représente les caractères de sortie HTML de la methode paginate()
     */
    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('paginate', [$this, 'paginate'], ['is_safe' => ['html']])
        ];
    }

    /** extension twig
     * @param Pagerfanta $paginatedResults: une instance de Pagerfanta
     * @param string $route : représent le nom de la route
     * @return string : qui représente du code HTML
     * @param array $queryArgs :
     */

    public function paginate(Pagerfanta $paginatedResults, string $route, array $queryArgs = []): string
    {
        $view = new TwitterBootstrap4View();
        return $view->render(
            $paginatedResults,
            //C'est une fonction qui prend en parametre la page et retourne la bonne route
            function (int $page) use ($route, $queryArgs) {
                if ($page > 1) {
                    $queryArgs['p'] = $page;
                }
                return $this->router->generateUri($route, [], $queryArgs);
            }
        );
    }
}
