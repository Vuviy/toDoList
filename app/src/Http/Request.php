<?php

namespace App\Http;

class Request
{

    private static $instance = null;

    private function __construct(
        private array $server,
        private array $get,
        private array $post,
        private array $files,
        private array $cookie,
        private array $env,
    )
    {
        $this->get = filter_var_array($this->get, FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?? [];
        $this->post = filter_var_array($this->post, FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?? [];
        $this->cookie = filter_var_array($this->cookie, FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?? [];
    }

    public static function create(): static
    {
        if (null === static::$instance) {
            static::$instance = new static(
                $_SERVER,
                $_GET,
                $_POST,
                $_FILES,
                $_COOKIE,
                $_ENV);
        }
        return static::$instance;
    }

    public function validate(array $rules): array
    {
        $data = [];
        $errors = [];

        foreach ($rules as $field => $ruleString) {
            $rulesList = explode('|', $ruleString);
            $value = $this->input($field);

            foreach ($rulesList as $rule) {
                switch ($rule) {
                    case 'required':
                        if ($value === null || $value === '') {
                            $errors[$field][] = "Field {$field} is required.";
                        }
                        break;

                    case 'int':
                        if (!filter_var($value, FILTER_VALIDATE_INT)) {
                            $errors[$field][] = "Field {$field} must be integer.";
                        }
                        break;

                    case 'string':
                        if (!is_string($value)) {
                            $errors[$field][] = "Field {$field} must be string.";
                        }
                        break;

                    case 'date':
                        if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $value)) {
                            $errors[$field][] = "Field {$field} must be date (Y-m-d).";
                        }
                        break;
                }
            }

            $data[$field] = htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
        }
        if (count($errors) > 0) {
            $errors['error'] = true;
            header('Content-Type: application/json; charset=utf-8', true, 400);
            echo json_encode($errors);
            exit;

        }

        return $data;
    }

    public function input(string $key): mixed
    {
        return $this->post[$key] ?? $this->get[$key] ?? null;
    }

    public function post(): array
    {
        return $this->post;
    }

    public function get(): array
    {
        return $this->get;
    }

    public function files(): array
    {
        return $this->files;
    }

}