<?php
namespace src\br\com\caelum\leilao\dominio;

class Usuario
{

    private $nome;

    private $id;

    public function __construct(string $nome, int $id = 0)
    {
        $this->nome = $nome;
        $this->id = $id;
    }

    /**
     *
     * @return string
     */
    public function getNome(): string
    {
        return $this->nome;
    }

    /**
     *
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }
}

