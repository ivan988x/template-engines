<?php

namespace ExampleApp\Controller;

use Psr\Http\Message\ResponseInterface;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use Twig\Loader\FilesystemLoader;

class BaseController
{
    protected $response;

    public function __construct(
        ResponseInterface $response
    )
    {
        $this->response = $response->withHeader('Content-Type', 'text/html');
    }

    protected function render(string $template, array $data = [])
    {
        $loader = new FilesystemLoader([$GLOBALS['viewDir'], $GLOBALS['layoutDir']]);
        $twig = new Environment($loader);

        try {
            $this->response->getBody()->write(
                $twig->render($template, $data)
            );
        } catch (LoaderError $e) {
            $this->response->getBody()->write($e->getMessage());
        } catch (RuntimeError $e) {
            $this->response->getBody()->write($e->getMessage());
        } catch (SyntaxError $e) {
            $this->response->getBody()->write($e->getMessage());
        }

        return $this->response;
    }
}