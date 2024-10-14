<?php
/**
 * Created by PhpStorm.
 * User: micaelomota
 * Date: 06/11/18
 * Time: 16:00
 */

namespace App\Component;


use Cake\Core\Configure;
use Cake\Error\Middleware\ErrorHandlerMiddleware;
use Cake\Log\Log;
use Cake\Mailer\Email;

class AgendaErrorHandlerMiddleware extends ErrorHandlerMiddleware
{

    /**
     * Handle an exception and generate an error response
     *
     * @param \Exception $exception The exception to handle.
     * @param \Psr\Http\Message\ServerRequestInterface $request The request.
     * @param \Psr\Http\Message\ResponseInterface $response The response.
     * @return \Psr\Http\Message\ResponseInterface A response
     * @throws \Exception
     */
    public function handleException($exception, $request, $response)
    {
        $renderer = $this->getRenderer($exception);
        try {
            $res = $renderer->render();
            $this->logException($request, $exception);
            if ($res->getStatusCode() >= 500) {
                $this->sendEmail($exception, $request);
            }
            return $res;
        } catch (\Exception $e) {
            $this->logException($request, $e);
            $this->sendEmail($e, $request);
            $body = $response->getBody();
            $body->write('An Internal Server Error Occurred');
            $response = $response->withStatus(500)
                ->withBody($body);
        }

        return $response;
    }

    private function sendEmail($exception, $request) {
        $debug = Configure::read('debug');
        if (!$debug) {
            $email = new Email();
            $email->template('error');
            $email->emailFormat('html');
            $email->to("micael.mota@defensoria.ba.def.br");
            $email->addTo("analistas.dev@defensoria.ba.def.br");
            $url = $request->getRequestTarget();
            $email->subject("[AGENDAMENTO ONLINE] - Erro em $url");
            $email->viewVars(['error' => $this->getEmailMessage($request, $exception)]);
            $email->send();
        }
    }


    private function getEmailMessage($request, $exception)
    {
        $message = sprintf(
            '[%s] %s',
            get_class($exception),
            $exception->getMessage()
        );

        if ($exception instanceof CakeException) {
            $attributes = $exception->getAttributes();
            if ($attributes) {
                $message .= "\nException Attributes: " . var_export($exception->getAttributes(), true);
            }
        }
        $message .= "\nRequest URL: " . $request->getRequestTarget();
        $referer = $request->getHeaderLine('Referer');
        if ($referer) {
            $message .= "\nReferer URL: " . $referer;
        }
        if ($this->getConfig('trace')) {
            $message .= "\nStack Trace:\n" . $exception->getTraceAsString() . "\n\n";
        }

        return $message;
    }
}