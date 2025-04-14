<?php
namespace Backoffice\Core;

class Controller
{
    /**
     * Renvoie une réponse JSON avec un statut HTTP.
     *
     * @param array $data Les données à renvoyer.
     * @param int $status Le code de statut HTTP (par défaut 200).
     * @return array
     */
    protected function jsonResponse(array $data, int $status = 200): array
    {
        return [
            'status' => $status,
            'body' => $data,
        ];
    }

    /**
     * Renvoie une réponse d'erreur.
     *
     * @param string $message Le message d'erreur.
     * @param int $status Le code de statut HTTP (par défaut 400).
     * @return array
     */
    protected function errorResponse(string $message, int $status = 400): array
    {
        return $this->jsonResponse(['error' => $message], $status);
    }
}