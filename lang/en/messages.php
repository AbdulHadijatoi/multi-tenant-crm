<?php

return [

    /*
    |--------------------------------------------------------------------------
    | API Messages
    |--------------------------------------------------------------------------
    |
    | The following language lines are used for API responses and messages
    | throughout the application.
    |
    */

    'api' => [
        'operation_completed_successfully' => 'Operation completed successfully.',
        'data_retrieved_successfully' => 'Data retrieved successfully.',
        'resource_created_successfully' => 'Resource created successfully.',
        'unauthorized' => 'Please log in to continue.',
        'forbidden' => 'Access denied.',
        'resource_not_found' => 'The requested information could not be found.',
        'validation_failed' => 'Please check your input and try again.',
        'server_error' => 'Something went wrong. Please try again later.',
        
        // Error code to user-friendly message mappings
        'error_codes' => [
            'UNAUTHORIZED' => 'Access denied. Please provide correct login information or contact support.',
            'INVALID_TOKEN' => 'Access denied. Please provide correct login information or contact support.',
            'TOKEN_EXPIRED' => 'Your session has expired. Please log in again.',
            'INVALID_TENANT' => 'Access denied. Please verify your account information or contact support.',
            'INVALID_LICENSE' => 'Access denied. Please verify your account information or contact support.',
            'LICENSE_INACTIVE' => 'Your account access has been suspended. Please contact support for assistance.',
            'SUBSCRIPTION_NOT_FOUND' => 'Your account subscription could not be found. Please contact support.',
            'SUBSCRIPTION_INACTIVE' => 'Your account subscription is inactive. Please contact support to reactivate.',
            'DOMAIN_MISMATCH' => 'Access denied. Please verify your account information or contact support.',
            'TENANT_NOT_FOUND' => 'The requested account could not be found. Please contact support.',
            'USER_NOT_FOUND' => 'The requested account could not be found. Please contact support.',
            'INVALID_CREDENTIALS' => 'Invalid email or password. Please check your credentials and try again.',
            'UNAUTHORIZED_ROLE' => 'Access denied. You do not have permission to access this resource.',
            'INVALID_ROLE' => 'Invalid role selected. Please choose a valid role.',
            'MISSING_DOMAIN_HEADER' => 'Access denied. Please provide all required information or contact support.',
            'PASSWORD_RESET_FAILED' => 'Unable to process your request. Please try again or contact support.',
            'INVALID_CURRENT_PASSWORD' => 'The current password you entered is incorrect. Please try again.',
            'FORBIDDEN' => 'Access denied. You do not have permission to perform this action.',
        ],
    ],

];
