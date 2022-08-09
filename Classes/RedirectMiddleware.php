<?php
declare(strict_types=1);

namespace Flownative\RuleBasedRedirects;

use Neos\Flow\Annotations as Flow;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * A simple HTTP middleware to apply rule-based redirects
 */
final class RedirectMiddleware implements MiddlewareInterface
{
    /**
     * @Flow\Inject
     * @var ResponseFactoryInterface
     */
    protected $responseFactory;

    /**
     * @Flow\InjectConfiguration
     * @var array
     */
    protected array $settings = ['rules' => []];

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        return $this->buildResponse($request) ?? $handler->handle($request);
    }

    private function buildResponse(ServerRequestInterface $request): ?ResponseInterface
    {
        if (FLOW_SAPITYPE !== 'CLI' && headers_sent() === true) {
            return null;
        }

        $requestHost = $request->getUri()->getHost();
        $requestPath = $request->getUri()->getPath();

        $response = null;

        foreach ($this->settings['rules'] as $rule) {
            if (preg_match($rule['host'], $requestHost) !== 1 || preg_match($rule['path'], $requestPath) !== 1) {
                continue;
            }

            $targetUri = preg_replace($rule['path'], $rule['target'], $requestPath);

            $response = $this->responseFactory->createResponse($rule['status'])
                ->withHeader('Location', $targetUri)
                ->withHeader('Cache-Control', 'no-store, no-cache, must-revalidate, post-check=0, pre-check=0')
                ->withHeader('Expires', 'Sat, 26 Jul 1997 05:00:00 GMT');
            break;
        }

        return $response;
    }
}
