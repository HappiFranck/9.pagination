<?php
namespace App\Blog\Actions;

use Framework\Router;
use App\Blog\Table\PostTable;
use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Framework\Actions\RouterAwareAction;
use Framework\Renderer\RendererInterface;
use Psr\Http\Message\ServerRequestInterface as Request;

class BlogAction
{

    /**
     * @var RendererInterface
     */
    private $renderer;

    /**
     * @var Router
     */
    private $router;
    /**
     * @var PostTable
     */
    private $postTable;

    use RouterAwareAction;

    public function __construct(RendererInterface $renderer, Router $router, PostTable $postTable)
    {
        $this->renderer = $renderer;
        $this->router = $router;
        $this->postTable = $postTable;
    }

    public function __invoke(Request $request)
    {
        if ($request->getAttribute('id')) {
            return $this->show($request);
        }
        return $this->index($request);
    }

    /**
     *
     */

    public function index(Request $request): string
    {
        //On recupere les parametres de la requete
        $params = $request->getQueryParams();
        //12 représente le nombre d'enregistrement à utiliser
        // $params['p'] ?? 1 : on recupere la clé de pagination qui représente le numéro de page
        // visible dans l'URL par la lettre p si il est vide par defaut sa val est 1
        $posts = $this->postTable->findPaginated(12, $params['p'] ?? 1);

        return $this->renderer->render('@blog/index', compact('posts'));
    }

    /**
     * Affiche un article
     *
     * @param Request $request
     * @return ResponseInterface|string
     */
    public function show(Request $request)
    {
        $slug = $request->getAttribute('slug');
        $post = $this->postTable->find($request->getAttribute('id'));
        if ($post->slug !== $slug) {
            return $this->redirect('blog.show', [
                'slug' => $post->slug,
                'id' => $post->id
            ]);
        }
        return $this->renderer->render('@blog/show', [
            'post' => $post
        ]);
    }
}
