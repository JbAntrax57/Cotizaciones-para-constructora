<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;

trait ApiResponse
{
    /**
     * Respuesta exitosa con datos
     */
    protected function successResponse($data = null, $message = 'Operación exitosa', $code = 200): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data
        ], $code);
    }

    /**
     * Respuesta de creación exitosa
     */
    protected function createdResponse($data = null, $message = 'Registro creado exitosamente'): JsonResponse
    {
        return $this->successResponse($data, $message, 201);
    }

    /**
     * Respuesta de actualización exitosa
     */
    protected function updatedResponse($data = null, $message = 'Registro actualizado exitosamente'): JsonResponse
    {
        return $this->successResponse($data, $message, 200);
    }

    /**
     * Respuesta de eliminación exitosa
     */
    protected function deletedResponse($message = 'Registro eliminado exitosamente'): JsonResponse
    {
        return $this->successResponse(null, $message, 200);
    }

    /**
     * Respuesta de error
     */
    protected function errorResponse($message = 'Error en la operación', $code = 400): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => $message,
            'data' => null
        ], $code);
    }

    /**
     * Respuesta de validación
     */
    protected function validationErrorResponse($errors, $message = 'Error de validación'): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => $message,
            'errors' => $errors,
            'data' => null
        ], 422);
    }
} 